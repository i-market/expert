<?php

namespace App\Services;

use Core\Underscore as _;
use Core\Nullable as nil;
use Core\Strings as str;

// TODO unused?
class Calculator {
    // TODO move to `Parser`?
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