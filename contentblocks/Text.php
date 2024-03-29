<?php namespace LearnKit\LMS\ContentBlocks;

use LearnKit\LMS\Classes\Base\ContentBlockBase;

class Text extends ContentBlockBase
{
    public static $code = 'learnkit.lms::text';

    public static $label = 'Text';

    public static $description = 'Show content.';

    public function formFields()
    {
        return [
            'text' => [
                'label' => 'Text',
                'type' => 'richeditor',
                'size' => 'large',
                'span' => 'full',
                'trigger' => [
                    'action' => 'show',
                    'field' => 'content_block_type',
                    'condition' => 'value[learnkit.lms::text]',
                ],
            ],
        ];
    }

    public function render()
    {
        return $this->config['text'];
    }
}