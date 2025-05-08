<?php

namespace App\CurrencyExchange\DAO\ExchangeRate;

use App\CurrencyExchange\Model\ExchangeRate;

interface ExchangeRateDAOInterface
{
    public function findAll(): array;

    public function findOne(string $currencyPair): ExchangeRate;

    public function add(ExchangeRate $exchangeRate): void;

    public function update(ExchangeRate $exchangeRate): void;
}