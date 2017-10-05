<?php

use App\Services\Monitoring;
use App\Services\MonitoringParser;
use Core\Util;
use PHPUnit\Framework\TestCase;
use Core\Underscore as _;

class MonitoringTest extends TestCase {
    static $derefedParams = array(
        'STRUCTURES_TO_MONITOR' =>
            array(
                0 => '0',
                1 => '7',
            ),
        'DOCUMENTS' =>
            array(
                0 => '0',
                1 => '5',
            ),
        'DESCRIPTION' => 'desc',
        'LOCATION' => '2',
        'ADDRESS' => 'address',
        'SITE_COUNT' => 2,
        'DISTANCE_BETWEEN_SITES' => '1',
        'USED_FOR' => '0',
        'TOTAL_AREA' => 42,
        'VOLUME' => 43,
        'FLOORS' =>
            array(
                0 => 44,
                1 => 45,
            ),
        'HAS_UNDERGROUND_FLOORS' => 'Имеется',
        'UNDERGROUND_FLOORS' => 2,
        'MONITORING_GOAL' => '1',
        'DURATION' => '1',
        'TRANSPORT_ACCESSIBILITY' => '1',
        'PACKAGE_SELECTION' => 'INDIVIDUAL',
    );

    function testCalculatorContext() {
        $params = [
            "STRUCTURES_TO_MONITOR" => [
                "1",
                "2",
            ],
            "DOCUMENTS" => [
                "1",
                "2",
            ],
            "DESCRIPTION" => "desc",
            "LOCATION" => "1",
            "ADDRESS" => "address",
            "SITE_COUNT" => 2,
            "DISTANCE_BETWEEN_SITES" => "1",
            "USED_FOR" => "1",
            "TOTAL_AREA" => 42,
            "VOLUME" => 43,
            "FLOORS" => [
                44,
                45,
            ],
            "HAS_UNDERGROUND_FLOORS" => true,
            "UNDERGROUND_FLOORS" => 2,
            "MONITORING_GOAL" => "1",
            "DURATION" => "1",
            "TRANSPORT_ACCESSIBILITY" => "1",
            "PACKAGE_SELECTION" => "INDIVIDUAL",
        ];
        $expected = [
            "apiEndpoint" => "/api/services/monitoring/calculator/calculate",
            "state" => [
                "action" => "calculate",
                "params" => $params,
                "errors" => [],
                "model" => [
                    "STRUCTURES_TO_MONITOR" => [
                        [
                            "ID" => "1",
                            "NAME" => "комплексный мониторинг состояния строительных конструкций зданий и сооружений",
                        ],
                        [
                            "ID" => "2",
                            "NAME" => "мониторинг состояния фундаментов",
                        ],
                    ],
                    "DOCUMENTS" => [
                        [
                            "ID" => "1",
                            "NAME" => "Результаты выполненых обследований или экспертиз",
                        ],
                        [
                            "ID" => "2",
                            "NAME" => "Результаты ранее проведенного мониторинга",
                        ],
                    ],
                    "DESCRIPTION" => "desc",
                    "LOCATION" => [
                        "ID" => "1",
                        "NAME" => "Москва",
                    ],
                    "ADDRESS" => "address",
                    "SITE_COUNT" => 2,
                    "DISTANCE_BETWEEN_SITES" => [
                        "ID" => "1",
                        "NAME" => "Объекты находятся в одном месте",
                    ],
                    "USED_FOR" => [
                        "ID" => "1",
                        "NAME" => "Однаквартирные жилые здания",
                    ],
                    "TOTAL_AREA" => 42,
                    "VOLUME" => 43,
                    "FLOORS" => [
                        44,
                        45,
                    ],
                    "HAS_UNDERGROUND_FLOORS" => [
                        "ID" => "1",
                        "NAME" => "Имеется",
                    ],
                    "UNDERGROUND_FLOORS" => 2,
                    "MONITORING_GOAL" => [
                        "ID" => "1",
                        "NAME" => "Реконструкция или капитальный ремонт",
                    ],
                    "DURATION" => [
                        "ID" => "1",
                        "NAME" => "1",
                    ],
                    "TRANSPORT_ACCESSIBILITY" => [
                        "ID" => "1",
                        "NAME" => "Зона действия общественного транспорта",
                    ],
                    "PACKAGE_SELECTION" => "INDIVIDUAL",
                ],
                "result" => [
                    "total_price" => 6975.3398003638295,
                ],
            ],
            "heading" => "Определение стоимости<br> проведения мониторинга",
            "floorInputs" => [
                [
                    "label" => "Строение 1",
                ],
                [
                    "label" => "Строение 2",
                ],
            ],
            "showDistanceSelect" => true,
            "showDistanceWarning" => false,
            "showUndergroundFloors" => true,
            "resultBlock" => [
                "apiUri" => "/api/services/monitoring/calculator/send_proposal",
                "screen" => "result",
                "result" => [
                    "total_price" => "6 975 руб./мес.",
                    "summary_values" => [
                        "Продолжительность выполнения работ" => "1 месяц",
                    ],
                ],
                "params" => [
                    "EMAIL" => "",
                ],
                "errors" => [],
            ],
        ];
        $parser = new MonitoringParser();
        $data = $parser->parseFile(Util::joinPath([__DIR__, '../fixtures/calculator/Мониторинг калькуляторы.xlsx']));
        $ctx = Monitoring::calculatorContext(Monitoring::state($params, 'calculate', $data));
        $actual = array_reduce(['state.data_set', 'options'], [_::class, 'remove'], $ctx);
        $this->assertEquals($expected, $actual);
    }

    function testProposalTables() {
        // TODO implement
    }

    function testProposalParams() {
        // TODO implement
    }
}
