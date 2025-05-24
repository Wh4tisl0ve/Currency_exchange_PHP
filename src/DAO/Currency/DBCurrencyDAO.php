<?php

namespace App\DAO\Currency;

use PDO;
use PDOException;
use App\DAO\Currency\Exception\CurrencyCodeExistsException;
use App\DAO\Currency\Exception\CurrencyNotFoundException;
use App\DAO\Currency\Exception\ValidationCodeCurrencyException;
use App\Exception\FieldOverflowException;
use App\Model\Currency;


class DBCurrencyDAO implements CurrencyDAOInterface
{
    public function __construct(
        private PDO $pdo
    ) {}

    public function findAll(): array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM currencies;");
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $currencies = [];
        foreach ($data as $currency) {
            $currencies[] = new Currency(
                $currency["code"],
                $currency["fullname"],
                $currency["sign"],
                $currency["id"]
            );
        }

        return $currencies;
    }

    /**
     * @throws CurrencyNotFoundException
     */
    public function findOne(string $code): Currency
    {
        $stmt = $this->pdo->prepare("SELECT * FROM currencies WHERE code ILIKE :code;");
        $stmt->execute(['code' => $code]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$data) {
            throw new CurrencyNotFoundException("Валюта с кодом $code не найдена");
        }

        return new Currency(
            $data["code"],
            $data["fullname"],
            $data["sign"],
            $data["id"]
        );
    }

    /**
     * @throws CurrencyCodeExistsException|ValidationCodeCurrencyException|FieldOverflowException
     */
    public function add(Currency $currency): void
    {
        $code = strtoupper($currency->getCode());
        $fullName = $currency->getFullName();
        $sign = $currency->getSign();

        try {
            $stmt = $this->pdo->prepare("INSERT INTO currencies (code, fullname, sign) VALUES (:code, :fullName, :sign)");
            $stmt->bindParam(':code', $code);
            $stmt->bindParam(':fullName', $fullName);
            $stmt->bindParam(':sign', $sign);

            $stmt->execute();
        } catch (PDOException $exception) {
            if ($exception->errorInfo[0] == 22001)
                throw new FieldOverflowException("Длина строки была больше ожидаемой");

            if ($exception->errorInfo[0] == 23514)
                throw new ValidationCodeCurrencyException("Код валюты должен состоять из 3 латинских букв");

            if ($exception->errorInfo[0] == 23505)
                throw new CurrencyCodeExistsException("Валюта с кодом $code уже существует");
        }
    }
}