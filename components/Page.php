<?php namespace LearnKit\LMS\Components;

use Cms\Classes\ComponentBase;
use LearnKit\LMS\Models\Page as PageModel;

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

        // Run PHP code after saving
        if ($this->pageModel->code_after_save) {
            eval($this->pageModel->code_after_save);
        }

        if (!$this->nextPage) {
            // Course completed
            return [];
        }

        if (input('redirect')) {
            return redirect(input('redirect'));
        }
    }
}
