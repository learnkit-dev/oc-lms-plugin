<?php namespace LearnKit\LMS\Classes\Extend\LearnKit;

use Event;
use LearnKit\LMS\Controllers\ContentBlocks;
use LearnKit\LMS\Classes\Helper\ContentBlockHelper;

class ContentBlock
{
    public function subscribe()
    {
        Event::listen('backend.form.extendFields', function ($formWidget, $formData) {
            if (!$formWidget->isNested) {
                return;
            }

            if (!$formWidget->model instanceof \LearnKit\LMS\Models\Page) {
                return;
            }

            if (!isset($formData['content_block_type'])) {
                return;
            }

            if ($formData['content_block_type']->value) {
                $code = $formData['content_block_type']->value;

                $instance = ContentBlockHelper::instance()
                    ->getTypeByCode($code);

                $instance = new $instance;

                $formWidget->addFields($instance->formFields());
            }
        });
    }
}