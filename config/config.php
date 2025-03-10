<?php
return [
    'database' => [
        'host' => 'localhost',
        'dbname' => 'webtoprint',
        'username' => 'root',
        'password' => '',
        'charset' => 'utf8mb4'
    ],
    'app' => [
        'name' => 'Web-to-Print',
        'url' => 'http://localhost',
        'debug' => true,
        'timezone' => 'Europe/Lisbon'
    ],
    'security' => [
        'salt' => 'your-random-salt-here',
        'session_timeout' => 3600
    ],
    'email' => [
        'smtp_host' => 'smtp.example.com',
        'smtp_port' => 587,
        'smtp_user' => 'your-email@example.com',
        'smtp_pass' => 'your-password',
        'from_email' => 'noreply@example.com',
        'from_name' => 'Web-to-Print System'
    ],
    'products' => [
        'image_path' => '/uploads/products/',
        'allowed_extensions' => ['pdf', 'ai', 'eps', 'psd', 'jpg', 'png'],
        'max_file_size' => 50 * 1024 * 1024 // 50MB
    ],
    'payment' => [
        'mbway' => [
            'api_key' => 'your-mbway-api-key',
            'merchant_id' => 'your-merchant-id',
            'callback_url' => 'https://your-domain.com/payment/callback'
        ],
        'multibanco' => [
            'entity' => '12345',
            'subentity' => '123'
        ],
        'credit_card' => [
            'merchant_id' => 'your-cc-merchant-id',
            'public_key' => 'your-public-key',
            'private_key' => 'your-private-key'
        ]
    ],
    'print_service' => [
        'endpoint' => 'https://api.printservice.com/v1',
        'api_key' => 'your-print-service-api-key',
        'webhook_secret' => 'your-webhook-secret',
        'notification_email' => 'prints@yourdomain.com'
    ],
    'api' => [
        'version' => 'v1',
        'rate_limit' => 100, // requests per minute
        'doc_path' => '/docs/api'
    ],
    'queue' => [
        'host' => 'localhost',
        'port' => 5672,
        'user' => 'guest',
        'password' => 'guest',
        'vhost' => '/',
        'queues' => [
            'print_jobs' => [
                'durable' => true,
                'auto_delete' => false
            ],
            'notifications' => [
                'durable' => true,
                'auto_delete' => false
            ]
        ]
    ],
    'qa' => [
        'minimum_score' => 0.8,
        'checks' => [
            'design' => true,
            'prepress' => true,
            'materials' => true,
            'color_profile' => true
        ],
        'thresholds' => [
            'resolution' => 300,
            'color_accuracy' => 0.95,
            'bleed_margin' => 3
        ]
    ]
];
