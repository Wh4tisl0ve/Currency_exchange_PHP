<?php

namespace App\Service;

use BcMath\Number;

class ExchangerService
{
    public static function calcByDirectRate(Number $rate, Number $amount): array
    {
        return [$rate, $rate->mul($amount, 4)];
    }

    public static function calcByReverseRate(Number $rate, Number $amount): array
    {
        $rate = new Number(1)->div($rate, 4);
        return [$rate, $amount->mul($rate, 4)];
    }

    public static function calcByCrossRate(Number $usdToBaseRate, $usdToTargetRate, Number $amount): array
    {
        $baseToUsd = $amount->mul(new Number(1)->div($usdToBaseRate));
        $convertedAmount = $baseToUsd->mul($usdToTargetRate, 4);
        $rate = $convertedAmount->div($amount, 4);
        return [$rate, $convertedAmount];
    }
}