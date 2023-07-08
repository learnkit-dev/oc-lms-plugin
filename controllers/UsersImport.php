<?php

namespace LearnKit\LMS\Controllers;

use Backend\Behaviors\ImportExportController;
use Backend\Classes\Controller;

class UsersImport extends Controller
{
    public $implement = [
        ImportExportController::class,
    ];

    public $importExportConfig = 'config_import_export.yaml';
}
