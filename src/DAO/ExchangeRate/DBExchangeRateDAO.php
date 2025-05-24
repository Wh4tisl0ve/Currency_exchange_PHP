<?php

namespace App\DAO\ExchangeRate;

use PDO;
use PDOException;
use App\DAO\ExchangeRate\Exception\ExchangeRateExistsException;
use App\DAO\ExchangeRate\Exception\ExchangeRateNotFoundException;
use App\Model\ExchangeRate;


class DBExchangeRateDAO implements ExchangeRateDAOInterface
{
    public function __construct(
        private PDO $pdo
    ) {}

    public function findAll(): array
    {
        $stmt = $this->pdo->prepare(
            "SELECT er.id, 
                   base.id as base_currency_id, 
                   base.code as base_currency_code, 
                   base.fullname as base_currency_fullname, 
                   base.sign as base_currency_sign, 
                   target.id as target_currency_id, 
                   target.code as target_currency_code, 
                   target.fullname as target_currency_fullname, 
                   target.sign as target_currency_sign, 
                   er.rate
                   FROM exchange_rates er
                   JOIN currencies as base ON er.base_currency_id = base.id
                   JOIN currencies as target ON er.target_currency_id = target.id"
        );
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * @throws ExchangeRateNotFoundException
     */
    public function findOne(int $baseCurrencyId, int $targetCurrencyId): array
    {
        $stmt = $this->pdo->prepare(
            "SELECT er.id, 
                   base.id as base_currency_id, 
                   base.code as base_currency_code, 
                   base.fullname as base_currency_fullname, 
                   base.sign as base_currency_sign, 
                   target.id as target_currency_id, 
                   target.code as target_currency_code, 
                   target.fullname as target_currency_fullname, 
                   target.sign as target_currency_sign, 
                   er.rate
                   FROM exchange_rates er
                   JOIN currencies as base ON er.base_currency_id = base.id
                   JOIN currencies as target ON er.target_currency_id = target.id
                   WHERE base_currency_id = :base_id AND target_currency_id = :target_id"
        );
        $stmt->execute(['base_id' => $baseCurrencyId, 'target_id' => $targetCurrencyId]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$data) {
            throw new ExchangeRateNotFoundException("Обменный курс с base_id: $baseCurrencyId и target_id: $targetCurrencyId не найден");
        }

        return $data;
    }

    /**
     * @throws ExchangeRateExistsException
     */
    public function add(ExchangeRate $exchangeRate): void
    {
        $baseCurrencyId = $exchangeRate->getBaseCurrencyId();
        $targetCurrencyId = $exchangeRate->getTargetCurrencyId();
        $rate = $exchangeRate->getRate();

        try {
            $stmt = $this->pdo->prepare("INSERT INTO exchange_rates (base_currency_id, target_currency_id, rate) 
                                               VALUES (:base_id, :target_id, :rate)");
            $stmt->bindParam(':base_id', $baseCurrencyId);
            $stmt->bindParam(':target_id', $targetCurrencyId);
            $stmt->bindParam(':rate', $rate);

            $stmt->execute();
        } catch (PDOException $exception) {
            if ($exception->errorInfo[0] == 23505) {
                throw new ExchangeRateExistsException("Обменный курс с base_Id: $baseCurrencyId и target_Id: $targetCurrencyId уже существует");
            }
        }
    }

    public function update(ExchangeRate $exchangeRate): void
    {
        $baseCurrencyId = $exchangeRate->getBaseCurrencyId();
        $targetCurrencyId = $exchangeRate->getTargetCurrencyId();
        $rate = $exchangeRate->getRate();

        $stmt = $this->pdo->prepare("UPDATE exchange_rates SET rate = :rate
                                           WHERE base_currency_id = :base_id AND target_currency_id = :target_id");
        $stmt->bindParam(':base_id', $baseCurrencyId);
        $stmt->bindParam(':target_id', $targetCurrencyId);
        $stmt->bindParam(':rate', $rate);

        $stmt->execute();
    }
}