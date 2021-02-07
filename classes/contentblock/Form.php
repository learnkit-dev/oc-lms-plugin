<?php namespace LearnKit\LMS\Classes\ContentBlock;

use Ramsey\Uuid\Uuid;
use LearnKit\LMS\Classes\Base\ContentBlockBase;

class Form extends ContentBlockBase
{
    public static $code = 'learnkit.lms::form';

    public static $label = 'Form';

    public static $description = 'Shows a form which results can be used in the course.';

    public function beforeSave($config)
    {
        $fields = $config['fields'];

        foreach ($fields as $key => $field) {
            if (!$field['uuid']) {
                $fields[$key]['uuid'] = Uuid::uuid4();
            }
        }

        $config['fields'] = $fields;

        return $config;
    }

    public function saveResults()
    {
        // Prepare the data for the database
        $payload = $this->getFormResults();
        return $this->newResult(null, null, $payload);
    }

    protected function getFormResults()
    {
        $payload = [];

        foreach ($this->config['fields'] as $field) {
            $payload[$field['uuid']] = input($field['uuid']) ? input($field['uuid']) : null;
        }

        return $payload;
    }
}