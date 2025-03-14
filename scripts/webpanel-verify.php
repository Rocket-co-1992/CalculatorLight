<?php
echo "=== Verificando Ambiente WebPanel ===\n";

$requirements = [
    'PHP Version' => [
        'required' => '8.0.0',
        'current' => PHP_VERSION,
        'status' => version_compare(PHP_VERSION, '8.0.0', '>=')
    ],
    'Extensions' => [
        'pdo_mysql' => extension_loaded('pdo_mysql'),
        'gd' => extension_loaded('gd'),
        'mbstring' => extension_loaded('mbstring'),
        'openssl' => extension_loaded('openssl'),
        'zip' => extension_loaded('zip')
    ],
    'Functions' => [
        'proc_open' => function_exists('proc_open'),
        'exec' => function_exists('exec')
    ],
    'Directories' => [
        'storage' => is_writable(dirname(__DIR__) . '/storage'),
        'public' => is_writable(dirname(__DIR__) . '/public'),
        'temp' => is_writable(dirname(__DIR__) . '/public/temp')
    ]
];

$passed = true;
foreach ($requirements as $category => $checks) {
    echo "\n{$category}:\n";
    foreach ($checks as $name => $result) {
        $status = $result ? '✅' : '❌';
        echo "{$status} {$name}\n";
        if (!$result) $passed = false;
    }
}

if (!$passed) {
    echo "\n❌ Ambiente não atende aos requisitos mínimos.\n";
    exit(1);
}

echo "\n✅ Ambiente WebPanel verificado com sucesso!\n";
