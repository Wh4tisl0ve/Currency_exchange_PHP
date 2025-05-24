<?php

namespace App\Controller;

use App\DAO\Currency\CurrencyDAOInterface;
use App\DAO\ExchangeRate\ExchangeRateDAOInterface;
use App\Model\ExchangeRate;
use MiniBox\Http\HttpRequest;
use MiniBox\Http\Response\HttpResponse;
use MiniBox\Http\Response\JsonResponse;

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
        $data = $this->exchangeRateDAO->findAll();

        $exchangeRatesJson = json_encode(array_map(function ($exchangeRate) {
            return $this->getArrayView($exchangeRate);
        }, $data), JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

        return new JsonResponse($exchangeRatesJson, 200);
    }

    public function getExchangeRate(HttpRequest $httpRequest, string $currencyPair): HttpResponse
    {
        $baseCurrencyCode = substr($currencyPair, 0, 3);
        $targetCurrencyCode = substr($currencyPair, 3, 6);

        $baseCurrency = $this->currencyDAO->findOne($baseCurrencyCode);
        $targetCurrency = $this->currencyDAO->findOne($targetCurrencyCode);

        $data = $this->exchangeRateDAO->findOne($baseCurrency->getId(), $targetCurrency->getId());

        $exchangeRateJson = json_encode(
            $this->getArrayView($data),
            JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT
        );

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

        $data = $this->exchangeRateDAO->findOne($baseCurrency->getId(), $targetCurrency->getId());

        $exchangeRateJson = json_encode($this->getArrayView($data),
            JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

        return new JsonResponse($exchangeRateJson, 201);
    }

    public function updateExchangeRate(HttpRequest $httpRequest, string $currencyPair): HttpResponse{
        $httpRequest->validateData(["rate"]);

        $data = $httpRequest->getData();

        $baseCurrencyCode = substr($currencyPair, 0, 3);
        $targetCurrencyCode = substr($currencyPair, 3, 6);
        $rate = $data['rate'];

        $baseCurrency = $this->currencyDAO->findOne($baseCurrencyCode);
        $targetCurrency = $this->currencyDAO->findOne($targetCurrencyCode);

        $exchangeRate = new ExchangeRate(
            $baseCurrency->getId(),
            $targetCurrency->getId(),
            $rate,
        );

        $this->exchangeRateDAO->update($exchangeRate);

        $data = $this->exchangeRateDAO->findOne($baseCurrency->getId(), $targetCurrency->getId());

        $exchangeRateJson = json_encode($this->getArrayView($data),
            JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

        return new JsonResponse($exchangeRateJson, 200);
    }

    private function getArrayView(array $data): array
    {
        return [
            'id' => $data['id'],
            'baseCurrency' => [
                'id' => $data['base_currency_id'],
                'name' => $data['base_currency_fullname'],
                'code' => $data['base_currency_code'],
                'sign' => $data['base_currency_sign'],
            ],
            'targetCurrency' => [
                'id' => $data['target_currency_id'],
                'name' => $data['target_currency_fullname'],
                'code' => $data['target_currency_code'],
                'sign' => $data['target_currency_sign'],
            ],
            'rate' => $data['rate'],
        ];
    }

}