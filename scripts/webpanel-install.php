<?php
echo "Iniciando instalação no WebPanel...\n";

// Verificar ambiente
if (!file_exists('.env')) {
    copy('.env.example', '.env');
    echo "Arquivo .env criado\n";
}

// Criar diretórios necessários
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
        echo "Diretório criado: {$dir}\n";
    }
}

// Configurar permissões
$permissions = [
    'storage' => 0755,
    'public' => 0755,
    '.env' => 0644,
    'public/.htaccess' => 0644
];

foreach ($permissions as $path => $mode) {
    chmod($path, $mode);
    echo "Permissões definidas para {$path}\n";
}

// Verificar conexão com banco de dados
try {
    require_once 'bootstrap/app.php';
    $db = \Core\Database::getInstance()->getConnection();
    echo "Conexão com banco de dados estabelecida\n";
} catch (Exception $e) {
    die("Erro na conexão com banco de dados: " . $e->getMessage() . "\n");
}

echo "Instalação concluída!\n";
