<?php

namespace App\CurrencyExchange\Controller;

use App\CurrencyExchange\DAO\Currency\CurrencyDAOInterface;
use App\CurrencyExchange\DAO\Exception\CurrencyNotFoundException;
use App\CurrencyExchange\Model\Currency;
use App\Framework\Http\HttpRequest;
use App\Framework\Http\Response\HttpResponse;
use App\Framework\Http\Response\JsonResponse;
use App\Framework\Validation\ValidationException;
use PDOException;

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

        $currenciesJson = array_map(function ($currency) {
            return [
                'id' => $currency->getId(),
                'code' => $currency->getCode(),
                'name' => $currency->getFullName(),
                'sign' => $currency->getSign(),
            ];
        }, $currencies);

        return new JsonResponse(
            json_encode($currenciesJson, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT),
            200
        );
    }

    public function getCurrency(HttpRequest $httpRequest, string $currencyCode): HttpResponse
    {
        $currency = $this->currencyDAO->findOne($currencyCode);

        $currencyJson = [
            'id' => $currency->getId(),
            'code' => $currency->getCode(),
            'name' => $currency->getFullName(),
            'sign' => $currency->getSign(),
        ];

        return new JsonResponse(
            json_encode($currencyJson, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT),
            200);
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

        $currencyJson = [
            'id' => $currencyResult->getId(),
            'code' => $currencyResult->getCode(),
            'name' => $currencyResult->getFullName(),
            'sign' => $currencyResult->getSign(),
        ];

        return new JsonResponse(
            json_encode($currencyJson, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT),
            201);
    }
}