<?php

namespace App\Services;

use Core\Underscore as _;
use Core\Nullable as nil;
use Core\Strings as str;

abstract class Calculator {
    // TODO debugging data
    /** put debugging data here */
    static $debug = [];

    abstract function pricePerSquareMeter($sqMeters);

    function totalPrice($totalArea, $multipliers) {
        $scale = 2; // копейки
        $multiplier = array_reduce(array_values($multipliers), function($acc, $x) {
            return $acc + $x;
        }, 0);
        $price = round($this->pricePerSquareMeter($totalArea), $scale);
        return $price * $totalArea * $multiplier;
    }

    /** @deprecated moved to Parser */
    static function parseNumericPredicate($str) {
        return Parser::parseNumericPredicate($str);
    }

    // TODO remove unused
    /** @deprecated */
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