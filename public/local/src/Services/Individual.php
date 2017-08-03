<?php

namespace App\Services;

use App\Services;
use Core\Underscore as _;
use Core\Util;
use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Validator as v;

class Individual {
    use \Core\DynamicMethods; // TODO tmp for dev
    static function initialState($data) {
        return [
            'data_set' => $data['MULTIPLE_BUILDINGS'],
            'params' => [],
            'errors' => [],
        ];
    }

    static function state($params, $action, $data, $validate = true) {
        $dataSet = $data['MULTIPLE_BUILDINGS'];
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
            $findEntity = function($field, $val, $dataSet) {
                return Services::findEntity2($field, $val, $dataSet['ENTITIES']);
            };
            $model = Services::dereferenceParams($params, $dataSet, $findEntity);
            $state['model'] = $model;
            $state['result'] = array_reduce($model['SERVICES'], function($acc, $service) {
                $x = _::update($acc, 'total_price', function($price) use ($service) {
                    return $price + intval($service['PRICE']);
                });
                return _::update($x, 'duration', _::partial('max', intval($service['DURATION'])));
            }, ['total_price' => 0, 'duration' => 0]);
        }
        return $state;
    }

    static function calculatorContext($state) {
        $summaryValues = $state['result']['duration'] > 0
            ? ['Продолжительность выполнения работ' => Services::formatDuration(strval($state['result']['duration']))]
            : [];
        $resultBlock = Services::resultBlockContext($state, '/api/services/individual/calculator/send_proposal', $summaryValues);
        return [
            'apiEndpoint' => '/api/services/individual/calculator/calculate',
            'state' => $state,
            'options' => self::options($state['data_set']['ENTITIES']),
            // TODO !!! heading
            'heading' => 'Определение стоимости',
            'resultBlock' => $resultBlock
        ];
    }

    static function proposalParams($state, $outgoingId, $opts = []) {
        // TODO fn
    }

    static function options($roots) {
        $isEntities = function($x) {
            return _::has(_::first($x), 'ID');
        };
        $wrapEntities = function($entities) {
            return ['type' => 'entities', 'value' => $entities];
        };
        $f = function($depth, $xs, $k) use (&$f, $isEntities, $wrapEntities) {
            if ($isEntities($xs)) {
                if ($depth === 0) {
                    return [$wrapEntities($xs)];
                } else {
                    return [
                        ['type' => 'subsection', 'value' => $k],
                        $wrapEntities($xs)
                    ];
                }
            } else {
                return _::flatMap($xs, _::partial($f, $depth + 1));
            }
        };
        return _::map($roots, _::partial($f, 0));
    }

    static function validateParams($params) {
        $validator = v::key('SERVICES', v::arrayType()->notEmpty());
        $errors = [];
        try {
            $validator->assert($params);
        } catch (NestedValidationException $exception) {
            $errors = Services::getMessages($exception);
        }
        return $errors;
    }

    static function proposalTables($model) {
        // TODO fn
    }
}