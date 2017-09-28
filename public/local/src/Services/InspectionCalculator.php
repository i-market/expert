<?php

namespace App\Services;

use App\Services;
use Core\Util as u;
use Exception;
use Core\Underscore as _;

class InspectionCalculator extends Calculator {
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

    // TODO refactor: use model instead of params
    function multipliers($params, $dataSet) {
        $ignoredKeys = ['TOTAL_AREA', 'VOLUME', 'PRICES'];
        if (!$params['HAS_UNDERGROUND_FLOORS']) {
            $ignoredKeys[] = 'UNDERGROUND_FLOORS';
        }
        if ($params['SITE_COUNT'] === 1) {
            $ignoredKeys[] = 'DISTANCE_BETWEEN_SITES';
        }
        $knownKeys = array_keys($dataSet['MULTIPLIERS']);
        $requiredKeys = array_diff($knownKeys, $ignoredKeys);
        $missingKeys = array_diff($requiredKeys, array_keys($params));
        assert(_::isEmpty($missingKeys));
        // TODO refactor mutation, complexity
        $multiplierRec = function($val, $field, $dataSet) use (&$multiplierRec) {
            if ($field === 'FLOORS') {
                $val = u::sum($val);
            } elseif ($field === 'STRUCTURES_TO_INSPECT') {
                // conditional multipliers
                $entities = _::flatMap($dataSet['MULTIPLIERS'][$field], _::identity());
                $multipliers = array_map(function($id) use ($entities, $val) {
                    // TODO refactor: use `findEntity`
                    $entity = _::find($entities, function($entity) use ($id) {
                        return $entity['ID'] === $id;
                    });
                    if (is_array($entity['VALUE'])) {
                        // pick a column based on the number of values
                        return _::find($entity['VALUE'], function($_, $predStr) use ($val) {
                            $countPred = Calculator::parseNumericPredicate($predStr);
                            return $countPred(count($val));
                        });
                    } else {
                        return $entity['VALUE'];
                    }
                }, $val);
                return u::product($this->debugFactors($field, $multipliers));
            } elseif (is_array($val)) {
                $multipliers = array_map(function($v) use (&$multiplierRec, $field, $dataSet) {
                    return $multiplierRec($v, $field, $dataSet);
                }, $val);
                return u::product($this->debugFactors($field, $multipliers));
            }
            $entity = Services::findEntity($field, $val, $dataSet);
            if ($field === 'DOCUMENTS') {
                $multiplier = $entity['VALUE'][true];
            } else {
                $multiplier = $entity['VALUE'];
            }
            assert(is_numeric($multiplier));
            return $multiplier;
        };
        $multipliers = array_reduce($requiredKeys, function($acc, $field) use ($dataSet, $params, $multiplierRec) {
            return _::set($acc, $field, $multiplierRec($params[$field], $field, $dataSet));
        }, []);
        self::$debug['multipliers'] = $multipliers;
        return $multipliers;
    }
}