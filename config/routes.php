<?php


use App\CurrencyExchange\Controller\CurrencyController;

return [
    'GET' => [
        '/currencies' => [CurrencyController::class, 'getAllCurrencies']
    ],
];