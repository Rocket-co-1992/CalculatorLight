<?php
return [
    'paths' => [
        'public' => '/public',
        'storage' => '/storage',
        'temp' => '/public/temp',
        'uploads' => '/public/uploads'
    ],
    'database' => [
        'type' => 'mysql',
        'prefix' => 'wtp_'
    ],
    'security' => [
        'force_ssl' => true,
        'allowed_ips' => [],
        'admin_path' => 'admin_' . bin2hex(random_bytes(8))
    ],
    'email' => [
        'driver' => 'smtp',
        'host' => '{cpanel.hostname}',
        'port' => 587,
        'encryption' => 'tls'
    ],
    'caching' => [
        'enabled' => true,
        'driver' => 'file',
        'ttl' => 3600
    ]
];
