<?php

namespace Core;

use Core\Underscore as _;
use Core\Strings as str;

class Util {
    static private $lastId = 0;

    static function uniqueId($prefix='') {
        self::$lastId += 1;
        return self::$lastId;
    }
    
    static function joinPath($paths) {
        $trimmed = _::clean(array_map(function($path) {
            return trim($path, DIRECTORY_SEPARATOR);
        }, $paths));
        $prefix = str::startsWith(_::first($paths), DIRECTORY_SEPARATOR) ? DIRECTORY_SEPARATOR : '';
        return $prefix.join(DIRECTORY_SEPARATOR, $trimmed);
    }
    
    static function formInputNamePath($name) {
        return array_map(function($segment) {
            return trim($segment, ']');
        }, explode('[', $name));
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

    static function fileExtension($path) {
        $ret = _::last(explode('.', $path));
        return $ret === $path ? null : $ret;
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
}