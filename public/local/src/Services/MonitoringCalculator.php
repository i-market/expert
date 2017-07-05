<?php

namespace App\Services;

use Core\Util as u;
use Exception;
use Core\Underscore as _;

class MonitoringCalculator extends Calculator {
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
            } elseif ($field === 'STRUCTURES_TO_MONITOR') {
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
                return u::product($multipliers);
            } elseif (is_array($val)) {
                $multipliers = array_map(function($v) use (&$multiplierRec, $field, $dataSet) {
                    return $multiplierRec($v, $field, $dataSet);
                }, $val);
                return u::product($multipliers);
            }
            $entity = Monitoring::findEntity($field, $val, $dataSet);
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
        return $multipliers;
    }
 }