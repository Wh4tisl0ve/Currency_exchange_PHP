<?php

namespace App\Controller;

use BcMath\Number;
use ValueError;
use App\DAO\Currency\CurrencyDAOInterface;
use App\DAO\ExchangeRate\Exception\ExchangeRateExistsException;
use App\DAO\ExchangeRate\Exception\ExchangeRateNotFoundException;
use App\DAO\ExchangeRate\ExchangeRateDAOInterface;
use App\Model\ExchangeRate;
use MiniBox\Exception\InvalidDataException;
use MiniBox\Exception\ValidationException;
use MiniBox\Http\HttpRequest;
use MiniBox\Http\Response\HttpResponse;
use MiniBox\Http\Response\JsonResponse;


readonly class ExchangeRateController
{
    public function __construct(
        private ExchangeRateDAOInterface $exchangeRateDAO,
        private CurrencyDAOInterface     $currencyDAO)
    {
    }

    public function getAllExchangeRates(HttpRequest $httpRequest): HttpResponse
    {
        $data = $this->exchangeRateDAO->findAll();

        $exchangeRatesJson = json_encode(array_map(function ($exchangeRate) {
            return $this->getArrayView($exchangeRate);
        }, $data), JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

        return new JsonResponse($exchangeRatesJson, 200);
    }

    /**
     * @throws ExchangeRateNotFoundException
     */
    public function getExchangeRate(HttpRequest $httpRequest, string $currencyPair): HttpResponse
    {
        $baseCurrencyCode = substr($currencyPair, 0, 3);
        $targetCurrencyCode = substr($currencyPair, 3, 6);

        try {
            $baseCurrency = $this->currencyDAO->findOne($baseCurrencyCode);
            $targetCurrency = $this->currencyDAO->findOne($targetCurrencyCode);

            $data = $this->exchangeRateDAO->findOne($baseCurrency->getId(), $targetCurrency->getId());

            $exchangeRateJson = json_encode(
                $this->getArrayView($data),
                JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT
            );

            return new JsonResponse($exchangeRateJson, 200);
        } catch (ExchangeRateNotFoundException) {
            throw new ExchangeRateNotFoundException("Обменный курс из $baseCurrencyCode в $targetCurrencyCode не найден");
        }
    }

    /**
     * @throws ValidationException|ExchangeRateExistsException|InvalidDataException
     */
    public function addExchangeRate(HttpRequest $httpRequest): HttpResponse
    {
        $httpRequest->validateData(["baseCurrencyCode", "targetCurrencyCode", "rate"]);

        $data = $httpRequest->getData();

        $baseCurrencyCode = $data['baseCurrencyCode'];
        $targetCurrencyCode = $data['targetCurrencyCode'];

        try {
            $rate = new Number(str_replace(',', '.', $data['rate']));

            $baseCurrency = $this->currencyDAO->findOne($baseCurrencyCode);
            $targetCurrency = $this->currencyDAO->findOne($targetCurrencyCode);

            $exchangeRate = new ExchangeRate(
                $baseCurrency->getId(),
                $targetCurrency->getId(),
                $rate,
            );

            $this->exchangeRateDAO->add($exchangeRate);

            $data = $this->exchangeRateDAO->findOne($baseCurrency->getId(), $targetCurrency->getId());

            $exchangeRateJson = json_encode($this->getArrayView($data),
                JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

            return new JsonResponse($exchangeRateJson, 201);
        } catch (ValueError) {
            throw new InvalidDataException("Поле rate должно быть числовым типом");
        } catch (ExchangeRateExistsException) {
            throw new ExchangeRateExistsException("Обменный курс из $baseCurrencyCode в $targetCurrencyCode уже существует");
        }
    }

    /**
     * @throws ValidationException|InvalidDataException|ExchangeRateNotFoundException
     */
    public function updateExchangeRate(HttpRequest $httpRequest, string $currencyPair): HttpResponse
    {
        $httpRequest->validateData(["rate"]);

        $data = $httpRequest->getData();

        $baseCurrencyCode = substr($currencyPair, 0, 3);
        $targetCurrencyCode = substr($currencyPair, 3, 6);

        try {
            $rate = new Number(str_replace(',', '.', $data['rate']));

            $baseCurrency = $this->currencyDAO->findOne($baseCurrencyCode);
            $targetCurrency = $this->currencyDAO->findOne($targetCurrencyCode);

            $exchangeRate = new ExchangeRate(
                $baseCurrency->getId(),
                $targetCurrency->getId(),
                $rate,
            );

            $this->exchangeRateDAO->update($exchangeRate);

            $data = $this->exchangeRateDAO->findOne($baseCurrency->getId(), $targetCurrency->getId());

            $exchangeRateJson = json_encode($this->getArrayView($data),
                JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

            return new JsonResponse($exchangeRateJson, 200);
        } catch (ValueError) {
            throw new InvalidDataException("Поле rate должно быть числовым типом");
        } catch (ExchangeRateNotFoundException) {
            throw new ExchangeRateNotFoundException("Обменный курс из $baseCurrencyCode в $targetCurrencyCode не найден");
        }
    }

    private function getArrayView(array $data): array
    {
        return [
            'id' => $data['id'],
            'baseCurrency' => [
                'id' => $data['base_currency_id'],
                'name' => $data['base_currency_fullname'],
                'code' => $data['base_currency_code'],
                'sign' => $data['base_currency_sign'],
            ],
            'targetCurrency' => [
                'id' => $data['target_currency_id'],
                'name' => $data['target_currency_fullname'],
                'code' => $data['target_currency_code'],
                'sign' => $data['target_currency_sign'],
            ],
            'rate' => $data['rate'],
        ];
    }
}