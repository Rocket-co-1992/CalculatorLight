<?php
namespace Core;

class Router {
    private $routes = [];
    
    public function add($method, $path, $handler) {
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'handler' => $handler
        ];
    }
    
    public function match($requestMethod, $requestPath) {
        foreach ($this->routes as $route) {
            if ($route['method'] === $requestMethod && $this->matchPath($route['path'], $requestPath)) {
                return $route['handler'];
            }
        }
        throw new \Exception('Route not found', 404);
    }
    
    private function matchPath($routePath, $requestPath) {
        $routeRegex = preg_replace('/\/{([^\/]+)}/', '/([^/]+)', $routePath);
        $routeRegex = '#^' . $routeRegex . '$#';
        return preg_match($routeRegex, $requestPath);
    }

    public function addApi($path, $handler) {
        $this->routes[] = [
            'method' => 'ANY',
            'path' => '/api/' . ltrim($path, '/'),
            'handler' => $handler,
            'type' => 'api'
        ];
    }

    private function handleApiRoute($handler) {
        try {
            $endpoint = new $handler();
            return $endpoint->handle();
        } catch (\Exception $e) {
            return json_encode([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }

    public function handleRequest($request) {
        try {
            if (strpos($request->getPath(), '/api/') === 0) {
                return $this->handleApiRequest($request);
            }
            
            $handler = $this->match($request->getMethod(), $request->getPath());
            return $this->executeHandler($handler, $request);
        } catch (\Exception $e) {
            return $this->handleError($e);
        }
    }

    private function handleApiRequest($request) {
        header('Content-Type: application/json');
        
        try {
            $endpoint = $this->matchApiEndpoint($request->getPath());
            return $endpoint->handle();
        } catch (\Exception $e) {
            http_response_code($e->getCode() ?: 500);
            return json_encode([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }
}
