<?php

namespace App\Calc;

use Respect\Validation\Validator as v;

/** @deprecated use Services namespace */
abstract class AbstractCalc {
    abstract function validateState($state);

    abstract protected function multipliers($state);

    function calculate($state) {
        // TODO better error reporting, refactor assertions
        assert($this->validateState($state));
        $multipliers = $this->multipliers($state);
        assert(v::arrayType()->each(v::numeric()->positive())->validate($multipliers));
        // TODO stub
        $multiplier = array_reduce(array_values($multipliers), function($acc, $x) {
            return $acc * $x;
        }, 1);
        return [
            'MULTIPLIERS' => $multipliers,
            'MULTIPLIER' => $multiplier,
            'TOTAL_PRICE' => 11 * $multiplier // TODO stub
        ];
    }
}