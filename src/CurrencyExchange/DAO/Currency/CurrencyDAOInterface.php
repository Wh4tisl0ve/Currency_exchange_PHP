<?php

namespace App\CurrencyExchange\DAO\Currency;

use App\CurrencyExchange\DAO\Exception\CurrencyNotFoundException;
use App\CurrencyExchange\Model\Currency;

interface CurrencyDAOInterface
{
    public function findAll(): array;

    public function findOne(string $code): Currency;

    public function add(Currency $currency): void;
}