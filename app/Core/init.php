<?php

define('BASE_PATH', realpath(__DIR__ . '/../../'));
define('APP_PATH', BASE_PATH . '/app');

date_default_timezone_set('Asia/Jerusalem');
if(session_status() === PHP_SESSION_NONE) session_start();


// Autoload dependencies (if using Composer)
require_once __DIR__ . '/../../vendor/autoload.php';

// Load environment variables
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../..');
$dotenv->load();

// Connect to the database
require_once __DIR__ . '/Database.php';

// Include the router class
require_once __DIR__ . '/Router.php';

require_once __DIR__ . '/../Helpers/PathHelper.php';

// Include the routes file
require_once __DIR__ . '/../../config/routes.php';

