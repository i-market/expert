<?php

namespace App\Services;

use Core\Util as u;
use Exception;

class InspectionCalculator {
    function pricePerSquareMeter($sqMeters) {
        $x = $sqMeters;
        // https://www.wolframalpha.com/input/?i=Plot%5BPiecewise%5B%7B%7B-52.33ln(x)+%2B+561.69,+500+%3C%3D+x+%3C%3D+15000%7D,%7B11766x%5E-0.548,+15000+%3C%3D+x+%3C%3D+30000%7D,%7B59249x%5E-0.703,+30000+%3C%3D+x+%3C%3D+120000%7D,%7B-5.227ln(x)+%2B+77.029,+120000+%3C%3D+x+%3C%3D+200000%7D%7D%5D,+%7Bx,+500,+200000%7D%5D
        if ($x < 500 && $x > 0) {
            return 240;
        } elseif (u::inRange($x, 500, 15000)) {
            // -52.33ln(x) + 561.69
            return -52.33 * log($x) + 561.69;
        } elseif (u::inRange($x, 15000, 30000)) {
            // 11766x^-0.548
            return 11766 * pow($x, -0.548);
        } elseif (u::inRange($x, 30000, 120000)) {
            // 59249x^-0.703
            return 59249 * pow($x, -0.703);
        } elseif (u::inRange($x, 120000, 200000)) {
            // -5.227ln(x) + 77.029
            return -5.227 * log($x) + 77.029;
        } elseif ($x > 200000) {
            return 13.23;
        } else {
            throw new Exception('input is outside of the function domain. perhaps the function is not continuous.');
        }
    }
 }