<?php

namespace Core;

use ArrayIterator;
use Bitrix\Main\Config\Configuration;
use Bitrix\Main\Config\Option;
use Bitrix\Main\Mail\Event;
use CBitrixComponentTemplate;
use CFile;
use CIBlock;
use Closure;
use Core\Underscore as _;
use Underscore\Methods\ArraysMethods;
use Underscore\Methods\StringsMethods;
use Pimple\Container;

class Underscore extends ArraysMethods {
    // TODO implement partialAny
    // https://github.com/lstrojny/functional-php/blob/dac3eea4cf1618d3639d10a655e105de41963649/src/Functional/PartialAny.php
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
        foreach ($key as $k) {
            if (!is_array($ref)) {
                $ref = [];
            }
            $ref = &$ref[$k];
        }
        $ref = $value;
        return $collection;
    }

    /**
     * @param array $collection
     * @param array|string $key
     * @param mixed $default
     * @return mixed
     */
    static function get($collection, $key, $default = null) {
        if (is_string($key)) {
            return parent::get($collection, $key, $default);
        }
        $ret = $collection;
        foreach ($key as $k) {
            if (!isset($ret[$k])) {
                return $default instanceof Closure ? $default() : $default;
            }
            // TODO add object support
            $ret = $ret[$k];
        }
        return $ret;
    }

    // TODO string callables support
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
            $xs = is_string($f) ? self::get($v, $f) : $f($v, $k);
            foreach ($xs as $x) {
                $ret[] = $x;
            }
        }
        return $ret;
    }

    static function flattenDeep($array, callable $pred = null) {
        $pred = $pred ?: self::complement('is_array');
        $reducer = function($acc, $x) use (&$reducer, $pred) {
            if ($pred($x)) {
                return self::append($acc, $x);
            } else {
                return array_merge($acc, array_reduce($x, $reducer, []));
            }
        };
        return array_reduce($array, $reducer, []);
    }

    static function mapKeys($array, $f) {
        $ret = [];
        foreach ($array as $k => $v) {
            $result = is_string($f) ? self::get($v, $f) : $f($v, $k);
            $ret[$result] = $v;
        }
        return $ret;
    }

    // TODO strict by default
    static function pick($array, $keys, $strict = null) {
        return array_filter($array, function ($key) use ($keys, $strict) {
            return in_array($key, $keys, $strict);
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

    static function take($array, $n, $preserveKeys = null) {
        return array_slice($array, 0, $n, $preserveKeys ?: !self::isIndexed($array));
    }

    static function takeWhile($array, $pred) {
        $ret = [];
        foreach ($array as $x) {
            if (!$pred($x)) {
                return $ret;
            }
            $ret[] = $x;
        }
        return $ret;
    }

    static function dropWhile($array, $pred) {
        $from = 0;
        foreach ($array as $k => $v) {
            if (!$pred($v, $k)) {
                return array_slice($array, $from);
            }
            $from += 1;
        }
        return [];
    }

    static function update($array, $key, callable $f) {
        return !self::has($array, $key)
            ? $array
            : self::set($array, $key, $f(self::get($array, $key)));
    }

    static function isEmpty($x) {
        return is_array($x) && count($x) === 0;
    }

    // TODO seems like a bad heuristic to rely on
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

    static function minBy($array, callable $f) {
        return array_reduce($array, function($ret, $x) use ($f) {
            return $ret === null || $f($x) < $f($ret) ? $x : $ret;
        });
    }

    static function maxBy($array, callable $f) {
        return array_reduce($array, function($ret, $x) use ($f) {
            return $ret === null || $f($x) > $f($ret) ? $x : $ret;
        });
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
    
    static function renameKeys(array $map, array $keyMap) {
        return self::mapKeys($map, function($_, $k) use ($keyMap) {
            return self::get($keyMap, $k, $k);
        });
    }

    // TODO refactor: unwrap
    static function identity() {
        return function($x) {
            return $x;
        };
    }

    static function constantly($x) {
        return function() use ($x) {
            return $x;
        };
    }

    // TODO varargs
    static function compose(callable $f, callable $g) {
        return function(...$args) use ($f, $g) {
            return $f($g(...$args));
        };
    }

    /** aka juxtapose */
    static function over($fns, ...$args) {
        $ret = function(...$args) use ($fns) {
            return array_map(function($f) use ($args) {
                return $f(...$args);
            }, $fns);
        };
        return self::isEmpty($args) ? $ret : $ret(...$args);
    }

    static function complement(callable $f) {
        return function(...$args) use ($f) {
            return !$f(...$args);
        };
    }

    static function partial(callable $f, ...$args) {
        return function (...$rest) use ($f, $args) {
            return $f(...array_merge($args, $rest));
        };
    }

    static function partialRight(callable $f, ...$args) {
        return function (...$rest) use ($f, $args) {
            return $f(...array_merge($rest, $args));
        };
    }

    /** useful for inline type hints */
    static function func(callable $x) {
        return $x;
    }

    /**
     * @param $array
     * @param Closure $closure
     * @return mixed
     */
    static function find($array, Closure $closure) {
        return parent::find($array, $closure);
    }

    static function initial($array, $to = 1) {
        // underscore.php returns x for initial([x]) and that's quite a surprise
        if (count($array) === 1 && $to >= 1) {
            return [];
        }
        return parent::initial($array, $to);
    }
}

class Nullable {
    static public function get($nullable, $default) {
        return $nullable === null ? $default : $nullable;
    }

    static public function map($nullable, $f) {
        return $nullable !== null ? $f($nullable) : $nullable;
    }

    /**
     * @param $nullable
     * @return \Iterator
     */
    static function iter($nullable) {
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

    static function capitalize($s) {
        return self::upper(mb_substr($s, 0, 1)) . self::lower(mb_substr($s, 1));
    }
}

// TODO warn when used, so we don't leave it in production code
/** useful for interactive development with a REPL */
trait DynamicMethods {
    static $_instance = [];
    static $_static = [];

    function __call($name, $arguments) {
        if (isset(self::$_instance[$name])) {
            $f = \Closure::bind(self::$_instance[$name], $this, __CLASS__);
            return $f(...$arguments);
        } else {
            throw new \BadMethodCallException("Call to undefined instance method '{$name}'");
        }
    }

    static function __callStatic($name, $arguments) {
        if (isset(self::$_static[$name])) {
            $f = \Closure::bind(self::$_static[$name], null, __CLASS__);
            return $f(...$arguments);
        } else {
            throw new \BadMethodCallException("Call to undefined static method '{$name}'");
        }
    }
}

class Env {
    const DEV = 'dev';
    const PROD = 'prod';
    const TEST = 'test';
}

class App {
    private static $instance;
    /** @deprecated */
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

    function sendMail($eventName, $fields, $siteId, $opts = []) {
        $app = Configuration::getValue('app');
        $emailFromMaybe = _::get($app, 'override_default_email_from');
        if ($emailFromMaybe !== null) {
            $fields['DEFAULT_EMAIL_FROM'] = $emailFromMaybe;
        }
        $event = array_merge([
            'EVENT_NAME' => $eventName,
            'LID' => $siteId,
            'C_FIELDS' => $fields
        ], _::get($opts, 'event', []));
        $result = Event::sendImmediate($event);
        $isSent = $result === Event::SEND_RESULT_SUCCESS;
        if (!$isSent && $this->env() !== Env::DEV) {
            trigger_error("mail sending issue: {$result}", E_USER_WARNING);
        }
        return $result;
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

    static function resize($imageFileArray, $width, $height, $type = BX_RESIZE_IMAGE_PROPORTIONAL) {
        $resized = CFile::ResizeImageGet($imageFileArray, [
            'width' => $width,
            'height' => $height
        ], $type);
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

    static function lower($str) {
        return Strings::lower($str);
    }

    static function capitalize($str) {
        return Strings::capitalize($str);
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

    static function get($collection, $key, $default = null) {
        return _::get($collection, $key, $default);
    }
}

trait NewsListLike {
    /**
     * @param array $el
     * @param CBitrixComponentTemplate $template
     * @return string dom element id
     */
    static function addEditingActions($el, $template, $type = 'element') {
        $isSection = $type === 'section' || isset($el['DEPTH_LEVEL']);
        if (!_::isEmpty(array_diff(['EDIT_LINK', 'DELETE_LINK'] , array_keys($el)))) {
            $links = $isSection ? Util::sectionEditingLinks($el) : Util::elementEditingLinks($el);
            $el = array_merge($el, $links);
        }
        $template->AddEditAction($el['ID'], $el['EDIT_LINK'],
            CIBlock::GetArrayByID($el['IBLOCK_ID'], $isSection ? 'SECTION_EDIT' : 'ELEMENT_EDIT'));
        $template->AddDeleteAction($el['ID'], $el['DELETE_LINK'],
            CIBlock::GetArrayByID($el['IBLOCK_ID'], $isSection ? 'SECTION_DELETE' : 'ELEMENT_DELETE'),
            ['CONFIRM' => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')]);
        return $template->GetEditAreaId($el['ID']);
    }
}

