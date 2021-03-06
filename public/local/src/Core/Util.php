<?php

namespace Core;

use Core\Underscore as _;
use Core\Strings as str;

class Util {
    static private $lastId = 0;

    static function uniqueId() {
        self::$lastId += 1;
        return self::$lastId;
    }
    
    static function inRange($x, $min, $max) {
        return $x >= $min && $x <= $max;
    }
    
    static function joinPath(array $paths) {
        $trimmed = _::clean(array_map(function($path) {
            return trim($path, DIRECTORY_SEPARATOR);
        }, $paths));
        $prefix = str::startsWith(_::first($paths), DIRECTORY_SEPARATOR) ? DIRECTORY_SEPARATOR : '';
        return $prefix.join(DIRECTORY_SEPARATOR, $trimmed);
    }
    
    static function formInputNamePath($name) {
        // clean = remove empty strings
        return _::clean(array_map(function($segment) {
            return trim($segment, ']');
        }, explode('[', $name)));
    }

    static function humanFileSize($size, $precision = 0) {
        $units = array('Б','КБ','МБ','ГБ','ТБ','ПБ','EB','ZB','YB');
        $step = 1024;
        $i = 0;
        while (($size / $step) > 0.9) {
            $size = $size / $step;
            $i++;
        }
        return round($size, $precision).' '.$units[$i];
    }

    static function monthRu($n) {
        $months = explode('|', '|января|февраля|марта|апреля|мая|июня|июля|августа|сентября|октября|ноября|декабря');
        return $months[$n];
    }

    static function splitFileExtension($path) {
        $filename = str::contains($path, DIRECTORY_SEPARATOR)
            ? self::basename($path)
            : $path;
        $parts = explode('.', $filename);
        if (str::startsWith($filename, '.')) {
            return [$filename, ''];
        } elseif (count($parts) < 2) {
            return [$filename, ''];
        } else {
            return [join('', _::initial($parts)), _::last($parts)];
        }
    }

    /** @deprecated use splitFileExtension */
    static function fileExtension($path) {
        $ext = _::last(self::splitFileExtension($path));
        return $ext === '' ? null : $ext;
    }

    // cyrillic characters break standard library `basename`
    static function basename($path) {
        return _::last(explode(DIRECTORY_SEPARATOR, $path));
    }

    /**
     * Возвращает единицу измерения с правильным окончанием
     * @param $number int Число
     * @param $cases array Варианты слова {nom: 'час', gen: 'часа', plu: 'часов'}
     * @return string
     */
    static function units($number, $cases) {
        // shorthand syntax
        $args = func_get_args();
        if (count($args) === 4) {
            $cases = array('nom' => $args[1], 'gen' => $args[2], 'plu' => $args[3]);
        }
        $num = abs($number);
        return (mb_strpos(strval($num), '.') !== false
            ? $cases['gen']
            : ($num % 10 === 1 && $num % 100 !== 11
                ? $cases['nom']
                : ($num % 10 >= 2 && $num % 10 <= 4 && ($num % 100 < 10 || $num % 100 >= 20)
                    ? $cases['gen']
                    : $cases['plu'])));
    }

    static function formatCurrency($num, $options = []) {
        $opts = array_merge([
            'cents' => true
        ], $options);
        return number_format($num, $opts['cents'] ? 2 : 0, ',', ' ');
    }

    static function ensureList($x) {
        return !is_array($x) || !_::isIndexed($x) ? [$x] : $x;
    }

    // TODO refactor
    static function descendants($parents, $childrenFn, $ret = []) {
        if (is_string($childrenFn)) {
            $path = $childrenFn;
            $childrenFn = function($array) use ($path) {
                return _::get($array, $path);
            };
        }
        $children = _::flatMap($parents, $childrenFn);
        if (_::isEmpty($children)) {
            return $ret;
        } else {
            return self::descendants($children, $childrenFn, array_merge($ret, $children));
        }
    }

    /** @deprecated use `Underscore::operator` */
    static function sum(array $xs) {
        return array_reduce($xs, _::operator('+'), 0);
    }

    /** @deprecated use `Underscore::operator` */
    static function product(array $xs) {
        return array_reduce($xs, _::operator('*'), 1);
    }

    // TODO refactor: move to `NewsListLike`
    static function elementEditingLinks($element) {
        assert(isset($element['IBLOCK_ID']));
        assert(isset($element['ID']));
        // from news.list
        $arButtons = \CIBlock::GetPanelButtons(
            $element["IBLOCK_ID"],
            $element["ID"],
            0,
            array("SECTION_BUTTONS"=>false, "SESSID"=>false)
        );
        return [
            "EDIT_LINK" => $arButtons["edit"]["edit_element"]["ACTION_URL"],
            "DELETE_LINK" => $arButtons["edit"]["delete_element"]["ACTION_URL"]
        ];
    }

    static function sectionEditingLinks($section) {
        assert(isset($section['IBLOCK_ID']));
        assert(isset($section['ID']));
        // from catalog.section.list
        $arButtons = \CIBlock::GetPanelButtons(
            $section["IBLOCK_ID"],
            0,
            $section["ID"],
            array("SESSID"=>false, "CATALOG"=>true)
        );
        return [
            "EDIT_LINK" => $arButtons["edit"]["edit_section"]["ACTION_URL"],
            "DELETE_LINK" => $arButtons["edit"]["delete_section"]["ACTION_URL"]
        ];
    }
}