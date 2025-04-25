<?php


use App\DI\ContainerDI;

return [
    PDO::class => function (ContainerDI $container) {
        $host = getenv('host');
        $port = getenv('port');
        $dbName = getenv('db_name');
        $user = getenv('user');
        $password = getenv('password');

        return new PDO("pgsql:host=$host;port=$port;dbname=$dbName", $user, $password);
    },

];