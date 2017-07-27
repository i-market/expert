<?php

namespace App\Services;

use App\Services;
use Core\Underscore as _;
use Core\Util;
use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Validator as v;

class Examination {
    use \Core\DynamicMethods; // TODO tmp for dev
    static function initialState($data) {
        return [
            'data_set' => $data['SINGLE_BUILDING'],
            'params' => [],
            'errors' => [],
        ];
    }

    static function state($params, $action, $data, $validate = true) {
        $dataSet = $params['SITE_COUNT'] > 1
            ? $data['MULTIPLE_BUILDINGS']
            : $data['SINGLE_BUILDING'];
        $state = [
            'data_set' => $dataSet,
            'params' => $params,
            'errors' => [],
            'action' => $action
        ];
        if (!$validate) {
            return $state;
        }
        $state['errors'] = self::validateParams($params);
        if (_::isEmpty($state['errors'])) {
            $model = Services::dereferenceParams($params, $dataSet, [Services::class, 'findEntity']);
            // TODO extract?
            $model['TIME'] = _::find($dataSet['TIME'], function($_, $rangeText) use ($model) {
                $range = Parser::parseRangeText($rangeText, ['min' => 0, 'max' => PHP_INT_MAX]);
                return Util::inRange($model['TOTAL_AREA'], $range['min'], $range['max']);
            });
            $calculator = new ExaminationCalculator();
            // TODO !!!!
            // TODO use the model to get multipliers
//            $multipliers = $calculator->multipliers($params, $dataSet);
//            $totalPrice = $calculator->totalPrice($model['TOTAL_AREA'], $multipliers);
            $state['model'] = $model;
//            $state['result'] = [
//                'total_price' => $totalPrice
//            ];
        }
        return $state;
    }

    static function calculatorContext($state) {
        $params = $state['params'];
        $siteCount = _::get($params, 'SITE_COUNT', 1);
        $floorInputs = array_map(function($num) {
            return ['label' => "Строение {$num}"];
        }, range(1, $siteCount));
        $resultBlock = Services::resultBlockContext($state, '/api/services/examination/calculator/send_proposal', [
            // TODO
//            'Срок выполнения' => $state['model']['TIME']
        ]);
        return [
            'apiEndpoint' => '/api/services/examination/calculator/calculate',
            'state' => $state,
            'options' => self::options($state['data_set']['MULTIPLIERS']),
            // TODO move it to the template?
            'heading' => 'Определение стоимости<br> проведения экспертизы',
            'floorInputs' => $floorInputs,
            'showDistanceSelect' => $siteCount > 1,
            'showDistanceWarning' => $siteCount > 1 && $params['DISTANCE_BETWEEN_SITES'] === Services::$distanceSpecialValue,
            'showUndergroundFloors' => $params['HAS_UNDERGROUND_FLOORS'],
            'resultBlock' => $resultBlock
        ];
    }

    static function proposalParams($state, $outgoingId, $opts = []) {
    }

    static function options($entities) {
        $opts = Services::entities2options($entities);
        $mergeOptions = function($uiElements) {
            return array_reduce($uiElements, function($acc, $x) {
                $type = function($x) { return isset($x['type']) ? $x['type'] : null; };
                return _::map([_::last($acc), $x], $type) === ['options', 'options']
                    ? _::append(_::initial($acc), _::update(_::last($acc), 'value', _::partialRight('array_merge', $x['value'])))
                    : _::append($acc, $x);
            }, []);
        };
        $isOptions = function($x) {
            return _::has(_::first($x), 'value');
        };
        $wrapOptions = function($options) {
            return [
                'type' => 'options',
                'value' => _::map($options, function($opt) {
                    return ['type' => 'option', 'value' => $opt];
                })
            ];
        };
        $flatten = function($depth, $v, $k) use (&$flatten, $isOptions, $wrapOptions) {
            $subsection = ['type' => 'subsection', 'value' => $k, 'depth' => $depth];
            if ($isOptions($v)) {
                if ($depth === 0) {
                    return [$subsection, $wrapOptions($v)];
                } else {
                    return [_::update($wrapOptions($v), 'value', _::partialRight([_::class, 'prepend'], $subsection))];
                }
            } else {
                return _::prepend(_::flatMap($v, _::partial($flatten, $depth + 1)), $subsection);
            }
        };
        $uiElementsByRoot = _::map($opts['GOALS'], function($root) use ($isOptions, $wrapOptions, $flatten) {
            if ($isOptions($root)) {
                return [$wrapOptions($root)];
            } else {
                return _::flatMap($root, _::partial($flatten, 0));
            }
        });
        $goalsFilter = _::map(array_keys($opts['GOALS']), function($name, $idx) {
            return ['value' => $idx, 'text' => $name];
        });
        return array_merge($opts, [
            'GOALS_FILTER' => $goalsFilter,
            // keys should match goals_filter values
            'GOAL_UI_ELEMENTS' => array_values(array_map($mergeOptions, $uiElementsByRoot))
        ]);
    }

    static function validateParams($params) {
        // TODO check for self::$distanceSpecialValue
        $validator = v::allOf(
            Services::keyValidator('SITE_COUNT', $params),
            Services::keyValidator('DESCRIPTION', $params),
            Services::keyValidator('SITE_CATEGORY', $params),
            Services::keyValidator('USED_FOR', $params),
            Services::keyValidator('TOTAL_AREA', $params),
            Services::keyValidator('VOLUME', $params),
            Services::keyValidator('FLOORS', $params),
            Services::keyValidator('UNDERGROUND_FLOORS', $params),
            !$params['NEEDS_VISIT']
                ? v::alwaysValid()
                : v::allOf(
                Services::keyValidator('LOCATION', $params),
                v::key('ADDRESS', v::stringType()->notEmpty()),
                Services::keyValidator('DISTANCE_BETWEEN_SITES', $params),
                Services::keyValidator('TRANSPORT_ACCESSIBILITY', $params)
            ),
            v::key('GOALS', v::arrayType()->notEmpty()),
            Services::keyValidator('DOCUMENTS', $params)
        );
        $errors = [];
        try {
            $validator->assert($params);
        } catch (NestedValidationException $exception) {
            $errors = Services::getMessages($exception);
            // TODO refactor: custom messages
            $errors = _::update($errors, 'STRUCTURES_TO_INSPECT', _::constantly(Services::EMPTY_LIST_MESSAGE));
        }
        return $errors;
    }

    static function proposalTables($model) {
    }
}