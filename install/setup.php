<?php
require_once __DIR__ . '/../vendor/autoload.php';

$installer = new \Install\Install();

// Check requirements
if (!$installer->checkRequirements()) {
    die("System requirements not met. Please check the error log.");
}

// Create directories
$directories = [
    'storage/app/public',
    'storage/framework/cache',
    'storage/framework/sessions',
    'storage/framework/views',
    'storage/logs',
    'public/uploads/products',
    'public/uploads/designs',
    'public/uploads/temp'
];

foreach ($directories as $dir) {
    if (!file_exists($dir)) {
        mkdir($dir, 0755, true);
    }
}

// Set permissions
$permissions = [
    'storage' => 0755,
    'public' => 0755,
    'public/uploads' => 0755
];

foreach ($permissions as $path => $mode) {
    chmod($path, $mode);
}
