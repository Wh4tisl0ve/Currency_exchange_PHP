<?php


use App\CurrencyExchange\Controller\CurrencyController;
use App\CurrencyExchange\Controller\ExchangeRateController;

return [
    'GET' => [
        "#/currencies$#" => [CurrencyController::class, 'getAllCurrencies'],
        "#^/currency/(?P<currencyCode>[a-zA-Z]{3})$#" => [CurrencyController::class, 'getCurrency'],
        "#/exchangeRates#" => [ExchangeRateController::class, 'getAllExchangeRates'],
        "#^/exchangeRate/(?P<currencyPair>[a-zA-Z]{6})$#" => [ExchangeRateController::class, 'getExchangeRate'],
    ],
    'POST' => [
        "#/currencies$#" => [CurrencyController::class, 'addCurrency'],
        "#/exchangeRates$#" => [ExchangeRateController::class, 'addExchangeRate'],
    ],
    'PATCH' => [
        "#^/exchangeRate/(?P<currencyPair>[a-zA-Z]{6})$#" => [ExchangeRateController::class, 'updateExchangeRate'],
    ]
];