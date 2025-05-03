<?php

use App\CurrencyExchange\Controller\CurrencyController;
use App\CurrencyExchange\DAO\Currency\CurrencyDAOInterface;
use App\CurrencyExchange\DAO\Currency\DBCurrencyDAO;
use App\Framework\DI\ContainerDI;
use App\Framework\Router\AbstractRouter;
use App\Framework\Router\HttpRouter;


return [
    PDO::class => function (ContainerDI $container) {
        $host = getenv('DB_HOST');
        $port = getenv('DB_PORT');
        $dbName = getenv('DB_NAME');
        $user = getenv('DB_USER');
        $password = getenv('DB_PASSWORD');

        return new PDO("pgsql:host=$host;port=$port;dbname=$dbName", $user, $password);
    },
    AbstractRouter::class => function (ContainerDI $container) {
        return new HttpRouter(__DIR__);
    },

    # DAO
    CurrencyDAOInterface::class => function (ContainerDI $container) {
        return new DBCurrencyDAO($container->get(PDO::class));
    },

    # Controller
    CurrencyController::class => function (ContainerDI $container) {
        return new CurrencyController($container->get(CurrencyDAOInterface::class));
    }
];