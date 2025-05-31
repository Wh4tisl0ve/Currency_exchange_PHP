<?php

namespace App\Controller;

use BcMath\Number;
use ValueError;
use App\DAO\ExchangeRate\Exception\ExchangeRateNotFoundException;
use App\Model\Currency;
use App\Service\ExchangerService;
use App\DAO\Currency\CurrencyDAOInterface;
use App\DAO\ExchangeRate\ExchangeRateDAOInterface;
use MiniBox\Exception\InvalidDataException;
use MiniBox\Exception\ValidationException;
use MiniBox\Http\HttpRequest;
use MiniBox\Http\Response\HttpResponse;
use MiniBox\Http\Response\JsonResponse;


class ExchangerController
{
    public function __construct(
        private ExchangeRateDAOInterface $exchangeRateDAO,
        private CurrencyDAOInterface     $currencyDAO,
    )
    {
    }

    /**
     * @throws ValidationException|InvalidDataException
     */
    public function exchange(HttpRequest $httpRequest): HttpResponse
    {
        $httpRequest->validateData(["from", "to", "amount"]);
        try {
            $data = $httpRequest->getData();

            $fromCurrencyCode = $data["from"];
            $toCurrencyCode = $data["to"];
            $amount = new Number(str_replace(',', '.', $data["amount"]));
            if ($amount->compare(new Number(0)) == -1) {
                throw new ValidationException('amount должно быть больше 0');
            }

            $baseCurrency = $this->currencyDAO->findOne($fromCurrencyCode);
            $targetCurrency = $this->currencyDAO->findOne($toCurrencyCode);

            try {
                [$rateValue, $convertedAmount] = $this->calcToDirectRate($baseCurrency, $targetCurrency, $amount);
            } catch (ExchangeRateNotFoundException) {
                try {
                    [$rateValue, $convertedAmount] = $this->calcToReverseRate($targetCurrency, $baseCurrency, $amount);
                } catch (ExchangeRateNotFoundException) {
                    try {
                        [$rateValue, $convertedAmount] = $this->calcToCrossRate($targetCurrency, $baseCurrency, $amount);
                    } catch (ExchangeRateNotFoundException) {
                        throw new InvalidDataException(
                            "Не возможно провести обмен из " . $baseCurrency->getCode() . " в " . $targetCurrency->getCode()
                        );
                    }
                }
            }

            $exchangeResultJson = json_encode(
                $this->getArrayView(
                    $baseCurrency,
                    $targetCurrency,
                    $rateValue,
                    $amount,
                    $convertedAmount,
                ),
                JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
            return new JsonResponse($exchangeResultJson, 200);
        } catch (ValueError) {
            throw new InvalidDataException("Поле amount должно быть числовым типом");
        }
    }

    private function calcToDirectRate(Currency $baseCurrency, Currency $targetCurrency, Number $amount): array
    {
        $dataRate = $this->exchangeRateDAO->findOne($baseCurrency->getId(), $targetCurrency->getId());
        return ExchangerService::calcByDirectRate(
            new Number(str_replace(',', '.', $dataRate['rate'])),
            $amount
        );
    }

    private function calcToReverseRate(Currency $baseCurrency, Currency $targetCurrency, Number $amount): array
    {
        $dataRate = $this->exchangeRateDAO->findOne($baseCurrency->getId(), $targetCurrency->getId());
        return ExchangerService::calcByReverseRate(
            new Number(str_replace(',', '.', $dataRate['rate'])),
            $amount
        );
    }

    private function calcToCrossRate(Currency $baseCurrency, Currency $targetCurrency, Number $amount): array
    {
        $usdCurrency = $this->currencyDAO->findOne('USD');

        $dataUsdToBaseRate = $this->exchangeRateDAO->findOne($usdCurrency->getId(), $baseCurrency->getId());
        $dataUsdToTargetRate = $this->exchangeRateDAO->findOne($usdCurrency->getId(), $targetCurrency->getId());

        return ExchangerService::calcByCrossRate(
            new Number(str_replace(',', '.', $dataUsdToBaseRate["rate"])),
            new Number(str_replace(',', '.', $dataUsdToTargetRate ["rate"])),
            $amount
        );
    }

    private function getArrayView(Currency $baseCurrency,
                                  Currency $targetCurrency,
                                  Number   $rate,
                                  Number   $amount,
                                  Number   $convertedAmount,
    ): array
    {
        return [
            'baseCurrency' => [
                'id' => $baseCurrency->getId(),
                'name' => $baseCurrency->getFullname(),
                'code' => $baseCurrency->getCode(),
                'sign' => $baseCurrency->getSign(),
            ],
            'targetCurrency' => [
                'id' => $targetCurrency->getId(),
                'name' => $targetCurrency->getFullname(),
                'code' => $targetCurrency->getCode(),
                'sign' => $targetCurrency->getSign(),
            ],
            'rate' => $rate->value,
            'amount' => $amount->value,
            'convertedAmount' => $convertedAmount->value,
        ];
    }
}