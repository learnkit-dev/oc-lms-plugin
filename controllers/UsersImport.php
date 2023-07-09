<?php

namespace LearnKit\LMS\Controllers;

use Backend\Behaviors\ImportExportController;
use Backend\Classes\Controller;
use Backend\Facades\BackendMenu;

class UsersImport extends Controller
{
    public $implement = [
        ImportExportController::class,
    ];

    public $importExportConfig = 'config_import_export.yaml';

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('RainLab.User', 'user', 'import');
    }
}
