<?php

use App\Services\Monitoring;
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
        $calc = new MonitoringCalculator();
        $data = [
            'SINGLE_BUILDING' => [
                'MULTIPLIERS' => [
                    'FLOORS' => [
                        '1' => [
                            'ID' => '1',
                            'NAME' => '4',
                            'VALUE' => 1
                        ]
                    ],
                    'TOTAL_AREA' => [],
                    'LOCATION' => [
                        '1' => [
                            'ID' => '1',
                            'NAME' => 'Московская область',
                            'VALUE' => 1.1
                        ]
                    ],
                    'MONITORING_GOAL' => [
                        '1' => [
                            'ID' => '1',
                            'NAME' => 'Однаквартирное жилое здание',
                            'VALUE' => 0.8
                        ]
                    ]
                ]
            ]
        ];
        $params = [
            'SITE_COUNT' => 1,
            'HAS_UNDERGROUND_FLOORS' => false,
            'FLOORS' => [4],
            'TOTAL_AREA' => 200000,
            'LOCATION' => '1',
            'MONITORING_GOAL' => '1'
        ];
        $result = $calc->multipliers($params, Monitoring::dataSet($data, $params));
        $expected = [
            'FLOORS' => 1,
            'LOCATION' => 1.1,
            'MONITORING_GOAL' => 0.8
        ];
        $this->assertEquals($expected, $result);
        $data = array (
            'SINGLE_BUILDING' =>
                array (
                    'MULTIPLIERS' =>
                        array (
                            'SITE_COUNT' =>
                                array (
                                    1 =>
                                        array (
                                            'ID' => '1',
                                            'NAME' => '1',
                                            'VALUE' => 1,
                                        ),
                                ),
                            'LOCATION' =>
                                array (
                                    1 =>
                                        array (
                                            'ID' => '1',
                                            'NAME' => 'Москва',
                                            'VALUE' => 1,
                                        ),
                                    2 =>
                                        array (
                                            'ID' => '2',
                                            'NAME' => 'Московская область',
                                            'VALUE' => 1.1000000000000001,
                                        ),
                                    3 =>
                                        array (
                                            'ID' => '3',
                                            'NAME' => 'Санкт-Петерьборг',
                                            'VALUE' => 2,
                                        ),
                                    4 =>
                                        array (
                                            'ID' => '4',
                                            'NAME' => 'Ленинградская область',
                                            'VALUE' => 2,
                                        ),
                                    5 =>
                                        array (
                                            'ID' => '5',
                                            'NAME' => 'Адыгея',
                                            'VALUE' => 2.2000000000000002,
                                        ),
                                    6 =>
                                        array (
                                            'ID' => '6',
                                            'NAME' => 'Алтайский край',
                                            'VALUE' => 2.5,
                                        ),
                                    7 =>
                                        array (
                                            'ID' => '7',
                                            'NAME' => 'Амурская област',
                                            'VALUE' => 3,
                                        ),
                                    8 =>
                                        array (
                                            'ID' => '8',
                                            'NAME' => 'Архангельская область',
                                            'VALUE' => 2,
                                        ),
                                    9 =>
                                        array (
                                            'ID' => '9',
                                            'NAME' => 'Астраханская область',
                                            'VALUE' => 2,
                                        ),
                                    10 =>
                                        array (
                                            'ID' => '10',
                                            'NAME' => 'Башкоркостан',
                                            'VALUE' => 2,
                                        ),
                                    11 =>
                                        array (
                                            'ID' => '11',
                                            'NAME' => 'Белгородская область',
                                            'VALUE' => 2,
                                        ),
                                    12 =>
                                        array (
                                            'ID' => '12',
                                            'NAME' => 'Брянская область',
                                            'VALUE' => 1.8,
                                        ),
                                    13 =>
                                        array (
                                            'ID' => '13',
                                            'NAME' => 'Бурятия',
                                            'VALUE' => 2.5,
                                        ),
                                    14 =>
                                        array (
                                            'ID' => '14',
                                            'NAME' => 'Владимирская область',
                                            'VALUE' => 1.2,
                                        ),
                                    15 =>
                                        array (
                                            'ID' => '15',
                                            'NAME' => 'Волгоградская область',
                                            'VALUE' => 2.2000000000000002,
                                        ),
                                    16 =>
                                        array (
                                            'ID' => '16',
                                            'NAME' => 'Вологодская область',
                                            'VALUE' => 1.5,
                                        ),
                                    17 =>
                                        array (
                                            'ID' => '17',
                                            'NAME' => 'Воронежская облать',
                                            'VALUE' => 1.8,
                                        ),
                                    18 =>
                                        array (
                                            'ID' => '18',
                                            'NAME' => 'Дагестан',
                                            'VALUE' => 2,
                                        ),
                                    19 =>
                                        array (
                                            'ID' => '19',
                                            'NAME' => 'Еврейская  АО',
                                            'VALUE' => 3,
                                        ),
                                    20 =>
                                        array (
                                            'ID' => '20',
                                            'NAME' => 'Забайкальский край',
                                            'VALUE' => 3,
                                        ),
                                    21 =>
                                        array (
                                            'ID' => '21',
                                            'NAME' => 'Ивановская область',
                                            'VALUE' => 1.5,
                                        ),
                                    22 =>
                                        array (
                                            'ID' => '22',
                                            'NAME' => 'Ингушетия',
                                            'VALUE' => 2,
                                        ),
                                    23 =>
                                        array (
                                            'ID' => '23',
                                            'NAME' => 'Иркутская область',
                                            'VALUE' => 2.5,
                                        ),
                                    24 =>
                                        array (
                                            'ID' => '24',
                                            'NAME' => 'Кабордино-Балкария',
                                            'VALUE' => 2,
                                        ),
                                    25 =>
                                        array (
                                            'ID' => '25',
                                            'NAME' => 'Калининградская область',
                                            'VALUE' => 2,
                                        ),
                                    26 =>
                                        array (
                                            'ID' => '26',
                                            'NAME' => 'Калмыкия',
                                            'VALUE' => 2,
                                        ),
                                    27 =>
                                        array (
                                            'ID' => '27',
                                            'NAME' => 'Калужская область',
                                            'VALUE' => 1.5,
                                        ),
                                    28 =>
                                        array (
                                            'ID' => '28',
                                            'NAME' => 'Камчатский край',
                                            'VALUE' => 3,
                                        ),
                                    29 =>
                                        array (
                                            'ID' => '29',
                                            'NAME' => 'Карачаево -Черкесия',
                                            'VALUE' => 2,
                                        ),
                                    30 =>
                                        array (
                                            'ID' => '30',
                                            'NAME' => 'Карелия',
                                            'VALUE' => 2,
                                        ),
                                    31 =>
                                        array (
                                            'ID' => '31',
                                            'NAME' => 'Кемеровская область',
                                            'VALUE' => 2.5,
                                        ),
                                    32 =>
                                        array (
                                            'ID' => '32',
                                            'NAME' => 'Кировская область',
                                            'VALUE' => 2,
                                        ),
                                    33 =>
                                        array (
                                            'ID' => '33',
                                            'NAME' => 'Коми',
                                            'VALUE' => 2.5,
                                        ),
                                    34 =>
                                        array (
                                            'ID' => '34',
                                            'NAME' => 'Костромская  область',
                                            'VALUE' => 2,
                                        ),
                                    35 =>
                                        array (
                                            'ID' => '35',
                                            'NAME' => 'Краснодарский край',
                                            'VALUE' => 2,
                                        ),
                                    36 =>
                                        array (
                                            'ID' => '36',
                                            'NAME' => 'Красноярский край',
                                            'VALUE' => 2.5,
                                        ),
                                    37 =>
                                        array (
                                            'ID' => '37',
                                            'NAME' => 'Крым',
                                            'VALUE' => 2.2000000000000002,
                                        ),
                                    38 =>
                                        array (
                                            'ID' => '38',
                                            'NAME' => 'Курганская область',
                                            'VALUE' => 2.5,
                                        ),
                                    39 =>
                                        array (
                                            'ID' => '39',
                                            'NAME' => 'Курская  область',
                                            'VALUE' => 2,
                                        ),
                                    40 =>
                                        array (
                                            'ID' => '40',
                                            'NAME' => 'Липецкая область',
                                            'VALUE' => 2,
                                        ),
                                    41 =>
                                        array (
                                            'ID' => '41',
                                            'NAME' => 'Магаданская область',
                                            'VALUE' => 3,
                                        ),
                                    42 =>
                                        array (
                                            'ID' => '42',
                                            'NAME' => 'Марий Эл',
                                            'VALUE' => 3,
                                        ),
                                    43 =>
                                        array (
                                            'ID' => '43',
                                            'NAME' => 'Мордовия',
                                            'VALUE' => 2,
                                        ),
                                    44 =>
                                        array (
                                            'ID' => '44',
                                            'NAME' => 'Мурманская область',
                                            'VALUE' => 2.5,
                                        ),
                                    45 =>
                                        array (
                                            'ID' => '45',
                                            'NAME' => 'Ненецкий  АО',
                                            'VALUE' => 2.5,
                                        ),
                                    46 =>
                                        array (
                                            'ID' => '46',
                                            'NAME' => 'Нежегородская область',
                                            'VALUE' => 2,
                                        ),
                                    47 =>
                                        array (
                                            'ID' => '47',
                                            'NAME' => 'Новгородская область',
                                            'VALUE' => 2,
                                        ),
                                    48 =>
                                        array (
                                            'ID' => '48',
                                            'NAME' => 'Новосибирская область',
                                            'VALUE' => 2.5,
                                        ),
                                    49 =>
                                        array (
                                            'ID' => '49',
                                            'NAME' => 'Омская область',
                                            'VALUE' => 2.2999999999999998,
                                        ),
                                    50 =>
                                        array (
                                            'ID' => '50',
                                            'NAME' => 'Оренбурская область',
                                            'VALUE' => 2.2999999999999998,
                                        ),
                                    51 =>
                                        array (
                                            'ID' => '51',
                                            'NAME' => 'Орловская область',
                                            'VALUE' => 1.8,
                                        ),
                                    52 =>
                                        array (
                                            'ID' => '52',
                                            'NAME' => 'Пензенская область',
                                            'VALUE' => 2,
                                        ),
                                    53 =>
                                        array (
                                            'ID' => '53',
                                            'NAME' => 'Пермский край',
                                            'VALUE' => 2.5,
                                        ),
                                    54 =>
                                        array (
                                            'ID' => '54',
                                            'NAME' => 'Приморский край',
                                            'VALUE' => 3,
                                        ),
                                    55 =>
                                        array (
                                            'ID' => '55',
                                            'NAME' => 'Псковская область',
                                            'VALUE' => 2,
                                        ),
                                    56 =>
                                        array (
                                            'ID' => '56',
                                            'NAME' => 'Республика Алтай',
                                            'VALUE' => 2.5,
                                        ),
                                    57 =>
                                        array (
                                            'ID' => '57',
                                            'NAME' => 'Ростовская область',
                                            'VALUE' => 2,
                                        ),
                                    58 =>
                                        array (
                                            'ID' => '58',
                                            'NAME' => 'Рязанская область',
                                            'VALUE' => 1.5,
                                        ),
                                    59 =>
                                        array (
                                            'ID' => '59',
                                            'NAME' => 'Самарская область',
                                            'VALUE' => 2,
                                        ),
                                    60 =>
                                        array (
                                            'ID' => '60',
                                            'NAME' => 'Саратовская область',
                                            'VALUE' => 2,
                                        ),
                                    61 =>
                                        array (
                                            'ID' => '61',
                                            'NAME' => 'Сахалинская область',
                                            'VALUE' => 3,
                                        ),
                                    62 =>
                                        array (
                                            'ID' => '62',
                                            'NAME' => 'Саха (Якутия)',
                                            'VALUE' => 2.7999999999999998,
                                        ),
                                    63 =>
                                        array (
                                            'ID' => '63',
                                            'NAME' => 'Свердловская область',
                                            'VALUE' => 2.5,
                                        ),
                                    64 =>
                                        array (
                                            'ID' => '64',
                                            'NAME' => 'Северная  Осетия',
                                            'VALUE' => 2,
                                        ),
                                    65 =>
                                        array (
                                            'ID' => '65',
                                            'NAME' => 'Смоленская область',
                                            'VALUE' => 1.8,
                                        ),
                                    66 =>
                                        array (
                                            'ID' => '66',
                                            'NAME' => 'Ставропольский край',
                                            'VALUE' => 2,
                                        ),
                                    67 =>
                                        array (
                                            'ID' => '67',
                                            'NAME' => 'Тамбовская область',
                                            'VALUE' => 1.8,
                                        ),
                                    68 =>
                                        array (
                                            'ID' => '68',
                                            'NAME' => 'Татарстан',
                                            'VALUE' => 2.2000000000000002,
                                        ),
                                    69 =>
                                        array (
                                            'ID' => '69',
                                            'NAME' => 'Тверская область',
                                            'VALUE' => 1.5,
                                        ),
                                    70 =>
                                        array (
                                            'ID' => '70',
                                            'NAME' => 'Томская область',
                                            'VALUE' => 2.5,
                                        ),
                                    71 =>
                                        array (
                                            'ID' => '71',
                                            'NAME' => 'Тульская область',
                                            'VALUE' => 1.8,
                                        ),
                                    72 =>
                                        array (
                                            'ID' => '72',
                                            'NAME' => 'Тыва',
                                            'VALUE' => 2.5,
                                        ),
                                    73 =>
                                        array (
                                            'ID' => '73',
                                            'NAME' => 'Тюменская область',
                                            'VALUE' => 2.2000000000000002,
                                        ),
                                    74 =>
                                        array (
                                            'ID' => '74',
                                            'NAME' => 'Удмуртия',
                                            'VALUE' => 2.5,
                                        ),
                                    75 =>
                                        array (
                                            'ID' => '75',
                                            'NAME' => 'Ульяновская область',
                                            'VALUE' => 2.2000000000000002,
                                        ),
                                    76 =>
                                        array (
                                            'ID' => '76',
                                            'NAME' => 'Хабаровский край',
                                            'VALUE' => 3,
                                        ),
                                    77 =>
                                        array (
                                            'ID' => '77',
                                            'NAME' => 'Хакасия',
                                            'VALUE' => 2.7999999999999998,
                                        ),
                                    78 =>
                                        array (
                                            'ID' => '78',
                                            'NAME' => 'Ханты-Мансийский  АО',
                                            'VALUE' => 2.5,
                                        ),
                                    79 =>
                                        array (
                                            'ID' => '79',
                                            'NAME' => 'Челябинская область',
                                            'VALUE' => 2.2000000000000002,
                                        ),
                                    80 =>
                                        array (
                                            'ID' => '80',
                                            'NAME' => 'Чеченская республика',
                                            'VALUE' => 2,
                                        ),
                                    81 =>
                                        array (
                                            'ID' => '81',
                                            'NAME' => 'Чувашия',
                                            'VALUE' => 2,
                                        ),
                                    82 =>
                                        array (
                                            'ID' => '82',
                                            'NAME' => 'Чукотский АО',
                                            'VALUE' => 3,
                                        ),
                                    83 =>
                                        array (
                                            'ID' => '83',
                                            'NAME' => 'Ямало-Немецкий АО',
                                            'VALUE' => 2.7999999999999998,
                                        ),
                                    84 =>
                                        array (
                                            'ID' => '84',
                                            'NAME' => 'Ярославская область',
                                            'VALUE' => 1.8,
                                        ),
                                ),
                            'USED_FOR' =>
                                array (
                                    1 =>
                                        array (
                                            'ID' => '1',
                                            'NAME' => 'Однаквартирное жилое здание',
                                            'VALUE' => 0.80000000000000004,
                                        ),
                                    2 =>
                                        array (
                                            'ID' => '2',
                                            'NAME' => 'Многоквартирное жилое здание',
                                            'VALUE' => 1.1000000000000001,
                                        ),
                                    3 =>
                                        array (
                                            'ID' => '3',
                                            'NAME' => 'Административное здание',
                                            'VALUE' => 1.1000000000000001,
                                        ),
                                    4 =>
                                        array (
                                            'ID' => '4',
                                            'NAME' => 'Общественное здание',
                                            'VALUE' => 1.1000000000000001,
                                        ),
                                    5 =>
                                        array (
                                            'ID' => '5',
                                            'NAME' => 'Бытовое здание',
                                            'VALUE' => 0.80000000000000004,
                                        ),
                                    6 =>
                                        array (
                                            'ID' => '6',
                                            'NAME' => 'Производственное здание',
                                            'VALUE' => 1,
                                        ),
                                    7 =>
                                        array (
                                            'ID' => '7',
                                            'NAME' => 'Складское здание',
                                            'VALUE' => 1,
                                        ),
                                    8 =>
                                        array (
                                            'ID' => '8',
                                            'NAME' => 'Сельскохозяйственное здание',
                                            'VALUE' => 1,
                                        ),
                                    9 =>
                                        array (
                                            'ID' => '9',
                                            'NAME' => 'Техническое здание',
                                            'VALUE' => 0.80000000000000004,
                                        ),
                                    10 =>
                                        array (
                                            'ID' => '10',
                                            'NAME' => 'Многоэтажная автостоянка',
                                            'VALUE' => 1,
                                        ),
                                    11 =>
                                        array (
                                            'ID' => '11',
                                            'NAME' => 'Подземная автостоянка',
                                            'VALUE' => 1,
                                        ),
                                    12 =>
                                        array (
                                            'ID' => '12',
                                            'NAME' => 'Бомбоубежище',
                                            'VALUE' => 1,
                                        ),
                                ),
                            'VOLUME' =>
                                array (
                                ),
                            'FLOORS' =>
                                array (
                                    1 =>
                                        array (
                                            'ID' => '1',
                                            'NAME' => '1',
                                            'VALUE' => 0.69999999999999996,
                                        ),
                                    2 =>
                                        array (
                                            'ID' => '2',
                                            'NAME' => '2',
                                            'VALUE' => 0.75,
                                        ),
                                    3 =>
                                        array (
                                            'ID' => '3',
                                            'NAME' => '3',
                                            'VALUE' => 0.80000000000000004,
                                        ),
                                    4 =>
                                        array (
                                            'ID' => '4',
                                            'NAME' => '4',
                                            'VALUE' => 0.84999999999999998,
                                        ),
                                    5 =>
                                        array (
                                            'ID' => '5',
                                            'NAME' => '5',
                                            'VALUE' => 0.90000000000000002,
                                        ),
                                    6 =>
                                        array (
                                            'ID' => '6',
                                            'NAME' => '6',
                                            'VALUE' => 0.94999999999999996,
                                        ),
                                    7 =>
                                        array (
                                            'ID' => '7',
                                            'NAME' => '7',
                                            'VALUE' => 1,
                                        ),
                                    8 =>
                                        array (
                                            'ID' => '8',
                                            'NAME' => '8',
                                            'VALUE' => 1,
                                        ),
                                    9 =>
                                        array (
                                            'ID' => '9',
                                            'NAME' => '9',
                                            'VALUE' => 1,
                                        ),
                                    10 =>
                                        array (
                                            'ID' => '10',
                                            'NAME' => '10',
                                            'VALUE' => 1,
                                        ),
                                    11 =>
                                        array (
                                            'ID' => '11',
                                            'NAME' => '11',
                                            'VALUE' => 1.05,
                                        ),
                                    12 =>
                                        array (
                                            'ID' => '12',
                                            'NAME' => '12',
                                            'VALUE' => 1.05,
                                        ),
                                    13 =>
                                        array (
                                            'ID' => '13',
                                            'NAME' => '13',
                                            'VALUE' => 1.05,
                                        ),
                                    14 =>
                                        array (
                                            'ID' => '14',
                                            'NAME' => '14',
                                            'VALUE' => 1.05,
                                        ),
                                    15 =>
                                        array (
                                            'ID' => '15',
                                            'NAME' => '15',
                                            'VALUE' => 1.05,
                                        ),
                                    16 =>
                                        array (
                                            'ID' => '16',
                                            'NAME' => '16',
                                            'VALUE' => 1.1000000000000001,
                                        ),
                                    17 =>
                                        array (
                                            'ID' => '17',
                                            'NAME' => '17',
                                            'VALUE' => 1.1000000000000001,
                                        ),
                                    18 =>
                                        array (
                                            'ID' => '18',
                                            'NAME' => '18',
                                            'VALUE' => 1.1000000000000001,
                                        ),
                                    19 =>
                                        array (
                                            'ID' => '19',
                                            'NAME' => '19',
                                            'VALUE' => 1.1000000000000001,
                                        ),
                                    20 =>
                                        array (
                                            'ID' => '20',
                                            'NAME' => '20',
                                            'VALUE' => 1.1000000000000001,
                                        ),
                                    21 =>
                                        array (
                                            'ID' => '21',
                                            'NAME' => '21',
                                            'VALUE' => 1.1000000000000001,
                                        ),
                                    22 =>
                                        array (
                                            'ID' => '22',
                                            'NAME' => '22',
                                            'VALUE' => 1.1000000000000001,
                                        ),
                                    23 =>
                                        array (
                                            'ID' => '23',
                                            'NAME' => '23',
                                            'VALUE' => 1.2,
                                        ),
                                    24 =>
                                        array (
                                            'ID' => '24',
                                            'NAME' => '24',
                                            'VALUE' => 1.2,
                                        ),
                                    25 =>
                                        array (
                                            'ID' => '25',
                                            'NAME' => '25',
                                            'VALUE' => 1.2,
                                        ),
                                    26 =>
                                        array (
                                            'ID' => '26',
                                            'NAME' => '26',
                                            'VALUE' => 1.25,
                                        ),
                                    27 =>
                                        array (
                                            'ID' => '27',
                                            'NAME' => '27',
                                            'VALUE' => 1.25,
                                        ),
                                    28 =>
                                        array (
                                            'ID' => '28',
                                            'NAME' => '28',
                                            'VALUE' => 1.25,
                                        ),
                                    29 =>
                                        array (
                                            'ID' => '29',
                                            'NAME' => '29',
                                            'VALUE' => 1.25,
                                        ),
                                    30 =>
                                        array (
                                            'ID' => '30',
                                            'NAME' => '30',
                                            'VALUE' => 1.25,
                                        ),
                                    31 =>
                                        array (
                                            'ID' => '31',
                                            'NAME' => 'Более 30',
                                            'VALUE' => 3,
                                        ),
                                ),
                            'HAS_UNDERGROUND_FLOORS' =>
                                array (
                                    1 =>
                                        array (
                                            'ID' => '1',
                                            'NAME' => 'Имеется',
                                            'VALUE' => 1,
                                        ),
                                    2 =>
                                        array (
                                            'ID' => '2',
                                            'NAME' => 'Не имеется',
                                            'VALUE' => 0.90000000000000002,
                                        ),
                                ),
                            'UNDERGROUND_FLOORS' =>
                                array (
                                    1 =>
                                        array (
                                            'ID' => '1',
                                            'NAME' => '1',
                                            'VALUE' => 1,
                                        ),
                                    2 =>
                                        array (
                                            'ID' => '2',
                                            'NAME' => '2',
                                            'VALUE' => 1.1000000000000001,
                                        ),
                                    3 =>
                                        array (
                                            'ID' => '3',
                                            'NAME' => '3',
                                            'VALUE' => 1.1499999999999999,
                                        ),
                                    4 =>
                                        array (
                                            'ID' => '4',
                                            'NAME' => 'Более 3',
                                            'VALUE' => 1.2,
                                        ),
                                ),
                            'MONITORING_GOAL' =>
                                array (
                                    1 =>
                                        array (
                                            'ID' => '1',
                                            'NAME' => 'Реконструкция или капитальный ремонт',
                                            'VALUE' => 0.90000000000000002,
                                        ),
                                    2 =>
                                        array (
                                            'ID' => '2',
                                            'NAME' => 'Оценка возможности дальнейшей безаварийной эксплуатации, необходимости восстановления, усиления и пр.',
                                            'VALUE' => 1,
                                        ),
                                    3 =>
                                        array (
                                            'ID' => '3',
                                            'NAME' => 'Выявление конструкций и оборудования, которые изношены, повреждены или изменили свое напряженно-деформированное состояние',
                                            'VALUE' => 1,
                                        ),
                                    4 =>
                                        array (
                                            'ID' => '4',
                                            'NAME' => 'Установление возможности безопасной эксплуатации в зоне влияния строек и природно-техногенных воздействий',
                                            'VALUE' => 0.90000000000000002,
                                        ),
                                    5 =>
                                        array (
                                            'ID' => '5',
                                            'NAME' => 'Установление технического состояния при ограниченно работоспособном или аварийном состоянии, для оценки текущего технического состояния и проведения мероприятий по устранению аварийного состояния',
                                            'VALUE' => 1,
                                        ),
                                    6 =>
                                        array (
                                            'ID' => '6',
                                            'NAME' => 'Контроль состояния несущих конструкций и предотвращения катастроф, связанных с обрушением в том числе высотных и большепролетных, зданий и сооружений',
                                            'VALUE' => 1.1000000000000001,
                                        ),
                                    7 =>
                                        array (
                                            'ID' => '7',
                                            'NAME' => 'Установление пригодности к эксплуатации при изменении технологического назначения',
                                            'VALUE' => 0.90000000000000002,
                                        ),
                                    8 =>
                                        array (
                                            'ID' => '8',
                                            'NAME' => 'Установление пригодности к эксплуатации при обнаружении значительных дефектов, повреждений и деформаций в процессе технического обслуживания',
                                            'VALUE' => 1,
                                        ),
                                    9 =>
                                        array (
                                            'ID' => '9',
                                            'NAME' => 'Установление технического состояния по результатам последствий пожаров, стихийных бедствий, аварий и пр.',
                                            'VALUE' => 0.90000000000000002,
                                        ),
                                    10 =>
                                        array (
                                            'ID' => '10',
                                            'NAME' => 'Выявление деформаций грунтовых оснований',
                                            'VALUE' => 0.90000000000000002,
                                        ),
                                ),
                            'STRUCTURES_TO_MONITOR' =>
                                array (
                                    'PACKAGE' =>
                                        array (
                                            1 =>
                                                array (
                                                    'ID' => '1',
                                                    'NAME' => 'комплексный мониторинг состояния строительных конструкций зданий и сооружений',
                                                    'VALUE' => 1,
                                                ),
                                        ),
                                    'INDIVIDUAL' =>
                                        array (
                                            2 =>
                                                array (
                                                    'ID' => '2',
                                                    'NAME' => 'мониторинг состояния фундаментов',
                                                    'VALUE' =>
                                                        array (
                                                            1 => 0.68000000000000005,
                                                            2 => 0.34000000000000002,
                                                            3 => 0.22666666666667001,
                                                            4 => 0.17000000000000001,
                                                            '5-8' => 0.13600000000000001,
                                                        ),
                                                ),
                                            3 =>
                                                array (
                                                    'ID' => '3',
                                                    'NAME' => 'мониторинг состояния технических подпольев, цокольных помещений, подвальных помещений, подземных гаражей и стоянок',
                                                    'VALUE' =>
                                                        array (
                                                            1 => 0.71999999999999997,
                                                            2 => 0.35999999999999999,
                                                            3 => 0.23999999999999999,
                                                            4 => 0.17999999999999999,
                                                            '5-8' => 0.14399999999999999,
                                                        ),
                                                ),
                                            4 =>
                                                array (
                                                    'ID' => '4',
                                                    'NAME' => 'комплексный мониторинг состояния полов выполненных по грунтовому основанию (бетонных, железобетонных, фибробетонных)',
                                                    'VALUE' =>
                                                        array (
                                                            1 => 0.68999999999999995,
                                                            2 => 0.34499999999999997,
                                                            3 => 0.23000000000000001,
                                                            4 => 0.17249999999999999,
                                                            '5-8' => 0.13800000000000001,
                                                        ),
                                                ),
                                            5 =>
                                                array (
                                                    'ID' => '5',
                                                    'NAME' => 'мониторинг состояния стен, колонн, пилонов и пр.',
                                                    'VALUE' =>
                                                        array (
                                                            1 => 0.73999999999999999,
                                                            2 => 0.37,
                                                            3 => 0.24666666666667,
                                                            4 => 0.185,
                                                            '5-8' => 0.14799999999999999,
                                                        ),
                                                ),
                                            6 =>
                                                array (
                                                    'ID' => '6',
                                                    'NAME' => 'мониторинг состояния окон, дверей, витражных и светопрозрачных конструкций',
                                                    'VALUE' =>
                                                        array (
                                                            1 => 0.65000000000000002,
                                                            2 => 0.32500000000000001,
                                                            3 => 0.21666666666667,
                                                            4 => 0.16250000000000001,
                                                            '5-8' => 0.13,
                                                        ),
                                                ),
                                            7 =>
                                                array (
                                                    'ID' => '7',
                                                    'NAME' => 'мониторинг состояния перекрытий, лестничных площадок и маршей, покрытий',
                                                    'VALUE' =>
                                                        array (
                                                            1 => 0.72999999999999998,
                                                            2 => 0.36499999999999999,
                                                            3 => 0.24333333333332999,
                                                            4 => 0.1825,
                                                            '5-8' => 0.14599999999999999,
                                                        ),
                                                ),
                                            8 =>
                                                array (
                                                    'ID' => '8',
                                                    'NAME' => 'мониторинг состояния конструкций кровли',
                                                    'VALUE' =>
                                                        array (
                                                            1 => 0.70999999999999996,
                                                            2 => 0.35499999999999998,
                                                            3 => 0.23666666666666999,
                                                            4 => 0.17749999999999999,
                                                            '5-8' => 0.14199999999999999,
                                                        ),
                                                ),
                                            9 =>
                                                array (
                                                    'ID' => '9',
                                                    'NAME' => 'мониторинг состояния бассейнов, резервуаров',
                                                    'VALUE' =>
                                                        array (
                                                            1 => 0.71999999999999997,
                                                            2 => 0.35999999999999999,
                                                            3 => 0.23999999999999999,
                                                            4 => 0.17999999999999999,
                                                            '5-8' => 0.14399999999999999,
                                                        ),
                                                ),
                                        ),
                                ),
                            'DURATION' =>
                                array (
                                    1 =>
                                        array (
                                            'ID' => '1',
                                            'NAME' => '1',
                                            'VALUE' => 1.2,
                                        ),
                                    2 =>
                                        array (
                                            'ID' => '2',
                                            'NAME' => '2',
                                            'VALUE' => 1.2,
                                        ),
                                    3 =>
                                        array (
                                            'ID' => '3',
                                            'NAME' => '3',
                                            'VALUE' => 1.1000000000000001,
                                        ),
                                    4 =>
                                        array (
                                            'ID' => '4',
                                            'NAME' => '4',
                                            'VALUE' => 1.1000000000000001,
                                        ),
                                    5 =>
                                        array (
                                            'ID' => '5',
                                            'NAME' => '5',
                                            'VALUE' => 1.1000000000000001,
                                        ),
                                    6 =>
                                        array (
                                            'ID' => '6',
                                            'NAME' => '6',
                                            'VALUE' => 1,
                                        ),
                                    7 =>
                                        array (
                                            'ID' => '7',
                                            'NAME' => '7',
                                            'VALUE' => 1,
                                        ),
                                    8 =>
                                        array (
                                            'ID' => '8',
                                            'NAME' => '8',
                                            'VALUE' => 1,
                                        ),
                                    9 =>
                                        array (
                                            'ID' => '9',
                                            'NAME' => '9',
                                            'VALUE' => 1,
                                        ),
                                    10 =>
                                        array (
                                            'ID' => '10',
                                            'NAME' => '10',
                                            'VALUE' => 1,
                                        ),
                                    11 =>
                                        array (
                                            'ID' => '11',
                                            'NAME' => '11',
                                            'VALUE' => 1,
                                        ),
                                    12 =>
                                        array (
                                            'ID' => '12',
                                            'NAME' => '12',
                                            'VALUE' => 1,
                                        ),
                                    13 =>
                                        array (
                                            'ID' => '13',
                                            'NAME' => '13',
                                            'VALUE' => 0.90000000000000002,
                                        ),
                                    14 =>
                                        array (
                                            'ID' => '14',
                                            'NAME' => '14',
                                            'VALUE' => 0.90000000000000002,
                                        ),
                                    15 =>
                                        array (
                                            'ID' => '15',
                                            'NAME' => '15',
                                            'VALUE' => 0.90000000000000002,
                                        ),
                                    16 =>
                                        array (
                                            'ID' => '16',
                                            'NAME' => '16',
                                            'VALUE' => 0.90000000000000002,
                                        ),
                                    17 =>
                                        array (
                                            'ID' => '17',
                                            'NAME' => '17',
                                            'VALUE' => 0.90000000000000002,
                                        ),
                                    18 =>
                                        array (
                                            'ID' => '18',
                                            'NAME' => '18',
                                            'VALUE' => 0.80000000000000004,
                                        ),
                                    19 =>
                                        array (
                                            'ID' => '19',
                                            'NAME' => '19',
                                            'VALUE' => 0.80000000000000004,
                                        ),
                                    20 =>
                                        array (
                                            'ID' => '20',
                                            'NAME' => '20',
                                            'VALUE' => 0.80000000000000004,
                                        ),
                                    21 =>
                                        array (
                                            'ID' => '21',
                                            'NAME' => '21',
                                            'VALUE' => 0.80000000000000004,
                                        ),
                                    22 =>
                                        array (
                                            'ID' => '22',
                                            'NAME' => '22',
                                            'VALUE' => 0.80000000000000004,
                                        ),
                                    23 =>
                                        array (
                                            'ID' => '23',
                                            'NAME' => '23',
                                            'VALUE' => 0.80000000000000004,
                                        ),
                                    24 =>
                                        array (
                                            'ID' => '24',
                                            'NAME' => '24',
                                            'VALUE' => 0.80000000000000004,
                                        ),
                                    25 =>
                                        array (
                                            'ID' => '25',
                                            'NAME' => 'Более 24',
                                            'VALUE' => 0.80000000000000004,
                                        ),
                                ),
                            'DISTANCE_BETWEEN_SITES' =>
                                array (
                                ),
                            'TRANSPORT_ACCESSIBILITY' =>
                                array (
                                    1 =>
                                        array (
                                            'ID' => '1',
                                            'NAME' => 'Зона действия общественного транспорта',
                                            'VALUE' => 0.80000000000000004,
                                        ),
                                    2 =>
                                        array (
                                            'ID' => '2',
                                            'NAME' => 'Автотранспортом по дорогам общего пользования, время в пути не более 2-х часов',
                                            'VALUE' => 1,
                                        ),
                                    3 =>
                                        array (
                                            'ID' => '3',
                                            'NAME' => 'Автотранспортом по дорогам общего пользования, время в пути не более 5-и часов',
                                            'VALUE' => 1.2,
                                        ),
                                    4 =>
                                        array (
                                            'ID' => '4',
                                            'NAME' => 'Автотранспортом по дорогем общего пользования, время в пути более 5-и часов',
                                            'VALUE' => 1.5,
                                        ),
                                    5 =>
                                        array (
                                            'ID' => '5',
                                            'NAME' => 'Автотранспортом повышенной проходимости по груновым дорогам, время в пути не более 2-х часов',
                                            'VALUE' => 2,
                                        ),
                                    6 =>
                                        array (
                                            'ID' => '6',
                                            'NAME' => 'Автотранспортом повышенной проходимости по груновым дорогам, время в пути не более 5-и часов',
                                            'VALUE' => 2.2000000000000002,
                                        ),
                                    7 =>
                                        array (
                                            'ID' => '7',
                                            'NAME' => 'Автотрансопортом повышенной проходимости по груновым дорогам, время в пути более 5-и часов',
                                            'VALUE' => 2.5,
                                        ),
                                    8 =>
                                        array (
                                            'ID' => '8',
                                            'NAME' => 'Требуется спецтранспорт (вездиход, вертолет и пр.)',
                                            'VALUE' => 3,
                                        ),
                                ),
                            'DOCUMENTS' =>
                                array (
                                    1 =>
                                        array (
                                            'ID' => '1',
                                            'NAME' => 'Результаты выполненых обследований или экспертиз',
                                            'VALUE' =>
                                                array (
                                                    1 => 0.98999999999999999,
                                                ),
                                        ),
                                    2 =>
                                        array (
                                            'ID' => '2',
                                            'NAME' => 'Результаты ранее проведенного мониторинга',
                                            'VALUE' =>
                                                array (
                                                    1 => 0.98999999999999999,
                                                ),
                                        ),
                                    3 =>
                                        array (
                                            'ID' => '3',
                                            'NAME' => 'Результаты гидрогеологических изысканий',
                                            'VALUE' =>
                                                array (
                                                    1 => 0.98999999999999999,
                                                ),
                                        ),
                                    4 =>
                                        array (
                                            'ID' => '4',
                                            'NAME' => 'Проектная документация',
                                            'VALUE' =>
                                                array (
                                                    1 => 0.97999999999999998,
                                                ),
                                        ),
                                    5 =>
                                        array (
                                            'ID' => '5',
                                            'NAME' => 'Рабочая документация',
                                            'VALUE' =>
                                                array (
                                                    1 => 0.97999999999999998,
                                                ),
                                        ),
                                    6 =>
                                        array (
                                            'ID' => '6',
                                            'NAME' => 'Планы БТИ',
                                            'VALUE' =>
                                                array (
                                                    1 => 0.98999999999999999,
                                                ),
                                        ),
                                ),
                        ),
                ),
            'MULTIPLE_BUILDINGS' =>
                array (
                    'MULTIPLIERS' =>
                        array (
                            'SITE_COUNT' =>
                                array (
                                    1 =>
                                        array (
                                            'ID' => '1',
                                            'NAME' => '2',
                                            'VALUE' => 1.1200000000000001,
                                        ),
                                    2 =>
                                        array (
                                            'ID' => '2',
                                            'NAME' => '3',
                                            'VALUE' => 1.1399999999999999,
                                        ),
                                    3 =>
                                        array (
                                            'ID' => '3',
                                            'NAME' => '4',
                                            'VALUE' => 1.1599999999999999,
                                        ),
                                    4 =>
                                        array (
                                            'ID' => '4',
                                            'NAME' => '5',
                                            'VALUE' => 1.1799999999999999,
                                        ),
                                    5 =>
                                        array (
                                            'ID' => '5',
                                            'NAME' => '6',
                                            'VALUE' => 1.2,
                                        ),
                                    6 =>
                                        array (
                                            'ID' => '6',
                                            'NAME' => '7',
                                            'VALUE' => 1.22,
                                        ),
                                    7 =>
                                        array (
                                            'ID' => '7',
                                            'NAME' => '8',
                                            'VALUE' => 1.24,
                                        ),
                                    8 =>
                                        array (
                                            'ID' => '8',
                                            'NAME' => '9',
                                            'VALUE' => 1.26,
                                        ),
                                    9 =>
                                        array (
                                            'ID' => '9',
                                            'NAME' => '10',
                                            'VALUE' => 1.28,
                                        ),
                                    10 =>
                                        array (
                                            'ID' => '10',
                                            'NAME' => '11',
                                            'VALUE' => 1.3,
                                        ),
                                    11 =>
                                        array (
                                            'ID' => '11',
                                            'NAME' => '12',
                                            'VALUE' => 1.3200000000000001,
                                        ),
                                    12 =>
                                        array (
                                            'ID' => '12',
                                            'NAME' => '13',
                                            'VALUE' => 1.3400000000000001,
                                        ),
                                    13 =>
                                        array (
                                            'ID' => '13',
                                            'NAME' => '14',
                                            'VALUE' => 1.3600000000000001,
                                        ),
                                    14 =>
                                        array (
                                            'ID' => '14',
                                            'NAME' => '15',
                                            'VALUE' => 1.3799999999999999,
                                        ),
                                    15 =>
                                        array (
                                            'ID' => '15',
                                            'NAME' => '16',
                                            'VALUE' => 1.3999999999999999,
                                        ),
                                    16 =>
                                        array (
                                            'ID' => '16',
                                            'NAME' => '17',
                                            'VALUE' => 1.4199999999999999,
                                        ),
                                    17 =>
                                        array (
                                            'ID' => '17',
                                            'NAME' => '18',
                                            'VALUE' => 1.4399999999999999,
                                        ),
                                    18 =>
                                        array (
                                            'ID' => '18',
                                            'NAME' => '19',
                                            'VALUE' => 1.46,
                                        ),
                                    19 =>
                                        array (
                                            'ID' => '19',
                                            'NAME' => '20',
                                            'VALUE' => 1.48,
                                        ),
                                    20 =>
                                        array (
                                            'ID' => '20',
                                            'NAME' => '21',
                                            'VALUE' => 1.5,
                                        ),
                                    21 =>
                                        array (
                                            'ID' => '21',
                                            'NAME' => '22',
                                            'VALUE' => 1.51,
                                        ),
                                    22 =>
                                        array (
                                            'ID' => '22',
                                            'NAME' => '23',
                                            'VALUE' => 1.52,
                                        ),
                                    23 =>
                                        array (
                                            'ID' => '23',
                                            'NAME' => '24',
                                            'VALUE' => 1.53,
                                        ),
                                    24 =>
                                        array (
                                            'ID' => '24',
                                            'NAME' => '25',
                                            'VALUE' => 1.54,
                                        ),
                                    25 =>
                                        array (
                                            'ID' => '25',
                                            'NAME' => '26',
                                            'VALUE' => 1.55,
                                        ),
                                    26 =>
                                        array (
                                            'ID' => '26',
                                            'NAME' => '27',
                                            'VALUE' => 1.5600000000000001,
                                        ),
                                    27 =>
                                        array (
                                            'ID' => '27',
                                            'NAME' => '28',
                                            'VALUE' => 1.5700000000000001,
                                        ),
                                    28 =>
                                        array (
                                            'ID' => '28',
                                            'NAME' => '29',
                                            'VALUE' => 1.5800000000000001,
                                        ),
                                    29 =>
                                        array (
                                            'ID' => '29',
                                            'NAME' => '30',
                                            'VALUE' => 1.5900000000000001,
                                        ),
                                    30 =>
                                        array (
                                            'ID' => '30',
                                            'NAME' => '31',
                                            'VALUE' => 1.6000000000000001,
                                        ),
                                    31 =>
                                        array (
                                            'ID' => '31',
                                            'NAME' => '32',
                                            'VALUE' => 1.6100000000000001,
                                        ),
                                    32 =>
                                        array (
                                            'ID' => '32',
                                            'NAME' => '33',
                                            'VALUE' => 1.6200000000000001,
                                        ),
                                    33 =>
                                        array (
                                            'ID' => '33',
                                            'NAME' => '34',
                                            'VALUE' => 1.6299999999999999,
                                        ),
                                    34 =>
                                        array (
                                            'ID' => '34',
                                            'NAME' => '35',
                                            'VALUE' => 1.6399999999999999,
                                        ),
                                    35 =>
                                        array (
                                            'ID' => '35',
                                            'NAME' => '36',
                                            'VALUE' => 1.6499999999999999,
                                        ),
                                    36 =>
                                        array (
                                            'ID' => '36',
                                            'NAME' => '37',
                                            'VALUE' => 1.6599999999999999,
                                        ),
                                    37 =>
                                        array (
                                            'ID' => '37',
                                            'NAME' => '38',
                                            'VALUE' => 1.6699999999999999,
                                        ),
                                    38 =>
                                        array (
                                            'ID' => '38',
                                            'NAME' => '39',
                                            'VALUE' => 1.6799999999999999,
                                        ),
                                    39 =>
                                        array (
                                            'ID' => '39',
                                            'NAME' => '40',
                                            'VALUE' => 1.6899999999999999,
                                        ),
                                    40 =>
                                        array (
                                            'ID' => '40',
                                            'NAME' => '41',
                                            'VALUE' => 1.7,
                                        ),
                                    41 =>
                                        array (
                                            'ID' => '41',
                                            'NAME' => '42',
                                            'VALUE' => 1.71,
                                        ),
                                    42 =>
                                        array (
                                            'ID' => '42',
                                            'NAME' => '43',
                                            'VALUE' => 1.72,
                                        ),
                                    43 =>
                                        array (
                                            'ID' => '43',
                                            'NAME' => '44',
                                            'VALUE' => 1.73,
                                        ),
                                    44 =>
                                        array (
                                            'ID' => '44',
                                            'NAME' => '45',
                                            'VALUE' => 1.74,
                                        ),
                                    45 =>
                                        array (
                                            'ID' => '45',
                                            'NAME' => '46',
                                            'VALUE' => 1.75,
                                        ),
                                    46 =>
                                        array (
                                            'ID' => '46',
                                            'NAME' => '47',
                                            'VALUE' => 1.76,
                                        ),
                                    47 =>
                                        array (
                                            'ID' => '47',
                                            'NAME' => '48',
                                            'VALUE' => 1.77,
                                        ),
                                    48 =>
                                        array (
                                            'ID' => '48',
                                            'NAME' => '49',
                                            'VALUE' => 1.78,
                                        ),
                                    49 =>
                                        array (
                                            'ID' => '49',
                                            'NAME' => '50',
                                            'VALUE' => 1.79,
                                        ),
                                    50 =>
                                        array (
                                            'ID' => '50',
                                            'NAME' => '51',
                                            'VALUE' => 1.8,
                                        ),
                                    51 =>
                                        array (
                                            'ID' => '51',
                                            'NAME' => '52',
                                            'VALUE' => 1.8100000000000001,
                                        ),
                                    52 =>
                                        array (
                                            'ID' => '52',
                                            'NAME' => '53',
                                            'VALUE' => 1.8200000000000001,
                                        ),
                                    53 =>
                                        array (
                                            'ID' => '53',
                                            'NAME' => '54',
                                            'VALUE' => 1.8300000000000001,
                                        ),
                                    54 =>
                                        array (
                                            'ID' => '54',
                                            'NAME' => '55',
                                            'VALUE' => 1.8400000000000001,
                                        ),
                                    55 =>
                                        array (
                                            'ID' => '55',
                                            'NAME' => '56',
                                            'VALUE' => 1.8500000000000001,
                                        ),
                                    56 =>
                                        array (
                                            'ID' => '56',
                                            'NAME' => '57',
                                            'VALUE' => 1.8600000000000001,
                                        ),
                                    57 =>
                                        array (
                                            'ID' => '57',
                                            'NAME' => '58',
                                            'VALUE' => 1.8700000000000001,
                                        ),
                                    58 =>
                                        array (
                                            'ID' => '58',
                                            'NAME' => '59',
                                            'VALUE' => 1.8799999999999999,
                                        ),
                                    59 =>
                                        array (
                                            'ID' => '59',
                                            'NAME' => '60',
                                            'VALUE' => 1.8899999999999999,
                                        ),
                                    60 =>
                                        array (
                                            'ID' => '60',
                                            'NAME' => '61',
                                            'VALUE' => 1.8999999999999999,
                                        ),
                                    61 =>
                                        array (
                                            'ID' => '61',
                                            'NAME' => '62',
                                            'VALUE' => 1.905,
                                        ),
                                    62 =>
                                        array (
                                            'ID' => '62',
                                            'NAME' => '63',
                                            'VALUE' => 1.9099999999999999,
                                        ),
                                    63 =>
                                        array (
                                            'ID' => '63',
                                            'NAME' => '64',
                                            'VALUE' => 1.915,
                                        ),
                                    64 =>
                                        array (
                                            'ID' => '64',
                                            'NAME' => '65',
                                            'VALUE' => 1.9199999999999999,
                                        ),
                                    65 =>
                                        array (
                                            'ID' => '65',
                                            'NAME' => '66',
                                            'VALUE' => 1.925,
                                        ),
                                    66 =>
                                        array (
                                            'ID' => '66',
                                            'NAME' => '67',
                                            'VALUE' => 1.9299999999999999,
                                        ),
                                    67 =>
                                        array (
                                            'ID' => '67',
                                            'NAME' => '68',
                                            'VALUE' => 1.9350000000000001,
                                        ),
                                    68 =>
                                        array (
                                            'ID' => '68',
                                            'NAME' => '69',
                                            'VALUE' => 1.9399999999999999,
                                        ),
                                    69 =>
                                        array (
                                            'ID' => '69',
                                            'NAME' => '70',
                                            'VALUE' => 1.9450000000000001,
                                        ),
                                    70 =>
                                        array (
                                            'ID' => '70',
                                            'NAME' => '71',
                                            'VALUE' => 1.95,
                                        ),
                                    71 =>
                                        array (
                                            'ID' => '71',
                                            'NAME' => '72',
                                            'VALUE' => 1.9550000000000001,
                                        ),
                                    72 =>
                                        array (
                                            'ID' => '72',
                                            'NAME' => '73',
                                            'VALUE' => 1.96,
                                        ),
                                    73 =>
                                        array (
                                            'ID' => '73',
                                            'NAME' => '74',
                                            'VALUE' => 1.9650000000000001,
                                        ),
                                    74 =>
                                        array (
                                            'ID' => '74',
                                            'NAME' => '75',
                                            'VALUE' => 1.97,
                                        ),
                                    75 =>
                                        array (
                                            'ID' => '75',
                                            'NAME' => '76',
                                            'VALUE' => 1.9750000000000001,
                                        ),
                                    76 =>
                                        array (
                                            'ID' => '76',
                                            'NAME' => '77',
                                            'VALUE' => 1.98,
                                        ),
                                    77 =>
                                        array (
                                            'ID' => '77',
                                            'NAME' => '78',
                                            'VALUE' => 1.9850000000000001,
                                        ),
                                    78 =>
                                        array (
                                            'ID' => '78',
                                            'NAME' => '79',
                                            'VALUE' => 1.99,
                                        ),
                                    79 =>
                                        array (
                                            'ID' => '79',
                                            'NAME' => '80',
                                            'VALUE' => 1.9950000000000001,
                                        ),
                                    80 =>
                                        array (
                                            'ID' => '80',
                                            'NAME' => '81',
                                            'VALUE' => 2,
                                        ),
                                    81 =>
                                        array (
                                            'ID' => '81',
                                            'NAME' => '82',
                                            'VALUE' => 2.0499999999999998,
                                        ),
                                    82 =>
                                        array (
                                            'ID' => '82',
                                            'NAME' => '83',
                                            'VALUE' => 2.1000000000000001,
                                        ),
                                    83 =>
                                        array (
                                            'ID' => '83',
                                            'NAME' => '84',
                                            'VALUE' => 2.1499999999999999,
                                        ),
                                    84 =>
                                        array (
                                            'ID' => '84',
                                            'NAME' => '85',
                                            'VALUE' => 2.2000000000000002,
                                        ),
                                    85 =>
                                        array (
                                            'ID' => '85',
                                            'NAME' => '86',
                                            'VALUE' => 2.25,
                                        ),
                                    86 =>
                                        array (
                                            'ID' => '86',
                                            'NAME' => '87',
                                            'VALUE' => 2.2999999999999998,
                                        ),
                                    87 =>
                                        array (
                                            'ID' => '87',
                                            'NAME' => '88',
                                            'VALUE' => 2.3500000000000001,
                                        ),
                                    88 =>
                                        array (
                                            'ID' => '88',
                                            'NAME' => '89',
                                            'VALUE' => 2.3999999999999999,
                                        ),
                                    89 =>
                                        array (
                                            'ID' => '89',
                                            'NAME' => '90',
                                            'VALUE' => 2.4500000000000002,
                                        ),
                                    90 =>
                                        array (
                                            'ID' => '90',
                                            'NAME' => '91',
                                            'VALUE' => 2.5,
                                        ),
                                    91 =>
                                        array (
                                            'ID' => '91',
                                            'NAME' => '92',
                                            'VALUE' => 2.5499999999999998,
                                        ),
                                    92 =>
                                        array (
                                            'ID' => '92',
                                            'NAME' => '93',
                                            'VALUE' => 2.6000000000000001,
                                        ),
                                    93 =>
                                        array (
                                            'ID' => '93',
                                            'NAME' => '94',
                                            'VALUE' => 2.6499999999999999,
                                        ),
                                    94 =>
                                        array (
                                            'ID' => '94',
                                            'NAME' => '95',
                                            'VALUE' => 2.7000000000000002,
                                        ),
                                    95 =>
                                        array (
                                            'ID' => '95',
                                            'NAME' => '96',
                                            'VALUE' => 2.75,
                                        ),
                                    96 =>
                                        array (
                                            'ID' => '96',
                                            'NAME' => '97',
                                            'VALUE' => 2.7999999999999998,
                                        ),
                                    97 =>
                                        array (
                                            'ID' => '97',
                                            'NAME' => '98',
                                            'VALUE' => 2.8500000000000001,
                                        ),
                                    98 =>
                                        array (
                                            'ID' => '98',
                                            'NAME' => '99',
                                            'VALUE' => 2.8999999999999999,
                                        ),
                                    99 =>
                                        array (
                                            'ID' => '99',
                                            'NAME' => '100',
                                            'VALUE' => 2.9500000000000002,
                                        ),
                                    100 =>
                                        array (
                                            'ID' => '100',
                                            'NAME' => 'Более 100',
                                            'VALUE' => 3,
                                        ),
                                ),
                            'LOCATION' =>
                                array (
                                    1 =>
                                        array (
                                            'ID' => '1',
                                            'NAME' => 'Москва',
                                            'VALUE' => 1,
                                        ),
                                    2 =>
                                        array (
                                            'ID' => '2',
                                            'NAME' => 'Московская область',
                                            'VALUE' => 1.1000000000000001,
                                        ),
                                    3 =>
                                        array (
                                            'ID' => '3',
                                            'NAME' => 'Санкт-Петерьборг',
                                            'VALUE' => 2,
                                        ),
                                    4 =>
                                        array (
                                            'ID' => '4',
                                            'NAME' => 'Ленинградская область',
                                            'VALUE' => 2,
                                        ),
                                    5 =>
                                        array (
                                            'ID' => '5',
                                            'NAME' => 'Адыгея',
                                            'VALUE' => 2.2000000000000002,
                                        ),
                                    6 =>
                                        array (
                                            'ID' => '6',
                                            'NAME' => 'Алтайский край',
                                            'VALUE' => 2.5,
                                        ),
                                    7 =>
                                        array (
                                            'ID' => '7',
                                            'NAME' => 'Амурская област',
                                            'VALUE' => 3,
                                        ),
                                    8 =>
                                        array (
                                            'ID' => '8',
                                            'NAME' => 'Архангельская область',
                                            'VALUE' => 2,
                                        ),
                                    9 =>
                                        array (
                                            'ID' => '9',
                                            'NAME' => 'Астраханская область',
                                            'VALUE' => 2,
                                        ),
                                    10 =>
                                        array (
                                            'ID' => '10',
                                            'NAME' => 'Башкоркостан',
                                            'VALUE' => 2,
                                        ),
                                    11 =>
                                        array (
                                            'ID' => '11',
                                            'NAME' => 'Белгородская область',
                                            'VALUE' => 2,
                                        ),
                                    12 =>
                                        array (
                                            'ID' => '12',
                                            'NAME' => 'Брянская область',
                                            'VALUE' => 1.8,
                                        ),
                                    13 =>
                                        array (
                                            'ID' => '13',
                                            'NAME' => 'Бурятия',
                                            'VALUE' => 2.5,
                                        ),
                                    14 =>
                                        array (
                                            'ID' => '14',
                                            'NAME' => 'Владимирская область',
                                            'VALUE' => 1.2,
                                        ),
                                    15 =>
                                        array (
                                            'ID' => '15',
                                            'NAME' => 'Волгоградская область',
                                            'VALUE' => 2.2000000000000002,
                                        ),
                                    16 =>
                                        array (
                                            'ID' => '16',
                                            'NAME' => 'Вологодская область',
                                            'VALUE' => 1.5,
                                        ),
                                    17 =>
                                        array (
                                            'ID' => '17',
                                            'NAME' => 'Воронежская облать',
                                            'VALUE' => 1.8,
                                        ),
                                    18 =>
                                        array (
                                            'ID' => '18',
                                            'NAME' => 'Дагестан',
                                            'VALUE' => 2,
                                        ),
                                    19 =>
                                        array (
                                            'ID' => '19',
                                            'NAME' => 'Еврейская  АО',
                                            'VALUE' => 3,
                                        ),
                                    20 =>
                                        array (
                                            'ID' => '20',
                                            'NAME' => 'Забайкальский край',
                                            'VALUE' => 3,
                                        ),
                                    21 =>
                                        array (
                                            'ID' => '21',
                                            'NAME' => 'Ивановская область',
                                            'VALUE' => 1.5,
                                        ),
                                    22 =>
                                        array (
                                            'ID' => '22',
                                            'NAME' => 'Ингушетия',
                                            'VALUE' => 2,
                                        ),
                                    23 =>
                                        array (
                                            'ID' => '23',
                                            'NAME' => 'Иркутская область',
                                            'VALUE' => 2.5,
                                        ),
                                    24 =>
                                        array (
                                            'ID' => '24',
                                            'NAME' => 'Кабордино-Балкария',
                                            'VALUE' => 2,
                                        ),
                                    25 =>
                                        array (
                                            'ID' => '25',
                                            'NAME' => 'Калининградская область',
                                            'VALUE' => 2,
                                        ),
                                    26 =>
                                        array (
                                            'ID' => '26',
                                            'NAME' => 'Калмыкия',
                                            'VALUE' => 2,
                                        ),
                                    27 =>
                                        array (
                                            'ID' => '27',
                                            'NAME' => 'Калужская область',
                                            'VALUE' => 1.5,
                                        ),
                                    28 =>
                                        array (
                                            'ID' => '28',
                                            'NAME' => 'Камчатский край',
                                            'VALUE' => 3,
                                        ),
                                    29 =>
                                        array (
                                            'ID' => '29',
                                            'NAME' => 'Карачаево -Черкесия',
                                            'VALUE' => 2,
                                        ),
                                    30 =>
                                        array (
                                            'ID' => '30',
                                            'NAME' => 'Карелия',
                                            'VALUE' => 2,
                                        ),
                                    31 =>
                                        array (
                                            'ID' => '31',
                                            'NAME' => 'Кемеровская область',
                                            'VALUE' => 2.5,
                                        ),
                                    32 =>
                                        array (
                                            'ID' => '32',
                                            'NAME' => 'Кировская область',
                                            'VALUE' => 2,
                                        ),
                                    33 =>
                                        array (
                                            'ID' => '33',
                                            'NAME' => 'Коми',
                                            'VALUE' => 2.5,
                                        ),
                                    34 =>
                                        array (
                                            'ID' => '34',
                                            'NAME' => 'Костромская  область',
                                            'VALUE' => 2,
                                        ),
                                    35 =>
                                        array (
                                            'ID' => '35',
                                            'NAME' => 'Краснодарский край',
                                            'VALUE' => 2,
                                        ),
                                    36 =>
                                        array (
                                            'ID' => '36',
                                            'NAME' => 'Красноярский край',
                                            'VALUE' => 2.5,
                                        ),
                                    37 =>
                                        array (
                                            'ID' => '37',
                                            'NAME' => 'Крым',
                                            'VALUE' => 2.2000000000000002,
                                        ),
                                    38 =>
                                        array (
                                            'ID' => '38',
                                            'NAME' => 'Курганская область',
                                            'VALUE' => 2.5,
                                        ),
                                    39 =>
                                        array (
                                            'ID' => '39',
                                            'NAME' => 'Курская  область',
                                            'VALUE' => 2,
                                        ),
                                    40 =>
                                        array (
                                            'ID' => '40',
                                            'NAME' => 'Липецкая область',
                                            'VALUE' => 2,
                                        ),
                                    41 =>
                                        array (
                                            'ID' => '41',
                                            'NAME' => 'Магаданская область',
                                            'VALUE' => 3,
                                        ),
                                    42 =>
                                        array (
                                            'ID' => '42',
                                            'NAME' => 'Марий Эл',
                                            'VALUE' => 3,
                                        ),
                                    43 =>
                                        array (
                                            'ID' => '43',
                                            'NAME' => 'Мордовия',
                                            'VALUE' => 2,
                                        ),
                                    44 =>
                                        array (
                                            'ID' => '44',
                                            'NAME' => 'Мурманская область',
                                            'VALUE' => 2.5,
                                        ),
                                    45 =>
                                        array (
                                            'ID' => '45',
                                            'NAME' => 'Ненецкий  АО',
                                            'VALUE' => 2.5,
                                        ),
                                    46 =>
                                        array (
                                            'ID' => '46',
                                            'NAME' => 'Нежегородская область',
                                            'VALUE' => 2,
                                        ),
                                    47 =>
                                        array (
                                            'ID' => '47',
                                            'NAME' => 'Новгородская область',
                                            'VALUE' => 2,
                                        ),
                                    48 =>
                                        array (
                                            'ID' => '48',
                                            'NAME' => 'Новосибирская область',
                                            'VALUE' => 2.5,
                                        ),
                                    49 =>
                                        array (
                                            'ID' => '49',
                                            'NAME' => 'Омская область',
                                            'VALUE' => 2.2999999999999998,
                                        ),
                                    50 =>
                                        array (
                                            'ID' => '50',
                                            'NAME' => 'Оренбурская область',
                                            'VALUE' => 2.2999999999999998,
                                        ),
                                    51 =>
                                        array (
                                            'ID' => '51',
                                            'NAME' => 'Орловская область',
                                            'VALUE' => 1.8,
                                        ),
                                    52 =>
                                        array (
                                            'ID' => '52',
                                            'NAME' => 'Пензенская область',
                                            'VALUE' => 2,
                                        ),
                                    53 =>
                                        array (
                                            'ID' => '53',
                                            'NAME' => 'Пермский край',
                                            'VALUE' => 2.5,
                                        ),
                                    54 =>
                                        array (
                                            'ID' => '54',
                                            'NAME' => 'Приморский край',
                                            'VALUE' => 3,
                                        ),
                                    55 =>
                                        array (
                                            'ID' => '55',
                                            'NAME' => 'Псковская область',
                                            'VALUE' => 2,
                                        ),
                                    56 =>
                                        array (
                                            'ID' => '56',
                                            'NAME' => 'Республика Алтай',
                                            'VALUE' => 2.5,
                                        ),
                                    57 =>
                                        array (
                                            'ID' => '57',
                                            'NAME' => 'Ростовская область',
                                            'VALUE' => 2,
                                        ),
                                    58 =>
                                        array (
                                            'ID' => '58',
                                            'NAME' => 'Рязанская область',
                                            'VALUE' => 1.5,
                                        ),
                                    59 =>
                                        array (
                                            'ID' => '59',
                                            'NAME' => 'Самарская область',
                                            'VALUE' => 2,
                                        ),
                                    60 =>
                                        array (
                                            'ID' => '60',
                                            'NAME' => 'Саратовская область',
                                            'VALUE' => 2,
                                        ),
                                    61 =>
                                        array (
                                            'ID' => '61',
                                            'NAME' => 'Сахалинская область',
                                            'VALUE' => 3,
                                        ),
                                    62 =>
                                        array (
                                            'ID' => '62',
                                            'NAME' => 'Саха (Якутия)',
                                            'VALUE' => 2.7999999999999998,
                                        ),
                                    63 =>
                                        array (
                                            'ID' => '63',
                                            'NAME' => 'Свердловская область',
                                            'VALUE' => 2.5,
                                        ),
                                    64 =>
                                        array (
                                            'ID' => '64',
                                            'NAME' => 'Северная  Осетия',
                                            'VALUE' => 2,
                                        ),
                                    65 =>
                                        array (
                                            'ID' => '65',
                                            'NAME' => 'Смоленская область',
                                            'VALUE' => 1.8,
                                        ),
                                    66 =>
                                        array (
                                            'ID' => '66',
                                            'NAME' => 'Ставропольский край',
                                            'VALUE' => 2,
                                        ),
                                    67 =>
                                        array (
                                            'ID' => '67',
                                            'NAME' => 'Тамбовская область',
                                            'VALUE' => 1.8,
                                        ),
                                    68 =>
                                        array (
                                            'ID' => '68',
                                            'NAME' => 'Татарстан',
                                            'VALUE' => 2.2000000000000002,
                                        ),
                                    69 =>
                                        array (
                                            'ID' => '69',
                                            'NAME' => 'Тверская область',
                                            'VALUE' => 1.5,
                                        ),
                                    70 =>
                                        array (
                                            'ID' => '70',
                                            'NAME' => 'Томская область',
                                            'VALUE' => 2.5,
                                        ),
                                    71 =>
                                        array (
                                            'ID' => '71',
                                            'NAME' => 'Тульская область',
                                            'VALUE' => 1.8,
                                        ),
                                    72 =>
                                        array (
                                            'ID' => '72',
                                            'NAME' => 'Тыва',
                                            'VALUE' => 2.5,
                                        ),
                                    73 =>
                                        array (
                                            'ID' => '73',
                                            'NAME' => 'Тюменская область',
                                            'VALUE' => 2.2000000000000002,
                                        ),
                                    74 =>
                                        array (
                                            'ID' => '74',
                                            'NAME' => 'Удмуртия',
                                            'VALUE' => 2.5,
                                        ),
                                    75 =>
                                        array (
                                            'ID' => '75',
                                            'NAME' => 'Ульяновская область',
                                            'VALUE' => 2.2000000000000002,
                                        ),
                                    76 =>
                                        array (
                                            'ID' => '76',
                                            'NAME' => 'Хабаровский край',
                                            'VALUE' => 3,
                                        ),
                                    77 =>
                                        array (
                                            'ID' => '77',
                                            'NAME' => 'Хакасия',
                                            'VALUE' => 2.7999999999999998,
                                        ),
                                    78 =>
                                        array (
                                            'ID' => '78',
                                            'NAME' => 'Ханты-Мансийский  АО',
                                            'VALUE' => 2.5,
                                        ),
                                    79 =>
                                        array (
                                            'ID' => '79',
                                            'NAME' => 'Челябинская область',
                                            'VALUE' => 2.2000000000000002,
                                        ),
                                    80 =>
                                        array (
                                            'ID' => '80',
                                            'NAME' => 'Чеченская республика',
                                            'VALUE' => 2,
                                        ),
                                    81 =>
                                        array (
                                            'ID' => '81',
                                            'NAME' => 'Чувашия',
                                            'VALUE' => 2,
                                        ),
                                    82 =>
                                        array (
                                            'ID' => '82',
                                            'NAME' => 'Чукотский АО',
                                            'VALUE' => 3,
                                        ),
                                    83 =>
                                        array (
                                            'ID' => '83',
                                            'NAME' => 'Ямало-Немецкий АО',
                                            'VALUE' => 2.7999999999999998,
                                        ),
                                    84 =>
                                        array (
                                            'ID' => '84',
                                            'NAME' => 'Ярославская область',
                                            'VALUE' => 1.8,
                                        ),
                                ),
                            'USED_FOR' =>
                                array (
                                    1 =>
                                        array (
                                            'ID' => '1',
                                            'NAME' => 'Однаквартирные жилые здания',
                                            'VALUE' => 1.1000000000000001,
                                        ),
                                    2 =>
                                        array (
                                            'ID' => '2',
                                            'NAME' => 'Многоквартирные жилые здания',
                                            'VALUE' => 1.1000000000000001,
                                        ),
                                    3 =>
                                        array (
                                            'ID' => '3',
                                            'NAME' => 'Административные здания',
                                            'VALUE' => 1.1000000000000001,
                                        ),
                                    4 =>
                                        array (
                                            'ID' => '4',
                                            'NAME' => 'Общественные здания',
                                            'VALUE' => 1.1000000000000001,
                                        ),
                                    5 =>
                                        array (
                                            'ID' => '5',
                                            'NAME' => 'Бытовые здания',
                                            'VALUE' => 1.1000000000000001,
                                        ),
                                    6 =>
                                        array (
                                            'ID' => '6',
                                            'NAME' => 'Производственные здания',
                                            'VALUE' => 1.1000000000000001,
                                        ),
                                    7 =>
                                        array (
                                            'ID' => '7',
                                            'NAME' => 'Складские здания',
                                            'VALUE' => 1.1000000000000001,
                                        ),
                                    8 =>
                                        array (
                                            'ID' => '8',
                                            'NAME' => 'Сельскохозяйственные здания',
                                            'VALUE' => 1.1000000000000001,
                                        ),
                                    9 =>
                                        array (
                                            'ID' => '9',
                                            'NAME' => 'Технические здания',
                                            'VALUE' => 1.1000000000000001,
                                        ),
                                    10 =>
                                        array (
                                            'ID' => '10',
                                            'NAME' => 'Многоэтажные автостоянки',
                                            'VALUE' => 1.1000000000000001,
                                        ),
                                    11 =>
                                        array (
                                            'ID' => '11',
                                            'NAME' => 'Подземные автостоянки',
                                            'VALUE' => 1.1000000000000001,
                                        ),
                                    12 =>
                                        array (
                                            'ID' => '12',
                                            'NAME' => 'Комплекс бомбоубежищь',
                                            'VALUE' => 1.1000000000000001,
                                        ),
                                    13 =>
                                        array (
                                            'ID' => '13',
                                            'NAME' => 'Жилой комплекс',
                                            'VALUE' => 1.1000000000000001,
                                        ),
                                    14 =>
                                        array (
                                            'ID' => '14',
                                            'NAME' => 'Производственный комплекс',
                                            'VALUE' => 1.1000000000000001,
                                        ),
                                    15 =>
                                        array (
                                            'ID' => '15',
                                            'NAME' => 'Сельскохозяйственный комплекс',
                                            'VALUE' => 1.1000000000000001,
                                        ),
                                    16 =>
                                        array (
                                            'ID' => '16',
                                            'NAME' => 'Административно-офисный комплекс',
                                            'VALUE' => 1.1000000000000001,
                                        ),
                                    17 =>
                                        array (
                                            'ID' => '17',
                                            'NAME' => 'Комплекс общественных зданий',
                                            'VALUE' => 1.1000000000000001,
                                        ),
                                ),
                            'VOLUME' =>
                                array (
                                ),
                            'FLOORS' =>
                                array (
                                    1 =>
                                        array (
                                            'ID' => '1',
                                            'NAME' => '1',
                                            'VALUE' => 1.1000000000000001,
                                        ),
                                    2 =>
                                        array (
                                            'ID' => '2',
                                            'NAME' => '2',
                                            'VALUE' => 1.1000000000000001,
                                        ),
                                    3 =>
                                        array (
                                            'ID' => '3',
                                            'NAME' => '3',
                                            'VALUE' => 1.1000000000000001,
                                        ),
                                    4 =>
                                        array (
                                            'ID' => '4',
                                            'NAME' => '4',
                                            'VALUE' => 1.1000000000000001,
                                        ),
                                    5 =>
                                        array (
                                            'ID' => '5',
                                            'NAME' => '5',
                                            'VALUE' => 1.1000000000000001,
                                        ),
                                    6 =>
                                        array (
                                            'ID' => '6',
                                            'NAME' => '6',
                                            'VALUE' => 1.1000000000000001,
                                        ),
                                    7 =>
                                        array (
                                            'ID' => '7',
                                            'NAME' => '7',
                                            'VALUE' => 1.1000000000000001,
                                        ),
                                    8 =>
                                        array (
                                            'ID' => '8',
                                            'NAME' => '8',
                                            'VALUE' => 1.1000000000000001,
                                        ),
                                    9 =>
                                        array (
                                            'ID' => '9',
                                            'NAME' => '9',
                                            'VALUE' => 1.1000000000000001,
                                        ),
                                    10 =>
                                        array (
                                            'ID' => '10',
                                            'NAME' => '10',
                                            'VALUE' => 1.1000000000000001,
                                        ),
                                    11 =>
                                        array (
                                            'ID' => '11',
                                            'NAME' => '11',
                                            'VALUE' => 1.1000000000000001,
                                        ),
                                    12 =>
                                        array (
                                            'ID' => '12',
                                            'NAME' => '12',
                                            'VALUE' => 1.1000000000000001,
                                        ),
                                    13 =>
                                        array (
                                            'ID' => '13',
                                            'NAME' => '13',
                                            'VALUE' => 1.1000000000000001,
                                        ),
                                    14 =>
                                        array (
                                            'ID' => '14',
                                            'NAME' => '14',
                                            'VALUE' => 1.1000000000000001,
                                        ),
                                    15 =>
                                        array (
                                            'ID' => '15',
                                            'NAME' => '15',
                                            'VALUE' => 1.1000000000000001,
                                        ),
                                    16 =>
                                        array (
                                            'ID' => '16',
                                            'NAME' => '16',
                                            'VALUE' => 1.1000000000000001,
                                        ),
                                    17 =>
                                        array (
                                            'ID' => '17',
                                            'NAME' => '17',
                                            'VALUE' => 1.1000000000000001,
                                        ),
                                    18 =>
                                        array (
                                            'ID' => '18',
                                            'NAME' => '18',
                                            'VALUE' => 1.1000000000000001,
                                        ),
                                    19 =>
                                        array (
                                            'ID' => '19',
                                            'NAME' => '19',
                                            'VALUE' => 1.1000000000000001,
                                        ),
                                    20 =>
                                        array (
                                            'ID' => '20',
                                            'NAME' => '20',
                                            'VALUE' => 1.1000000000000001,
                                        ),
                                    21 =>
                                        array (
                                            'ID' => '21',
                                            'NAME' => '21',
                                            'VALUE' => 1.1000000000000001,
                                        ),
                                    22 =>
                                        array (
                                            'ID' => '22',
                                            'NAME' => '22',
                                            'VALUE' => 1.1000000000000001,
                                        ),
                                    23 =>
                                        array (
                                            'ID' => '23',
                                            'NAME' => '23',
                                            'VALUE' => 1.1000000000000001,
                                        ),
                                    24 =>
                                        array (
                                            'ID' => '24',
                                            'NAME' => '24',
                                            'VALUE' => 1.1000000000000001,
                                        ),
                                    25 =>
                                        array (
                                            'ID' => '25',
                                            'NAME' => '25',
                                            'VALUE' => 1.1000000000000001,
                                        ),
                                    26 =>
                                        array (
                                            'ID' => '26',
                                            'NAME' => '26',
                                            'VALUE' => 1.1000000000000001,
                                        ),
                                    27 =>
                                        array (
                                            'ID' => '27',
                                            'NAME' => '27',
                                            'VALUE' => 1.1000000000000001,
                                        ),
                                    28 =>
                                        array (
                                            'ID' => '28',
                                            'NAME' => '28',
                                            'VALUE' => 1.1000000000000001,
                                        ),
                                    29 =>
                                        array (
                                            'ID' => '29',
                                            'NAME' => '29',
                                            'VALUE' => 1.1000000000000001,
                                        ),
                                    30 =>
                                        array (
                                            'ID' => '30',
                                            'NAME' => '30',
                                            'VALUE' => 1.1000000000000001,
                                        ),
                                    31 =>
                                        array (
                                            'ID' => '31',
                                            'NAME' => 'Более 30',
                                            'VALUE' => 1.1000000000000001,
                                        ),
                                ),
                            'HAS_UNDERGROUND_FLOORS' =>
                                array (
                                    1 =>
                                        array (
                                            'ID' => '1',
                                            'NAME' => 'Имеется',
                                            'VALUE' => 1,
                                        ),
                                    2 =>
                                        array (
                                            'ID' => '2',
                                            'NAME' => 'Не имеется',
                                            'VALUE' => 1,
                                        ),
                                ),
                            'UNDERGROUND_FLOORS' =>
                                array (
                                    1 =>
                                        array (
                                            'ID' => '1',
                                            'NAME' => '1',
                                            'VALUE' => 1,
                                        ),
                                    2 =>
                                        array (
                                            'ID' => '2',
                                            'NAME' => '2',
                                            'VALUE' => 1,
                                        ),
                                    3 =>
                                        array (
                                            'ID' => '3',
                                            'NAME' => '3',
                                            'VALUE' => 1,
                                        ),
                                    4 =>
                                        array (
                                            'ID' => '4',
                                            'NAME' => 'Более 3',
                                            'VALUE' => 1,
                                        ),
                                ),
                            'MONITORING_GOAL' =>
                                array (
                                    1 =>
                                        array (
                                            'ID' => '1',
                                            'NAME' => 'Реконструкция или капитальный ремонт',
                                            'VALUE' => 0.90000000000000002,
                                        ),
                                    2 =>
                                        array (
                                            'ID' => '2',
                                            'NAME' => 'Оценка возможности дальнейшей безаварийной эксплуатации, необходимости восстановления, усиления и пр.',
                                            'VALUE' => 1,
                                        ),
                                    3 =>
                                        array (
                                            'ID' => '3',
                                            'NAME' => 'Выявление конструкций и оборудования, которые изношены, повреждены или изменили свое напряженно-деформированное состояние',
                                            'VALUE' => 1,
                                        ),
                                    4 =>
                                        array (
                                            'ID' => '4',
                                            'NAME' => 'Установление возможности безопасной эксплуатации в зоне влияния строек и природно-техногенных воздействий',
                                            'VALUE' => 0.90000000000000002,
                                        ),
                                    5 =>
                                        array (
                                            'ID' => '5',
                                            'NAME' => 'Установление технического состояния при ограниченно работоспособном или аварийном состоянии, для оценки текущего технического состояния и проведения мероприятий по устранению аварийного состояния',
                                            'VALUE' => 1,
                                        ),
                                    6 =>
                                        array (
                                            'ID' => '6',
                                            'NAME' => 'Контроль состояния несущих конструкций и предотвращения катастроф, связанных с обрушением в том числе высотных и большепролетных, зданий и сооружений',
                                            'VALUE' => 1.1000000000000001,
                                        ),
                                    7 =>
                                        array (
                                            'ID' => '7',
                                            'NAME' => 'Установление пригодности к эксплуатации при изменении технологического назначения',
                                            'VALUE' => 0.90000000000000002,
                                        ),
                                    8 =>
                                        array (
                                            'ID' => '8',
                                            'NAME' => 'Установление пригодности к эксплуатации при обнаружении значительных дефектов, повреждений и деформаций в процессе технического обслуживания',
                                            'VALUE' => 1,
                                        ),
                                    9 =>
                                        array (
                                            'ID' => '9',
                                            'NAME' => 'Установление технического состояния по результатам последствий пожаров, стихийных бедствий, аварий и пр.',
                                            'VALUE' => 0.90000000000000002,
                                        ),
                                    10 =>
                                        array (
                                            'ID' => '10',
                                            'NAME' => 'Выявление деформаций грунтовых оснований',
                                            'VALUE' => 0.90000000000000002,
                                        ),
                                ),
                            'STRUCTURES_TO_MONITOR' =>
                                array (
                                    'PACKAGE' =>
                                        array (
                                            1 =>
                                                array (
                                                    'ID' => '1',
                                                    'NAME' => 'комплексный мониторинг состояния строительных конструкций зданий и сооружений',
                                                    'VALUE' => 1,
                                                ),
                                        ),
                                    'INDIVIDUAL' =>
                                        array (
                                            2 =>
                                                array (
                                                    'ID' => '2',
                                                    'NAME' => 'мониторинг состояния фундаментов',
                                                    'VALUE' =>
                                                        array (
                                                            1 => 0.68000000000000005,
                                                            2 => 0.34000000000000002,
                                                            3 => 0.22666666666667001,
                                                            4 => 0.17000000000000001,
                                                            '5-8' => 0.13600000000000001,
                                                        ),
                                                ),
                                            3 =>
                                                array (
                                                    'ID' => '3',
                                                    'NAME' => 'мониторинг состояния технических подпольев, цокольных помещений, подвальных помещений, подземных гаражей и стоянок',
                                                    'VALUE' =>
                                                        array (
                                                            1 => 0.71999999999999997,
                                                            2 => 0.35999999999999999,
                                                            3 => 0.23999999999999999,
                                                            4 => 0.17999999999999999,
                                                            '5-8' => 0.14399999999999999,
                                                        ),
                                                ),
                                            4 =>
                                                array (
                                                    'ID' => '4',
                                                    'NAME' => 'комплексный мониторинг состояния полов выполненных по грунтовому основанию (бетонных, железобетонных, фибробетонных)',
                                                    'VALUE' =>
                                                        array (
                                                            1 => 0.68999999999999995,
                                                            2 => 0.34499999999999997,
                                                            3 => 0.23000000000000001,
                                                            4 => 0.17249999999999999,
                                                            '5-8' => 0.13800000000000001,
                                                        ),
                                                ),
                                            5 =>
                                                array (
                                                    'ID' => '5',
                                                    'NAME' => 'мониторинг состояния стен, колонн, пилонов и пр.',
                                                    'VALUE' =>
                                                        array (
                                                            1 => 0.73999999999999999,
                                                            2 => 0.37,
                                                            3 => 0.24666666666667,
                                                            4 => 0.185,
                                                            '5-8' => 0.14799999999999999,
                                                        ),
                                                ),
                                            6 =>
                                                array (
                                                    'ID' => '6',
                                                    'NAME' => 'мониторинг состояния окон, дверей, витражных и светопрозрачных конструкций',
                                                    'VALUE' =>
                                                        array (
                                                            1 => 0.65000000000000002,
                                                            2 => 0.32500000000000001,
                                                            3 => 0.21666666666667,
                                                            4 => 0.16250000000000001,
                                                            '5-8' => 0.13,
                                                        ),
                                                ),
                                            7 =>
                                                array (
                                                    'ID' => '7',
                                                    'NAME' => 'мониторинг состояния перекрытий, лестничных площадок и маршей, покрытий',
                                                    'VALUE' =>
                                                        array (
                                                            1 => 0.72999999999999998,
                                                            2 => 0.36499999999999999,
                                                            3 => 0.24333333333332999,
                                                            4 => 0.1825,
                                                            '5-8' => 0.14599999999999999,
                                                        ),
                                                ),
                                            8 =>
                                                array (
                                                    'ID' => '8',
                                                    'NAME' => 'мониторинг состояния конструкций кровли',
                                                    'VALUE' =>
                                                        array (
                                                            1 => 0.70999999999999996,
                                                            2 => 0.35499999999999998,
                                                            3 => 0.23666666666666999,
                                                            4 => 0.17749999999999999,
                                                            '5-8' => 0.14199999999999999,
                                                        ),
                                                ),
                                            9 =>
                                                array (
                                                    'ID' => '9',
                                                    'NAME' => 'мониторинг состояния бассейнов, резервуаров',
                                                    'VALUE' =>
                                                        array (
                                                            1 => 0.71999999999999997,
                                                            2 => 0.35999999999999999,
                                                            3 => 0.23999999999999999,
                                                            4 => 0.17999999999999999,
                                                            '5-8' => 0.14399999999999999,
                                                        ),
                                                ),
                                        ),
                                ),
                            'DURATION' =>
                                array (
                                    1 =>
                                        array (
                                            'ID' => '1',
                                            'NAME' => '1',
                                            'VALUE' => 1.2,
                                        ),
                                    2 =>
                                        array (
                                            'ID' => '2',
                                            'NAME' => '2',
                                            'VALUE' => 1.2,
                                        ),
                                    3 =>
                                        array (
                                            'ID' => '3',
                                            'NAME' => '3',
                                            'VALUE' => 1.1000000000000001,
                                        ),
                                    4 =>
                                        array (
                                            'ID' => '4',
                                            'NAME' => '4',
                                            'VALUE' => 1.1000000000000001,
                                        ),
                                    5 =>
                                        array (
                                            'ID' => '5',
                                            'NAME' => '5',
                                            'VALUE' => 1.1000000000000001,
                                        ),
                                    6 =>
                                        array (
                                            'ID' => '6',
                                            'NAME' => '6',
                                            'VALUE' => 1,
                                        ),
                                    7 =>
                                        array (
                                            'ID' => '7',
                                            'NAME' => '7',
                                            'VALUE' => 1,
                                        ),
                                    8 =>
                                        array (
                                            'ID' => '8',
                                            'NAME' => '8',
                                            'VALUE' => 1,
                                        ),
                                    9 =>
                                        array (
                                            'ID' => '9',
                                            'NAME' => '9',
                                            'VALUE' => 1,
                                        ),
                                    10 =>
                                        array (
                                            'ID' => '10',
                                            'NAME' => '10',
                                            'VALUE' => 1,
                                        ),
                                    11 =>
                                        array (
                                            'ID' => '11',
                                            'NAME' => '11',
                                            'VALUE' => 1,
                                        ),
                                    12 =>
                                        array (
                                            'ID' => '12',
                                            'NAME' => '12',
                                            'VALUE' => 1,
                                        ),
                                    13 =>
                                        array (
                                            'ID' => '13',
                                            'NAME' => '13',
                                            'VALUE' => 0.90000000000000002,
                                        ),
                                    14 =>
                                        array (
                                            'ID' => '14',
                                            'NAME' => '14',
                                            'VALUE' => 0.90000000000000002,
                                        ),
                                    15 =>
                                        array (
                                            'ID' => '15',
                                            'NAME' => '15',
                                            'VALUE' => 0.90000000000000002,
                                        ),
                                    16 =>
                                        array (
                                            'ID' => '16',
                                            'NAME' => '16',
                                            'VALUE' => 0.90000000000000002,
                                        ),
                                    17 =>
                                        array (
                                            'ID' => '17',
                                            'NAME' => '17',
                                            'VALUE' => 0.90000000000000002,
                                        ),
                                    18 =>
                                        array (
                                            'ID' => '18',
                                            'NAME' => '18',
                                            'VALUE' => 0.80000000000000004,
                                        ),
                                    19 =>
                                        array (
                                            'ID' => '19',
                                            'NAME' => '19',
                                            'VALUE' => 0.80000000000000004,
                                        ),
                                    20 =>
                                        array (
                                            'ID' => '20',
                                            'NAME' => '20',
                                            'VALUE' => 0.80000000000000004,
                                        ),
                                    21 =>
                                        array (
                                            'ID' => '21',
                                            'NAME' => '21',
                                            'VALUE' => 0.80000000000000004,
                                        ),
                                    22 =>
                                        array (
                                            'ID' => '22',
                                            'NAME' => '22',
                                            'VALUE' => 0.80000000000000004,
                                        ),
                                    23 =>
                                        array (
                                            'ID' => '23',
                                            'NAME' => '23',
                                            'VALUE' => 0.80000000000000004,
                                        ),
                                    24 =>
                                        array (
                                            'ID' => '24',
                                            'NAME' => '24',
                                            'VALUE' => 0.80000000000000004,
                                        ),
                                    25 =>
                                        array (
                                            'ID' => '25',
                                            'NAME' => 'Более 24',
                                            'VALUE' => 0.80000000000000004,
                                        ),
                                ),
                            'DISTANCE_BETWEEN_SITES' =>
                                array (
                                    1 =>
                                        array (
                                            'ID' => '1',
                                            'NAME' => 'Объекты находятся в одном месте',
                                            'VALUE' => 0.90000000000000002,
                                        ),
                                    2 =>
                                        array (
                                            'ID' => '2',
                                            'NAME' => 'Расстояние между объектами не более 0,5 км',
                                            'VALUE' => 1.1000000000000001,
                                        ),
                                    3 =>
                                        array (
                                            'ID' => '3',
                                            'NAME' => 'Расстояние между объектами 0,5-3 км',
                                            'VALUE' => 1.2,
                                        ),
                                ),
                            'TRANSPORT_ACCESSIBILITY' =>
                                array (
                                    1 =>
                                        array (
                                            'ID' => '1',
                                            'NAME' => 'Зона действия общественного транспорта',
                                            'VALUE' => 0.80000000000000004,
                                        ),
                                    2 =>
                                        array (
                                            'ID' => '2',
                                            'NAME' => 'Автотранспортом по дорогам общего пользования, время в пути не более 2-х часов',
                                            'VALUE' => 1,
                                        ),
                                    3 =>
                                        array (
                                            'ID' => '3',
                                            'NAME' => 'Автотранспортом по дорогам общего пользования, время в пути не более 5-и часов',
                                            'VALUE' => 1.2,
                                        ),
                                    4 =>
                                        array (
                                            'ID' => '4',
                                            'NAME' => 'Автотранспортом по дорогам общего пользования, время в пути более 5-и часов',
                                            'VALUE' => 1.5,
                                        ),
                                    5 =>
                                        array (
                                            'ID' => '5',
                                            'NAME' => 'Автотранспортом повышенной проходимости по груновым дорогам, время в пути не более 2-х часов',
                                            'VALUE' => 2,
                                        ),
                                    6 =>
                                        array (
                                            'ID' => '6',
                                            'NAME' => 'Автотранспортом повышенной проходимости по груновым дорогам, время в пути не более 5-и часов',
                                            'VALUE' => 2.2000000000000002,
                                        ),
                                    7 =>
                                        array (
                                            'ID' => '7',
                                            'NAME' => 'Автотрансопортом повышенной проходимости по груновым дорогам, время в пути более 5-и часов',
                                            'VALUE' => 2.5,
                                        ),
                                    8 =>
                                        array (
                                            'ID' => '8',
                                            'NAME' => 'Требуется спецтранспорт (вездиход, вертолет и пр.)',
                                            'VALUE' => 3,
                                        ),
                                ),
                            'DOCUMENTS' =>
                                array (
                                    1 =>
                                        array (
                                            'ID' => '1',
                                            'NAME' => 'Результаты выполненых обследований или экспертиз',
                                            'VALUE' =>
                                                array (
                                                    1 => 0.98999999999999999,
                                                ),
                                        ),
                                    2 =>
                                        array (
                                            'ID' => '2',
                                            'NAME' => 'Результаты ранее проведенного мониторинга',
                                            'VALUE' =>
                                                array (
                                                    1 => 0.98999999999999999,
                                                ),
                                        ),
                                    3 =>
                                        array (
                                            'ID' => '3',
                                            'NAME' => 'Результаты гидрогеологических изысканий',
                                            'VALUE' =>
                                                array (
                                                    1 => 0.98999999999999999,
                                                ),
                                        ),
                                    4 =>
                                        array (
                                            'ID' => '4',
                                            'NAME' => 'Проектная документация',
                                            'VALUE' =>
                                                array (
                                                    1 => 0.97999999999999998,
                                                ),
                                        ),
                                    5 =>
                                        array (
                                            'ID' => '5',
                                            'NAME' => 'Рабочая документация',
                                            'VALUE' =>
                                                array (
                                                    1 => 0.97999999999999998,
                                                ),
                                        ),
                                    6 =>
                                        array (
                                            'ID' => '6',
                                            'NAME' => 'Планы БТИ',
                                            'VALUE' =>
                                                array (
                                                    1 => 0.98999999999999999,
                                                ),
                                        ),
                                ),
                        ),
                ),
        );
        $params = array (
            'STRUCTURES_TO_MONITOR' =>
                array (
                    0 => '2',
                    1 => '9',
                    2 => '2',
                    3 => '9',
                ),
            'DOCUMENTS' =>
                array (
                    0 => '1',
                    1 => '6',
                    2 => '1',
                    3 => '6',
                ),
            'DESCRIPTION' => 'desc',
            'LOCATION' => '1',
            'ADDRESS' => 'address',
            'SITE_COUNT' => 1,
            'DISTANCE_BETWEEN_SITES' => '',
            'USED_FOR' => '1',
            'TOTAL_AREA' => 42,
            'VOLUME' => 42,
            'FLOORS' =>
                array (
                    0 => 42,
                ),
            'HAS_UNDERGROUND_FLOORS' => true,
            'UNDERGROUND_FLOORS' => 42,
            'MONITORING_GOAL' => '1',
            'DURATION' => '1',
            'TRANSPORT_ACCESSIBILITY' => '1',
            'PACKAGE_SELECTION' => 'INDIVIDUAL',
        );
        $expected = array (
            'SITE_COUNT' => 1,
            'LOCATION' => 1,
            'USED_FOR' => 0.80000000000000004,
            'FLOORS' => 3,
            'HAS_UNDERGROUND_FLOORS' => 1,
            'UNDERGROUND_FLOORS' => 1.2,
            'MONITORING_GOAL' => 0.90000000000000002,
            'STRUCTURES_TO_MONITOR' => 0.00093636000000000012,
            'DURATION' => 1.2,
            'TRANSPORT_ACCESSIBILITY' => 0.80000000000000004,
            'DOCUMENTS' => 0.96059600999999994,
        );
        $this->assertEquals($expected, $calc->multipliers($params, Monitoring::dataSet($data, $params)));
    }

    function testTotalPrice() {
        $calc = new MonitoringCalculator();
        $this->assertEquals(120000 * 4.6 * (2 + 1.5), $calc->totalPrice(120000, [2, 1.5]));
    }
}
