<?php

namespace App\Services;

use Core\Underscore as _;
use Core\Nullable as nil;

// TODO unused?
class Calculator {
    function parseNumericPredicate($str) {
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

    function conditionalTable($selected, $data) {
        // TODO replace values with pk ids
        $subtableMaybe = _::find($data['SUBTABLES'], function($subtable) use ($selected) {
            return _::matches($selected, function($value) use ($subtable) {
                return _::contains($subtable, $value);
            });
        });
        return nil::get($subtableMaybe, $data['TABLE']);
    }
}