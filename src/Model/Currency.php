<?php

namespace App\Model;


class Currency
{
    public function __construct(
        private string $code,
        private string $fullName,
        private string $sign,
        private ?int   $id = null
    ) {}

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
