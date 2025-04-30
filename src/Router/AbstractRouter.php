<?php

namespace App\Router;

use App\Router\Exception\FailReadRoutesConfigException;
use App\Router\Exception\RouteExistsException;
use App\Router\Exception\RouteNotExistsException;


abstract class AbstractRouter
{
    private array $routes = [];

    private string $configPath;

    public function __construct(string $configPath)
    {
        $this->configPath = $configPath;
    }

    public function build(string $filename = 'routes.php'): void
    {
        $routes = require $this->configPath . "/$filename";

        foreach ($routes as $method => $route) {
            foreach ($route as $path => $handler) {
                $this->register($handler, $path, $method);
            }
        }
    }

    public function register(array $handler, string $path, string $method = 'GET'): void
    {
        if (isset($this->routes[$method][$path])) {
            throw new RouteExistsException("Маршрут для method: $method Уже существует");
        }
        $this->routes[$method][$path] = $handler;
    }

    public function get(string $path, string $method = 'GET'): array
    {
        if (isset($this->routes[$method][$path])) {
            return $this->routes[$method][$path];
        }
        throw new RouteNotExistsException("Не найдено обработчика для $path");
    }
}