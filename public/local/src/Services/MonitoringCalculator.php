<?php

namespace App\Services;

use Core\Util as u;
use Exception;
use Core\Underscore as _;

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

    // TODO no need for bc math?
    function mul($x, $y, $scale) {
        if (extension_loaded('bcmath')) {
            return bcmul($x, $y, $scale);
        } else {
            trigger_error('bcmath extension is not loaded, calculations are going to be less precise', E_USER_NOTICE);
            return $x * $y;
        }
    }

    function multipliers($params, $data) {
        $ignoredKeys = ['TOTAL_AREA', 'PRICES'];
        // TODO double check
        $dataSet = $params['SITE_COUNT'] > 1
            ? $data['MULTIPLE_BUILDINGS']
            : $data['SINGLE_BUILDING'];
        $knownKeys = array_keys($dataSet['MULTIPLIERS']);
        $missingKeys = array_diff($knownKeys, array_keys($params));
        $requiredKeys = array_diff($knownKeys, $ignoredKeys);
        // TODO
        assert(_::matches($requiredKeys, function($k) use ($params) {
            return _::has($params, $k);
        }));
        // TODO conditional multipliers
        $multipliers = array_reduce($requiredKeys, function($acc, $k) use ($dataSet, $params) {
            $v = $params[$k];
            return _::set($acc, $k, $dataSet['MULTIPLIERS'][$k][$v]);
        }, []);
        return $multipliers;
    }

    function totalPrice($totalArea, $multipliers) {
        $scale = 2; // копейки
        $multiplier = array_reduce(array_values($multipliers), function($acc, $x) {
            return $acc + $x;
        }, 0);
        $price = round($this->pricePerSquareMeter($totalArea), $scale);
        return $this->mul($this->mul($price, $totalArea, $scale), $multiplier, $scale);
    }
 }