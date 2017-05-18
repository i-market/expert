<?php

namespace App\Calc;

use Core\Strings as str;
use League\Csv\Reader;
use Respect\Validation\Validator as v;
use Core\Underscore as _;

class MonitoringCalc extends AbstractCalc {
    function validateState($state) {
        $validator = v::keySet(
            v::key('DESCRIPTION', v::stringType()->notEmpty()),
            v::key('BUILDING_COUNT', v::intType()->min(1)),
            // TODO validate reference?
            v::key('LOCATION_ID', v::intType()),
            v::key('ADDRESS', v::stringType()->notEmpty())
        );
        return $validator->validate($state);
    }

    protected function multipliers($state) {
        // TODO stub
        return [
            'BUILDING_COUNT' => 2,
            'LOCATION_ID' => 1.5
        ];
    }

    /**
     * @return array structured data with minimal transformations
     */
    static function parseCsv($path) {
        // TODO report unexpected file "format" (e.g. missing/extra sections)
        $isEmptyRow = function($row) {
            return _::matches($row, function($str) {
                return str::isEmpty($str);
            });
        };
        $sections = [
//            'Описание объекта(ов) мониторинга',
            [
                'KEY' => 'BUILDING_COUNT',
                'STARTS_WITH' => 'Количество зданий сооружений, строений (шт.)'
            ],
            [
                'KEY' => 'LOCATION',
                'STARTS_WITH' => 'Местонахождение'
            ],
//            'Адрес',
            [
                'KEY' => 'USED_FOR',
                'STARTS_WITH' => 'Назначение объекта мониторинга',
            ],
//            'Общая площадь объекта (кв.м.)',
            [
                'KEY' => 'VOLUME',
                'STARTS_WITH' => 'Строительный объем объекта (куб. м.)',
            ],
            [
                'KEY' => 'FLOORS',
                'STARTS_WITH' => 'Количество надземных этажей',
            ],
            [
                'KEY' => 'HAS_UNDERGROUND_FLOORS',
                'STARTS_WITH' => 'Наличие технического подполья, подвала, подземных этажей',
            ],
            [
                'KEY' => 'UNDERGROUND_FLOORS',
                'STARTS_WITH' => 'Количество подземных этажей',
            ],
            [
                'KEY' => 'MONITORING_GOAL',
                'STARTS_WITH' => 'Цели мониторинга',
            ],
            [
                'KEY' => 'STRUCTURES_TO_MONITOR',
                'STARTS_WITH' => 'Конструкции подлежащие мониторингу',
            ],
//            'Продолжетельность мониторинга (мес.)',
            // TODO Удаленность объектов друг от друга?
//            'Удаленность объектов друг от друга',
//            'Транспортная доуступность',
//            'Наличие документов'
        ];
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
        $sectionKey2Rows = [];
        $state = ['find_section'];
        foreach ($reader->getIterator() as $rawCells) {
            $cells = array_map('trim', $rawCells);
            $stateName = _::first($state);
            if ($stateName === 'find_section') {
                $sectionMaybe = _::find($sections, function($section) use ($cells) {
                    return str::startsWith(_::first($cells), $section['STARTS_WITH']);
                });
                if ($sectionMaybe !== null) {
                    $state = ['in_section', $sectionMaybe];
                }
            } elseif ($stateName === 'in_section') {
                list($_, $section) = $state;
                // TODO если проверять только первую ячейку можно потерять (плохо составленные) данные,
                // лучше чтобы вся строка была пустой
                // TODO extract function
                if (str::isEmpty(_::first($cells))) {
                    $state = ['find_section'];
                } else {
                    $sectionKey2Rows[$section['KEY']][] = $cells;
                }
            }
        }
        $parseFloat = function($str) {
            // TODO validate
            $normalized = str::replace($str, ',', '.');
            return floatval($normalized);
        };
        $nonEmptyCells = function($cells) {
            return array_filter($cells, function($cell) {
                return !str::isEmpty($cell);
            });
        };
        // $parseNumericPredicate doesn't belong in the parsing phase
//        $parseNumericPredicate = function($str) {
//            if (is_numeric($str)) {
//                return function($x) use ($str) {
//                    return $x == $str;
//                };
//            } else {
//                $matchesRef = [];
//                return preg_match('/(\d+)[-—](\d+)/', $str, $matchesRef)
//                    ? function($x) use ($matchesRef) {
//                        list($_, $min, $max) = $matchesRef;
//                        return $min <= $x && $x <= $max;
//                    }
//                    : null;
//            }
//        };
        $ret = _::map($sectionKey2Rows, function($rows, $sectionKey) use ($parseFloat, $nonEmptyCells) {
            // TODO extract function
            if ($sectionKey === 'STRUCTURES_TO_MONITOR') {
                $map = [];
                $inSection = null;
                $state = ['default'];
                foreach ($rows as $idx => $cells) {
                    $stateName = _::first($state);
                    if ($stateName === 'default' || $stateName === 'in_subsection') {
                        $isAllCaps = str::upper(_::first($cells)) === _::first($cells);
                        // TODO extract function
                        $isSubsectionName = $isAllCaps;
                        if (_::first($cells) === 'ВЫБОРОЧНЫЙ МОНИТОРИНГ') {
                            $header = _::drop($nonEmptyCells($cells), 1);
                            $state = ['in_conditional_multipliers', _::first($cells), $header];
                        } elseif ($isSubsectionName) {
                            $state = ['in_subsection', _::first($cells)];
                        } else {
                            // simple case
                            list($k, $v) = $cells;
                            $value = $parseFloat($v);
                            if ($stateName === 'in_subsection') {
                                list($_, $subsection) = $state;
                                $map[$subsection][$k] = $value;
                            } else {
                                $map[$k] = $value;
                            }
                        }
                    } elseif ($stateName === 'in_conditional_multipliers') {
                        list($_, $subsection, $header) = $state;
                        $filteredCells = $nonEmptyCells($cells);
                        $isConditionalMultiplier = function($cells) use ($header) {
                            return count($cells) === count($header) + 1;
                        };
                        if ($isConditionalMultiplier($filteredCells)) {
                            $key = _::first($cells);
                            $multipliers = array_map($parseFloat, _::drop($filteredCells, 1));
                            $map[$subsection][$key] = array_combine($header, $multipliers);
                        }
                        // peek the next row
                        if (isset($rows[$idx + 1]) && !$isConditionalMultiplier($nonEmptyCells($rows[$idx + 1]))) {
                            $state = ['default'];
                        }
                    }
                }
                return $map;
            } else {
                // simple case
                $map = [];
                foreach ($rows as $cells) {
                    list($k, $v) = $cells;
                    $map[$k] = $parseFloat($v);
                }
                return $map;
            }
        });
        // TODO validate return value
        return ['MULTIPLIERS' => $ret];
    }
}