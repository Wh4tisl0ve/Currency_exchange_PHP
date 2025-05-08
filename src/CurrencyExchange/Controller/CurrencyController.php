<?php

namespace App\CurrencyExchange\Controller;

use App\CurrencyExchange\DAO\Currency\CurrencyDAOInterface;
use App\CurrencyExchange\Model\Currency;
use App\Framework\Http\HttpRequest;
use App\Framework\Http\Response\HttpResponse;
use App\Framework\Http\Response\JsonResponse;

class CurrencyController
{
    private CurrencyDAOInterface $currencyDAO;

    public function __construct(CurrencyDAOInterface $currencyDAO)
    {
        $this->currencyDAO = $currencyDAO;
    }

    public function getAllCurrencies(HttpRequest $httpRequest): HttpResponse
    {
        $currencies = $this->currencyDAO->findAll();

        $currenciesJson = json_encode(array_map(function ($currency) {
            return [
                'id' => $currency->getId(),
                'code' => $currency->getCode(),
                'name' => $currency->getFullName(),
                'sign' => $currency->getSign(),
            ];
        }, $currencies), JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

        return new JsonResponse($currenciesJson, 200);
    }

    public function getCurrency(HttpRequest $httpRequest, string $currencyCode): HttpResponse
    {
        $currency = $this->currencyDAO->findOne($currencyCode);

        $currencyJson = json_encode([
            'id' => $currency->getId(),
            'code' => $currency->getCode(),
            'name' => $currency->getFullName(),
            'sign' => $currency->getSign(),
        ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

        return new JsonResponse($currencyJson, 200);
    }

    public function addCurrency(HttpRequest $httpRequest): HttpResponse
    {
        $httpRequest->validateData(["code", "name", "sign"]);

        $data = $httpRequest->getData();
        $currency = new Currency(
            $data['code'],
            $data['name'],
            $data['sign'],
        );

        $this->currencyDAO->add($currency);

        $currencyResult = $this->currencyDAO->findOne($currency->getCode());

        $currencyJson = json_encode([
            'id' => $currencyResult->getId(),
            'code' => $currencyResult->getCode(),
            'name' => $currencyResult->getFullName(),
            'sign' => $currencyResult->getSign(),
        ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

        return new JsonResponse($currencyJson, 201);
    }
}