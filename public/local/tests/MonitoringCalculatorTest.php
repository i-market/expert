<?php

use App\Services\MonitoringCalculator;
use PHPUnit\Framework\TestCase;

class MonitoringCalculatorTest extends TestCase {
    /** @dataProvider priceProvider */
    function testPricePerSquareMeter($sqMeters, $expected) {
        $calc = new MonitoringCalculator();
        $actual = round($calc->pricePerSquareMeter($sqMeters), 2);
        $this->assertTrue(bccomp($expected, $actual, 2) === 0, "actual {$actual}");
    }

    function priceProvider() {
        return [
            [1, 120],
            [500, 120.11], // 120 in data
            [1000, 74.58], // 72.27
            [2500, 39.72], // 43.42
            [5000, 24.66], // 22.89
            [10000, 15.32], // 15.56
            [20000, 9.51], // 9.4
            [30000, 8.11], // 8.1
            [40000, 7.62], // 7.62
            [70000, 6.32], // 6.32
            [80000, 5.93], // 5.93
            [100000, 5.22], // 5.22
            [120000, 4.6], // 4.6
            [200000, 3], // 3
            [PHP_INT_MAX, 3],
        ];
    }

    function testMultipliers() {
        // TODO use good ids instead of text values
        $calc = new MonitoringCalculator();
        $data = [
            'SINGLE_BUILDING' => [
                'MULTIPLIERS' => [
                    'SITE_COUNT' => [
                        '1' => 1
                    ],
                    'TOTAL_AREA' => [],
                    'LOCATION' => [
                        'Московская область' => 1.1
                    ],
                    'MONITORING_GOAL' => [
                        'Однаквартирное жилое здание' => 0.8
                    ]
                ]
            ]
        ];
        $params = [
            'SITE_COUNT' => 1,
            'TOTAL_AREA' => 200000,
            'LOCATION' => 'Московская область',
            'MONITORING_GOAL' => 'Однаквартирное жилое здание'
        ];
        $result = $calc->multipliers($params, $data);
        $expected = [
            'SITE_COUNT' => 1,
            'LOCATION' => 1.1,
            'MONITORING_GOAL' => 0.8
        ];
        $this->assertEquals($expected, $result);
    }

    function testTotalPrice() {
        $calc = new MonitoringCalculator();
        $this->assertEquals(120000 * 4.6 * (2 + 1.5), $calc->totalPrice(120000, [2, 1.5]));
    }
}
