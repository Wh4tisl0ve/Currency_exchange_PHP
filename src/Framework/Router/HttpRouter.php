<?php

namespace App\Framework\Router;


use App\Framework\Router\Exception\RouteExistsException;
use App\Framework\Router\Exception\RouteNotExistsException;

class HttpRouter extends AbstractRouter
{
    public function build(string $filename = 'routes.php'): void
    {
        $routes = require $this->configPath . "/$filename";

        foreach ($routes as $method => $route) {
            foreach ($route as $path => $handler) {
                $this->register($handler, $path, $method);
            }
        }
    }

    public function register(array $handler, string $nameRoute, string $method = 'GET'): void
    {
        if (isset($this->routes[$method][$nameRoute])) {
            throw new RouteExistsException("Маршрут для method: $method Уже существует");
        }
        $this->routes[$method][$nameRoute] = $handler;
    }

    public function get(string $nameRoute, string $method = 'GET'): array
    {
        if (isset($this->routes[$method][$nameRoute])) {
            return $this->routes[$method][$nameRoute];
        }
        throw new RouteNotExistsException("Не найдено обработчика для $nameRoute");
    }
}
