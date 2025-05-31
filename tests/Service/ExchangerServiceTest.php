<?php

namespace Service;

use App\Service\ExchangerService;
use PHPUnit\Framework\TestCase;
use BcMath\Number;

class ExchangerServiceTest extends TestCase
{
    public function testCalcByDirectRate(){
        $rate = new Number(2);
        $amount = new Number(2);

        $expectedRate = '2';
        $expectedResult = '4';

        $result = ExchangerService::calcByDirectRate($rate, $amount);

        $rate = $result[0];
        $result = $result[1];

        $this->assertEquals($expectedRate, $rate);
        $this->assertEquals($expectedResult, $result);
    }

    public function testCalcByReverseRate(){
        $rate = new Number(2);
        $amount = new Number(2);

        $expectedRate = '0.5';
        $expectedResult = '1';

        $result = ExchangerService::calcByReverseRate($rate, $amount);

        $rate = $result[0];
        $result = $result[1];

        $this->assertEquals($expectedRate, $rate);
        $this->assertEquals($expectedResult, $result);
    }

    public function testCalcByCrossRate(){
        $usdToBaseRate = new Number(2);
        $usdToTargetRate = new Number(2);
        $amount = new Number(2);

        $expectedRate = '1';
        $expectedResult = '2';

        $result = ExchangerService::calcByCrossRate($usdToBaseRate, $usdToTargetRate, $amount);

        $rate = $result[0];
        $result = $result[1];

        $this->assertEquals($expectedRate, $rate);
        $this->assertEquals($expectedResult, $result);
    }
}