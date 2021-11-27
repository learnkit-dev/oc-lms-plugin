<?php namespace LearnKit\LMS\Classes\Extend\LearnKit;

use Codecycler\Extend\Classes\PluginExtender;
use LearnKit\LMS\Controllers\Pages;
use LearnKit\LMS\Models\Page as PageModel;

class Page extends PluginExtender
{
    public function model()
    {
        return PageModel::class;
    }

    public function controller()
    {
        return Pages::class;
    }

    public function addColumns()
    {
        return [
            'team_names' => [
                'label' => 'learnkit.lms::lang.fields.teams',
                'sortable' => false,
                'searchable' => false,
            ],
        ];
    }
}