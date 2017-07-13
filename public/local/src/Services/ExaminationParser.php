<?php

namespace App\Services;

use Core\Hierarchy as h;
use Core\Underscore as _;
use Core\Strings as str;
use PhpOffice\PhpSpreadsheet\Cell;
use PhpOffice\PhpSpreadsheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Row;

class ExaminationParser extends Parser {
    use \Core\DynamicMethods;
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
            // 5. Необходимость выезда на объект
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

    // TODO refactor: generalize?
    function parseGoals($rows) {
        // define the row type hierarchy
        $h = h::make();
        $h = h::derive($h, 'root_subsection_name', 'subsection_name');
        $h = h::derive($h, 'table_header', 'subsection_name');
        $h = h::derive($h, 'nesting_table_header', 'table_header');
        $findNumberingIdx = function($row) {
            return _::findKey($row['cells'], function($v) {
                return $v === 'Нумерация';
            });
        };
        // should probably return type + structured value
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
                // TODO refactor: referring to excel columns breaks the row.cells abstraction
                return _::matches(_::pick($cellObjs, ['C', 'D']), function(Cell $cell) {
                    // TODO brittle
                    return is_numeric($cell->getValue());
                });
            };
            $isSubsectionName = function($row) use ($isTableHeader) {
                $secondCell = $row['cells'][1];
                return $isTableHeader($row) || str::isEmpty($secondCell);
            };
            $isRootSubsection = function($row) use ($isSubsectionName) {
                // TODO do we need this type? also 14. prefix dependency
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
        $subsectionDepth = function($str) {
            // brittle context-independent heuristic. starts from 1.
            $isAllCaps = function($s) { return str::upper($s) === $s; };
            $matchesRef = [];
            $isMatch = preg_match('/^[\d\.]+/', $str, $matchesRef) === 1;
            if ($isMatch) {
                list($numberPrefix) = $matchesRef;
                return count(_::clean(explode('.', $numberPrefix))) - 1;
            } elseif ($isAllCaps($str)) {
                return 2;
            } else {
                return 3;
            }
        };
        $getNextPath = function($path, $subsection) use ($subsectionDepth) {
            $depth = $subsectionDepth($subsection);
            assert(count($path) >= $depth - 1);
            return _::append(_::take($path, $depth - 1), $subsection);
        };
        $tableHeader = function($row, $type) use ($h, $findNumberingIdx) {
            if (h::isa($h, $type, 'nesting_table_header')) {
                $numberingIdx = $findNumberingIdx($row);
                // drop 1, take until numbering column
                return array_slice($row['cells'], 1, $numberingIdx - 1);
            } else {
                return _::drop($this->nonEmptyCells($row['cells']), 1);
            }
        };
        $ret = [];
        $state = ['find_subsection'];
        foreach ($rows as $idx => $row) {
            $parseMultiplier = _::partialRight([$this, 'parseFloat'], $this->defaultMultiplierFn($row['row_number']));
            $cells = $row['cells'];
            $stateName = _::first($state);
            $type = $rowType($row);
            if ($stateName === 'find_subsection') {
                if (h::isa($h, $type, 'subsection_name')) {
                    $ret[_::first($cells)] = [];
                    $state = ['in_subsection', [_::first($cells)]];
                }
            } elseif ($stateName == 'in_subsection') {
                list($_, $path) = $state;
                if (h::isa($h, $type, 'subsection_name')) {
                    $subsection = _::first($cells);
                    $nextPath = $getNextPath($path, $subsection);
                    $ret = _::set($ret, $nextPath, []);
                    if (h::isa($h, $type, 'table_header')) {
                        $header = $tableHeader($row, $type);
                        if (h::isa($h, $type, 'nesting_table_header')) {
                            $state = ['in_nesting_table', $nextPath, $header];
                        } else {
                            // not used right now, stays in the `in_nesting_table` state
                            // $state = ['in_table', $nextPath, $header];
                            throw new \Exception('illegal state');
                        }
                    } else {
                        $state = ['in_subsection', $nextPath];
                    }
                } else {
                    $entity = $this->simpleValue($row);
                    $ret = _::set($ret, _::append($path, $entity['ID']), $entity);
                }
            } elseif ($stateName === 'in_nesting_table') {
                // once entered stays in this state forever
                list($_, $path, $header) = $state;
                if (h::isa($h, $type, 'subsection_name')) {
                    $subsection = _::first($cells);
                    $nextPath = $getNextPath($path, $subsection);
                    $nextHeader = h::isa($h, $type, 'table_header')
                        ? $tableHeader($row, $type)
                        : $header;
                    $state = ['in_nesting_table', $nextPath, $nextHeader];
                } else {
                    $name = _::first($cells);
                    // this will take empty columns for the "narrower" nested tables
                    $columns = _::take(_::drop($cells, 1), count($header));
                    // TODO feels hacky
                    $isSimpleEntity = count(_::clean($columns)) === 1;
                    if ($isSimpleEntity) {
                        $value = $this->simpleValue($row);
                    } else {
                        $multipliers = array_map($parseMultiplier, $columns);
                        $id = $row['metadata']['id'];
                        $value = ['ID' => $id, 'NAME' => $name, 'VALUE' => array_combine($header, $multipliers)];
                    }
                    $ret = _::set($ret, _::append($path, $value['ID']), $value);
                }
            } else {
                throw new \Exception('unknown state');
            }
        }
        return $ret;
    }

    function parseFile($path) {
        return $this->mapWorksheets($path, $this->spec['worksheets'], function(Worksheet $worksheet) {
            $sectionGroups = $this->sectionGroups($worksheet->getRowIterator(), $this->spec['sections']);
            // TODO refactor: split sections by type
            return [
                'TIME' => $sectionGroups['TIME'],
                'MULTIPLIERS' => _::map(_::remove($sectionGroups, 'TIME'), function($rows, $sectionKey) {
                    if ($sectionKey === 'GOALS') {
                        return $this->parseGoals($rows);
                    } else {
                        return $this->parseSimpleSection($rows);
                    }
                })
            ];
        });
    }
}