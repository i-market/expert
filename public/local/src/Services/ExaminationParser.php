<?php

namespace App\Services;

use Core\Underscore as _;
use Core\Strings as str;
use PhpOffice\PhpSpreadsheet\Cell;
use PhpOffice\PhpSpreadsheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Row;

class ExaminationParser extends Parser {
    public $spec = [
        'worksheets' => [
            [
                'key' => 'SINGLE_BUILDING',
                'name' => 'Экспертиза одного здания'
            ],
            [
                'key' => 'MULTIPLE_BUILDINGS',
                'name' => 'Экспертиза нескольких зданий'
            ]
        ],
        'sections' => [
            // TODO strip enumeration
            // 1. Описание объекта(ов) экспертизы
            // 2. Для суда
            // 3. Количество объектов экспертизы (шт.)
            // 4. Категория объекта экспертизы
            // 5. Необъодимость выезда на объект
            [
                'key' => 'LOCATION',
                'prefix' => 'Местонахождение'
            ],
            // 7. Адрес
            // 8. Назначение объекта экспертизы
            // 9. Общая площадь объекта (кв.м.)
            // 10. Строительный объем объекта (куб. м.)
            // 11. Количество надземных этажей
            // 12. Наличие технического подполья, подвала, подземных этажей
            // 13. Количество подземных этажей
            [
                'key' => 'GOALS',
                'prefix' => 'Цели и задачи экспертизы'
            ]
            // 15. Удаленность объектов друг от друга
            // 16. Транспортная доуступность
            // 17. Наличие документов
            // ЦЕНЫ
            // СРОКИ
            // TODO ... Рецензии на содержание отчетов по результатам проведенного обследования
        ]
    ];

    protected function parseConditionalMultipliers($rows) {
        $findNumberingIdx = function($row) {
            return _::findKey($row['cells'], function($v) {
                return $v === 'Нумерация';
            });
        };
        $isNestingTablesHeader = function($row) use ($findNumberingIdx) {
            return $findNumberingIdx($row) !== null;
        };
        $isTableHeader = function($row) use ($isNestingTablesHeader) {
            if ($isNestingTablesHeader($row)) {
                return true;
            }
            /** @var Row $rowObj */
            $rowObj = $row['object'];
            /** @var Cell[] $cellObjs */
            $cellObjs = iterator_to_array($rowObj->getCellIterator());
            return _::matches(_::pick($cellObjs, ['B', 'C']), function(Cell $cell) {
                // TODO brittle
                return is_numeric($cell->getValue()) ;
            });
        };
        $isSubsectionName = function($row) use ($isTableHeader) {
            return $isTableHeader($row) || $this->isEmptyRow(_::rest($row['cells']));
        };
        $maxSectionDepth = 3;
        $ret = [];
        $state = ['find_subsection'];
        foreach ($rows as $idx => $row) {
            $cells = $row['cells'];
            $stateName = _::first($state);
            if ($stateName === 'find_subsection') {
                if ($isSubsectionName($row)) {
                    $state = ['in_subsection', [_::first($cells)]];
                }
            } elseif ($stateName === 'in_subsection') {
                list($_, $path) = $state;
                if ($isSubsectionName($row)) {
                    $isAllCaps = str::upper(_::first($cells)) === _::first($cells);
                    // TODO brittle
                    $goUpOneLevel = count($path) >= 2 && $isAllCaps || count($path) >= $maxSectionDepth;
                    $nextPath = array_merge($goUpOneLevel ? _::initial($path) : $path, [_::first($cells)]);
                    $state = ['in_subsection', $nextPath];
                } else {
                    // simple case
                    list($k, $v) = $cells;
                    $value = $this->parseFloat($v, $this->defaultMultiplierFn($row['row_number']));
                    $ret = _::set($ret, array_merge($path, [$k]), $value);
                }
            } elseif ($stateName === 'in_conditional_multipliers') {
                // TODO unreachable code for now
                list($_, $subsection, $header) = $state;
                $filteredCells = $this->nonEmptyCells($cells);
                $isConditionalMultiplier = function($cells) use ($header) {
                    return count($cells) === count($header) + 1;
                };
                if ($isConditionalMultiplier($filteredCells)) {
                    $key = _::first($cells);
                    $multipliers = array_map(function($str) use ($row) {
                        return $this->parseFloat($str, $this->defaultMultiplierFn($row['row_number']));
                    }, _::drop($filteredCells, 1));
                    $ret[$subsection][$key] = array_combine($header, $multipliers);
                }
                // peek the next row
                if (isset($rows[$idx + 1]) && !$isConditionalMultiplier($this->nonEmptyCells($rows[$idx + 1]['cells']))) {
                    $state = ['find_subsection'];
                }
            }
        }
        return $ret;
    }

    function parseFile($path) {
        return $this->mapWorksheets($path, $this->spec['worksheets'], function(Worksheet $worksheet) {
            $sectionGroups = $this->sectionGroups($worksheet->getRowIterator(), $this->spec['sections']);
            return [
                'multipliers' => _::map($sectionGroups, function($rows, $sectionKey) {
                    if ($sectionKey === 'GOALS') {
                        return $this->parseConditionalMultipliers($rows);
                    } else {
                        return $this->parseSimpleSection($rows);
                    }
                })
            ];
        });
    }
}