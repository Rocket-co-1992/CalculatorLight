<?php
require_once __DIR__ . '/../../bootstrap/app.php';

header('Content-Type: application/json');

$api = new \Core\API\APIController();

try {
    $endpoint = $_GET['endpoint'] ?? '';
    $method = $_SERVER['REQUEST_METHOD'];
    $data = json_decode(file_get_contents('php://input'), true) ?? [];
    
    $response = $api->handleRequest($endpoint, $method, $data);
    echo json_encode($response);
} catch (\Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
