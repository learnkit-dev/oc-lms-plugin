<?php namespace LearnKit\LMS\Classes\Extend\RainLab;

use LearnKit\LMS\Models\Course;
use LearnKit\LMS\Models\Department;
use LearnKit\LMS\Models\Result;
use RainLab\User\Controllers\Users;
use LearnKit\LMS\Models\SubjectResult;
use Codecycler\Extend\Classes\PluginExtender;

class User extends PluginExtender
{
    public function model()
    {
        return new \RainLab\User\Models\User();
    }

    public function controller()
    {
        return new Users();
    }

    public function addFields()
    {
        return [
            'personal_id' => [
                'label' => 'Personal ID',
                'type' => 'text',
                'span' => 'auto',
            ],
            'manager_department' => [
                'label' => 'Manager department',
                'type' => 'recordfinder',
                'relation' => 'manager_department',
                'list' => [
                    'columns' => [
                        'name' => [
                            'type' => 'text',
                            'searchable' => true,
                            'sortable' => true,
                        ],
                        'school' => [
                            'type' => 'text',
                            'searchable' => true,
                            'sortable' => true,
                        ],
                        'team' => [
                            'label' => 'learnkit.lms::lang.fields.team',
                            'type' => 'text',
                            'searchable' => true,
                            'sortable' => true,
                            'select' => 'name',
                            'relation' => 'team',
                        ],
                        'type' => [
                            'label' => 'Type',
                            'type' => 'text',
                            'searchable' => true,
                            'sortable' => true,
                        ],
                    ],
                ],
                'nameFrom' => 'name',
                'span' => 'auto',
                'emptyOption' => 'Selecteer een department',
                'comment' => 'Vul alleen in op moment dat de gebruiker manager / admin rol heeft',
                'options' => Department::query()->pluck('name', 'id'),
            ],
        ];
    }

    public function addColumns()
    {
        return [
            'personal_id' => [
                'label' => 'Personal ID',
                'searchable' => true,
                'sortable' => true,
            ],
        ];
    }

    public function addTabFields()
    {
        return [
            'results' => [
                'label' => '',
                'type'  => 'partial',
                'path'  => '$/learnkit/lms/partials/user_results.htm',
                'tab'   => 'learnkit.lms::lang.tabs.results',
                'span'  => 'full',
            ],
            'departments' => [
                'label' => '',
                'type'  => 'partial',
                'path'  => '$/learnkit/lms/partials/user_departments.htm',
                'tab'   => 'learnkit.lms::lang.tabs.departments',
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
            'departments' => [
                'label' => 'group',
                'manage'    => [
                    'showSearch' => true,
                    'form' => '$/learnkit/lms/models/department/fields.yaml',
                ],
                'view'      => [
                    'recordOnClick' => 'javascript:;',
                    'toolbarButtons' => 'link|unlink',
                    'list' => '$/learnkit/lms/models/department/columns.yaml',
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
            'departments' => [
                Department::class,
                'table' => 'learnkit_lms_departments_users',
            ],
        ];
    }

    public function belongsTo()
    {
        return [
            'manager_department' => [
                Department::class,
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
