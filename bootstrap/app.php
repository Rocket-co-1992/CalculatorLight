<?php
// Load composer autoloader
require_once __DIR__ . '/../vendor/autoload.php';

// Load environment variables
$dotenv = \Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

// Initialize services
\Core\Database::init();
\Core\Session::start();

// Set error handling for production
if (config('app.environment') === 'production') {
    error_reporting(0);
    ini_set('display_errors', '0');
}
