<?php namespace LearnKit\LMS;

use Event;
use Backend;
use LearnKit\LMS\Classes\Extend\Codecycler\Teams;
use LearnKit\LMS\Classes\Extend\LearnKit\Course;
use LearnKit\LMS\Classes\Extend\LearnKit\Page;
use System\Classes\PluginBase;
use LearnKit\LMS\ContentBlocks\H5P;
use LearnKit\LMS\ContentBlocks\Text;
use LearnKit\LMS\ContentBlocks\Form;
use LearnKit\LMS\ContentBlocks\Chart;
use LearnKit\LMS\ContentBlocks\Report;
use LearnKit\LMS\ContentBlocks\Custom;
use LearnKit\LMS\Classes\Helper\H5pHelper;
use LearnKit\LMS\Classes\Extend\RainLab\User;
use LearnKit\LMS\ContentBlocks\PickAnItem;
use LearnKit\LMS\ContentBlocks\CreateAccount;
use LearnKit\LMS\Classes\Helper\ResultHelper;
use LearnKit\LMS\Classes\Helper\ContentBlockHelper;
use LearnKit\LMS\Classes\Extend\LearnKit\H5pResult;
use LearnKit\LMS\Classes\Extend\LearnKit\ContentBlock;
use System\Classes\PluginManager;

/**
 * LMS Plugin Information File
 */
class Plugin extends PluginBase
{
    public $require = [
        'RainLab.User',
        'LearnKit.H5p',
    ];

    /**
     * Returns information about this plugin.
     *
     * @return array
     */
    public function pluginDetails()
    {
        return [
            'name'        => 'LMS',
            'description' => 'No description provided yet...',
            'author'      => 'LearnKit',
            'icon'        => 'icon-leaf'
        ];
    }

    /**
     * Boot method, called right before the request route.
     *
     * @return array
     */
    public function boot()
    {
        $contentBlockHelper = ContentBlockHelper::instance();

        Event::subscribe(User::class);
        Event::subscribe(ContentBlock::class);
        Event::subscribe(H5pResult::class);

        if (PluginManager::instance()->exists('Codecycler.Teams')) {
            Event::subscribe(Teams::class);
            Event::subscribe(Course::class);
            Event::subscribe(Page::class);
        }

        // Extend H5P styles
        Event::listen('learnkit.h5p.extendStyles', function () {
            return ['/h5p_override_styles.css'];
        });
    }

    /**
     * Registers any front-end components implemented in this plugin.
     *
     * @return array
     */
    public function registerComponents()
    {
        return [
            'LearnKit\LMS\Components\Course'    => 'lmsCourse',
            'LearnKit\LMS\Components\Page'      => 'lmsPage',
        ];
    }

    /**
     * Registers any back-end permissions used by this plugin.
     *
     * @return array
     */
    public function registerPermissions()
    {
        return [
            'learnkit.lms.manage_courses' => [
                'tab' => 'learnkit.lms::lang.plugin.name',
                'label' => 'learnkit.lms::lang.permissions.manage_courses'
            ],
            'learnkit.lms.manage_pages' => [
                'tab' => 'learnkit.lms::lang.plugin.name',
                'label' => 'learnkit.lms::lang.permissions.manage_pages'
            ],
        ];
    }

    /**
     * Registers back-end navigation items for this plugin.
     *
     * @return array
     */
    public function registerNavigation()
    {
        return [
            'lms' => [
                'label'       => 'learnkit.lms::lang.menu.material',
                'url'         => Backend::url('learnkit/lms/courses'),
                'icon'        => 'icon-star',
                'permissions' => ['learnkit.lms.*'],
                'order'       => 500,
                'sideMenu'    => [
                    'courses' => [
                        'label'         => 'learnkit.lms::lang.menu.courses',
                        'url'           => Backend::url('learnkit/lms/courses'),
                        'icon'          => 'icon-university',
                        'permissions'   => ['learnkit.lms.manage_courses'],
                    ],
                    'pages' => [
                        'label'         => 'learnkit.lms::lang.menu.pages',
                        'url'           => Backend::url('learnkit/lms/pages'),
                        'icon'          => 'icon-window-maximize',
                        'permissions'   => ['learnkit.lms::manage_pages'],
                    ],
                    'results' => [
                        'label'         => 'learnkit.lms::lang.menu.results',
                        'url'           => Backend::url('learnkit/lms/results'),
                        'icon'          => 'icon-star',
                        'permissions'   => ['learnkit.lms::manage_pages'],
                    ],
                ],
            ],
        ];
    }

    public function registerContentBlocks()
    {
        return [
            Text::class,
            H5P::class,
            PickAnItem::class,
            Form::class,
            CreateAccount::class,
            Chart::class,
            Report::class,
            Custom::class,
        ];
    }

    public function registerMarkupTags()
    {
        return [
            'filters' => [
                // A local method, i.e $this->makeTextAllCaps()
                'encodeUrl' => [$this, 'encodeUrl']
            ],
            'functions' => [
                'resultForPage' => [ResultHelper::class, 'forPage'],

                'resultForBlock' => [ResultHelper::class, 'forBlock'],

                'resultForCourse' => [ResultHelper::class, 'forCourse'],

                'getH5pContent' => [H5pHelper::class, 'getContentById'],

                'percentageById' => [H5pHelper::class, 'percentageById'],
            ],
        ];
    }

    public function encodeUrl($text)
    {
        //$encoded = str_replace(':', '%3A', $text);
        //$encoded = str_replace(',', '%2C', $encoded);
        return rawurlencode($text);
    }

}
