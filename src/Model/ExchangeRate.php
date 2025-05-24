<?php

namespace App\Model;

use BcMath\Number;

class ExchangeRate
{
    private ?int $id;
    private int $baseCurrencyId;
    private int $targetCurrencyId;
    private Number $rate;

    public function __construct(int $baseCurrencyId, int $targetCurrencyId, Number $rate, ?int $id = null)
    {
        $this->baseCurrencyId = $baseCurrencyId;
        $this->targetCurrencyId = $targetCurrencyId;
        $this->rate = $rate;
        $this->id = $id;
    }

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