<?php namespace LearnKit\LMS\Components;

use App;
use Auth;
use View;
use Response;
use Cms\Classes\ComponentBase;
use LearnKit\H5p\Models\Result;
use LearnKit\LMS\Models\Page as PageModel;
use LearnKit\H5p\Models\ContentsUserData;
use LearnKit\LMS\Classes\Helper\ContentBlockHelper;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Page extends ComponentBase
{
    protected $pageModel;

    protected $nextPage;

    protected $previousPage;

    public function componentDetails()
    {
        return [
            'name'        => 'Page Component',
            'description' => 'No description provided yet...'
        ];
    }

    public function defineProperties()
    {
        return [
            'slug' => [
                'title'             => 'learnkit.lms::lang.fields.slug',
                'description'       => '',
                'type'              => 'string',
            ],
            'redirectMarkDone' => [
                'title'             => 'learnkit.lms::lang.fields.redirect_mark_done',
                'description'       => '',
                'type'              => 'string',
            ],
        ];
    }

    public function onRun()
    {
        if (! $this->property('slug')) {
            return redirect('/');
        }

        $this->pageModel = PageModel::findBySlug($this->property('slug'));

        if (!$this->pageModel) {
            return redirect('/');
        }

        $this->prepareVars();
    }

    public function prepareVars()
    {
        $this->pageModel = PageModel::findBySlug($this->property('slug'));

        $isPublic = (boolean) $this->pageModel->is_public;

        if (!Auth::getUser() && !$isPublic) {
            // Redirect to home
            return App::abort(403, 'You must be logged in to see this content!');
        }

        //
        $this->nextPage = $this->pageModel->course
            ->pages()
            ->orderBy('sort_order', 'asc')
            ->where('sort_order', '>', $this->pageModel->sort_order)
            ->first();

        //
        $this->previousPage = $this->pageModel->course
            ->pages()
            ->orderBy('sort_order', 'desc')
            ->where('sort_order', '<', $this->pageModel->sort_order)
            ->first();

        //
        $this->page['lmsPage'] = $this->pageModel;
        $this->page['previousPage'] = $this->previousPage;
        $this->page['nextPage'] = $this->nextPage;

        // Add the ability for content blocks to add assets
        foreach ($this->pageModel->content_blocks as $contentBlock) {
            $type = ContentBlockHelper::instance()->getTypeByCode($contentBlock['content_block_type']);
            $instance = new $type($contentBlock, $this->pageModel);

            $this->addJs($instance->getPath() . '/script.js');
        }
    }

    public function onRedirect()
    {
        $this->prepareVars();

        return redirect(input('redirect'));
    }

    public function onMarkDone()
    {
        $this->prepareVars();

        // Run PHP code before saving
        if ($this->pageModel->code_before_save) {
            eval($this->pageModel->code_before_save);
        }

        // Mark page done
        $results = $this->pageModel->markDone();

        if (!$results) {
            return $this->showError('Beantwoord bovenstaande vraag om verder te gaan');
        }

        // Run PHP code after saving
        if ($this->pageModel->code_after_save) {
            eval($this->pageModel->code_after_save);
        }

        if (input('redirect')) {
            return redirect(input('redirect'));
        }

        if (!$this->nextPage) {
            // Course completed
            return [];
        }
    }

    public function showError($message) : array
    {
        $this->page['errorMessage'] = $message;
        return [
            '#error-message' => $this->renderPartial('@message'),
        ];
    }

    public function onResetResults()
    {
        // Content block IDs
        $type = input('type');
        $id = input('id');
        $pageId = input('pageId');
        $methodName = 'reset'.ucfirst($type).'Results';

        if (method_exists($this, $methodName)) {
            if ($type === 'block') {
                $this->$methodName($pageId, $id);
            } else {
                $this->$methodName($id);
            }
        }

        if (input('redirect')) {
            return redirect(input('redirect'));
        } else {
            return redirect()->refresh();
        }
    }

    protected function resetCourseResults($id) : void
    {
        $course = \LearnKit\LMS\Models\Course::find($id);

        foreach ($course->pages as $page) {
            $this->resetPageResults($page->id);
        }
    }

    protected function resetPageResults($id) : void
    {
        if (is_array($id)) {
            foreach ($id as $singleId) {
                $this->resetPageResults($singleId);
            }
        }

        $page = \LearnKit\LMS\Models\Page::find($id);

        if (!isset($page->content_blocks)) {
            return;
        }

        foreach ($page->content_blocks as $block) {
            $this->resetBlockResults($id, $block['hash']);
        }
    }

    protected function resetBlockResults($pageId, $hash) : void
    {
        $page = \LearnKit\LMS\Models\Page::find($pageId);

        if (!isset($page->content_blocks)) {
            return;
        }

        $user = Auth::getUser();

        $blocks = collect($page->content_blocks);

        $block = $blocks->where('hash', $hash)->first();

        if ($block['content_block_type'] === 'learnkit.lms::h5p') {
            $h5pResult = Result::where('user_id', $user->id)
                ->where('content_id', $block['content_id'])
                ->first();

            $h5pUserData = ContentsUserData::where('user_id', $user->id)
                ->where('content_id', $block['content_id'])
                ->first();

            if ($h5pResult) {
                $h5pResult->delete();
            }

            if ($h5pUserData) {
                $h5pUserData->delete();
            }
        }

        // Delete result
        $result = \LearnKit\LMS\Models\Result::where('user_id', $user->id)
            ->where('page_id', $pageId)
            ->first();

        //
        if ($result) {
            $result->delete();
        }
    }
}
