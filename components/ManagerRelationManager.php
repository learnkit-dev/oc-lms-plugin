<?php

namespace LearnKit\LMS\Components;

use Cms\Classes\ComponentBase;

class ManagerRelationManager extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name' => 'Manager relation manager',
            'description' => 'Relation to managers',
        ];
    }

    public function defineProperties()
    {
        return [
            'departmentId' => [
                'title' => 'Department ID',
                'default' => '{{:departmentId}}',
                'type' => 'text',
            ],
            'relationName' => [
                'title' => 'Relation name',
                'default' => 'managers',
                'type' => 'text',
            ],
            'relationTitle' => [
                'title' => 'Relation title',
                'default' => 'Managers',
                'type' => 'text',
            ],
        ];
    }

    public function getItems()
    {
        return $this->page[$this->property('relationName')];
    }

    public function prepareVars()
    {
        if (! $this->property('departmentId')) {
            return;
        }

        $relationName = $this->property('relationName');

        $departmentId = $this->property('departmentId');
        $team = auth()->user()->teams()->first();

        $department = $team->departments()
            ->with($relationName)
            ->findOrFail($departmentId);

        $this->page[$relationName] = $department->$relationName;
    }

    public function onRun()
    {
        $this->prepareVars();
    }

    public function onSearchUser()
    {
        $term = input('search');

        if (strlen($term) === 0) {
            return [
                '#manager-' . $this->property('relationName') . '-add-form-results' => $this->renderPartial('@search-results', ['results' => []]),
            ];
        }

        if (strlen($term) < 3) {
            return;
        }

        $team = auth()->user()->teams()->first();
        $departmentId = $this->property('departmentId');

        $users = $team->users()
            ->where(function ($query) use ($departmentId, $term) {
                return $query
                    ->where('email', 'LIKE', '%' . $term . '%')
                    ->orWhere('name', 'LIKE', '%' . $term . '%')
                    ->orWhere('surname', 'LIKE', '%' . $term . '%');
            })
            ->whereDoesntHave('departments', function ($query) use ($departmentId) {
                return $query->where('learnkit_lms_departments.id', $departmentId);
            })
            ->limit(15)
            ->get();

        return [
            '#manager-' . $this->property('relationName') . '-add-form-results' => $this->renderPartial('@search-results', ['results' => $users]),
        ];
    }

    public function onAttach()
    {
        $relationName = $this->property('relationName');

        $team = auth()->user()->teams()->first();
        $department = $team->departments()->findOrFail($this->property('departmentId'));
        $user = $team->users()->findOrFail(input('userId'));

        $department->$relationName()->attach($user);

        $this->prepareVars();

        return [
            '#manager-' . $this->property('relationName') . '-add-form' => $this->renderPartial('@add-form'),
            '#manager-' . $this->property('relationName') . '-records' => $this->renderPartial('@table-records'),
        ];
    }

    public function onDetach()
    {
        $relationName = $this->property('relationName');

        $team = auth()->user()->teams()->first();
        $department = $team->departments()->findOrFail($this->property('departmentId'));
        $user = $team->users()->findOrFail(input('userId'));

        $department->$relationName()->detach($user);

        $this->prepareVars();

        return [
            '#manager-' . $this->property('relationName') . '-records' => $this->renderPartial('@table-records'),
        ];
    }
}
