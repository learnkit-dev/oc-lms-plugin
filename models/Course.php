<?php namespace LearnKit\LMS\Models;

use Model;

/**
 * Course Model
 */
class Course extends Model
{
    use \October\Rain\Database\Traits\Validation;

    public $implement = [
        '@Kloos.Saas.Behaviors.AttachedToTenant',
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
    protected $jsonable = [];

    /**
     * @var array Attributes to be appended to the API representation of the model (ex. toArray())
     */
    protected $appends = [];

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
    public $belongsToMany = [];
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
}
