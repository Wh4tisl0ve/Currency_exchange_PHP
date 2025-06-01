<?php


use App\Controller\CurrencyController;
use App\Controller\ExchangeRateController;
use App\Controller\ExchangerController;

return [
    'GET' => [
        "#/api/currencies$#" => [CurrencyController::class, 'getAllCurrencies'],
        "#^/api/currency/(?P<currencyCode>[a-zA-Z]{3})$#" => [CurrencyController::class, 'getCurrency'],
        "#/api/exchangeRates#" => [ExchangeRateController::class, 'getAllExchangeRates'],
        "#^/api/exchangeRate/(?P<currencyPair>[a-zA-Z]{6})$#" => [ExchangeRateController::class, 'getExchangeRate'],
        "#^/api/exchange#" => [ExchangerController::class, 'exchange'],
    ],
    'POST' => [
        "#/api/currencies$#" => [CurrencyController::class, 'addCurrency'],
        "#/api/exchangeRates$#" => [ExchangeRateController::class, 'addExchangeRate'],
    ],
    'PATCH' => [
        "#^/api/exchangeRate/(?P<currencyPair>[a-zA-Z]{6})$#" => [ExchangeRateController::class, 'updateExchangeRate'],
    ]
];