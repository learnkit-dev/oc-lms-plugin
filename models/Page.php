<?php namespace LearnKit\LMS\Models;

use Auth;
use Model;
use Ramsey\Uuid\Uuid;
use October\Rain\Parse\Twig;
use October\Rain\Database\Traits\Sortable;
use LearnKit\LMS\Classes\Helper\ContentBlockHelper;

/**
 * Page Model
 */
class Page extends Model
{
    use \October\Rain\Database\Traits\Validation;
    use Sortable;

    /**
     * @var string The database table used by the model.
     */
    public $table = 'learnkit_lms_pages';

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
        'content_blocks',
        'properties',
    ];

    /**
     * @var array Attributes to be appended to the API representation of the model (ex. toArray())
     */
    protected $appends = [
        'is_readonly',
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
    public $hasOneThrough = [];
    public $hasManyThrough = [];

    public $belongsTo = [
        'course' => Course::class,
    ];

    public $belongsToMany = [];
    public $morphTo = [];
    public $morphOne = [];
    public $morphMany = [];

    public $attachOne = [
        'image' => 'System\Models\File',
    ];

    public $attachMany = [];

    protected $activeContentBlockHash;

    public function getReorderNameAttribute()
    {
        if (!$this->course) {
            return $this->name;
        }

        return $this->course->name . ' - ' . $this->name;
    }

    public function getContentBlockTypeOptions()
    {
        return ContentBlockHelper::instance()
            ->getTypeOptions();
    }

    public function beforeSave()
    {
        $contentBlocks = $this->content_blocks;

        foreach ($contentBlocks as $key => $contentBlock) {
            // Get content block type
            $instance = ContentBlockHelper::instance()
                ->getTypeByCode($contentBlock['content_block_type']);

            // Extend fields
            $instance = new $instance($contentBlock, $this);
            $contentBlock = $instance->beforeSave($contentBlock);

            if (!$contentBlock['hash']) {
                $contentBlock['hash'] = Uuid::uuid4();
            }

            $contentBlocks[$key] = $contentBlock;
        }

        $this->content_blocks = $contentBlocks;
    }

    public static function findBySlug($slug)
    {
        return static::where('slug', $slug)
            ->first();
    }

    public function renderContentBlock($block)
    {
        //
        if (Auth::getUser()) {
            $results = Auth::getUser()
                ->results()
                ->where('content_block_hash', $block['hash'])
                ->get();
        } else {
            $results = collect();
        }

        //
        $instance = ContentBlockHelper::instance()
            ->getTypeByCode($block['content_block_type']);

        $instance = new $instance($block, $this);

        $content = $instance->render();

        //
        $twig = new Twig();
        return $twig->parse($content, [
            'config' => $block,
            'page' => $this,
            'results' => $results,
            'user' => Auth::getUser(),
            'block' => $instance,
        ]);
    }

    public function markDone()
    {
        $results = collect([]);

        // Loop through all the content blocks and create a new result record
        foreach ($this->content_blocks as $contentBlock) {
            $instance = ContentBlockHelper::instance()
                ->getTypeByCode($contentBlock['content_block_type']);

            $this->activeContentBlockHash = $contentBlock['hash'];

            $instance = new $instance($contentBlock, $this);

            // Run PHP code before saving
            if ($contentBlock['code_subject_result']) {
                eval($contentBlock['code_subject_result']);
            }

            // Run PHP code before saving
            if ($contentBlock['code_result']) {
                eval($contentBlock['code_result']);
            } else {
                $result = $instance->saveResults();
            }

            $results->push($result);
        }

        // Mark whole page done when no content blocks exists
        if (count($this->content_blocks) < 1) {
            $result = Result::create([
                'user_id' => Auth::getUser()->id,
                'course_id' => $this->course->id,
                'page_id' => $this->id,
            ]);

            $results->push($result);
        }

        return $results;
    }

    public function getIsReadonlyAttribute()
    {
        // Check if is not multiple and result exists
        $result = null;

        if (Auth::getUser()) {
            $result = Auth::getUser()
                ->results()
                ->where('page_id', $this->id)
                ->first();
        }

        if (!$this->is_multiple && $result) {
            return true;
        } else {
            return false;
        }
    }

    public function newSubjectResult($subject, $score = 1)
    {
        $subjectResult = SubjectResult::create([
            'user_id' => Auth::getUser()->id,
            'content_block_hash' => $this->activeContentBlockHash,
            'page_id' => $this->id,
            'course_id' => $this->course->id,
            'score' => $score,
            'subject' => $subject,
        ]);

        return $subjectResult;
    }

    public function getIsCompletedAttribute()
    {
        $user = Auth::getUser();

        if ($user) {
            //
            $hashes = collect($this->content_blocks)
                ->pluck('hash')
                ->toArray();

            $results = Result::where('user_id', $user->id)
                ->whereIn('content_block_hash', $hashes)
                ->get();

            if (count($hashes) === 0) {
                // Check if page is done
                $result = Result::where('user_id', $user->id)
                    ->where('page_id', $this->id)
                    ->first();

                if ($result) {
                    return true;
                }

                return false;
            }

            if ($results->count() >= count($hashes)) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
}
