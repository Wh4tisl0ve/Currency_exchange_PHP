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
            return json_encode($currencies, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        } catch (PDOException $exception) {
            return json_encode(["message" => "База данных недоступна"], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        }

    }
}