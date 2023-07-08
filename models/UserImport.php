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

        foreach ($results as $row) {
            try {

                // Try to find the user
                $user = User::findByEmail($row['email']);

                if (! $user) {
                    $user = new User();

                    $user->email = $row['email'];
                    $user->is_activated = true;
                }

                $user->name = $row['name'];
                $user->surname = $row['surname'];

                if (filled($row['password'])) {
                    $password = bcrypt($row['password']);

                    $user->password = $password;
                    $user->password_confirmation = $password;
                }

                $user->save();

                if (! $team->users()->find($user->id)) {
                    $team->users()->attach($user);
                }

                if (filled($row['department'])) {
                    $department = $team->departments->where('name', $row['department'])->first();

                    if (! $department) {
                        $department = new Department();

                        $department->team_id = $team->id;
                        $department->name = $row['department'];

                        $department->save();
                    }

                    if (! $department->users()->find($user->id)) {
                        $department->users()->attach($user);
                    }
                }

                $this->logCreated();
            } catch (\Exception $ex) {
                ray($ex);
                $this->logError($row, $ex->getMessage());
            }
        }
    }

    public function getTeamOptions()
    {
        return Team::query()->pluck('name', 'id')->toArray();
    }
}
