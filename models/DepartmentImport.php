<?php

namespace LearnKit\LMS\Models;

use Backend\Models\ImportModel;
use Codecycler\Teams\Models\Team;

class DepartmentImport extends ImportModel
{
    public $rules = [
        // ...
    ];

    public function importData($results, $sessionKey = null)
    {
        $team = Team::query()
            ->with('departments')
            ->find($this->team);

        foreach ($results as $key => $row) {
            try {
                $department = $team->departments()->create([
                    'name' => $row['name'] ?? '',
                    'kostenplaats' => $row['kostenplaats'] ?? '',
                    'type' => $row['type'] ?? '',
                    'school' => $row['school'] ?? '',
                ]);

                $this->logCreated();
            } catch (\Exception $ex) {
                $this->logError($key, $ex->getMessage());
            }
        }
    }

    public function getTeamOptions()
    {
        return Team::query()->pluck('name', 'id')->toArray();
    }
}
