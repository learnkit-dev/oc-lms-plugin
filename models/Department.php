<?php namespace LearnKit\LMS\Models;

use Model;
use RainLab\User\Models\User;
use Ramsey\Uuid\Uuid;

/**
 * Model
 */
class Department extends Model
{
    use \October\Rain\Database\Traits\Validation;

    use \October\Rain\Database\Traits\SoftDelete;

    public $implement = [
        '@Codecycler\Teams\Concerns\BelongsToTeams',
    ];

    protected $dates = ['deleted_at'];

    public $jsonable = ['extra_data'];


    /**
     * @var string The database table used by the model.
     */
    public $table = 'learnkit_lms_departments';

    /**
     * @var array Validation rules
     */
    public $rules = [
    ];

    protected $guarded = [];

    public $belongsToMany = [
        'users' => [
            User::class,
            'table' => 'learnkit_lms_departments_users',
        ],
    ];

    public function beforeSave()
    {
        if (!$this->code) {
            $this->code = Uuid::uuid4();
        }
    }
}
