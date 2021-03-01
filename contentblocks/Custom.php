<?php namespace LearnKit\LMS\ContentBlocks;

use LearnKit\LMS\Classes\Base\ContentBlockBase;

class Custom extends ContentBlockBase
{
    public static $code = 'learnkit.lms::custom';

    public static $label = 'Custom';

    public static $description = 'Show some custom content';

    public $payload = [];

    public function beforeRender()
    {
        if ($this->config['php_code']) {
            eval($this->config['php_code']);
        }
    }

    public function render()
    {
        return $this->config['html_code'];
    }
}