<?php namespace LearnKit\LMS\Controllers;

use Flash;
use Backend;
use BackendMenu;
use Backend\Classes\Controller;
use LearnKit\LMS\Models\Course;

/**
 * Courses Back-end Controller
 */
class Courses extends Controller
{
    /**
     * @var array Behaviors that are implemented by this controller.
     */
    public $implement = [
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.ListController',
        'Backend.Behaviors.RelationController',
    ];

    /**
     * @var string Configuration file for the `FormController` behavior.
     */
    public $formConfig = 'config_form.yaml';

    /**
     * @var string Configuration file for the `ListController` behavior.
     */
    public $listConfig = 'config_list.yaml';

    public $relationConfig = 'config_relations.yaml';

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('LearnKit.LMS', 'lms', 'courses');
    }

    public function create()
    {
        $this->bodyClass = 'compact-container';

        $this->asExtension('FormController')->create();
    }

    public function update($id)
    {
        $this->bodyClass = 'compact-container';

        $this->asExtension('FormController')->update($id);
    }

    public function onDuplicate($id)
    {
        $course = Course::find($id);

        $newCourse = $course->duplicate();

        Flash::success('Succeeded!');

        return redirect(Backend::url("learnkit/lms/courses/update/{$newCourse->id}"));
    }
}
