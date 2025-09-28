<?php

namespace JamCommits\Router;

class Router {
    private array $routes = [];

    /**
    * Function to add a route handled by the router.
    * @param HttpMethod $method It is the compatible http verb with the route
    * @Param string $path It is the path of the route ex "login" for http://domaine.fr/login
    * @Param callable $callback It is the callback executed when the route is reached.
    */
    public function addRoute(HttpMethod $method, string $path, callable $callback): void {
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'callback' => $callback
        ];
    }


    /**
    * Function that match route with uri and method and execute associated callback.
    * @param $requestUri request uri of the http request
    * @param $requestMethod verb of the http request
    */
    public function dispatch(string $requestUri, string $requestMethod): void {
        $httpMethod = HttpMethod::tryFrom($requestMethod);
        if (!$httpMethod) {
            http_response_code(405);
            echo "405 Method Not Allowed";
            return;
        }

        foreach ($this->routes as $route) {
            if ($route['path'] === $requestUri && $route['method'] === $httpMethod) {
                call_user_func($route['callback']);
                return;
            }
        }

        http_response_code(404);
        echo "404 Not Found";
    }
}
