<?php

namespace Core;

use ArrayIterator;
use Bitrix\Main\Config\Configuration;
use Bitrix\Main\Config\Option;
use CBitrixComponentTemplate;
use CFile;
use CIBlock;
use Core\Underscore as _;
use Core\Nullable as nil;
use Underscore\Methods\ArraysMethods;
use Underscore\Methods\StringsMethods;
use Pimple\Container;

class Underscore extends ArraysMethods {
    /**
     * @param mixed $collection
     * @param array|string $key
     * @param mixed $value
     * @return mixed
     */
    static function set($collection, $key, $value) {
        if (is_string($key)) {
            return parent::set($collection, $key, $value);
        }
        $ref = &$collection;
        foreach($key as $k) {
            if (!is_array($ref)) {
                $ref = [];
            }
            $ref = &$ref[$k];
        }
        $ref = $value;
        return $collection;
    }

    static function get($collection, $key, $default = null) {
        // TODO implement array path
        assert(!is_array($key), 'array path $key is not implemented yet');
        return parent::get($collection, $key, $default);
    }

    static function map($array, $f) {
        $ret = [];
        foreach ($array as $k => $v) {
            $ret[$k] = is_string($f) ? self::get($v, $f) : $f($v, $k);
        }
        return $ret;
    }

    static function flatMap($array, $f) {
        $ret = [];
        foreach ($array as $k => $v) {
            // TODO optimize
            $ret = array_merge($ret, is_string($f) ? self::get($v, $f) : $f($v, $k));
        }
        return $ret;
    }

    static function mapKeys($array, $f) {
        $ret = [];
        foreach ($array as $k => $v) {
            $result = is_string($f) ? self::get($v, $f) : $f($v, $k);
            $ret[$result] = $v;
        }
        return $ret;
    }

    static function pick($array, $keys) {
        return array_filter($array, function ($key) use ($keys) {
            return in_array($key, $keys);
        }, ARRAY_FILTER_USE_KEY);
    }

    static function reduce($array, $f, $initial) {
        return array_reduce(array_keys($array), function($ret, $k) use ($array, $f) {
            return $f($ret, $array[$k], $k);
        }, $initial);
    }

    static function filter($array, $pred = null) {
        /** @var callable $pred */
        if ($pred === null) {
            return self::clean($array);
        }
        $ret = array_filter($array, function($key) use ($array, $pred) {
            return $pred($array[$key], $key);
        }, ARRAY_FILTER_USE_KEY);
        // restore indices
        return self::isIndexed($array) ? array_values($ret) : $ret;
    }
    
    static function drop($array, $n) {
        return array_slice($array, $n);
    }

    static function take($array, $n) {
        return array_slice($array, 0, $n);
    }

    static function update($array, $key, $f) {
        $nullable = nil::map(self::get($array, $key), $f);
        return $nullable === null ? $array : self::set($array, $key, $nullable);
    }

    static function isEmpty($x) {
        return is_array($x) && count($x) === 0;
    }

    static function isIndexed(array $array) {
        if (!is_array($array)) return false;
        return isset($array[0]);
    }
    
    static function groupBy($array, $f) {
        $ret = [];
        foreach ($array as $x) {
            $key = is_string($f) ? self::get($x, $f) : $f($x);
            $ret[$key][] = $x;
        }
        return $ret;
    }

    // TODO inconsistent argument ordering
    static function keyBy($by, $array) {
        // TODO add callable support
        assert(is_string($by));
        $ret = [];
        foreach ($array as $x) {
            $ret[$x[$by]] = $x;
        }
        return $ret;
    }

    /**
     * @return array returns an array of [take(n), drop(n)]
     */
    static function splitAt($array, $n) {
        return [self::take($array, $n), self::drop($array, $n)];
    }

    static function findKey($array, $pred) {
        foreach ($array as $key => $value) {
            if ($pred($value, $key)) {
                return $key;
            }
        }
        return null;
    }

    static function identity($x) {
        return $x;
    }

    static function constantly($x) {
        return function() use ($x) {
            return $x;
        };
    }
}

class Nullable {
    static public function get($nullable, $default) {
        return $nullable === null ? $default : $nullable;
    }

    static public function map($nullable, $f) {
        return $nullable !== null ? $f($nullable) : $nullable;
    }

    static function iterator($nullable) {
        return new ArrayIterator($nullable === null ? [] : [$nullable]);
    }
}

class Strings extends StringsMethods {
    static function isEmpty($str) {
        return $str === null || trim($str) === '';
    }

    static function ifEmpty($str, $value) {
        return self::isEmpty($str) ? $value : $str;
    }

    static function contains($s, $subString) {
        return strpos($s, $subString) !== false;
    }

    static function replaceAll($s, $pattern, $replacement) {
        while(preg_match($pattern, $s)) {
            $s = preg_replace($pattern, $replacement, $s);
        }
        return $s;
    }
}

class Env {
    const DEV = 'dev';
    const PROD = 'prod';
    const TEST = 'test';
}

class App {
    private static $instance;
    public $container;

    static function getInstance() {
        if (!isset(self::$instance)) {
            self::$instance = new static();
        }
        return self::$instance;
    }

    function __construct() {
        $this->container = new Container();
    }

    static function env() {
        $app = Nullable::get(Configuration::getValue('app'), []);
        return _::get($app, 'env', Env::PROD);
    }

    static function useBitrixAsset() {
        // use bitrix asset pipeline for non-dev environments
        return self::env() !== Env::DEV;
    }

    function adminEmailMaybe() {
        return Option::get('main', 'email_from', null);
    }

    static function requestUrl() {
        global $APPLICATION;
        $host = _::first(explode(':', $_SERVER['HTTP_HOST']));
        $isHttps = !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443;
        return ($isHttps ? 'https' : 'http').'://'.$host.$APPLICATION->GetCurUri();
    }
}

class View {
    static function asset($path) {
        return SITE_TEMPLATE_PATH.'/build/assets/'.$path;
    }

    static function resize($imageFileArray, $width, $height) {
        $resized = CFile::ResizeImageGet($imageFileArray, [
            'width' => $width,
            'height' => $height
        ]);
        return $resized['src'];
    }

    static function template($path) {
        return SITE_TEMPLATE_PATH.'/'.$path;
    }

    static function path($path) {
        // TODO ad-hoc
        if ($path === '/') return SITE_DIR;
        return SITE_DIR.$path.'/';
    }

    static function includedArea($path) {
        return SITE_DIR.'include/'.$path;
    }

    static function isEmpty($x) {
        return
            $x === null
            || $x === false
            || (is_array($x) && _::isEmpty($x))
            || (is_string($x) && Strings::isEmpty($x));
    }
    
    static function upper($str) {
        return Strings::upper($str);
    }

    static function appendToView($view, $content) {
        global $APPLICATION;
        $APPLICATION->AddViewContent($view, $APPLICATION->GetViewContent($view).$content);
    }

    static function attrs($map) {
        return join(' ', _::map($map, function($value, $key) {
            return $key.'="'.htmlspecialchars($value).'"';
        }));
    }
}

trait NewsListLike {
    /**
     * @param array $element
     * @param CBitrixComponentTemplate $template
     * @return string dom element id
     */
    static function addEditingActions($element, $template) {
        assert(array_key_exists('EDIT_LINK', $element));
        assert(array_key_exists('DELETE_LINK', $element));
        $template->AddEditAction($element['ID'], $element['EDIT_LINK'],
            CIBlock::GetArrayByID($element['IBLOCK_ID'], 'ELEMENT_EDIT'));
        $template->AddDeleteAction($element['ID'], $element['DELETE_LINK'],
            CIBlock::GetArrayByID($element['IBLOCK_ID'], 'ELEMENT_DELETE'),
            ['CONFIRM' => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')]);
        return $template->GetEditAreaId($element['ID']);
    }
}

