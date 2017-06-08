<?php

use App\Services\ExaminationCalculator;
use PHPUnit\Framework\TestCase;

class ExaminationCalculatorTest extends TestCase {
    /** @dataProvider priceProvider */
    function testPricePerSquareMeter($sqMeters, $expected) {
        $calc = new ExaminationCalculator();
        $actual = round($calc->pricePerSquareMeter($sqMeters), 2);
        $this->assertTrue(bccomp($expected, $actual, 2) === 0, "actual {$actual}");
    }

    function priceProvider() {
        return [
            [1, 80],
            [500, 80.3], // 80 in data
            [550, 79.07],
            [2500, 59.41], // 59.7
            [4000, 53.31],
            [10000, 41.42], // 40.63
            [10100, 40.43],
            [15000, 33.34],
            [20100, 28.04],
            [30000, 20.48], // 20.23
            [40000, 16.07], // 16.28
            [70000, 10.74], // 10.6
            [80000, 10.03],
            [100000, 9.7],
            [110000, 9.8], // 9.65
            [120000, 9.45], // 9.46
            [150000, 8.8],
            [200000, 7.73], // 7.73
            [PHP_INT_MAX, 7.73],
        ];
    }
}
