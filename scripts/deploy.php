<?php
echo "Starting deployment...\n";

// Directories to create
$dirs = [
    'storage/app/public',
    'storage/framework/cache',
    'storage/framework/sessions',
    'storage/framework/views',
    'storage/logs'
];

foreach ($dirs as $dir) {
    if (!file_exists($dir)) {
        mkdir($dir, 0755, true);
        echo "Created directory: $dir\n";
    }
}

// Set permissions
$commands = [
    'chmod -R 755 public/',
    'chmod -R 755 storage/',
    'chmod 644 .env',
    'chmod 644 public/.htaccess'
];

foreach ($commands as $command) {
    shell_exec($command);
    echo "Executed: $command\n";
}

echo "Deployment completed!\n";
