<?php

namespace App\Services;

use Core\Strings as str;
use Core\Underscore as _;
use PhpOffice\PhpSpreadsheet\Worksheet;

class MonitoringParser extends Parser {
    public $spec = [
        'worksheets' => [
            [
                'key' => 'SINGLE_BUILDING',
                'name' => 'Мониторинг одного здания'
            ],
            [
                'key' => 'MULTIPLE_BUILDINGS',
                'name' => 'Мониторинг нескольких зданий'
            ]
        ],
        'sections' => [
//            'Описание объекта(ов) мониторинга',
            [
                'key' => 'SITE_COUNT',
                'prefix' => 'Количество зданий сооружений, строений (шт.)'
            ],
            [
                'key' => 'LOCATION',
                'prefix' => 'Местонахождение'
            ],
//            'Адрес',
            [
                'key' => 'USED_FOR',
                'prefix' => [
                    'Назначение объекта мониторинга',
                    'Назначение объектов мониторинга'
                ],
            ],
//            'Общая площадь объекта (кв.м.)',
            [
                'key' => 'VOLUME',
                'prefix' => [
                    'Строительный объем объекта (куб. м.)',
                    'Строительный объем объектов (куб. м.)'
                ],
            ],
            [
                'key' => 'FLOORS',
                'prefix' => 'Количество надземных этажей',
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
                'key' => 'MONITORING_GOAL',
                'prefix' => 'Цели мониторинга',
            ],
            [
                'key' => 'STRUCTURES_TO_MONITOR',
                'prefix' => 'Конструкции подлежащие мониторингу',
            ],
            [
                'key' => 'DURATION',
                'prefix' => 'Продолжетельность мониторинга (мес.)',
            ],
            [
                'key' => 'DISTANCE_BETWEEN_SITES',
                'prefix' => 'Удаленность объектов друг от друга'
            ],
            [
                'key' => 'TRANSPORT_ACCESSIBILITY',
                'prefix' => 'Транспортная доуступность',
            ],
            [
                'key' => 'DOCUMENTS',
                'prefix' => 'Наличие документов',
            ],
            [
                'key' => 'PRICES',
                // TODO case-insensitive
                'prefix' => 'ЦЕНЫ'
            ]
        ]
    ];

    private function parseStructuresToMonitor($rows) {
        $ret = [];
        $state = ['default'];
        foreach ($rows as $idx => $row) {
            $cells = $row['cells'];
            $stateName = _::first($state);
            if ($stateName === 'default' || $stateName === 'in_subsection') {
                $isAllCaps = str::upper(_::first($cells)) === _::first($cells);
                // TODO extract function
                $isSubsectionName = $isAllCaps;
                if (_::first($cells) === 'ВЫБОРОЧНЫЙ МОНИТОРИНГ') {
                    $header = _::drop($this->nonEmptyCells($cells), 1);
                    $state = ['in_conditional_multipliers', _::first($cells), $header];
                } elseif ($isSubsectionName) {
                    $state = ['in_subsection', _::first($cells)];
                } else {
                    // simple case
                    list($k, $v) = $cells;
                    $value = $this->parseFloat($v, $this->defaultMultiplierFn($row['row_number']));
                    if ($stateName === 'in_subsection') {
                        list($_, $subsection) = $state;
                        $ret[$subsection][$k] = $value;
                    } else {
                        $ret[$k] = $value;
                    }
                }
            } elseif ($stateName === 'in_conditional_multipliers') {
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
                    $state = ['default'];
                }
            }
        }
        return $ret;
    }

    private function parseDocuments($rows) {
        $ret = [];
        $state = ['find_document'];
        foreach ($rows as $idx => $row) {
            $cells = $row['cells'];
            $stateName = _::first($state);
            if ($stateName === 'find_document') {
                if ($this->parseBoolean(_::first($cells)) === null) {
                    $state = ['in_document', _::first($cells)];
                }
            } elseif ($stateName === 'in_document') {
                list($_, $document) = $state;
                list($k, $v) = $cells;
                $booleanMaybe = $this->parseBoolean($k);
                if ($booleanMaybe !== null) {
                    $ret[$document][$booleanMaybe] = $this->parseFloat($v, $this->defaultMultiplierFn($row['row_number']));
                } else {
                    // TODO handle the unexpected
                }
                // peek the next row
                if (isset($rows[$idx + 1]) && !$this->parseBoolean(_::first($rows[$idx + 1]))) {
                    $state = ['find_document'];
                }
            }
        }
        return $ret;
    }

    function parseFile($path) {
        return $this->mapWorksheets($path, $this->spec['worksheets'], function(Worksheet $worksheet) {
            $sectionGroups = $this->sectionGroups($worksheet->getRowIterator(), $this->spec['sections']);
            return [
                'multipliers' => _::map($sectionGroups, function ($rows, $sectionKey) {
                    if ($sectionKey === 'STRUCTURES_TO_MONITOR') {
                        return $this->parseStructuresToMonitor($rows);
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