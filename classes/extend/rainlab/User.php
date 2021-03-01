<?php namespace LearnKit\LMS\Classes\Extend\RainLab;

use LearnKit\LMS\Models\Course;
use LearnKit\LMS\Models\Result;
use RainLab\User\Controllers\Users;
use LearnKit\LMS\Models\SubjectResult;
use Codecycler\Toolbox\Classes\Base\OctoberExtend;

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
            'results' => [
                'label' => '',
                'type'  => 'partial',
                'path'  => '$/learnkit/lms/partials/user_results.htm',
                'tab'   => 'learnkit.lms::lang.tabs.results',
                'span'  => 'full',
            ],
        ];
    }

    public function addRelationConfig()
    {
        return [
            'results' => [
                'label' => 'result',
                'manage'    => [
                    'form' => '$/learnkit/lms/models/result/fields.yaml',
                ],
                'view'      => [
                    'toolbarButtons' => 'delete',
                    'list' => '$/learnkit/lms/models/result/columns.yaml',
                ],
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

    public function hasMany()
    {
        return [
            'results' => Result::class,
            'subject_results' => SubjectResult::class,
        ];
    }
}