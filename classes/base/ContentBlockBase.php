<?php namespace LearnKit\LMS\Classes\Base;

use Auth;
use Yaml;
use System\Traits\ViewMaker;
use LearnKit\LMS\Models\Result;
use LearnKit\LMS\Models\SubjectResult;

class ContentBlockBase
{
    use ViewMaker;

    public static $code = 'learnkit.lms::placeholder';

    public static $label = 'Content block type';

    public static $description = 'Contains a short description for the content block type.';

    protected $config = [];

    protected $plugin;

    protected $type;

    protected $page;

    protected $path;

    public function __construct($config = [], $page = null)
    {
        if (!$page) {
            $page = new \stdClass();
        }

        $this->page = $page;

        $plugin = str_replace('.', '/', static::$code);

        $exploded = explode('::', $plugin);

        $this->plugin = $exploded[0];
        $this->type = $exploded[1];

        $this->config = $config;

        $this->addViewPath(plugins_path($this->plugin . '/contentblocks/' . str_replace('_', '', $this->type)));

        $this->path = '/plugins/' . $this->plugin . '/contentblocks/' . str_replace('_', '', $this->type);
    }

    public function getPath()
    {
        return $this->path;
    }

    public function beforeSave($config)
    {
        return $config;
    }

    public function render()
    {
        // Check if default partial file exists for the content block type
        return $this->makePartial('default');
    }

    public function formFields()
    {
        // Check if yaml file exists for the content block type
        $fieldsPath = plugins_path($this->plugin . '/contentblocks/' . str_replace('_', '', $this->type) . '/fields.yaml');
        if (file_exists($fieldsPath)) {
            return Yaml::parseFile($fieldsPath)['fields'];
        }

        return [];
    }

    public function saveResults()
    {
        return $this->newResult();
    }

    public function newSubjectResult($subject, $score = 1)
    {
        $result = Auth::getUser()
            ->subject_results()
            ->where('content_block_hash', $this->config['hash'])
            ->get();

        if (count($result) < 1 || (count($result) > 0 && $this->page->is_multiple)) {
            // First check if there is already a subject score for this content block / user
            return SubjectResult::create([
                'user_id' => Auth::getUser()->id,
                'content_block_hash' => $this->config['hash'],
                'page_id' => $this->page->id,
                'course_id' => $this->page->course->id,
                'score' => $score,
                'subject' => $subject,
            ]);
        }
    }

    public function isEmpty()
    {
        return false;
    }

    public function beforeRender()
    {
    }

    public function newResult($score = null, $maxScore = null, $payload = [])
    {
        $isObligatory = (boolean) $this->config['is_obligatory'];

        if ($this->isEmpty() && $isObligatory) {
            return false;
        }

        $result = Auth::getUser()
            ->subject_results()
            ->where('content_block_hash', $this->config['hash'])
            ->get();

        if (count($result) < 1 || (count($result) > 0 && $this->page->is_multiple)) {
            return Result::create([
                'score' => $score,
                'max_score' => $maxScore,
                'user_id' => Auth::getUser()->id,
                'course_id' => $this->page->course->id,
                'page_id' => $this->page->id,
                'content_block_hash' => $this->config['hash'],
                'payload' => $payload,
            ]);
        }
    }
}