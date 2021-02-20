<?php namespace LearnKit\LMS\ContentBlocks;

use LearnKit\H5p\Models\Content;
use LearnKit\LMS\Classes\Base\ContentBlockBase;

class H5P extends ContentBlockBase
{
    public static $code = 'learnkit.lms::h5p';

    public static $label = 'H5P content';

    public static $description = 'Embed H5P content';

    public function formFields()
    {
        $options = Content::all()
            ->pluck('title', 'id')
            ->toArray();

        return [
            'content_id' => [
                'label' => 'H5P Content',
                'type' => 'dropdown',
                'options' => $options,
                'span' => 'left',
            ],
        ];
    }

    public function saveResults()
    {
        return $this->newResult(null, null);
    }
}