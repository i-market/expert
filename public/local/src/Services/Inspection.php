<?php

namespace App\Services;

use App\Services;
use Core\Underscore as _;
use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Validator as v;

class Inspection {
    static function findEntity($field, $val, $dataSet) {
        $entities = $dataSet['MULTIPLIERS'][$field];
        if (in_array($field, ['FLOORS', 'SITE_COUNT', 'UNDERGROUND_FLOORS'])) {
            $pred = function($entity) use ($val) {
                $f = Parser::parseNumericPredicate($entity['NAME']);
                return $f($val);
            };
        } elseif (in_array($field, ['HAS_UNDERGROUND_FLOORS'])) {
            $pred = function($entity) use ($val) {
                $bool = Parser::parseBoolean($entity['NAME']);
                return $val === $bool;
            };
        } elseif (in_array($field, ['STRUCTURES_TO_INSPECT'])) {
            $entities = _::flatMap($entities, _::identity());
            $pred = function($entity) use ($val) {
                return $entity['ID'] === $val;
            };
        } else {
            $pred = function($entity) use ($val) {
                return $entity['ID'] === $val;
            };
        }
        return _::find($entities, $pred);
    }

    static function calculate($params, $dataSet) {
        // TODO refactor: unwrap key validator
        // TODO extract
        $validators = [
            'SITE_COUNT' => v::key('SITE_COUNT', v::intType()->positive()),
            'DISTANCE_BETWEEN_SITES' => v::key(
                'DISTANCE_BETWEEN_SITES',
                $params['SITE_COUNT'] === 1
                    ? v::alwaysValid()
                    : v::notOptional()
            ),
            'DESCRIPTION' => v::key('DESCRIPTION', v::stringType()->notEmpty()),
            'LOCATION' => v::key('LOCATION', v::notOptional()),
            'USED_FOR' => v::key('USED_FOR', v::notOptional()),
            'TOTAL_AREA' => v::key('TOTAL_AREA', v::intType()->positive()),
            'VOLUME' => v::key('VOLUME', v::optional(v::intType()->positive())),
            // have to use custom `callback` validator because e.g. built-in `each` validator hides the field name
            'FLOORS' => v::key('FLOORS', v::callback(function($values) {
                return is_array($values) && _::matches($values, function($v) {
                    return v::notOptional()->intType()->validate($v);
                });
            })),
            'UNDERGROUND_FLOORS' => v::key('UNDERGROUND_FLOORS',
                $params['HAS_UNDERGROUND_FLOORS']
                    ? v::intType()->positive()
                    : v::alwaysValid()),
            'DURATION' => v::key('DURATION', v::notOptional()),
            'TRANSPORT_ACCESSIBILITY' => v::key('TRANSPORT_ACCESSIBILITY', v::notOptional()),
            'DOCUMENTS' => v::key('DOCUMENTS', v::arrayType())
        ];
        $validator = v::allOf(
            $validators['SITE_COUNT'],
            $validators['DISTANCE_BETWEEN_SITES'],
            $validators['DESCRIPTION'],
            $validators['LOCATION'],
            $validators['USED_FOR'],
            $validators['TOTAL_AREA'],
            $validators['VOLUME'],
            $validators['FLOORS'],
            $validators['UNDERGROUND_FLOORS'],
            $validators['TRANSPORT_ACCESSIBILITY'],
            v::key('STRUCTURES_TO_INSPECT', v::arrayType()->notEmpty()),
            $validators['DOCUMENTS']
        );
        $errors = [];
        try {
            $validator->assert($params);
        } catch (NestedValidationException $exception) {
            $errors = Services::getMessages($exception);
            // TODO refactor: custom messages
            $errors = _::update($errors, 'STRUCTURES_TO_INSPECT', _::constantly(Services::EMPTY_LIST_MESSAGE));
            $errors = _::update($errors, 'FLOORS', _::constantly('В каждом поле должно быть положительное число.'));
        }
        $state = [
            'params' => $params,
            'errors' => $errors,
            'result' => []
        ];
        $isValid = _::isEmpty($errors);
        if ($isValid) {
            $calculator = new InspectionCalculator();
            $multipliers = $calculator->multipliers($params, $dataSet);
            $totalPrice = $calculator->totalPrice($params['TOTAL_AREA'], $multipliers);
            $state['result'] = [
                'total_price' => $totalPrice
            ];
        }
        return $state;
    }
}