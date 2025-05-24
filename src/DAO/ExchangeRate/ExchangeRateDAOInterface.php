<?php

namespace App\DAO\ExchangeRate;

use App\Model\ExchangeRate;

interface ExchangeRateDAOInterface
{
    public function findAll(): array;

    public function findOne(int $baseCurrencyId, int $targetCurrencyId): array;

    public function add(ExchangeRate $exchangeRate): void;

    public function update(ExchangeRate $exchangeRate): void;
}