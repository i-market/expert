<?php

namespace App\Services;

use Core\Util as u;
use Exception;

class MonitoringCalculator {
    function pricePerSquareMeter($sqMeters) {
        $x = $sqMeters;
        if ($x < 500 && $x > 0) {
            return 120;
        } elseif (u::inRange($x, 500, 20000)) {
            // 8612.12722859096x^-0.68749765493319
            return 8612.12722859096 * pow($x, -0.68749765493319);
        } elseif (u::inRange($x, 20000, 30000)) {
            // 356.48x^-0.367
            return 356.48 * pow($x, -0.367);
        } elseif (u::inRange($x, 30000, 200000)) {
            // 0.000000000111296332998882x^2 - 0.0000555777785347687x + 9.66440207360428
            return 0.000000000111296332998882 * pow($x, 2) - 0.0000555777785347687 * $x + 9.66440207360428;
        } elseif ($x > 200000) {
            return 3;
        } else {
            throw new Exception('input is outside of the function domain. perhaps the function is not continuous.');
        }
    }
 }