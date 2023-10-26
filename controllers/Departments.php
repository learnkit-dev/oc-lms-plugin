<?php namespace LearnKit\LMS\Controllers;

use Backend\Behaviors\ImportExportController;
use Backend\Behaviors\RelationController;
use BackendMenu;
use Backend\Classes\Controller;

/**
 * Departments Backend Controller
 */
class Departments extends Controller
{
    public $implement = [
        \Backend\Behaviors\FormController::class,
        \Backend\Behaviors\ListController::class,
        RelationController::class,
        ImportExportController::class,
    ];

    /**
     * @var string formConfig file
     */
    public $formConfig = 'config_form.yaml';

    /**
     * @var string listConfig file
     */
    public $listConfig = 'config_list.yaml';

    public $relationConfig = 'config_relations.yaml';

    public $importExportConfig = 'config_import_export.yaml';

    /**
     * __construct the controller
     */
    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('LearnKit.LMS', 'lms', 'departments');
    }
}
