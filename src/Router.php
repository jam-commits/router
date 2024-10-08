<?php

namespace JamCommits\Router;

class Router {
    private array $routes = [];

    public function addRoute(HttpMethod $method, string $path, callable $callback): void {
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'callback' => $callback
        ];
    }

    public function dispatch(string $requestUri, string $requestMethod): void {
        $httpMethod = HttpMethod::tryFrom($requestMethod);
        if (!$httpMethod) {
            http_response_code(405); // 405 Method Not Allowed
            echo "405 Method Not Allowed";
            return;
        }

        foreach ($this->routes as $route) {
            if ($route['path'] === $requestUri && $route['method'] === $httpMethod) {
                $route['callback'](); // First-class callable, mais pas de return nécessaire
                return;
            }
        }

        http_response_code(404);
        echo "404 Not Found";
    }
}
