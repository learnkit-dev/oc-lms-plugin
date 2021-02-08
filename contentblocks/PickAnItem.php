<?php namespace LearnKit\LMS\ContentBlocks;

use LearnKit\LMS\Classes\Base\ContentBlockBase;

class PickAnItem extends ContentBlockBase
{
    public static $code = 'learnkit.lms::pick_an_item';

    public static $label = 'Pick an item';

    public static $description = 'Choose between a few options.';

    public function formFields()
    {
        return [
            'options' => [
                'label' => 'Options',
                'type' => 'repeater',
                'span' => 'full',
                'form' => [
                    'fields' => [
                        'label' => [
                            'label' => 'Label',
                            'type' => 'text',
                            'span' => 'left',
                        ],
                        'value' => [
                            'label' => 'Value',
                            'type' => 'text',
                            'span' => 'right',
                        ],
                        'description' => [
                            'label' => 'Description',
                            'type' => 'richeditor',
                            'span' => 'left',
                        ],
                        'image' => [
                            'label' => 'Image',
                            'type' => 'mediafinder',
                            'span' => 'right',
                        ],
                    ],
                ],
            ],
        ];
    }

    public function saveResults()
    {
        $payload = $this->preparePayload();

        // Store subject results
        $this->newSubjectResult($payload);

        return $this->newResult(null, null, $payload);
    }

    public function preparePayload()
    {
        return input('picked');
    }
}