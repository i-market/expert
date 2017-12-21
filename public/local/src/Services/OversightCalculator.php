<?php

namespace App\Services;

use App\Services;
use Core\Util as u;
use Exception;
use Core\Underscore as _;

class OversightCalculator extends Calculator {
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

    function multipliers($params, $dataSet) {
        $ignoredKeys = ['TOTAL_AREA', 'VOLUME', 'PRICES'];
        // TODO temporarily ignore while distance data is missing
        $ignoredKeys[] = 'DISTANCE_BETWEEN_SITES';
        if (!$params['HAS_UNDERGROUND_FLOORS']) {
            $ignoredKeys[] = 'UNDERGROUND_FLOORS';
        }
        if ($params['SITE_COUNT'] === 1) {
            $ignoredKeys[] = 'DISTANCE_BETWEEN_SITES';
        }
        $knownKeys = array_keys($dataSet['MULTIPLIERS']);
        $requiredKeys = array_diff($knownKeys, $ignoredKeys);
        $missingKeys = array_diff($requiredKeys, array_keys($params));
        assert(_::isEmpty($missingKeys), var_export($missingKeys, true));
        $findMult = function($val, $field) use (&$findMult, $dataSet) {
            if (is_array($val)) {
                $multipliers = array_map(function($v) use (&$findMult, $field) {
                    return $findMult($v, $field);
                }, $val);
                return u::product($this->debugFactors($field, '*', $multipliers));
            }
            $entity = Services::findEntity($field, $val, $dataSet);
            if (in_array($field, ['DOCUMENTS'])) {
                $multiplier = $entity['VALUE'][true];
            } else {
                $multiplier = $entity['VALUE'];
            }
            assert(is_numeric($multiplier));
            return $multiplier;
        };
        $multipliers = _::map(_::pick($params, $requiredKeys, true), function($x, $field) use ($findMult, $dataSet) {
            if ($field === 'FLOORS') {
                return $findMult(u::sum($x), $field);
            } else {
                return $findMult($x, $field);
            }
        });
        self::$debug['multipliers'] = $multipliers;
        return $multipliers;
    }
 }