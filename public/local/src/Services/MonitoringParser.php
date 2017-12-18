<?php

namespace App\Services;

use Core\Strings as str;
use Core\Underscore as _;
use Core\Nullable as nil;
use PhpOffice\PhpSpreadsheet\Worksheet;

class MonitoringParser extends Parser {
    public $spec = [
        'package_selection_individual' => 'ВЫБОРОЧНЫЙ МОНИТОРИНГ',
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
                'prefix' => 'Продолжительность мониторинга (мес.)',
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
            ],
            // TODO prices section
//            [
//                'key' => 'PRICES',
//                // TODO case-insensitive
//                'prefix' => 'ЦЕНЫ'
//            ]
        ]
    ];

    // TODO use general `Parser/parseStructures`
    private function parseStructuresToMonitor($rows) {
        $setSubsectionValue = function($array, $subsection, $key, $value) {
            $renameSubsection = [
                // TODO refactor
                $this->spec['package_selection_individual'] => 'INDIVIDUAL',
                'КОМПЛЕКСНЫЙ МОНИТОРИНГ' => 'PACKAGE'
            ];
            $renamed = _::get($renameSubsection, $subsection, $subsection);
            return _::set($array, [$renamed, $key], $value);
        };
        $ret = [];
        $state = ['default'];
        foreach ($rows as $idx => $row) {
            $cells = $row['cells'];
            $stateName = _::first($state);
            if ($stateName === 'default' || $stateName === 'in_subsection') {
                $isAllCaps = str::upper(_::first($cells)) === _::first($cells);
                // TODO extract function
                $isSubsectionName = $isAllCaps;
                if (_::first($cells) === $this->spec['package_selection_individual']) {
                    $header = _::drop($this->nonEmptyCells($cells), 1);
                    $state = ['in_conditional_multipliers', _::first($cells), $header];
                } elseif ($isSubsectionName) {
                    $state = ['in_subsection', _::first($cells)];
                } else {
                    $value = $this->simpleValue($row);
                    if ($stateName === 'in_subsection') {
                        list($_, $subsection) = $state;
                        $ret = $setSubsectionValue($ret, $subsection, $value['ID'], $value);
                    } else {
                        $ret[$value['ID']] = $value;
                    }
                }
            } elseif ($stateName === 'in_conditional_multipliers') {
                list($_, $subsection, $header) = $state;
                $filteredCells = $this->nonEmptyCells($cells);
                $isConditionalMultiplier = function($cells) use ($header) {
                    return count($cells) === count($header) + 1;
                };
                if ($isConditionalMultiplier($filteredCells)) {
                    $name = _::first($cells);
                    $multipliers = array_map(function($str) use ($row) {
                        return $this->parseFloat($str, $this->defaultMultiplierFn($row['row_number']));
                    }, _::drop($filteredCells, 1));
                    $id = $row['metadata']['id'];
                    $value = ['ID' => $id, 'NAME' => $name, 'VALUE' => array_combine($header, $multipliers)];
                    $ret = $setSubsectionValue($ret, $subsection, $id, $value);
                }
                // peek the next row
                if (isset($rows[$idx + 1]) && !$isConditionalMultiplier($this->nonEmptyCells($rows[$idx + 1]['cells']))) {
                    $state = ['default'];
                }
            }
        }
        return $ret;
    }

    function parseFile($path) {
        return $this->mapWorksheets($path, $this->spec['worksheets'], function(Worksheet $worksheet) {
            $sectionGroups = $this->sectionGroups($worksheet->getRowIterator(), $this->spec['sections']);
            return [
                'MULTIPLIERS' => _::map($sectionGroups, function ($rows, $sectionKey) {
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