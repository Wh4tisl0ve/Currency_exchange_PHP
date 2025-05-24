<?php

namespace App\Service;

use BcMath\Number;

class ExchangerService
{
    public static function calcByDirectRate(Number $rate, Number $amount): Number
    {
        return new Number(0);
    }

    public static function calcByReverseRate(Number $rate, Number $amount): Number
    {
        return new Number(0);
    }

    public static function calcByCrossRate(Number $rate, Number $amount): Number
    {
        return new Number(0);
    }
}