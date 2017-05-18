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
//            'Назначение объекта мониторинга',
//            'Общая площадь объекта (кв.м.)',
//            'Строительный объем объекта (куб. м.)',
//            'Количество надземных этажей',
//            'Наличие технического подполья, подвала, подземных этажей',
//            'Количество подземных этажей',
//            'Цели мониторинга',
//            'Конструкции подлежащие мониторингу',
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
                if ($isEmptyRow($cells)) {
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
        $ret = _::map($sectionKey2Rows, function($rows, $sectionKey) use ($parseFloat) {
            // basic case
            $map = [];
            foreach ($rows as $cells) {
                list($k, $v) = $cells;
                $map[$k] = $parseFloat($v);
            }
            return $map;
        });
        // TODO validate return value
        return ['MULTIPLIERS' => $ret];
    }
}