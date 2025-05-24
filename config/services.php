<?php

use App\Controller\CurrencyController;
use App\Controller\ExchangeRateController;
use App\Controller\ExchangerController;
use App\DAO\Currency\CurrencyDAOInterface;
use App\DAO\Currency\DBCurrencyDAO;
use App\DAO\ExchangeRate\DBExchangeRateDAO;
use App\DAO\ExchangeRate\ExchangeRateDAOInterface;
use App\Service\ExchangerService;
use MiniBox\Container\Container;


return [
    PDO::class => function (Container $container) {
        $host = getenv('DB_HOST');
        $port = getenv('DB_PORT');
        $dbName = getenv('DB_NAME');
        $user = getenv('DB_USER');
        $password = getenv('DB_PASSWORD');

        return new PDO("pgsql:host=$host;port=$port;dbname=$dbName", $user, $password);
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
    },
    ExchangerController::class => function (Container $container) {
        return new ExchangerController(
            $container->get(ExchangeRateDAOInterface::class),
            $container->get(CurrencyDAOInterface::class),
        );
    }
];