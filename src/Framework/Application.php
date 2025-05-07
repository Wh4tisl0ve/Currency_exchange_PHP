<?php

namespace App\Framework;

use App\Framework\ArgumentsResolver\ArgumentsResolver;
use App\Framework\Container\Container;
use App\Framework\Contract\AbstractRouter;
use App\Framework\Http\HttpRequest;
use App\Framework\Http\Response\HttpResponse;
use ReflectionMethod;


class Application extends Container
{
    private AbstractRouter $router;

    public function __construct()
    {
        $this->compile();
        $this->router = $this->get(AbstractRouter::class);
        $this->router->build();
    }

    public function handle(HttpRequest $httpRequest): HttpResponse
    {
        $route = $this->router->get($httpRequest->getUri(), $httpRequest->getMethod());

        [$controllerClass, $methodName] = $route['handler'];

        $controllerInstance = $this->get($controllerClass);
        $methodParams = (new ReflectionMethod($controllerInstance, $methodName))->getParameters();

        $args = [$httpRequest, $route['params']];

        $methodArguments = ArgumentsResolver::resolveArguments($methodParams, $args);

        return call_user_func_array([$controllerInstance, $methodName], $methodArguments);
    }
}