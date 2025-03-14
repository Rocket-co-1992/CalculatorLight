<?php
define('INSTALL_PATH', __DIR__);
define('ROOT_PATH', dirname(__DIR__));

// Error handling
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Autoloader
spl_autoload_register(function ($class) {
    $class = str_replace('\\', '/', $class);
    $file = INSTALL_PATH . '/src/' . $class . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
});

// Helper functions
function getConfig($key = null) {
    static $config;
    if ($config === null) {
        $config = require INSTALL_PATH . '/config/install.php';
    }
    return $key ? ($config[$key] ?? null) : $config;
}

function isWritable($path) {
    return is_writable(ROOT_PATH . '/' . $path);
}

function generateRandomKey($length = 32) {
    return bin2hex(random_bytes($length / 2));
}
