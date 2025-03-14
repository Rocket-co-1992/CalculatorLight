<?php
// Verificar ambiente de produção
if (file_exists(__DIR__ . '/../.env.production')) {
    require_once __DIR__ . '/../bootstrap/production.php';
} else {
    require_once __DIR__ . '/../bootstrap/app.php';
}

// Verificar modo de manutenção
if (file_exists(__DIR__ . '/maintenance.flag')) {
    require 'maintenance.php';
    exit;
}

try {
    // Initialize router
    $router = new \Core\Router();
    
    // Define routes
    require_once __DIR__ . '/../routes/web.php';
    require_once __DIR__ . '/../routes/api.php';
    
    // Handle request with error catching
    $response = $router->dispatch();
    echo $response;
} catch (\Exception $e) {
    // Log error silently in production
    error_log($e->getMessage());
    
    // Show generic error in production
    header('HTTP/1.1 500 Internal Server Error');
    if (getenv('APP_DEBUG') === 'true') {
        echo $e->getMessage();
    } else {
        require 'error.php';
    }
}
