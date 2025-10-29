<?php
// ==============================================================
// PLAYLYTICS - Configuration File
// ==============================================================

// Database Configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'playlytics_db');

// Application Configuration
define('BASE_URL', 'http://localhost/Playlytics/');
define('APP_NAME', 'Playlytics');
define('TIMEZONE', 'UTC');

// Admin Configuration
define('ADMIN_EMAIL', 'admin@playlytics.com');
define('ADMIN_PASSWORD', 'admin123'); // Change in production

// Session Configuration - Start only if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Set timezone
date_default_timezone_set(TIMEZONE);

// Enable error reporting for development
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
