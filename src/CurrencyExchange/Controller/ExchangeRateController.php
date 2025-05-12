<?php

namespace App\CurrencyExchange\Controller;

use App\CurrencyExchange\DAO\Currency\CurrencyDAOInterface;
use App\CurrencyExchange\DAO\ExchangeRate\ExchangeRateDAOInterface;
use App\CurrencyExchange\Model\ExchangeRate;
use App\Framework\Http\HttpRequest;
use App\Framework\Http\Response\HttpResponse;
use App\Framework\Http\Response\JsonResponse;

class ExchangeRateController
{
    private ExchangeRateDAOInterface $exchangeRateDAO;
    private CurrencyDAOInterface $currencyDAO;

    public function __construct(ExchangeRateDAOInterface $exchangeRateDAO, CurrencyDAOInterface $currencyDAO)
    {
        $this->exchangeRateDAO = $exchangeRateDAO;
        $this->currencyDAO = $currencyDAO;
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

    public function getExchangeRate(HttpRequest $httpRequest, string $currencyPair): HttpResponse
    {
        $baseCurrencyCode = substr($currencyPair, 0, 3);
        $targetCurrencyCode = substr($currencyPair, 3, 6);

        $baseCurrency = $this->currencyDAO->findOne($baseCurrencyCode);
        $targetCurrency = $this->currencyDAO->findOne($targetCurrencyCode);

        $exchangeRate = $this->exchangeRateDAO->findOne($baseCurrency->getId(), $targetCurrency->getId());

        $exchangeRateJson = json_encode([
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
        ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

        return new JsonResponse($exchangeRateJson, 200);
    }

    public function addExchangeRate(HttpRequest $httpRequest): HttpResponse
    {
        $httpRequest->validateData(["baseCurrencyCode", "targetCurrencyCode", "rate"]);

        $data = $httpRequest->getData();

        $baseCurrencyCode = $data['baseCurrencyCode'];
        $targetCurrencyCode = $data['targetCurrencyCode'];
        $rate = $data['rate'];

        $baseCurrency = $this->currencyDAO->findOne($baseCurrencyCode);
        $targetCurrency = $this->currencyDAO->findOne($targetCurrencyCode);

        $exchangeRate = new ExchangeRate(
            $baseCurrency->getId(),
            $targetCurrency->getId(),
            $rate,
        );

        $this->exchangeRateDAO->add($exchangeRate);

        $exchangeRate = $this->exchangeRateDAO->findOne($baseCurrency->getId(), $targetCurrency->getId());

        $exchangeRateJson = json_encode([
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
        ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

        return new JsonResponse($exchangeRateJson, 200);
    }
}