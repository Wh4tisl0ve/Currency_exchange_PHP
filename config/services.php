<?php


use App\DI\ContainerDI;
use App\Router\Router;

return [
    PDO::class => function (ContainerDI $container) {
        $host = getenv('host');
        $port = getenv('port');
        $dbName = getenv('db_name');
        $user = getenv('user');
        $password = getenv('password');

        return new PDO("pgsql:host=$host;port=$port;dbname=$dbName", $user, $password);
    },
    Router::class => function (ContainerDI $container) {
        $router = new Router(__DIR__);
        $router->build();

        return $router;
    },

];