<?php

namespace App\CurrencyExchange\Controller;

use App\CurrencyExchange\DAO\ExchangeRate\ExchangeRateDAOInterface;
use App\Framework\Http\HttpRequest;
use App\Framework\Http\Response\HttpResponse;
use App\Framework\Http\Response\JsonResponse;

class ExchangeRateController
{
    private ExchangeRateDAOInterface $exchangeRateDAO;

    public function __construct(ExchangeRateDAOInterface $exchangeRateDAO)
    {
        $this->exchangeRateDAO = $exchangeRateDAO;
    }

    public function getAllExchangeRates(HttpRequest $httpRequest): HttpResponse
    {
        $exchangeRates = $this->exchangeRateDAO->findAll();

        $exchangeRatesJson = json_encode(array_map(function ($exchangeRate) {
            return [
                'id' => $exchangeRate['id'],
                'baseCurrency' => [
                    'id' => $exchangeRate['base_currency_id'],
                    'name' => $exchangeRate['base_currency_fullname'],
                    'code' => $exchangeRate['base_currency_code'],
                    'sign' => $exchangeRate['base_currency_sign'],
                ],
                'targetCurrency' => [
                    'id' => $exchangeRate['target_currency_id'],
                    'name' => $exchangeRate['target_currency_fullname'],
                    'code' => $exchangeRate['target_currency_code'],
                    'sign' => $exchangeRate['target_currency_sign'],
                ],
                'rate' => $exchangeRate['rate'],
            ];
        }, $exchangeRates), JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

        return new JsonResponse($exchangeRatesJson, 200);
    }
}