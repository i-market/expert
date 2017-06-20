<?php

namespace App\Services;

use Core\Hierarchy as h;
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

//    protected function isSectionDelimiter($row) {
//        /** @var Row $rowObj */
//        $rowObj = $row['object'];
//        /** @var Cell[] $cellObjs */
//        foreach ($rowObj->getCellIterator() as $col => $cell) {
//            if ($col === 'A') {
//                $bottomBorder = $cell->getStyle()->getBorders()->getBottom();
//                // ! broken: always equals 'none' for some reason
//                $hasBottomBorder = $bottomBorder->getBorderStyle() !== Border::BORDER_NONE;
//                return str::isEmpty($cell->getValue()) && $hasBottomBorder;
//            }
//        }
//        return false;
//    }

    protected function parseConditionalMultipliers($rows) {
        $h = h::make();
        $h = h::derive($h, 'root_subsection_name', 'subsection_name');
        $h = h::derive($h, 'table_header', 'subsection_name');
        $h = h::derive($h, 'nesting_table_header', 'table_header');
        // TODO log errors
        $findNumberingIdx = function($row) {
            return _::findKey($row['cells'], function($v) {
                return $v === 'Нумерация';
            });
        };
        // TODO refactor
        $rowType = function($row) use ($findNumberingIdx) {
            $isNestingTableHeader = function($row) use ($findNumberingIdx) {
                return $findNumberingIdx($row) !== null;
            };
            $isTableHeader = function($row) use ($isNestingTableHeader) {
                if ($isNestingTableHeader($row)) {
                    return true;
                }
                /** @var Row $rowObj */
                $rowObj = $row['object'];
                /** @var Cell[] $cellObjs */
                $cellObjs = iterator_to_array($rowObj->getCellIterator());
                return _::matches(_::pick($cellObjs, ['B', 'C']), function(Cell $cell) {
                    // TODO brittle
                    return is_numeric($cell->getValue());
                });
            };
            $isSubsectionName = function($row) use ($isTableHeader) {
                $secondCell = $row['cells'][1];
                return $isTableHeader($row) || str::isEmpty($secondCell);
            };
            $isRootSubsection = function($row) use ($isSubsectionName) {
                return $isSubsectionName($row) && str::startsWith(_::first($row['cells']), '14.');
            };
            if ($isNestingTableHeader($row)) {
                return 'nesting_table_header';
            } elseif ($isTableHeader($row)) {
                return 'table_header';
            } elseif ($isRootSubsection($row)) {
                return 'root_subsection_name';
            } elseif ($isSubsectionName($row)) {
                return 'subsection_name';
            } else {
                return 'unknown';
            }
        };
        // TODO
        // 14.6 max = 4, otherwise 3?
        $maxSectionDepth = 3;
        $ret = [];
        $state = ['find_subsection'];
        foreach ($rows as $idx => $row) {
            $cells = $row['cells'];
            $stateName = _::first($state);
            $type = $rowType($row);
            if ($stateName === 'find_subsection') {
                if (h::isa($h, $type, 'subsection_name')) {
                    $state = ['in_subsection', [_::first($cells)]];
                }
            } elseif ($stateName === 'in_subsection') {
                list($_, $path) = $state;
                if (h::isa($h, $type, 'subsection_name')) {
                    $name = _::first($cells);
                    // TODO refactor high cyclomatic complexity
                    if (h::isa($h, $type, 'root_subsection_name')) {
                        $nextPath = [$name];
                    } else {
                        $isAllCaps = str::upper($name) === $name;
                        // TODO ! depth logic
                        $goUpOneLevel = count($path) >= 2 && $isAllCaps;
                        $nextPath = array_merge($goUpOneLevel ? _::initial($path) : $path, [$name]);
                    }
                    if (h::isa($h, $type, 'table_header')) {
                        if (h::isa($h, $type, 'nesting_table_header')) {
                            $numberingIdx = $findNumberingIdx($row);
                            // drop 1, take until numbering column
                            $header = array_slice($this->nonEmptyCells($cells), 1, $numberingIdx - 1);
                            $tables = [[array_merge($path, ['TABLE']), $header]];
                            $state = ['in_nesting_table', $tables, $numberingIdx];
                        } else {
                            $header = _::drop($this->nonEmptyCells($cells), 1);
                            $state = ['in_table', $nextPath, $header];
                        }
                    } else {
                        $state = ['in_subsection', $nextPath];
                    }
                } else {
                    // simple case
                    list($k, $v) = $cells;
                    $value = $this->parseFloat($v, $this->defaultMultiplierFn($row['row_number']));
                    // TODO check numbering (14.)
                    $ret = _::set($ret, array_merge($path, [$k]), $value);
                }
            } elseif ($stateName === 'in_table') {
                list($_, $path, $header) = $state;
                $filteredCells = $this->nonEmptyCells($cells);
                // TODO refactor, extract function
                $isConditionalMultiplier = function($cells) use ($header) {
                    return count($cells) === count($header) + 1;
                };
                if ($isConditionalMultiplier($filteredCells)) {
                    $key = _::first($cells);
                    $multipliers = array_map(function($str) use ($row) {
                        return $this->parseFloat($str, $this->defaultMultiplierFn($row['row_number']));
                    }, _::drop($filteredCells, 1));
                    $ret = _::set($ret, array_merge($path, [$key]), array_combine($header, $multipliers));
                }
                // peek the next row
                if (isset($rows[$idx + 1]) && !$isConditionalMultiplier($this->nonEmptyCells($rows[$idx + 1]['cells']))) {
                    if (count($path) > 1) {
                        $state = ['in_subsection', _::initial($path)];
                    } else {
                        $state = ['find_subsection'];
                    }
                }
            } elseif ($stateName === 'in_nesting_table') {
                list($_, $tables, $numberingIdx) = $state;
//                $indices = array_reverse(array_slice($cells, ));
            }
        }
        return $ret;
    }

    function parseFile($path) {
        return $this->mapWorksheets($path, $this->spec['worksheets'], function(Worksheet $worksheet) {
            $sectionGroups = $this->sectionGroups($worksheet->getRowIterator(), $this->spec['sections']);
            return [
                'MULTIPLIERS' => _::map($sectionGroups, function($rows, $sectionKey) {
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