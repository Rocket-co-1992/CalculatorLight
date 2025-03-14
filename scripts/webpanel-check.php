<?php
echo "=== Verificação de Compatibilidade WebPanel ===\n";

function checkRequirement($name, $check, $required = true) {
    echo $name . ": ";
    if ($check) {
        echo "✅ OK\n";
        return true;
    } else {
        echo $required ? "❌ FALHOU (Obrigatório)\n" : "⚠️ AVISO\n";
        return !$required;
    }
}

$checks = [
    'PHP Version >= 8.0' => version_compare(PHP_VERSION, '8.0.0', '>='),
    'MySQL Support' => extension_loaded('pdo_mysql'),
    'GD Library' => extension_loaded('gd'),
    'Mod Rewrite' => in_array('mod_rewrite', apache_get_modules()),
    'SSL Available' => !empty($_SERVER['HTTPS']),
    'Storage Writable' => is_writable('storage'),
    'Temp Directory' => is_writable('public/temp')
];

$passed = true;
foreach ($checks as $name => $result) {
    if (!checkRequirement($name, $result)) {
        $passed = false;
    }
}

if (!$passed) {
    echo "\n⚠️ Alguns requisitos não foram atendidos. Revise as configurações.\n";
    exit(1);
}

echo "\n✅ Sistema compatível com WebPanel!\n";
