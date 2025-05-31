<?php

namespace App\Model;

use BcMath\Number;

class ExchangeRate
{
    public function __construct(
        private int    $baseCurrencyId,
        private int    $targetCurrencyId,
        private Number $rate,
        private ?int   $id = null
    ) {}

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBaseCurrencyId(): int
    {
        return $this->baseCurrencyId;
    }

    public function getTargetCurrencyId(): int
    {
        return $this->targetCurrencyId;
    }

    public function getRate(): Number
    {
        return $this->rate;
    }
}