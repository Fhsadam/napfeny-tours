<?php

namespace App\Core;

class Router
{
    private array $routes = [
        'GET' => [],
        'POST' => [],
    ];

    public function get(string $path, callable|array $handler): void
    {
        $this->routes['GET'][] = [$path, $handler];
    }

    public function post(string $path, callable|array $handler): void
    {
        $this->routes['POST'][] = [$path, $handler];
    }

    public function dispatch(): void
    {
        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        $route = current_route();

        foreach ($this->routes[$method] ?? [] as [$path, $handler]) {
            $path = trim($path, '/');
            $pattern = preg_replace('#\{([a-zA-Z_][a-zA-Z0-9_]*)\}#', '(?P<$1>[^/]+)', $path);
            $pattern = '#^' . $pattern . '$#';

            if (preg_match($pattern, $route, $matches)) {
                $params = array_filter($matches, static fn ($key) => !is_int($key), ARRAY_FILTER_USE_KEY);
                if (is_array($handler) && is_string($handler[0])) {
                    $controller = new $handler[0]();
                    $methodName = $handler[1];
                    $controller->$methodName(...array_values($params));
                    return;
                }
                $handler(...array_values($params));
                return;
            }
        }

        http_response_code(404);
        render('404', ['title' => '404 - Az oldal nem található']);
    }
}
