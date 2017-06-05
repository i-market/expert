<?php

namespace App\Services;

use Core\Strings as str;
use Core\Underscore as _;

class MonitoringParser extends Parser {
    public $log = [];

    public $sections = [
//            'Описание объекта(ов) мониторинга',
        [
            'KEY' => 'SITE_COUNT',
            'PREFIX' => 'Количество зданий сооружений, строений (шт.)'
        ],
        [
            'KEY' => 'LOCATION',
            'PREFIX' => 'Местонахождение'
        ],
//            'Адрес',
        [
            'KEY' => 'USED_FOR',
            'PREFIX' => [
                'Назначение объекта мониторинга',
                'Назначение объектов мониторинга'
            ],
        ],
//            'Общая площадь объекта (кв.м.)',
        [
            'KEY' => 'VOLUME',
            'PREFIX' => [
                'Строительный объем объекта (куб. м.)',
                'Строительный объем объектов (куб. м.)'
            ],
        ],
        [
            'KEY' => 'FLOORS',
            'PREFIX' => 'Количество надземных этажей',
        ],
        [
            'KEY' => 'HAS_UNDERGROUND_FLOORS',
            'PREFIX' => [
                'Наличие технического подполья, подвала, подземных этажей',
                'Наличие технических подпольев, подвалов, подземных этажей'
            ],
        ],
        [
            'KEY' => 'UNDERGROUND_FLOORS',
            'PREFIX' => 'Количество подземных этажей',
        ],
        [
            'KEY' => 'MONITORING_GOAL',
            'PREFIX' => 'Цели мониторинга',
        ],
        [
            'KEY' => 'STRUCTURES_TO_MONITOR',
            'PREFIX' => 'Конструкции подлежащие мониторингу',
        ],
        [
            'KEY' => 'DURATION',
            'PREFIX' => 'Продолжетельность мониторинга (мес.)',
        ],
        [
            'KEY' => 'DISTANCE_BETWEEN_SITES',
            'PREFIX' => 'Удаленность объектов друг от друга'
        ],
        [
            'KEY' => 'TRANSPORT_ACCESSIBILITY',
            'PREFIX' => 'Транспортная доуступность',
        ],
        [
            'KEY' => 'DOCUMENTS',
            'PREFIX' => 'Наличие документов',
        ],
        [
            'KEY' => 'PRICES',
            'PREFIX' => 'ЦЕНЫ'
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

    function parseWorksheet($rowIterator) {
        $sectionGroups = $this->sectionGroups($rowIterator, $this->sections);
        $ret = _::map($sectionGroups, function($rows, $sectionKey) {
            if ($sectionKey === 'STRUCTURES_TO_MONITOR') {
                return $this->parseStructuresToMonitor($rows);
            } elseif ($sectionKey === 'DOCUMENTS') {
                return $this->parseDocuments($rows);
            }  else {
                return $this->parseSimpleSection($rows);
            }
        });
        // TODO validate return VALUE
        return [
            'MULTIPLIERS' => $ret
        ];
    }
}