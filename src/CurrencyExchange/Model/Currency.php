<?php

namespace App\CurrencyExchange\Model;


class Currency
{
    private int $id;
    private string $code;
    private string $fullName;
    private string $sign;

    public function __construct(int $id, string $code, string $fullName, string $sign)
    {
        $this->id = $id;
        $this->code = $code;
        $this->fullName = $fullName;
        $this->sign = $sign;
    }

    public function getSign(): string
    {
        return $this->sign;
    }

    public function getFullName(): string
    {
        return $this->fullName;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getId(): int
    {
        return $this->id;
    }
}
