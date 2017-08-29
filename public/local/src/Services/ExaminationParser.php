<?php

namespace App\Services;

use Core\Hierarchy as h;
use Core\Underscore as _;
use Core\Strings as str;
use Core\Nullable as nil;
use PhpOffice\PhpSpreadsheet\Cell;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Row;

class ExaminationParser extends Parser {
    use \Core\DynamicMethods; // TODO tmp for dev
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
            [
                'key' => 'FOR_LEGAL_CASE',
                'prefix' => 'Для суда'
            ],
            [
                'key' => 'SITE_COUNT',
                'prefix' => 'Количество объектов экспертизы (шт.)'
            ],
            [
                'key' => 'SITE_CATEGORY',
                'prefix' => [
                    'Категория объекта экспертизы',
                    'Категория экспертизы'
                ]
            ],
            [
                'key' => 'NEEDS_VISIT',
                'prefix' => 'Необходимость выезда на объект'
            ],
            [
                'key' => 'LOCATION',
                'prefix' => 'Местонахождение'
            ],
            // 7. Адрес
            [
                'key' => 'USED_FOR',
                'prefix' => [
                    'Назначение объекта экспертизы',
                    'Назначение объектов экспертизы'
                ]
            ],
            [
                'key' => 'TOTAL_AREA',
                'prefix' => [
                    'Общая площадь объекта (кв.м.)',
                    'Общая площадь объектов (кв.м.)'
                ]
            ],
            [
                'key' => 'VOLUME',
                'prefix' => [
                    'Строительный объем объекта (куб. м.)',
                    'Строительный объем объектов (куб. м.)'
                ]
            ],
            [
                'key' => 'FLOORS',
                'prefix' => 'Количество надземных этажей'
            ],
            [
                'key' => 'HAS_UNDERGROUND_FLOORS',
                'prefix' => [
                    'Наличие технического подполья, подвала, подземных этажей',
                    'Наличие технических подпольев, подвалов, подземных этажей'
                ],
            ],
            [
                'key' => 'UNDERGROUND_FLOORS',
                'prefix' => 'Количество подземных этажей',
            ],
            [
                'key' => 'GOALS',
                'prefix' => 'Цели и задачи экспертизы'
            ],
            [
                'key' => 'DISTANCE_BETWEEN_SITES',
                'prefix' => 'Удаленность объектов друг от друга'
            ],
            [
                'key' => 'TRANSPORT_ACCESSIBILITY',
                'prefix' => 'Транспортная доступность',
            ],
            [
                'key' => 'DOCUMENTS',
                'prefix' => 'Наличие документов',
            ]
            // ЦЕНЫ
            // СРОКИ
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
            /** @var Row $rowObj */
            $rowObj = $row['object'];
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
                            $state = ['in_nesting_table', $nextPath, $header, []];
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
                list($_, $path, $header, $metadata) = $state;
                if (h::isa($h, $type, 'subsection_name')) {
                    $subsection = _::first($cells);
                    $nextPath = $getNextPath($path, $subsection);
                    $nextHeader = h::isa($h, $type, 'table_header')
                        ? $tableHeader($row, $type)
                        : $header;
                    $state = ['in_nesting_table', $nextPath, $nextHeader, _::remove($metadata, 'RANGE_BOUNDARY')];
                } else {
                    $name = _::first($cells);
                    $dataCells = _::drop($cells, 1);
                    // this will take empty columns for "narrower" nested tables
                    $multCells = _::take($dataCells, count($header));
                    // TODO feels hacky
                    $isSimpleEntity = count(_::clean($multCells)) === 1;
                    if ($isSimpleEntity) {
                        $value = $this->simpleValue($row);
                    } else {
                        $multipliers = array_map($parseMultiplier, $multCells);
                        if (!isset($metadata['RANGE_BOUNDARY'])) {
                            // ranges that are used in the multiplier selection business logic.
                            // `row.cell` index. assume all ranges start from the beginning.
                            $metadata['RANGE_BOUNDARY'] = _::find(range(1, count($multCells)), function($idx) use ($rowObj) {
                                $style = $rowObj->getWorksheet()->getStyle($this->cellCoordinate($idx, $rowObj->getRowIndex()));
                                $hasRightBorder = $style->getBorders()->getRight()->getBorderStyle() !== Border::BORDER_NONE;
                                return $hasRightBorder;
                            });
                        }
                        $value = [
                            'ID' => $row['metadata']['id'],
                            'NAME' => $name,
                            'VALUE' => array_combine($header, $multipliers),
                            // convert row.cell index to entity `VALUE` index
                            'RANGE_BOUNDARY' => nil::map(_::get($metadata, 'RANGE_BOUNDARY'), function($rangeBoundary) {
                                return $rangeBoundary - 1;
                            }),
                            'NUMBERING' => array_filter(_::drop($dataCells, count($header)), _::complement([str::class, 'isEmpty']))
                        ];
                    }

                    if (str::startsWith(_::last($path), '14.7. Рецензирование')) {
                        // TODO refactor hack: move fixed prices out of `multipliers`
                        $value['IS_FIXED_PRICE'] = true;
                    }
                    
                    $ret = _::set($ret, _::append($path, $value['ID']), $value);
                    $state = ['in_nesting_table', $path, $header, $metadata];
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
                    } elseif ($sectionKey === 'DOCUMENTS') {
                        return $this->parseDocuments($rows);
                    } else {
                        return $this->parseSimpleSection($rows);
                    }
                })
            ];
        });
    }
}