<?php namespace LearnKit\LMS\Classes\Extend\LearnKit;

use Codecycler\Extend\Classes\PluginExtender;
use Codecycler\Teams\Models\Team;
use LearnKit\LMS\Controllers\Courses;

class Course extends PluginExtender
{
    public function model()
    {
        return \LearnKit\LMS\Models\Course::class;
    }

    public function controller()
    {
        return Courses::class;
    }

    public function addTabFields()
    {
        return [
            'teams' => [
                'tab' => 'Teams',
                'type' => 'partial',
                'path' => '$/learnkit/lms/partials/teams.htm',
                'span' => 'full',
            ],
        ];
    }

    public function addRelationConfig()
    {
        return [
            'teams' => [
                'label' => 'team',
                'manage' => [
                    'form' => '$/codecycler/teams/models/team/fields.yaml',
                    'showSearch' => 'true',
                ],
                'view' => [
                    'toolbarButtons' => 'link|unlink',
                    'showSearch' => 'true',
                    'list' => '$/codecycler/teams/models/team/columns.yaml',
                ],
            ],
        ];
    }

    public function belongsToMany()
    {
        return [
            'teams' => [
                Team::class,
                'table' => 'learnkit_lms_courses_teams',
            ],
        ];
    }
}