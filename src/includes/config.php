<?php
// includes/config.php

define('APP_NAME', 'My PHP App');
define('APP_VERSION', '1.0.0');
define('ENVIRONMENT', 'development');

// Database configuration for SQLite
define('DB_TYPE', 'sqlite');
define('DB_PATH', __DIR__ . '/database/database.db'); // Adjust the path as needed

// Base URL of the application
define('BASE_URL', 'http://localhost/');

if (ENVIRONMENT === 'development') {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', 0);
    ini_set('display_startup_errors', 0);
    error_reporting(0);
}

// Other configuration constants can be added here
?>