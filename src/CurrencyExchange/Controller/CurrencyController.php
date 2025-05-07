<?php

namespace App\CurrencyExchange\Controller;

use App\CurrencyExchange\DAO\Currency\CurrencyDAOInterface;
use App\CurrencyExchange\DAO\Exception\CurrencyNotFoundException;
use App\Framework\Http\HttpRequest;
use App\Framework\Http\Response\HttpResponse;
use App\Framework\Http\Response\JsonResponse;
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

            return new JsonResponse(
                json_encode($currenciesJson, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT),
                200
            );
        } catch (PDOException) {
            return new JsonResponse(
                json_encode(["message" => "База данных недоступна"], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT),
                500
            );
        }
    }

    public function getCurrency(HttpRequest $httpRequest, string $currencyCode): HttpResponse
    {
        try {
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
        } catch (CurrencyNotFoundException $exception) {
            return new JsonResponse(
                json_encode(["message" => $exception->getMessage()], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT),
                404
            );
        }
        catch (PDOException) {
            return new JsonResponse(
                json_encode(["message" => "База данных недоступна"], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT),
                500
            );
        }
    }
}