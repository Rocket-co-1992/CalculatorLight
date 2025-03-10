<?php
namespace Core\Documentation;

class APIDocGenerator {
    private $routes;
    private $outputPath;

    public function __construct($routes) {
        $this->routes = $routes;
        $this->outputPath = __DIR__ . '/../../../docs/api';
    }

    public function generate() {
        $documentation = [];
        foreach ($this->routes as $route) {
            if ($route['type'] === 'api') {
                $documentation[] = $this->generateEndpointDoc($route);
            }
        }
        return $this->saveDocumentation($documentation);
    }

    private function generateEndpointDoc($route) {
        $reflection = new \ReflectionClass($route['handler']);
        $docComment = $reflection->getDocComment();
        
        return [
            'path' => $route['path'],
            'method' => $route['method'],
            'description' => $this->parseDocComment($docComment),
            'parameters' => $this->getMethodParameters($reflection)
        ];
    }
}
