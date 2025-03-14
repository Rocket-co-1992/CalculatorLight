<?php
return [
    'paths' => [
        'public' => '/public_html',
        'storage' => '/storage',
        'temp' => '/public_html/temp',
        'cache' => '/storage/cache'
    ],
    'database' => [
        'type' => 'mysql',
        'charset' => 'utf8mb4',
        'prefix' => 'wtp_'
    ],
    'mail' => [
        'type' => 'smtp',
        'host' => '{webpanel.hostname}',
        'port' => 587,
        'encryption' => 'tls'
    ],
    'security' => [
        'force_ssl' => true,
        'admin_path' => 'admin_' . bin2hex(random_bytes(8)),
        'allowed_ips' => [],
        'rate_limit' => [
            'enabled' => true,
            'max_requests' => 100,
            'per_minutes' => 1
        ]
    ],
    'cache' => [
        'driver' => 'file',
        'path' => '/storage/cache',
        'prefix' => 'wtp_',
        'ttl' => 3600
    ],
    'debug' => [
        'enabled' => false,
        'ips' => []
    ]
];
