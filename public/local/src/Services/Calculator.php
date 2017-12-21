<?php

namespace App\Services;

use Core\Underscore as _;
use Core\Nullable as nil;
use Core\Strings as str;

abstract class Calculator {
    static $minTotalArea = 900;

    /** put debugging data here */
    static $debug = [];

    abstract function pricePerSquareMeter($sqMeters);

    function totalPrice($_totalArea, $multipliers) {
        $totalArea = max($_totalArea, self::$minTotalArea);
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

    // TODO rename factors
    protected function debugFactors($field, $operator, $factors) {
        self::$debug['factors'][$field] = [
            'operator' => $operator,
            'value' => array_values($factors)
        ];
        return $factors;
    }
}