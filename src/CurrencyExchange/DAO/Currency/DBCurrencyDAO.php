<?php

namespace App\CurrencyExchange\DAO\Currency;

use PDO;
use App\CurrencyExchange\Model\Currency;


class DBCurrencyDAO implements CurrencyDAOInterface
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function findAll(): array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM currencies;");
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $currencies = [];
        foreach ($data as $currency) {
            $currencies[] = new Currency(
                $currency["id"],
                $currency["code"],
                $currency["fullname"],
                $currency["sign"]
            );
        }

        return $currencies;
    }

    public function findOne(string $code): Currency
    {
        $stmt = $this->pdo->prepare("SELECT * FROM currencies WHERE code ILIKE :code;");
        $stmt->execute(['code' => $code]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$data) {
            throw new \Exception("Валюта с кодом $code не найдена");
        }

        return new Currency(
            $data["id"],
            $data["code"],
            $data["fullname"],
            $data["sign"]
        );
    }

    public function add(Currency $currency): void
    {
        $stmt = $this->pdo->prepare("INSERT INTO currencies VALUES (:code, :fullName, :sign)");
        $stmt->execute([
            'code' => $currency->getCode(),
            'fullName' => $currency->getFullName(),
            'sign' => $currency->getSign()
        ]);
    }
}