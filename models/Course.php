<?php namespace LearnKit\LMS\Models;

use Auth;
use Codecycler\Teams\Classes\TeamManager;
use Model;
use RainLab\User\Models\User;
use LearnKit\LMS\Classes\Helper\ResultHelper;
use System\Classes\PluginManager;

/**
 * Course Model
 */
class Course extends Model
{
    use \October\Rain\Database\Traits\Validation;

    public $implement = [
        //'@Codecycler\Teams\Concerns\BelongsToTeams',
    ];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'learnkit_lms_courses';

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = [];

    /**
     * @var array Validation rules for attributes
     */
    public $rules = [];

    /**
     * @var array Attributes to be cast to native types
     */
    protected $casts = [];

    /**
     * @var array Attributes to be cast to JSON
     */
    protected $jsonable = [
        'properties',
        'subjects',
    ];

    /**
     * @var array Attributes to be appended to the API representation of the model (ex. toArray())
     */
    protected $appends = [
        'is_visible',
    ];

    /**
     * @var array Attributes to be removed from the API representation of the model (ex. toArray())
     */
    protected $hidden = [];

    /**
     * @var array Attributes to be cast to Argon (Carbon) instances
     */
    protected $dates = [
        'created_at',
        'updated_at'
    ];

    /**
     * @var array Relations
     */
    public $hasOne = [];

    public $hasMany = [
        'pages' => Page::class,
    ];

    public $hasOneThrough = [];
    public $belongsTo = [];

    public $belongsToMany = [
        'users' => [
            User::class,
            'table' => 'learnkit_lms_courses_users',
        ],
    ];

    public $morphTo = [];
    public $morphOne = [];
    public $morphMany = [];

    public $attachOne = [
        'image' => 'System\Models\File',
    ];

    public $attachMany = [];

    public static function findBySlug($slug)
    {
        return static::where('slug', $slug)
            ->first();
    }

    public function daysOld()
    {
        $now = \Carbon\Carbon::now();
        return $now->diffInDays($this->created_at);
    }

    public function getFirstPageSlugAttribute()
    {
        $page = $this->pages->first();
        return $page ? $page->slug : '';
    }

    public function getIsVisibleAttribute()
    {
        if ($this->is_public) {
            return true;
        }

        if (!Auth::getUser()) {
            return false;
        }

        if (PluginManager::instance()->exists('Codecycler.Teams')) {
            $team = TeamManager::instance()->active();

            if (!$team) {
                return false;
            }

            if (!$team->team_courses->contains($this)) {
                return false;
            }
        }

        return true;
    }

    public function scopeActive($query)
    {
        $query->where('is_active', 1);
    }

    public function getAvgScoreAttribute()
    {
        $score = 0;
        $maxScore = 0;
        $percentageDone = 0;

        foreach ($this->team->users as $user) {
            // Get score
            $uScore = ResultHelper::forCourse($this->id, $user);

            $score += $uScore->total;
            $maxScore += $uScore->max;
            $percentageDone += $uScore->percentageDone;
        }

        return [
            'score' => floor($score / count($this->team->users)),
            'max' => floor($maxScore / count($this->team->users)),
            'percentageDone' => floor($percentageDone / count($this->team->users)),
        ];
    }


}
