<?php namespace LearnKit\LMS\Classes\Extend\Codecycler;

use LearnKit\LMS\Models\Course;
use Codecycler\Teams\Models\Team;
use Codecycler\Extend\Classes\PluginExtender;

class Teams extends PluginExtender
{
    public function model()
    {
        return Team::class;
    }

    public function controller()
    {
        return \Codecycler\Teams\Controllers\Teams::class;
    }

    public function belongsToMany()
    {
        return [
            'team_courses' => [
                Course::class,
                'table' => 'learnkit_lms_courses_teams',
            ],
        ];
    }

    public function addRelationConfig()
    {
        return [
            'team_courses' => [
                'label' => 'course',
                'view' => [
                    'toolbarButtons' => 'link|unlink',
                    'showSearch' => true,
                    'list' => '$/learnkit/lms/models/course/columns.yaml',
                ],
                'manage' => [
                    'form' => '$/learnkit/lms/models/course/fields.yaml',
                ],
            ],
        ];
    }

    public function addTabFields()
    {
        return [
            'team_courses' => [
                'tab' => 'Courses',
                'type' => 'partial',
                'path' => '$/learnkit/lms/partials/team_courses.htm',
            ],
        ];
    }
}