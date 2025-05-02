<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Framework\DI\ContainerDI;
use App\Framework\HttpParser\HttpParser;
use App\Framework\Router\HttpRouter;


$container = new ContainerDI(__DIR__ . '/../config/');
$container->compile();

$router = $container->get(HttpRouter::class);
[$className, $methodName] = $router->get('/', 'GET');

$httpRequest = HttpParser::parse($_SERVER);

$instance = $container->get($className);

# call_user_func_array([$instance, $methodName], [$httpRequest]);
