<?php

namespace App\Services;

use Core\Util;
// TODO remove dependency
use League\Csv\Reader;
use Core\Underscore as _;
use Core\Nullable as nil;
use Core\Strings as str;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\BaseReader;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Row;

abstract class Parser {
    public $log = [];
    /** @deprecated */
    protected $keyValueSections = ['СРОКИ' => 'TIME'];

    abstract function parseFile($path);

    function classifyCells($cells) {
        return [
            'metadata' => [
                'id' => _::first($cells),
            ],
            'data' => _::rest($cells)
        ];
    }

    /**
     * maps abstracted row.cells indices to the real excel coordinates
     */
    function cellCoordinate($idx, $rowIdx) {
        // see `classifyCells`
        $columns = range('B', 'Z');
        assert(isset($columns[$idx]), 'index is out of range');
        return $columns[$idx].$rowIdx;
    }

    /**
     * @return Spreadsheet
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     */
    function spreadsheet($path) {
        // TODO check path/file format
        $readerType = IOFactory::identify($path);
        /** @var BaseReader $reader */
        $reader = IOFactory::createReader($readerType);
//        $reader->setReadDataOnly(true);
//        $reader->setReadFilter(...);
        /** @var Spreadsheet $spreadsheet */
        $spreadsheet = $reader->load($path);
        return $spreadsheet;
    }

    function cellValues(Row $row) {
        $ret = [];
        // TODO expose cut-off column
        foreach ($row->getCellIterator('A', 'Z') as $cell) {
            // this value is not guaranteed to reflect the actual calculated value because it is
            // possible that auto-calculation was disabled in the original spreadsheet, and underlying data
            // values used by the formula have changed since it was last calculated
            $ret[] = nil::get($cell->getOldCalculatedValue(), $cell->getValue());
        }
        return $ret;
    }

    protected function defaultMultiplierFn($rowNumber) {
        return function($str) use ($rowNumber) {
            $this->log[] = ['error', "Не могу найти коэффициент в строке номер {$rowNumber}."];
            return (float) 1;
        };
    }

    protected function isSectionDelimiter($row) {
        // TODO если проверять только первую ячейку можно потерять (плохо составленные) данные,
        // лучше чтобы вся строка была пустой
        return str::isEmpty(_::first($row['cells']));
    }

    protected function isEmptyRow($row) {
        return _::matches($row, function($str) {
            return str::isEmpty($str);
        });
    }

    protected function findSection($cells, $sections) {
        $isMatch = function($prefix) use ($cells) {
            // strip numbering
            $str = str::replaceAll(_::first($cells), '/^\d+\.\s*/', '');
            // TODO maybe ignore whitespace?
            // TODO make it case-insensitive
            return str::startsWith($str, $prefix);
        };
        return _::find($sections, function($section) use ($isMatch) {
            if (is_array($section['prefix'])) {
                return _::matchesAny($section['prefix'], $isMatch);
            } else {
                return $isMatch($section['prefix']);
            }
        });
    }

    /** private */
    function parseFloat($str, $defaultFn) {
        $normalized = str::replace($str, ',', '.');
        if (!is_numeric($normalized)) {
            return $defaultFn($normalized);
        } else {
            return floatval($normalized);
        }
    }

    protected function parseSimpleSection($rows) {
        $ret = [];
        foreach ($rows as $row) {
            $value = $this->simpleValue($row);
            $ret[$value['ID']] = $value;
        }
        return $ret;
    }

    protected function parseDocuments($rows) {
        $ret = [];
        $state = ['find_document'];
        foreach ($rows as $idx => $row) {
            $cells = $row['cells'];
            $stateName = _::first($state);
            if ($stateName === 'find_document') {
                if ($this->parseBoolean(_::first($cells)) === null) {
                    $document = ['ID' => $row['metadata']['id'], 'NAME' => _::first($cells), 'VALUE' => []];
                    $ret[$document['ID']] = $document;
                    $state = ['in_document', $document];
                }
            } elseif ($stateName === 'in_document') {
                list($_, $document) = $state;
                list($k, $v) = $cells;
                $booleanMaybe = $this->parseBoolean($k);
                if ($booleanMaybe !== null) {
                    $ret[$document['ID']]['VALUE'][$booleanMaybe] = $this->parseFloat($v, $this->defaultMultiplierFn($row['row_number']));
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

    protected function parseStructures($rows, $renameSubsection, $conditionalSection) {
        $setSubsectionValue = function($array, $subsection, $key, $value) use ($renameSubsection) {
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
                if (_::first($cells) === $conditionalSection) {
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

    protected function sectionGroups($rowIterator, $sections) {
        $validSectionKeys = join(', ', array_reduce($sections, function($acc, $section) {
            return array_merge($acc, array_map(function($prefix) {
                return '"'.$prefix.'"';
            }, Util::ensureList($section['prefix'])));
        }, []));
        $ret = [];
        $state = ['find_section'];
        foreach ($rowIterator as $rowNumber => $sheetRow) {
            $rawCells = $this->cellValues($sheetRow);
            $cellGroups = $this->classifyCells(array_map('trim', $rawCells));
            $cells = $cellGroups['data'];
            $row = [
                'object' => $sheetRow,
                'cells' => $cells,
                'row_number' => $rowNumber,
                'metadata' => $cellGroups['metadata']
            ];
            $stateName = _::first($state);
            if ($stateName === 'find_section') {
                $sectionMaybe = $this->findSection($cells, $sections);
                if ($sectionMaybe !== null) {
                    $ret[$sectionMaybe['key']] = [];
                    $state = ['in_section', $sectionMaybe];
                } else {
                    // TODO refactor
                    $kvSectionMaybe = _::find(array_keys($this->keyValueSections), function($str) use ($cells) {
                        return $str === _::first($cells);
                    });
                    if ($kvSectionMaybe !== null) {
                        $key = $this->keyValueSections[$kvSectionMaybe];
                        $ret[$key] = [];
                        $state = ['in_key_value_section', $key];
                    }
                }
                if ($sectionMaybe === null && !$this->isEmptyRow($cells)) {
                    $msg = "Не могу найти заголовок раздела в строке номер {$rowNumber}. "
                        ."Заголовок должен начинаться с одной из следующих строк: {$validSectionKeys}";
                    $this->log[] = ['error', $msg];
                }
            } elseif ($stateName === 'in_section') {
                list($_, $section) = $state;
                if ($this->isSectionDelimiter($row)) {
                    $state = ['find_section'];
                } else {
                    $ret[$section['key']][] = $row;
                }
            } elseif ($stateName === 'in_key_value_section') {
                list($_, $sectionKey) = $state;
                if ($this->isEmptyRow($cells)) {
                    $state = ['find_section'];
                } else {
                    list($k, $v) = $cells;
                    $ret[$sectionKey][$k] = $v;
                }
            }
        }
        return $ret;
    }

    /** private. simple key-value pair entity. */
    function simpleValue($row) {
        $pair = _::clean(_::take($row['cells'], 2));
        // TODO report to the user
        assert(count($pair) === 2, var_export(_::set($row, 'object', '[hidden]'), true));
        list($name, $v) = $pair;
        $id = $row['metadata']['id'];
        // TODO log human-readable message
        assert(!str::isEmpty($id), "row {$row['row_number']} has no id value");
        $multiplier = $this->parseFloat($v, $this->defaultMultiplierFn($row['row_number']));
        return ['ID' => $id, 'NAME' => $name, 'VALUE' => $multiplier];
    }

    protected function mapWorksheets($path, $worksheetSpecs, callable $f) {
        $ret = [];
        $spreadsheet = $this->spreadsheet($path);
        foreach ($worksheetSpecs as $worksheetSpec) {
            $worksheetMaybe = $spreadsheet->getSheetByName($worksheetSpec['name']);
            if ($worksheetMaybe === null) {
                $this->log[] = ['error', 'Не могу найти лист "'.$worksheetSpec['name'].'".'];
            }
            foreach (nil::iter($worksheetMaybe) as $worksheet) {
                $ret[$worksheetSpec['key']] = $f($worksheet);
            }
        }
        // TODO validate return VALUE
        return $ret;
    }

    static function parseBoolean($str) {
        // TODO keywords class property
        $truthy = ['Имеется', 'ЕСТЬ', 'Для суда', 'Требуется'];
        $falsy = ['Не имеется', 'НЕТ', 'Не для суда', 'Не требуется'];
        if (in_array($str, $truthy)) {
            return true;
        } elseif (in_array($str, $falsy)) {
            return false;
        } else {
            return null;
        }
    }

    static function parseNumericPredicate($str) {
        if (is_numeric($str)) {
            return function ($x) use ($str) {
                return is_numeric($x) && $x == $str;
            };
        } else {
            $matchesRef = [];
            // names
            $isMatch = preg_match('/более\s+(\d+)/', str::lower($str), $matchesRef);
            if ($isMatch) {
                return function($x) use ($matchesRef) {
                    list($_, $minExclusive) = $matchesRef;
                    return is_numeric($x) && $x > $minExclusive;
                };
            } else {
                // table headers
                return preg_match('/(\d+)[-—\s]+(\d+)/', $str, $matchesRef)
                    ? function ($x) use ($matchesRef) {
                        list($_, $min, $max) = $matchesRef;
                        return is_numeric($x) && $min <= $x && $x <= $max;
                    }
                    : null;
            }
        }
    }

    static function parseRangeText($str, $defaults) {
        $keywords = [
            'от' => 'min',
            'до' => 'max',
            'более' => 'min'
        ];
        $keywordsOr = join('|', array_keys($keywords));
        $matchesRef = [];
        preg_match_all('/('.$keywordsOr.')\s*([\d\s]+)/', $str, $matchesRef);
        list($words, $rawNumbers) = _::rest($matchesRef);
        $numbers = array_map(_::compose('intval', _::partial('preg_replace', '/\s/', '')), $rawNumbers);
        $range = array_combine(array_map(_::partial([_::class, 'get'], $keywords), $words), $numbers);
        return array_merge($defaults, $range);
    }

    /** take while not empty */
    protected function nonEmptyCells($cells) {
        return _::takeWhile($cells, function ($cell) {
            return !str::isEmpty($cell);
        });
    }
}