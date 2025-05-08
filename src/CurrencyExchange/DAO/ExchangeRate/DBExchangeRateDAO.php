<?php

namespace App\CurrencyExchange\DAO\ExchangeRate;

use App\CurrencyExchange\Model\ExchangeRate;
use PDO;

class DBExchangeRateDAO implements ExchangeRateDAOInterface
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function findAll(): array
    {
        $stmt = $this->pdo->prepare(
            "SELECT er.id, 
                   base.id as base_currency_id, 
                   base.code as base_currency_code, 
                   base.fullname as base_currency_fullname, 
                   base.sign as base_currency_sign, 
                   target.id as target_currency_id, 
                   target.code as target_currency_code, 
                   target.fullname as target_currency_fullname, 
                   target.sign as target_currency_sign, 
                   er.rate
                   FROM exchange_rates er
                   JOIN currencies as base ON er.base_currency_id = base.id
                   JOIN currencies as target ON er.target_currency_id = target.id"
        );
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findOne(string $currencyPair): ExchangeRate
    {
        // TODO: Implement findOne() method.
    }

    public function add(ExchangeRate $exchangeRate): void
    {
        // TODO: Implement add() method.
    }

    public function update(ExchangeRate $exchangeRate): void
    {
        // TODO: Implement update() method.
    }
}