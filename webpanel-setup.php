<?php
echo "=== Configuração WebPanel ===\n";

// Verificar requisitos
$requirements = [
    'PHP Version' => PHP_VERSION,
    'MySQL Version' => (new PDO("mysql:host=localhost", "root", ""))->getAttribute(PDO::ATTR_SERVER_VERSION),
    'Document Root' => $_SERVER['DOCUMENT_ROOT'],
    'Max Upload' => ini_get('upload_max_filesize'),
    'Memory Limit' => ini_get('memory_limit')
];

foreach ($requirements as $key => $value) {
    echo "{$key}: {$value}\n";
}

// Criar estrutura de diretórios
$directories = [
    'storage/app/public',
    'storage/cache',
    'storage/logs',
    'public/uploads',
    'public/temp'
];

foreach ($directories as $dir) {
    if (!file_exists($dir)) {
        mkdir($dir, 0755, true);
        echo "Diretório criado: {$dir}\n";
    }
}

// Verificar permissões
$permissions = [
    'storage' => 0755,
    'public' => 0755,
    '.env' => 0644
];

foreach ($permissions as $path => $mode) {
    chmod($path, $mode);
}
