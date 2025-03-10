<?php
require_once __DIR__ . '/../bootstrap/app.php';

$router = new Router();
$request = new Request();

// Initialize core components
$app = new Application($router, $request);
$app->run();
