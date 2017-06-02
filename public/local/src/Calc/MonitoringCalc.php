<?php

namespace App\Calc;

use Core\Strings as str;
use Core\Util;
use League\Csv\Reader;
use Respect\Validation\Validator as v;
use Core\Underscore as _;
use Core\Nullable as nil;

class MonitoringCalc extends AbstractCalc {
    private static $sections = [
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
        ]
    ];

    function validateState($state) {
        // TODO validate
        return true;
        $validator = v::allOf(
            v::key('DESCRIPTION', v::stringType()->notEmpty()),
            v::key('SITE_COUNT', v::intType()->min(1)),
            // TODO validate reference?
            v::key('LOCATION_ID', v::intType()),
            v::key('ADDRESS', v::stringType()->notEmpty())
        );
        return $validator->validate($state);
    }

    protected function multipliers($state) {
        // TODO stub
        return [
            'SITE_COUNT' => 2,
            'LOCATION_ID' => 1.5
        ];
    }

    private static function findSection($row) {
        $isMatch = function($prefix) use ($row) {
            // TODO maybe ignore whitespace?
            return str::startsWith(_::first($row), $prefix);
        };
        return _::find(self::$sections, function($section) use ($isMatch) {
            if (is_array($section['PREFIX'])) {
                return _::matchesAny($section['PREFIX'], $isMatch);
            } else {
                return $isMatch($section['PREFIX']);
            }
        });
    }

    private static function isSectionDelimiter($row) {
        // TODO если проверять только первую ячейку можно потерять (плохо составленные) данные,
        // лучше чтобы вся строка была пустой
        return str::isEmpty(_::first($row));
    }

    private static function isEmptyRow($row) {
        return _::matches($row, function($str) {
            return str::isEmpty($str);
        });
    }

    private static function parseFloat($str) {
        // TODO validate
        $normalized = str::replace($str, ',', '.');
        $val = floatval($normalized);
        return $val == 0 ? 1 : $val;
    }

    private static function parseBoolean($str) {
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

    private static function parseNumericPredicate($str) {
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

    private static function nonEmptyCells($cells) {
        return array_filter($cells, function($cell) {
            return !str::isEmpty($cell);
        });
    }

    private static function parseStructuresToMonitor($rows) {
        $ret = [];
        $state = ['default'];
        foreach ($rows as $idx => $cells) {
            $stateName = _::first($state);
            if ($stateName === 'default' || $stateName === 'in_subsection') {
                $isAllCaps = str::upper(_::first($cells)) === _::first($cells);
                // TODO extract function
                $isSubsectionName = $isAllCaps;
                if (_::first($cells) === 'ВЫБОРОЧНЫЙ МОНИТОРИНГ') {
                    $header = _::drop(self::nonEmptyCells($cells), 1);
                    $state = ['in_conditional_multipliers', _::first($cells), $header];
                } elseif ($isSubsectionName) {
                    $state = ['in_subsection', _::first($cells)];
                } else {
                    // simple case
                    list($k, $v) = $cells;
                    $value = self::parseFloat($v);
                    if ($stateName === 'in_subsection') {
                        list($_, $subsection) = $state;
                        $ret[$subsection][$k] = $value;
                    } else {
                        $ret[$k] = $value;
                    }
                }
            } elseif ($stateName === 'in_conditional_multipliers') {
                list($_, $subsection, $header) = $state;
                $filteredCells = self::nonEmptyCells($cells);
                $isConditionalMultiplier = function($cells) use ($header) {
                    return count($cells) === count($header) + 1;
                };
                if ($isConditionalMultiplier($filteredCells)) {
                    $key = _::first($cells);
                    $multipliers = array_map(self::class.'::parseFloat', _::drop($filteredCells, 1));
                    $ret[$subsection][$key] = array_combine($header, $multipliers);
                }
                // peek the next row
                if (isset($rows[$idx + 1]) && !$isConditionalMultiplier(self::nonEmptyCells($rows[$idx + 1]))) {
                    $state = ['default'];
                }
            }
        }
        return $ret;
    }

    private static function parseDocuments($rows) {
        $ret = [];
        $state = ['find_document'];
        foreach ($rows as $idx => $cells) {
            $stateName = _::first($state);
            if ($stateName === 'find_document') {
                if (self::parseBoolean(_::first($cells)) === null) {
                    $state = ['in_document', _::first($cells)];
                }
            } elseif ($stateName === 'in_document') {
                list($_, $document) = $state;
                list($k, $v) = $cells;
                $booleanMaybe = self::parseBoolean($k);
                if ($booleanMaybe !== null) {
                    $ret[$document][$booleanMaybe] = self::parseFloat($v);
                } else {
                    // TODO handle the unexpected
                }
                // peek the next row
                if (isset($rows[$idx + 1]) && !self::parseBoolean(_::first($rows[$idx + 1]))) {
                    $state = ['find_document'];
                }
            }
        }
        return $ret;
    }

    private static function messageRow($row) {
        return '"'.join(', ', self::nonEmptyCells($row)).'"';
    }

    /**
     * @return array structured data with minimal transformations
     */
    static function parseCsv($path) {
        // TODO report unexpected file "format" (e.g. missing/extra sections)
        // Help PHP detect line ending in Mac OS X.
        // http://csv.thephpleague.com/8.0/instantiation/#csv-and-macintosh
        if (!ini_get('auto_detect_line_endings')) {
            ini_set('auto_detect_line_endings', '1');
        }
        $reader = Reader::createFromPath($path);
        // TODO set delimiter automatically
        $extension = _::last(explode('.', $path));
        if ($extension === 'tsv') {
            $reader->setDelimiter("\t");
        }
        // convert to UTF-8
        $inputBom = $reader->getInputBOM();
        if ($inputBom === Reader::BOM_UTF16_LE || $inputBom === Reader::BOM_UTF16_BE) {
            $reader->appendStreamFilter('convert.iconv.UTF-16/UTF-8');
        }
        $validSectionKeys = join(', ', array_reduce(self::$sections, function($acc, $section) {
            return array_merge($acc, array_map(function($prefix) {
                return '"'.$prefix.'"';
            }, Util::ensureList($section['PREFIX'])));
        }, []));
        $log = [];
        $sectionKey2Rows = [];
        $state = ['find_section'];
        foreach ($reader->getIterator() as $idx => $rawCells) {
            $rowNumber = $idx + 1;
            $cells = array_map('trim', $rawCells);
            $stateName = _::first($state);
            if ($stateName === 'find_section') {
                $sectionMaybe = self::findSection($cells);
                foreach (nil::iterator($sectionMaybe) as $section) {
                    $sectionKey2Rows[$section['KEY']] = [];
                    $state = ['in_section', $section];
                }
                if ($sectionMaybe === null && !self::isEmptyRow($cells)) {
                    $msg = "Не могу найти заголовок раздела в строке номер {$rowNumber}: ".self::messageRow($cells).'. '
                        ."Заголовок должен начинаться с одной из следующих строк: {$validSectionKeys}";
                    $log[] = ['error', $msg];
                }
            } elseif ($stateName === 'in_section') {
                list($_, $section) = $state;
                if (self::isSectionDelimiter($cells)) {
                    $state = ['find_section'];
                } else {
                    $sectionKey2Rows[$section['KEY']][] = $cells;
                }
            }
        }
        $ret = _::map($sectionKey2Rows, function($rows, $sectionKey) {
            if ($sectionKey === 'STRUCTURES_TO_MONITOR') {
                return self::parseStructuresToMonitor($rows);
            } elseif ($sectionKey === 'DOCUMENTS') {
                return self::parseDocuments($rows);
            } else {
                // simple case
                $map = [];
                foreach ($rows as $cells) {
                    list($k, $v) = $cells;
                    $map[$k] = self::parseFloat($v);
                }
                return $map;
            }
        });
        // TODO validate return VALUE
        $missingSections = array_diff(_::pluck(self::$sections, 'KEY'), array_keys($ret));
        return ['MULTIPLIERS' => $ret];
    }
}