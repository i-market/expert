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
            $ret[] = $cell->getValue();
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

    protected function parseFloat($str, $defaultFn) {
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
            list($k, $v) = $row['cells'];
            $ret[$k] = $this->parseFloat($v, $this->defaultMultiplierFn($row['row_number']));
        }
        return $ret;
    }

    function sectionGroups($rowIterator, $sections) {
        $validSectionKeys = join(', ', array_reduce($sections, function($acc, $section) {
            return array_merge($acc, array_map(function($prefix) {
                return '"'.$prefix.'"';
            }, Util::ensureList($section['prefix'])));
        }, []));
        $ret = [];
        $state = ['find_section'];
        foreach ($rowIterator as $rowNumber => $sheetRow) {
            $rawCells = $this->cellValues($sheetRow);
            $cells = array_map('trim', $rawCells);
            $row = [
                'object' => $sheetRow,
                'cells' => $cells,
                'row_number' => $rowNumber
            ];
            $stateName = _::first($state);
            if ($stateName === 'find_section') {
                $sectionMaybe = $this->findSection($cells, $sections);
                foreach (nil::iterator($sectionMaybe) as $section) {
                    $ret[$section['key']] = [];
                    $state = ['in_section', $section];
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
            }
        }
        return $ret;
    }

    protected function mapWorksheets($path, $worksheetSpecs, callable $f) {
        $ret = [];
        $spreadsheet = $this->spreadsheet($path);
        foreach ($worksheetSpecs as $worksheetSpec) {
            $worksheetMaybe = $spreadsheet->getSheetByName($worksheetSpec['name']);
            if ($worksheetMaybe === null) {
                $this->log[] = ['error', 'Не могу найти лист "'.$worksheetSpec['name'].'".'];
            }
            foreach (nil::iterator($worksheetMaybe) as $worksheet) {
                $ret[$worksheetSpec['key']] = $f($worksheet);
            }
        }
        // TODO validate return VALUE
        return $ret;
    }

    protected function parseBoolean($str) {
        // TODO keywords class property
        $truthy = ['Имеется', 'ЕСТЬ'];
        $falsy = ['Не имеется', 'НЕТ'];
        if (in_array($str, $truthy)) {
            return true;
        } elseif (in_array($str, $falsy)) {
            return false;
        } else {
            return null;
        }
    }

    // TODO
    protected function parseNumericPredicate($str) {
        if (is_numeric($str)) {
            return function ($x) use ($str) {
                return $x == $str;
            };
        } else {
            $matchesRef = [];
            return preg_match('/(\d+)[-—\s]+(\d+)/', $str, $matchesRef)
                ? function ($x) use ($matchesRef) {
                    list($_, $min, $max) = $matchesRef;
                    return $min <= $x && $x <= $max;
                }
                : null;
        }
    }

    protected function nonEmptyCells($cells) {
        return _::takeWhile($cells, function ($cell) {
            return !str::isEmpty($cell);
        });
    }
}