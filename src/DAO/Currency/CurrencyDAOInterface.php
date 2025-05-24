<?php

namespace App\DAO\Currency;

use App\Model\Currency;

interface CurrencyDAOInterface
{
    public function findAll(): array;

    public function findOne(string $code): Currency;

    public function add(Currency $currency): void;
}