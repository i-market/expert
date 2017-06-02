<?php

use App\Calc\MonitoringCalc;
use PHPUnit\Framework\TestCase;
use Core\Underscore as _;

class MonitoringCalcTest extends TestCase {
    private $validState = [
        // Описание объекта  (ов) мониторинга – текстовое поле
        'DESCRIPTION' => 'Описание объекта',
        // Количество зданий, сооружений, строений – числовое поле,  допустимые значения от 1 до  «более» 100
        'SITE_COUNT' => 42,
        // ref Местонахождение – единичный выбор из списка
        'LOCATION' => 1,
        // Адрес – поле для ввода
        'ADDRESS' => 'Адрес',
        // ref Назначение объекта  мониторинга –  набор  полей (зависит от значения в поле Количество зданий, 1 перечень для значений 1  ,другой перечень для значений 2-100 ) единичный выбор из предлагаемых вариантов, рядом разместить кнопку для вызова подсказки.
        'USED_FOR' => 2,
        // Общая площадь объекта, кв.м. – числовое поле для ввода
        'TOTAL_AREA' => 11,
        // Строительный объем объекта, куб.м. – число вводится в поле, в КП показывается, но в расчетах не участвует.
        'VOLUME' => 56,
        // Количество надземных этажей -– поле для ввода числового значения (Если количество зданий больше 1 – то можно выбрать несколько полей (множественный выбор). В не зависимости от количества выбранных полей коэффициент должен быть один для всех полей и соответствовать 1,1000 )
        'FLOORS' => 3,
        // Наличие технического подполья, подвала, подземных этажей – выбор да/нет.  В случае выбора ДА появляется следующий пункт:
        'HAS_UNDERGROUND_FLOORS' => true,
        // Количество подземных этажей  -– поле для ввода числового значения
        'UNDERGROUND_FLOORS' => 2,
        // ref Цели мониторинга – единичный выбор из предлагаемых значений
        'MONITORING_GOAL' => 1,
        // ref Конструкции подлежащие мониторингу –  выбор из 2 предлагаемых значений (комплексный и выборочный мониторинг), в случае выбора выборочного мониторинга предлагается множественный выбор из предлагаемых значений
        'STRUCTURES_TO_MONITOR' => [1, 2],
        // ref Продолжительность мониторинга – единичный выбор из предлагаемых значений
        'DURATION' => 2,
        // ref Удаленность объектов друг от друга – поле появляется если в пункте Количество зданий введено значение отличное от 1, единичный выбор из предлагаемых значений
        'DISTANCE_BETWEEN_SITES' => 'Объекты находятся в одном месте',
        // ref Транспортная доступность – единичный выбор из списка допустимых значений
        'TRANSPORT_ACCESSIBILITY' => 'Зона действия общественного транспорта',
        // ref Наличие документов  - множественный выбор из списка допустимых значений
        'DOCUMENTS' => [3, 4]
    ];

    function testValidateState() {
        $calc = new MonitoringCalc();
        $this->assertTrue($calc->validateState($this->validState));
    }

    function testCalculate() {
        $calc = new MonitoringCalc();
        $result = $calc->calculate($this->validState);
        $this->assertEquals(33, $result['TOTAL_PRICE']);
    }

    function testParseWorksheet() {
        $filenames = [
            'monitoring-single-building.tsv',
            'monitoring-multiple-buildings.tsv'
        ];
        foreach ($filenames as $filename) {
            $path = getcwd().'/fixtures/calc/'.$filename;
            $result = MonitoringCalc::parseWorksheet(MonitoringCalc::rowIterator($path));
            // TODO assertions
            $missingSections = array_diff(_::pluck(MonitoringCalc::$sections, 'KEY'), array_keys($result['MULTIPLIERS']));
            $this->assertTrue(_::isEmpty($missingSections));
        }
    }

    function testLocations() {
        // TODO set document root in phpunit bootstrap
        $_SERVER['DOCUMENT_ROOT'] = realpath(__DIR__ . '/../..');
        $locations = MonitoringCalc::locations();
        $this->assertTrue(!_::isEmpty($locations));
        $this->assertTrue(_::matches($locations, function($location) {
            return _::has($location, 'ID') && _::has($location, 'NAME');
        }));
    }
}