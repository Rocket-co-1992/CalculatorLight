<?php
namespace Core\API;

abstract class ApiEndpoint {
    protected $db;
    protected $request;
    protected $response;

    public function __construct() {
        $this->db = \Core\Database::getInstance()->getConnection();
        $this->request = new \Core\Request();
        $this->response = ['success' => true];
    }

    abstract public function handle();

    protected function requireAuth() {
        $apiKey = $this->request->getHeader('X-API-Key');
        if (!$this->validateApiKey($apiKey)) {
            throw new \Exception('Invalid API key', 401);
        }
    }

    protected function sendResponse($data = null, $code = 200) {
        http_response_code($code);
        header('Content-Type: application/json');
        echo json_encode(array_merge($this->response, ['data' => $data]));
        exit;
    }
}
