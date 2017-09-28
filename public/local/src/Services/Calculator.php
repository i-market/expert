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
        $multiplier = array_reduce(array_values($multipliers), _::operator('*'), 1);
        $price = round($this->pricePerSquareMeter($totalArea), $scale);
        self::$debug['price_per_square_meter'] = $price;
        return $price * $totalArea * $multiplier;
    }

    /** @deprecated moved to Parser */
    static function parseNumericPredicate($str) {
        return Parser::parseNumericPredicate($str);
    }

    protected function debugFactors($field, $factors) {
        self::$debug['factors'][$field] = array_values($factors);
        return $factors;
    }
}