<?php

namespace LearnKit\LMS\Models;

use Backend\Models\ImportModel;
use Codecycler\Teams\Models\Team;
use RainLab\User\Models\User;

class UserImport extends ImportModel
{
    public $rules = [
        // ...
    ];

    public function importData($results, $sessionKey = null)
    {
        $team = Team::query()
            ->with('departments')
            ->find($this->team);

        $newDepartments = [];
        $newManagerDepartments = [];

        foreach ($results as $key => $row) {
            try {
                $creating = false;

                // Try to find the user
                $user = User::findByEmail($row['email']);

                if (! $user) {
                    $user = new User();

                    $user->email = $row['email'];
                    $user->is_activated = true;

                    $creating = true;
                }

                $user->name = $row['name'];
                $user->surname = $row['surname'];

                if (filled($row['password']) && $creating) {
                    $password = bcrypt($row['password']);

                    $user->password = $password;
                    $user->password_confirmation = $password;
                }

                $user->save();

                if (! $team->users()->find($user->id)) {
                    $team->users()->attach($user);
                }

                if (isset($row['department']) && filled($row['department'])) {
                    if (isset($newDepartments[$row['department']])) {
                        $department = $newDepartments[$row['department']];
                    } else {
                        $department = $team->departments->where('name', $row['department'])->first();
                    }

                    if (! $department) {
                        $department = new Department();

                        $department->team_id = $team->id;
                        $department->name = $row['department'];

                        $department->save();

                        $newDepartments[$department->name] = $department;
                    }

                    if (! $department->users()->find($user->id)) {
                        $department->users()->attach($user);
                    }
                }

                if (isset($row['manager_department']) && filled($row['manager_department'])) {
                    if (isset($newManagerDepartments[$row['manager_department']])) {
                        $department = $newManagerDepartments[$row['manager_department']];
                    } else {
                        $department = $team->departments->where('name', $row['manager_department'])->first();
                    }

                    if (! $department) {
                        $department = new Department();

                        $department->team_id = $team->id;
                        $department->name = $row['manager_department'];

                        $department->save();

                        $newManagerDepartments[$department->name] = $department;
                    }

                    if (! $department->managers()->find($user->id)) {
                        $department->managers()->attach($user);
                    }
                }

                if (isset($row['manager_role']) && in_array($row['manager_role'], ['ja', 'yes', '1', 'x', 'v'])) {
                    $group = \RainLab\User\Models\UserGroup::where('code', 'manager')->first();

                    $user->addGroup($group);
                }

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
