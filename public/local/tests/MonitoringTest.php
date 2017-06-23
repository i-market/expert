<?php

use App\Services\Monitoring;
use PHPUnit\Framework\TestCase;
use Core\Underscore as _;

class MonitoringTest extends TestCase {
    static $state = array(
        'params' =>
            array(
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
                'HAS_UNDERGROUND_FLOORS' => true,
                'UNDERGROUND_FLOORS' => 2,
                'MONITORING_GOAL' => '1',
                'DURATION' => '1',
                'TRANSPORT_ACCESSIBILITY' => '1',
                'PACKAGE_SELECTION' => 'INDIVIDUAL',
            ),
        'errors' =>
            array(
            ),
        'total_price' => 16732.800000000003,
    );

    function testProposalTables() {
        $expected = array (
            0 =>
                array (
                    'heading' => 'Сведения об объекте (объектах) мониторинга',
                    'rows' =>
                        array (
                            0 =>
                                array (
                                    0 => '<strong>Описание объекта (объектов)</strong>',
                                    1 => 'desc',
                                ),
                            1 =>
                                array (
                                    0 => '<strong>Количество зданий, сооружений, строений, помещений</strong>',
                                    1 => 2,
                                ),
                            2 =>
                                array (
                                    0 => '<strong>Местонахождение</strong>',
                                    1 => '2',
                                ),
                            3 =>
                                array (
                                    0 => '<strong>Адрес (адреса)</strong>',
                                    1 => 'address',
                                ),
                            4 =>
                                array (
                                    0 => '<strong>Назначение</strong>',
                                    1 => '1',
                                ),
                            5 =>
                                array (
                                    0 => '<strong>Общая площадь</strong>',
                                    1 => 42,
                                ),
                            6 =>
                                array (
                                    0 => '<strong>Строительный объем</strong>',
                                    1 => 43,
                                ),
                            7 =>
                                array (
                                    0 => '<strong>Количество надземных этажей</strong>',
                                    1 => 89,
                                ),
                            8 =>
                                array (
                                    0 => '<strong>Наличие технического подполья, подвала, подземных этажей у одного или нескольких объектов</strong>',
                                    1 => 'Имеется',
                                ),
                            9 =>
                                array (
                                    0 => '<strong>Количество подземных этажей</strong>',
                                    1 => 2,
                                ),
                            10 =>
                                array (
                                    0 => '<strong>Удаленность объектов друг от друга</strong>',
                                    1 => '1',
                                ),
                            11 =>
                                array (
                                    0 => '<strong>Транспортная доступность</strong>',
                                    1 => '1',
                                ),
                            12 =>
                                array (
                                    0 => '<strong>Наличие документов</strong>',
                                    1 => '<ul><li>0</li><li>5</li></ul>',
                                ),
                        ),
                ),
            1 =>
                array (
                    'heading' => 'Цели мониторинга и конструкции подлежащие мониторингу',
                    'rows' =>
                        array (
                            0 =>
                                array (
                                    0 => '<strong>Цели мониторинга</strong>',
                                    1 => '1',
                                ),
                            1 =>
                                array (
                                    0 => '<strong>Конструкции подлежащие мониторингу</strong>',
                                    1 => '<ul><li>0</li><li>7</li></ul>',
                                ),
                        ),
                ),
        );
        $this->assertEquals($expected, Monitoring::proposalTables(self::$state));
        $actual = Monitoring::proposalTables(_::set(self::$state, 'params.VOLUME', null));
        $this->assertTrue(_::get($actual, '0.rows.6.1') === '');
    }

    function testProposalParams() {
        $date = new DateTime('2017-06-23');
        $tables = [];
        $path = 'php://memory';
        $expected = [
            'type' => 'monitoring',
            'heading' => 'КОММЕРЧЕСКОЕ ПРЕДЛОЖЕНИЕ<br> на проведение мониторинга',
            'outgoingId' => '0611-1/43',
            'date' => '23 июня 2017 г.',
            'endingDate' => '23 сентября 2017 г.',
            'totalPrice' => '150 000 руб./мес.',
            'duration' => '18 месяцев',
            'tables' => $tables,
            'output' => [
                'name' => $path,
                'dest' => 'F'
            ]
        ];
        $data = [
            'total_price' => 150000,
            'duration' => '18 месяцев',
            'tables' => $tables,
            'output' => [
                'name' => $path
            ]
        ];
        $this->assertEquals($expected, Monitoring::proposalParams(43, $data, $date));
    }
}
