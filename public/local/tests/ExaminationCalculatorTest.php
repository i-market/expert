<?php

use App\Services\ExaminationCalculator;
use PHPUnit\Framework\TestCase;

class ExaminationCalculatorTest extends TestCase {
    /** @dataProvider priceProvider */
    function testPricePerSquareMeter($sqMeters, $expected) {
        $calc = new ExaminationCalculator();
        $actual = round($calc->pricePerSquareMeter($sqMeters), 2);
        $this->assertTrue(bccomp($expected, $actual) === 0, "actual {$actual}");
    }

    function priceProvider() {
        return [
            [1, 80],
            [500, 80], // 80 in data
            [550, 79.06],
            [2500, 59.7], // 59.7 in data
            [4000, 53.31],
            [10000, 41.42], // 40.63 in data
            [10100, 40.43],
            [15000, 33.34],
            [20100, 28.04],
            [30000, 20.48], // 20.23 in data
            [40000, 16.07], // 16.28 in data
            [70000, 10.74], // 10.6 in data
            [80000, 10.03],
            [100000, 9.66],
            [110000, 9.66], // 9.65 in data
            [120000, 9.66], // 9.46 in data
            [150000, 8.8],
            [200000, 7.73], // 7.73 in data
            [PHP_INT_MAX, 7.73],
        ];
    }
}
