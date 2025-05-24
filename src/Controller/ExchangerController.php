<?php

namespace App\Controller;

use BcMath\Number;
use ValueError;
use App\Service\ExchangerService;
use App\DAO\Currency\CurrencyDAOInterface;
use App\DAO\ExchangeRate\ExchangeRateDAOInterface;
use MiniBox\Http\HttpRequest;
use MiniBox\Http\Response\HttpResponse;
use MiniBox\Http\Response\JsonResponse;


class ExchangerController
{
    public function __construct(
        private ExchangeRateDAOInterface $exchangeRateDAO,
        private CurrencyDAOInterface     $currencyDAO,
    ) {}

    public function exchange(HttpRequest $httpRequest): HttpResponse
    {
        $data = $httpRequest->getData();

        $fromCurrencyCode = $data["from"];
        $toCurrencyCode = $data["to"];
        $amount = new Number(str_replace(',', '.', $data["amount"]));

        $baseCurrency = $this->currencyDAO->findOne($fromCurrencyCode);
        $targetCurrency = $this->currencyDAO->findOne($toCurrencyCode);

        # direct
        $dataDirectRate = $this->exchangeRateDAO->findOne($baseCurrency->getId(), $targetCurrency->getId());

        # reverse
        $dataReverseRate = $this->exchangeRateDAO->findOne($targetCurrency->getId(), $baseCurrency->getId());

        # cross
        $usdCurrency = $this->currencyDAO->findOne('USD');
        $dataUsdToBaseRate = $this->exchangeRateDAO->findOne($usdCurrency->getId(), $baseCurrency->getId());
        $dataUsdToTargetRate = $this->exchangeRateDAO->findOne($usdCurrency->getId(), $targetCurrency->getId());

        #$rate = new Number($data["rate"]);

        $calculatedRate = ExchangerService::calcByDirectRate(new Number(0), $amount);
        return new JsonResponse("$calculatedRate", 200);
    }
}