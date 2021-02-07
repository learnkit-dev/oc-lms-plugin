<?php namespace LearnKit\LMS\Classes\Extend\RainLab;

use LearnKit\LMS\Models\Course;
use RainLab\User\Controllers\Users;
use Kloos\Toolbox\Classes\Base\OctoberExtend;

class User extends OctoberExtend
{
    public function model()
    {
        return new \RainLab\User\Models\User();
    }

    public function controller()
    {
        return new Users();
    }

    public function addTabFields()
    {
        return [
            'courses' => [
                'label' => 'learnkit.lms::lang.fields.courses',
                'type'  => 'relation',
                'tab'   => 'learnkit.lms::lang.tabs.lms',
                'span'  => 'left',
            ],
        ];
    }

    public function belongsToMany()
    {
        return [
            'courses' => [
                Course::class,
                'table' => 'learnkit_lms_courses_users',
            ],
        ];
    }
}