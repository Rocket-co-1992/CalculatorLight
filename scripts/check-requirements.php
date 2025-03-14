<?php
echo "Verificando requisitos do sistema...\n";

$requirements = [
    'PHP Version >= 8.0' => version_compare(PHP_VERSION, '8.0.0', '>='),
    'PDO Extension' => extension_loaded('pdo'),
    'PDO MySQL Extension' => extension_loaded('pdo_mysql'),
    'GD Extension' => extension_loaded('gd'),
    'JSON Extension' => extension_loaded('json'),
    'Mbstring Extension' => extension_loaded('mbstring'),
    'XML Extension' => extension_loaded('xml'),
    'ZIP Extension' => extension_loaded('zip'),
    'OpenSSL Extension' => extension_loaded('openssl'),
    'Curl Extension' => extension_loaded('curl')
];

$passed = true;
foreach ($requirements as $requirement => $satisfied) {
    echo $requirement . ': ' . ($satisfied ? '✅' : '❌') . "\n";
    if (!$satisfied) {
        $passed = false;
    }
}

if (!$passed) {
    echo "\n❌ Seu servidor não atende a todos os requisitos.\n";
    exit(1);
}

echo "\n✅ Seu servidor atende a todos os requisitos!\n";
