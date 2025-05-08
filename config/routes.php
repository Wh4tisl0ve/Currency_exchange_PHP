<?php


use App\CurrencyExchange\Controller\CurrencyController;
use App\CurrencyExchange\Controller\ExchangeRateController;

return [
    'GET' => [
        "#/currencies$#" => [CurrencyController::class, 'getAllCurrencies'],
        "#^/currency/(?P<currencyCode>[a-zA-Z]{3})$#" => [CurrencyController::class, 'getCurrency'],
        "#/exchangeRates#" => [ExchangeRateController::class, 'getAllExchangeRates'],
    ],
    'POST' => [
        "#/currencies$#" => [CurrencyController::class, 'addCurrency'],
    ],
];