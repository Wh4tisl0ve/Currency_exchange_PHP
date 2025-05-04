<?php

namespace App\CurrencyExchange\Controller;

use App\CurrencyExchange\DAO\Currency\CurrencyDAOInterface;
use App\Framework\Http\HttpRequest;
use PDOException;

class CurrencyController
{
    private CurrencyDAOInterface $currencyDAO;

    public function __construct(CurrencyDAOInterface $currencyDAO)
    {
        $this->currencyDAO = $currencyDAO;
    }

    public function getAllCurrencies(HttpRequest $httpRequest): string
    {
        try {
            $currencies = $this->currencyDAO->findAll();

            $currenciesJson = array_map(function ($currency) {
                return [
                    'id' => $currency->getId(),
                    'code' => $currency->getCode(),
                    'name' => $currency->getFullName(),
                    'sign' => $currency->getSign(),
                ];
            }, $currencies);

            return json_encode($currenciesJson, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        } catch (PDOException $exception) {
            return json_encode(["message" => "База данных недоступна"], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        }
    }

    public function getCurrency(HttpRequest $httpRequest, string $currencyCode): string
    {
        try {
            $currency = $this->currencyDAO->findOne($currencyCode);

            $currencyJson = [
                'id' => $currency->getId(),
                'code' => $currency->getCode(),
                'name' => $currency->getFullName(),
                'sign' => $currency->getSign(),
            ];

            return json_encode($currencyJson, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        } catch (PDOException $exception) {
            return json_encode(["message" => "База данных недоступна"], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        }
    }
}