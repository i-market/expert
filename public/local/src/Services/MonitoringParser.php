<?php

namespace App\Services;

use Core\Strings as str;
use Core\Util;
use Core\Underscore as _;
use Core\Nullable as nil;

class MonitoringParser {
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

    private function findSection($row) {
        $isMatch = function($prefix) use ($row) {
            // TODO maybe ignore whitespace?
            // TODO make it case-insensitive
            return str::startsWith(_::first($row), $prefix);
        };
        return _::find($this->sections, function($section) use ($isMatch) {
            if (is_array($section['PREFIX'])) {
                return _::matchesAny($section['PREFIX'], $isMatch);
            } else {
                return $isMatch($section['PREFIX']);
            }
        });
    }

    private function isSectionDelimiter($row) {
        // TODO если проверять только первую ячейку можно потерять (плохо составленные) данные,
        // лучше чтобы вся строка была пустой
        return str::isEmpty(_::first($row));
    }

    private function isEmptyRow($row) {
        return _::matches($row, function($str) {
            return str::isEmpty($str);
        });
    }

    private function parseFloat($str, $defaultFn) {
        $normalized = str::replace($str, ',', '.');
        if (!is_numeric($normalized)) {
            return $defaultFn($normalized);
        } else {
            return floatval($normalized);
        }
    }

    private function parseBoolean($str) {
        $truthy = ['Имеется', 'ЕСТЬ'];
        $falsy = ['Не имеется', 'НЕТ'];
        if (in_array($str, $truthy)) {
            return true;
        } elseif(in_array($str, $falsy)) {
            return false;
        } else {
            return null;
        }
    }

    // TODO
    private function parseNumericPredicate($str) {
        if (is_numeric($str)) {
            return function($x) use ($str) {
                return $x == $str;
            };
        } else {
            $matchesRef = [];
            return preg_match('/(\d+)[-—\s]+(\d+)/', $str, $matchesRef)
                ? function($x) use ($matchesRef) {
                    list($_, $min, $max) = $matchesRef;
                    return $min <= $x && $x <= $max;
                }
                : null;
        }
    }

    private function nonEmptyCells($cells) {
        return array_filter($cells, function($cell) {
            return !str::isEmpty($cell);
        });
    }

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

    private function messageRow($row) {
        return '"'.join(', ', self::nonEmptyCells($row)).'"';
    }

    private function defaultMultiplierFn($rowNumber) {
        return function($str) use ($rowNumber) {
            $this->log[] = ['error', "Не могу найти коэффициент в строке номер {$rowNumber}."];
            return (float) 1;
        };
    }

    function parseWorksheet($rowIterator) {
        $validSectionKeys = join(', ', array_reduce($this->sections, function($acc, $section) {
            return array_merge($acc, array_map(function($prefix) {
                return '"'.$prefix.'"';
            }, Util::ensureList($section['PREFIX'])));
        }, []));
        $sectionGroups = [];
        $state = ['find_section'];
        foreach ($rowIterator as $idx => $rawCells) {
            $rowNumber = $idx + 1;
            $cells = array_map('trim', $rawCells);
            $stateName = _::first($state);
            if ($stateName === 'find_section') {
                $sectionMaybe = $this->findSection($cells);
                foreach (nil::iterator($sectionMaybe) as $section) {
                    $sectionGroups[$section['KEY']] = [];
                    $state = ['in_section', $section];
                }
                if ($sectionMaybe === null && !$this->isEmptyRow($cells)) {
                    $msg = "Не могу найти заголовок раздела в строке номер {$rowNumber}. "
                        ."Заголовок должен начинаться с одной из следующих строк: {$validSectionKeys}";
                    $this->log[] = ['error', $msg];
                }
            } elseif ($stateName === 'in_section') {
                list($_, $section) = $state;
                if ($this->isSectionDelimiter($cells)) {
                    $state = ['find_section'];
                } else {
                    $sectionGroups[$section['KEY']][] = [
                        'cells' => $cells,
                        'row_number' => $rowNumber
                    ];
                }
            }
        }
        $ret = _::map($sectionGroups, function($rows, $sectionKey) {
            if ($sectionKey === 'STRUCTURES_TO_MONITOR') {
                return $this->parseStructuresToMonitor($rows);
            } elseif ($sectionKey === 'DOCUMENTS') {
                return $this->parseDocuments($rows);
            }  else {
                // simple case
                $map = [];
                foreach ($rows as $row) {
                    list($k, $v) = $row['cells'];
                    $map[$k] = $this->parseFloat($v, $this->defaultMultiplierFn($row['row_number']));
                }
                return $map;
            }
        });
        // TODO validate return VALUE
        return [
            'MULTIPLIERS' => $ret
        ];
    }
}