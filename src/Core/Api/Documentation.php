<?php
namespace Core\Api;

class Documentation {
    private $endpoints = [];

    public function addEndpoint($method, $path, $description, $params = [], $responses = []) {
        $this->endpoints[] = [
            'method' => $method,
            'path' => $path,
            'description' => $description,
            'parameters' => $params,
            'responses' => $responses
        ];
    }

    public function generateDocs() {
        return [
            'api_version' => '1.0',
            'base_url' => '/api/v1',
            'endpoints' => $this->endpoints
        ];
    }
}
