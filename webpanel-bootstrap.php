<?php
define('START_TIME', microtime(true));

// Verificar ambiente WebPanel
if (!defined('WEBPANEL_PATH')) {
    define('WEBPANEL_PATH', dirname(__FILE__));
}

// Carregar configurações do WebPanel
require_once WEBPANEL_PATH . '/config/webpanel.php';

// Inicializar serviços
require_once WEBPANEL_PATH . '/bootstrap/app.php';

// Configurar logging
error_reporting(E_ALL & ~E_DEPRECATED & ~E_STRICT);
ini_set('display_errors', '0');
ini_set('log_errors', '1');
ini_set('error_log', WEBPANEL_PATH . '/storage/logs/php-error.log');
