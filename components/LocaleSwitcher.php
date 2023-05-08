<?php

namespace LearnKit\LMS\Components;

use Cms\Classes\ComponentBase;
use RainLab\Translate\Models\Locale;

class LocaleSwitcher extends ComponentBase
{

    public $options = [];

    public function componentDetails()
    {
        return [
            'name' => 'Locale switcher',
            'description' => 'Use this one instead of the RainLab.Translate plugin',
        ];
    }

    public function defineProperties()
    {
        return [];
    }

    public function onRun()
    {
        $locale = null;

        if (! auth()->check()) {
            $locale = 'nl';
        }

        $this->page['activeLocale'] = $locale ? $locale : auth()->user()->locale;
        $this->page['locales'] = Locale::pluck('name', 'code')->toArray();
    }

    public function onSwitchLocale()
    {
        $user = auth()->user();

        $user->locale = input('locale');

        $user->save();

        return redirect()->refresh();
    }
}
