<?php


if (!defined('APP_NAME')) {
    define('APP_NAME', 'My PHP App');
}

if (!defined('APP_VERSION')) {
    define('APP_VERSION', '1.0.0');
}

if (!defined('ENVIRONMENT')) {
    define('ENVIRONMENT', 'development');
}

// Database configuration for SQLite
if (!defined('DB_TYPE')) {
    define('DB_TYPE', 'sqlite');
}

if (!defined('DB_PATH')) {
    define('DB_PATH', __DIR__ . '/database/database.db'); // Adjust the path as needed
}

// Base URL of the application
if (!defined('BASE_URL')) {
    define('BASE_URL', 'http://localhost/'); // Adjust as needed
}

if (ENVIRONMENT === 'development') {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', 0);
    ini_set('display_startup_errors', 0);
    error_reporting(0);
}


?>