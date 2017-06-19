<?php

namespace Core;

use Core\Underscore as _;

class Hierarchy {
    static function make() {
        return [
            'parents' => [],
            'ancestors' => [],
            'descendants' => []
        ];
    }

    // https://github.com/clojure/clojure/blob/d7e92e5d71ca2cf4503165e551859207ba709ddf/src/clj/clojure/core.clj#L5530
    static function derive(array $h, $tag, $parent) {
        assert($tag !== $parent);
        $tf = function($m, $source, $sources, $target, $targets) {
            return _::reduce(_::prepend(_::get($sources, $source, []), $source),
                function($ret, $k) use ($target, $targets) {
                    return _::set($ret, $k, _::reduce(_::prepend(_::get($targets, $target, []), $target),
                        function($acc, $x) {
                            return _::append($acc, $x);
                        }, _::get($targets, $k, []))); // TODO default value should be a set
                }, $m);
        };
        if (_::contains(_::get($h['parents'], $tag, []), $parent)) {
            return $h;
        }
        if (_::contains(_::get($h['ancestors'], $tag, []), $parent)) {
            throw new \Exception("${tag} already has {$parent} as ancestor");
        }
        if (_::contains(_::get($h['ancestors'], $parent, []), $tag)) {
            throw new \Exception("Cyclic derivation: {$parent} has {$tag} as ancestor");
        }
        return [
            'parents' => _::set($h['parents'], $tag, _::append(_::get($h['parents'], $tag, []), $parent)), // TODO default value should be a set
            'ancestors' => $tf($h['ancestors'], $tag, $h['descendants'], $parent, $h['ancestors']),
            'descendants' => $tf($h['descendants'], $parent, $h['ancestors'], $tag, $h['descendants'])
        ];
    }

    // https://github.com/clojure/clojure/blob/d7e92e5d71ca2cf4503165e551859207ba709ddf/src/clj/clojure/core.clj#L5468
    static function isa(array $h, $child, $parent) {
        return (
            $child === $parent
            || _::contains(_::get($h['ancestors'], $child, []), $parent)
            // TODO ... (and (vector? parent) (vector? child) ...)
        );
    }
}