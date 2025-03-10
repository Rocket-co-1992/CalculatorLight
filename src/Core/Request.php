<?php
namespace Core;

class Request {
    private $params;
    private $method;
    private $path;

    public function __construct() {
        $this->params = array_merge($_GET, $_POST);
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    }

    public function getMethod() {
        return $this->method;
    }

    public function getPath() {
        return $this->path;
    }

    public function getParam($key, $default = null) {
        return $this->params[$key] ?? $default;
    }

    public function isAjax() {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
               strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }

    public function getHeader($name) {
        $headerName = 'HTTP_' . strtoupper(str_replace('-', '_', $name));
        return $_SERVER[$headerName] ?? null;
    }

    public function getJsonBody() {
        $json = file_get_contents('php://input');
        return json_decode($json, true) ?? [];
    }
}
