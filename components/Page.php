<?php namespace LearnKit\LMS\Components;

use App;
use Auth;
use Cms\Classes\ComponentBase;
use LearnKit\LMS\Models\Page as PageModel;
use LearnKit\LMS\Classes\Helper\ContentBlockHelper;

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

        $this->nextPage = $this->pageModel->course
            ->pages()
            ->orderBy('sort_order', 'asc')
            ->where('sort_order', '>', $this->pageModel->sort_order)
            ->first();

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
}
