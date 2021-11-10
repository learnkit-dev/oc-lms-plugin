<?php namespace LearnKit\LMS\Classes\Helper;

use System\Classes\PluginManager;
use October\Rain\Support\Traits\Singleton;

class ContentBlockHelper
{
    use Singleton;

    protected $types = [];

    protected $mapping = [];

    public function init()
    {
        if (empty($this->types)) {
            $this->initTypes();
        }
    }

    public function getTypeOptions()
    {
        $options = [];

        foreach ($this->types as $type) {
            $typeClass = new $type;

            $options[$typeClass::$code] = $typeClass::$label;
        }

        return $options;
    }

    public function getTypeByCode($code)
    {
        if (empty($this->mapping)) {
            $this->getMapping();
        }

        return isset($this->mapping[$code]) ? $this->mapping[$code] : null;
    }

    public function getTypes()
    {
        if (empty($this->mapping)) {
            $this->getMapping();
        }

        return $this->mapping;
    }

    protected function getMapping()
    {
        $options = [];

        foreach ($this->types as $type) {
            $typeClass = new $type;
            $options[$typeClass::$code] = $typeClass;
        }

        $this->mapping = $options;
    }

    protected function initTypes()
    {
        foreach (PluginManager::instance()->getAllPlugins() as $code => $plugin) {
            if (method_exists($plugin, 'registerContentBlocks')) {
                $types = $plugin->registerContentBlocks();

                $this->registerTypes($types);
            }
        }
    }

    protected function registerTypes($types)
    {
        $this->types = array_merge($this->types, $types);
    }
}