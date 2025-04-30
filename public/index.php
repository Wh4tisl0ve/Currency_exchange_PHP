<?php

require_once '../autoload.php';

use App\DI\ContainerDI;
use App\Router\Router;


$container = new ContainerDI(__DIR__ . '/../config/');
$container->compile();

$router = $container->get(Router::class);
[$className, $methodName] = $router->get('/', 'GET');

$instance = $container->get($className);
