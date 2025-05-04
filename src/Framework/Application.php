<?php

namespace App\Framework;

use ReflectionMethod;
use App\Framework\Http\HttpRequest;
use App\Framework\ArgumentsResolver\ArgumentsResolver;
use App\Framework\Container\Container;
use App\Framework\Router\AbstractRouter;


class Application extends Container
{
    private AbstractRouter $router;

    public function __construct()
    {
        $this->compile();
        $this->router = $this->get(AbstractRouter::class);
        $this->router->build();
    }

    public function handle(HttpRequest $httpRequest): void
    {
        $route = $this->router->get($httpRequest->getUri(), $httpRequest->getMethod());

        [$controllerClass, $methodName] = $route['handler'];

        $controllerInstance = $this->get($controllerClass);
        $methodParams = (new ReflectionMethod($controllerInstance, $methodName))->getParameters();

        $args = [$httpRequest, $route['params']];

        $methodArguments = ArgumentsResolver::resolveArguments($methodParams, $args);

        echo call_user_func_array([$controllerInstance, $methodName], $methodArguments);
    }
}