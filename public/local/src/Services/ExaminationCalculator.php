<?php

namespace App\Services;

use Core\Util as u;
use Exception;

class ExaminationCalculator {
    function pricePerSquareMeter($sqMeters) {
        $x = $sqMeters;
        if ($x < 500 && $x > 0) {
            return 80;
        } elseif (u::inRange($x, 500, 10000)) {
            // -12,98ln(x) + 160,97
            return -12.98 * log($x) + 160.97;
        } elseif (u::inRange($x, 10000, 20000)) {
            // -17,92ln(x) + 205,66
            return -17.92 * log($x) + 205.66;
        } elseif (u::inRange($x, 20000, 110000)) {
            // -1.2964744E-23x^5 + 5.055434148345E-18x^4 - 7.86861159561658E-13x^3 + 6.30013330347825E-08x^2 - 0.00272269239487747x + 62.9204545423584
            return (
                -1.2964744E-23 * pow($x, 5)
                + 5.055434148345E-18 * pow($x, 4)
                - 7.86861159561658E-13 * pow($x, 3)
                + 6.30013330347825E-08 * pow($x, 2)
                - 0.00272269239487747 * $x
                + 62.9204545423584
            );
        } elseif (u::inRange($x, 110000, 200000)) {
            // -2.14452054794521E-05x + 12,0204794520548
            return -2.14452054794521E-05 * $x + 12.0204794520548;
        } elseif ($x > 200000) {
            return 7.73;
        } else {
            throw new Exception('input is outside of the function domain. perhaps the function is not continuous.');
        }
    }
 }