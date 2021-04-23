<?php namespace LearnKit\LMS\Components;

use Cms\Classes\ComponentBase;

class Course extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name'        => 'Course Component',
            'description' => 'No description provided yet...'
        ];
    }

    public function defineProperties()
    {
        return [
            'slug' => [
                'title'             => 'learnkit.lms::lang.fields.slug',
                'description'       => 'The most amount of todo items allowed',
                'type'              => 'string',
            ]
        ];
    }

    public function onRun()
    {
        $this->prepareVars();
    }

    public function prepareVars()
    {
        if ($this->property('slug')) {
            $this->page['lmsCourse'] = \LearnKit\LMS\Models\Course::findBySlug($this->property('slug'));
        }
    }

    public function all()
    {
        return \LearnKit\LMS\Models\Course::active()->get();
    }
}
