<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Framework\DI\ContainerDI;
use App\Framework\Http\HttpParser;
use App\Framework\Router\AbstractRouter;


$container = new ContainerDI(__DIR__ . '/../config/');
$container->compile();

$router = $container->get(AbstractRouter::class);
$router->build();

[$controllerClass, $methodName] = $router->get($_SERVER["REQUEST_URI"], $_SERVER["REQUEST_METHOD"]);
$controllerInstance = $container->get($controllerClass);

$httpRequest = HttpParser::parse($_SERVER);

header('Content-Type: application/json; charset=utf-8');
echo call_user_func_array([$controllerInstance, $methodName], [$httpRequest]);
