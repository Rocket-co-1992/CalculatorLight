<?php
namespace Core\API;

abstract class APIEndpoint {
    protected $db;
    protected $request;

    public function __construct() {
        $this->db = \Core\Database::getInstance()->getConnection();
        $this->request = new \Core\Request();
    }

    abstract public function handle();

    protected function response($data, $code = 200) {
        http_response_code($code);
        return json_encode([
            'success' => $code >= 200 && $code < 300,
            'data' => $data
        ]);
    }

    protected function validateApiKey() {
        $apiKey = $this->request->getHeader('X-API-Key');
        if (!$apiKey) {
            throw new \Exception('API key required', 401);
        }
        // Implement API key validation
        return true;
    }
}
