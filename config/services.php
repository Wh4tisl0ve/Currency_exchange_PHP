<?php

use App\CurrencyExchange\Controller\CurrencyController;
use App\CurrencyExchange\Controller\ExchangeRateController;
use App\CurrencyExchange\DAO\Currency\CurrencyDAOInterface;
use App\CurrencyExchange\DAO\Currency\DBCurrencyDAO;
use App\CurrencyExchange\DAO\ExchangeRate\DBExchangeRateDAO;
use App\CurrencyExchange\DAO\ExchangeRate\ExchangeRateDAOInterface;
use App\Framework\Container\Container;
use App\Framework\Contract\AbstractRouter;
use App\Framework\Router\HttpRouter;


return [
    PDO::class => function (Container $container) {
        $host = getenv('DB_HOST');
        $port = getenv('DB_PORT');
        $dbName = getenv('DB_NAME');
        $user = getenv('DB_USER');
        $password = getenv('DB_PASSWORD');

        return new PDO("pgsql:host=$host;port=$port;dbname=$dbName", $user, $password);
    },
    AbstractRouter::class => function (Container $container) {
        return new HttpRouter(__DIR__);
    },

    # DAO
    CurrencyDAOInterface::class => function (Container $container) {
        return new DBCurrencyDAO($container->get(PDO::class));
    },
    ExchangeRateDAOInterface::class => function (Container $container) {
        return new DBExchangeRateDAO($container->get(PDO::class));
    },

    # Controller
    CurrencyController::class => function (Container $container) {
        return new CurrencyController($container->get(CurrencyDAOInterface::class));
    },
    ExchangeRateController::class => function (Container $container) {
        return new ExchangeRateController(
            $container->get(ExchangeRateDAOInterface::class),
            $container->get(CurrencyDAOInterface::class),
        );
    }
];