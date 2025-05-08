<?php

namespace App\CurrencyExchange\Model;


class Currency
{
    private ?int $id;
    private string $code;
    private string $fullName;
    private string $sign;

    public function __construct(string $code, string $fullName, string $sign, ?int $id = null)
    {
        $this->code = $code;
        $this->fullName = $fullName;
        $this->sign = $sign;
        $this->id = $id;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getFullName(): string
    {
        return $this->fullName;
    }

    public function getSign(): string
    {
        return $this->sign;
    }
}
