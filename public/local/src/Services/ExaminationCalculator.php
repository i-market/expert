<?php

namespace App\Services;

use Core\Util as u;
use Core\Underscore as _;
use Core\Util;
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

    function goalViews($dataSet) {
        // TODO extract
        $reduceEntities = function($acc, $xs, $f) use (&$reduceEntities) {
            if (isset($xs['ID'])) {
                return $f($acc, $xs);
            } elseif (is_array($xs)) {
                return array_reduce($xs, _::partialRight($reduceEntities, $f), $acc);
            } else {
                return $acc;
            }
        };
        $flatten = function($xs) use ($reduceEntities) {
            return $reduceEntities([], $xs, [_::class, 'append']);
        };
        $numberingFn = function($entity) {
            return array_reverse(array_map('intval', $entity['NUMBERING']));
        };
        $entities = array_filter($flatten($dataSet['MULTIPLIERS']['GOALS']), function($entity) {
            return isset($entity['NUMBERING']) && !_::isEmpty($entity['NUMBERING']);
        });
        $byLevel = $this->groupWithNumbering($entities, $numberingFn, _::identity());
        $views = _::flatMap($byLevel, function($views, $level) {
            return _::map($views, function($view) use ($level) {
                $entities = _::map($view, function($entity) use ($level) {
                    return $level > 0 && _::get($entity, 'RANGE_BOUNDARY') !== null
                        ? _::update($entity, 'VALUE', function($value) use ($entity) {
                            return _::take($value, $entity['RANGE_BOUNDARY'] + 1);
                        })
                        : $entity;
                });
                return [
                    'LEVEL' => $level,
                    // TODO improvement: preserve keys in groupWithNumbering
                    'ENTITIES' => _::keyBy('ID', $entities)
                ];
            });
        });
        return $views;
    }

    function matchingGoalViews($ids, $views) {
        return _::clean(_::map($views, function($view) use ($ids) {
            // TODO improvement: add header keys?
            $multipliers = _::map($ids, function($id) use ($view, $ids) {
                if (!isset($view['ENTITIES'][$id])) {
                    return null;
                }
                $value = $view['ENTITIES'][$id]['VALUE'];
                // test in reverse order, otherwise you won't go past the "range boundary"
                $multiplierMaybe =  _::find(array_reverse($value, true), function($_, $predStr) use ($ids) {
                    $pred = Parser::parseNumericPredicate($predStr);
                    assert($pred !== null);
                    return $pred(count($ids));
                });
                return $multiplierMaybe;
            });
            return in_array(null, $multipliers)
                ? null
                : [
                    'LEVEL' => $view['LEVEL'],
                    'MULTIPLIERS' => $multipliers
                ];
        }));
    }

    function multipliers($model, $dataSet) {
        $ignoredKeys = ['TOTAL_AREA', 'VOLUME', 'PRICES'];
        if (!$model['HAS_UNDERGROUND_FLOORS']) {
            $ignoredKeys[] = 'UNDERGROUND_FLOORS';
        }
        if ($model['SITE_COUNT'] === 1) {
            $ignoredKeys[] = 'DISTANCE_BETWEEN_SITES';
        }
        $knownKeys = array_keys($dataSet['MULTIPLIERS']);
        $requiredKeys = array_diff($knownKeys, $ignoredKeys);
        $missingKeys = array_diff($requiredKeys, array_keys($model));
        assert(_::isEmpty($missingKeys), var_export($missingKeys, true));
        return _::map(_::pick($model, $requiredKeys), function($x, $field) use ($dataSet) {
            if ($field === 'GOALS') {
                $views = $this->goalViews($dataSet);
                $matchingViews = $this->matchingGoalViews($x, $views);
                $deepestView = _::maxBy($matchingViews, _::partialRight([_::class, 'get'], 'LEVEL'));
                return Util::product($deepestView['MULTIPLIERS']);
            } elseif (isset($x['ID'])) {
                // TODO makes sure multiplier exists
                // TODO nested
                return $dataSet['MULTIPLIERS'][$field][$x['ID']]['VALUE'];
            }
            else {
                // TODO non-ref case
                return $x;
            }
        });
    }
}