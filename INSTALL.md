# Instruções de Instalação - WebPanel

1. REQUISITOS DO SERVIDOR
- PHP 8.0 ou superior
- MySQL 5.7 ou superior
- Extensões PHP: pdo, pdo_mysql, gd, mbstring, xml, curl, zip
- Suporte a mod_rewrite

2. PASSOS DE INSTALAÇÃO
a) Faça upload dos arquivos via File Manager do WebPanel
b) Configure o Document Root para apontar para a pasta /public
c) Crie um banco de dados MySQL no WebPanel
d) Copie .env.example para .env e configure:
   - Credenciais do banco de dados
   - URL da aplicação
   - Configurações de e-mail
e) Execute via SSH ou PHP:
   - composer install
   - php scripts/check-requirements.php
   - php scripts/deploy.php

3. PERMISSÕES DE ARQUIVOS
Execute no terminal SSH:
chmod -R 755 public/
chmod -R 755 storage/
chmod 644 .env
find . -type f -name "*.php" -exec chmod 644 {} \;
