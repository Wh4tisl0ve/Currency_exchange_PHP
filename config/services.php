<?php


use App\Framework\DI\ContainerDI;
use App\Framework\Router\HttpRouter;

return [
    PDO::class => function (ContainerDI $container) {
        $host = getenv('host');
        $port = getenv('port');
        $dbName = getenv('db_name');
        $user = getenv('user');
        $password = getenv('password');

        return new PDO("pgsql:host=$host;port=$port;dbname=$dbName", $user, $password);
    },
    HttpRouter::class => function (ContainerDI $container) {
        $router = new HttpRouter(__DIR__);
        $router->build();

        return $router;
    },

];