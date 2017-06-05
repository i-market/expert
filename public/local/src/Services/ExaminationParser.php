<?php

namespace App\Services;

use Core\Underscore as _;

class ExaminationParser extends Parser {
    // TODO strip enumeration
    public $sections = [
        // 1. Описание объекта(ов) экспертизы
        // 2. Для суда
        // 3. Количество объектов экспертизы (шт.)
        // 4. Категория объекта экспертизы
        // 5. Необъодимость выезда на объект
        [
            'KEY' => 'LOCATION',
            'PREFIX' => 'Местонахождение'
        ]
        // 7. Адрес
        // 8. Назначение объекта экспертизы
        // 9. Общая площадь объекта (кв.м.)
        // 10. Строительный объем объекта (куб. м.)
        // 11. Количество надземных этажей
        // 12. Наличие технического подполья, подвала, подземных этажей
        // 13. Количество подземных этажей
        // 14. Цели и задачи экспертизы
        // 15. Удаленность объектов друг от друга
        // 16. Транспортная доуступность
        // 17. Наличие документов
        // ЦЕНЫ
        // СРОКИ
        // TODO ... Рецензии на содержание отчетов по результатам проведенного обследования
    ];

    function parseWorksheet($rowIterator) {
        $sectionGroups = $this->sectionGroups($rowIterator, $this->sections);
        $ret = _::map($sectionGroups, function($rows, $sectionKey) {
            return $this->parseSimpleSection($rows);
        });
        return [
            'MULTIPLIERS' => $ret
        ];
    }
}