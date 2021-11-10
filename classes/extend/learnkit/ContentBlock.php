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


            foreach (ContentBlockHelper::instance()->getTypes() as $key => $class) {
                $instance = new $class;

                $formWidget->addFields($instance->formFields());
            }
        });
    }
}