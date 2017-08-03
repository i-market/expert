<?php

namespace App\Services;

use Core\Underscore as _;
use Core\Strings as str;
use PhpOffice\PhpSpreadsheet\Worksheet;

class IndividualParser extends Parser {
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
        ]
    ];

    function parseFile($path) {
        $spreadsheet = $this->spreadsheet($path);
        $worksheets = _::keyBy('key', $this->spec['worksheets']);
        /** @var Worksheet $worksheet */
        $worksheet = $spreadsheet->getSheetByName($worksheets['MULTIPLE_BUILDINGS']['name']);
        if ($worksheet === null) {
            $this->log[] = ['error', 'Не могу найти лист "'.$worksheets['MULTIPLE_BUILDINGS']['name'].'".'];
        }
        $rowIter = $worksheet->getRowIterator();
        $findSection = function($row) {
            $reverseNonEmpty = _::dropWhile(array_reverse($row['cells']), [str::class, 'isEmpty']);
            return count($reverseNonEmpty) === 1
                ? _::first($reverseNonEmpty)
                : null;
        };
        $isCountMultipliers = function($row) {
            return _::first($row['cells']) === 'Коэффициенты';
        };
        $ret = [];
        $state = ['find_section'];
        // see Parser/sectionGroups
        foreach ($rowIter as $rowNumber => $sheetRow) {
            $rawCells = $this->cellValues($sheetRow);
            $cellGroups = $this->classifyCells(array_map('trim', $rawCells));
            $cells = $cellGroups['data'];
            $row = [
                'object' => $sheetRow,
                'cells' => $cells,
                'row_number' => $rowNumber,
                'metadata' => $cellGroups['metadata']
            ];
            $parseMultiplier = _::partialRight([$this, 'parseFloat'], $this->defaultMultiplierFn($row['row_number']));
            $stateName = _::first($state);
            if ($stateName === 'find_section') {
                if ($isCountMultipliers($row)) {
                    $state = ['in_count_multipliers'];
                } else {
                    $sectionMaybe = $findSection($row);
                    if ($sectionMaybe !== null) {
                        $state = ['in_section', [$sectionMaybe]];
                    }
                }
            } elseif ($stateName === 'in_section') {
                // TODO refactor
                list($_, $path) = $state;
                if ($this->isSectionDelimiter($row)) {
                    if (count($path) === 1) {
                        $state = ['find_section'];
                    } else {
                        // TODO smarter section depth algorithm
                        $state = ['in_section', _::initial($path)];
                    }
                } else {
                    $sectionMaybe = $findSection($row);
                    if ($sectionMaybe !== null) {
                        $state = ['in_section', _::append($path, $sectionMaybe)];
                    } elseif (!_::isEmpty($path)) {
                        $header = ['NAME', 'GOAL', 'PRICE', 'OLD_PRICE', 'UNIT', 'DURATION'];
                        $value = array_merge(array_combine($header, _::take($cells, count($header))), [
                            'ID' => $row['metadata']['id'],
                        ]);
                        $ret = _::set($ret, array_merge(['ENTITIES'], $path, [$value['ID']]), $value);
                    }
                }
            } elseif ($stateName === 'in_count_multipliers') {
                list($count, $multiplier) = $cells;
                if (_::matches([$count, $multiplier], _::complement([str::class, 'isEmpty']))) {
                    $ret = _::set($ret, ['COUNT_MULTIPLIERS', $count], $parseMultiplier($multiplier));
                }
            }
        }
        return ['MULTIPLE_BUILDINGS' => $ret];
    }
}