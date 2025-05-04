<?php


use App\CurrencyExchange\Controller\CurrencyController;

return [
    'GET' => [
        "#/currencies$#" => [CurrencyController::class, 'getAllCurrencies'],
        "#^/currency/(?P<currencyCode>[a-zA-Z]{3})$#" => [CurrencyController::class, 'getCurrency'],
    ],
];