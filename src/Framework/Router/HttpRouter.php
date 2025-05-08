<?php

namespace App\Framework\Router;


use App\Framework\Contract\AbstractRouter;
use App\Framework\Router\Exception\RouteExistsException;
use App\Framework\Router\Exception\RouteNotFoundException;

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
            return [
                "handler" => $this->routes[$method][$nameRoute],
                "params" => []
            ];
        }

        if(isset($this->routes[$method])){
            $routes = $this->routes[$method];
            foreach ($routes as $route => $handler) {
                if (preg_match($route, $nameRoute, $params)) {
                    $params = array_filter($params, 'is_string', ARRAY_FILTER_USE_KEY);
                    return [
                        "handler" => $handler,
                        "params" => $params
                    ];
                }
            }
        }

        throw new RouteNotFoundException("Не найдено обработчика для "  . $nameRoute);
    }
}
