<?php

namespace App\Services;

use App\Services;
use Core\Util as u;
use Core\Underscore as _;
use FunctionalPHP\Trampoline as t;
use Exception;

class ExaminationCalculator extends Calculator {
    use \Core\DynamicMethods; // TODO tmp for dev
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

    function groupWithNumbering($entities, callable $numberingFn, callable $valueFn) {
        $byLevel = [];
        $prevNum = [];
        // TODO preserve $entities keys?
        foreach ($entities as $entity) {
            $numbering = $numberingFn($entity);
            $value = $valueFn($entity);
            foreach ($numbering as $level => $num) {
                if ($num === 1) {
                    // start a view
                    $byLevel[$level][] = [$value];
                } elseif ($num === $prevNum[$level] + 1) {
                    // TODO refactor
                    $viewIdx = count($byLevel[$level]) - 1;
                    $nextEntityIdx = count($byLevel[$level][$viewIdx]);
                    $byLevel[$level][$viewIdx][$nextEntityIdx] = $value;
                }
                $prevNum[$level] = $num;
            }
        }
        return $byLevel;
    }

    /** transform flat parser output of nesting tables into higher level representation to be used in business logic */
    function goalViews($dataSet) {
        $numberingFn = function($entity) {
            return array_reverse(array_map('intval', $entity['NUMBERING']));
        };
        $isTable = function($x) {
            return _::has(_::first($x), 'ID');
        };
        $tables = _::flattenDeep($dataSet['MULTIPLIERS']['GOALS'], $isTable);
        list($nestingTables, $simpleTables) = _::groupBy($tables, function($entities) {
            return !_::isEmpty(_::get(_::first($entities), 'NUMBERING', [])) ? 0 : 1;
        });
        $byLevel = $this->groupWithNumbering(_::flatMap($nestingTables, _::identity()), $numberingFn, _::identity());
        $nestingViews = _::flatMap($byLevel, function($views, $level) {
            return _::map($views, function($view) use ($level) {
                $entities = _::map($view, function($entity) use ($level) {
                    return $level > 0 && _::get($entity, 'RANGE_BOUNDARY') !== null
                        ? _::update($entity, 'VALUE', function($value) use ($entity) {
                            // nested tables: take columns until "range boundary"
                            return _::take($value, $entity['RANGE_BOUNDARY'] + 1);
                        })
                        : $entity;
                });
                return [
                    'LEVEL' => $level,
                    // TODO refactor: optimize: preserve keys in groupWithNumbering
                    'ENTITIES' => _::keyBy('ID', $entities)
                ];
            });
        });
        $simpleViews = _::map($simpleTables, function($entities) {
            return [
                'LEVEL' => 0,
                'ENTITIES' => $entities
            ];
        });
        return array_merge($nestingViews, $simpleViews);
    }

    function matchingGoalViews($ids, $views) {
        return _::clean(_::map($views, function($view) use ($ids) {
            $multipliers = array_reduce($ids, function($acc, $id) use ($view, $ids) {
                if (!isset($view['ENTITIES'][$id])) {
                    return $acc;
                }
                $inView = array_intersect($ids, array_keys($view['ENTITIES']));
                $value = $view['ENTITIES'][$id]['VALUE'];
                if (is_array($value)) {
                    // test in reverse order, otherwise you won't go past the "range boundary"
                    $multiplierMaybe = _::find(array_reverse($value, true), function($_, $predStr) use ($inView) {
                        $pred = Parser::parseNumericPredicate($predStr);
                        assert($pred !== null);
                        return $pred(count($inView));
                    });
                    if ($multiplierMaybe !== null) {
                        $acc[$id] = $multiplierMaybe;
                    }
                } else {
                    $acc[$id] = $value;
                }
                return $acc;
            }, []);
            return _::isEmpty($multipliers)
                ? null
                : [
                    'LEVEL' => $view['LEVEL'],
                    'MULTIPLIERS' => $multipliers
                ];
        }));
    }

    function reduceGoalViews($ids, $matchingViews) {
        $loop = function($ids, $multipliers = []) use (&$loop, $matchingViews) {
            if (_::isEmpty($ids)) {
                return $multipliers;
            } else {
                $id = _::first($ids);
                $vs = _::filter($matchingViews, function($v) use ($id) {
                    return isset($v['MULTIPLIERS'][$id]);
                });
                $v = _::maxBy($vs, function($v) {
                    // pick the most fitting view
                    return count($v['MULTIPLIERS']) + $v['LEVEL'];
                });
                $remainingIds = array_diff($ids, array_keys($v['MULTIPLIERS']));
                return t\bounce($loop, $remainingIds, $multipliers + $v['MULTIPLIERS']);
            }
        };
        return t\trampoline($loop, $ids);
    }

    function multipliers($params, $dataSet) {
        $ignoredKeys = ['TOTAL_AREA', 'VOLUME', 'PRICES'];
        // TODO temporary ignore while we're missing distance data
        $ignoredKeys[] = 'DISTANCE_BETWEEN_SITES';
        if (!$params['HAS_UNDERGROUND_FLOORS']) {
            $ignoredKeys[] = 'UNDERGROUND_FLOORS';
        }
        if (!$params['NEEDS_VISIT']) {
            $ignoredKeys = array_merge($ignoredKeys, [
                'LOCATION',
                'ADDRESS',
                'DISTANCE_BETWEEN_SITES',
                'TRANSPORT_ACCESSIBILITY'
            ]);
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
                return u::product($multipliers);
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
        return _::map(_::pick($params, $requiredKeys, true), function($x, $field) use ($findMult, $dataSet) {
            if ($field === 'GOALS') {
                $views = $this->goalViews($dataSet);
                $matchingViews = $this->matchingGoalViews($x, $views);
                return u::product($this->reduceGoalViews($x, $matchingViews));
            } elseif ($field === 'FLOORS') {
                return $findMult(u::sum($x), $field);
            } else {
                return $findMult($x, $field);
            }
        });
    }
}