<?php namespace LearnKit\LMS;

use Event;
use Backend;
use System\Classes\PluginBase;
use LearnKit\LMS\ContentBlocks\H5P;
use LearnKit\LMS\ContentBlocks\Text;
use LearnKit\LMS\ContentBlocks\Form;
use LearnKit\LMS\Classes\Extend\RainLab\User;
use LearnKit\LMS\ContentBlocks\PickAnItem;
use LearnKit\LMS\Classes\Helper\ContentBlockHelper;
use LearnKit\LMS\Classes\Extend\LearnKit\ContentBlock;

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
     * Register method, called when the plugin is first registered.
     *
     * @return void
     */
    public function register()
    {

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
                        'icon'          => 'icon-star',
                        'permissions'   => ['learnkit.lms.manage_courses'],
                    ],
                    'pages' => [
                        'label'         => 'learnkit.lms::lang.menu.pages',
                        'url'           => Backend::url('learnkit/lms/pages'),
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
        ];
    }
}
