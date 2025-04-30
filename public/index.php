<?php

require_once '../autoload.php';

use App\DI\ContainerDI;
use App\Router\Router;


$container = new ContainerDI(__DIR__ . '/../config/');
$container->compile();

$router = $container->get(Router::class);
[$handler, $methodName] = $router->get('/', 'GET');

$instance = $container->get($handler);
call_user_func_array([$instance, $methodName], ["one"]);