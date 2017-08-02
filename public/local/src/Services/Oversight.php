<?php

namespace App\Services;

use App\Services;
use Core\Underscore as _;
use Core\Util;
use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Validator as v;

class Oversight {
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
            $calculator = new OversightCalculator();
            $multipliers = $calculator->multipliers($params, $dataSet);
            $totalPrice = $calculator->totalPrice($model['TOTAL_AREA'], $multipliers);
            $state['model'] = $model;
            $state['result'] = [
                'total_price' => $totalPrice
            ];
        }
        return $state;
    }

    static function calculatorContext($state) {
        $params = $state['params'];
        $siteCount = _::get($params, 'SITE_COUNT', 1);
        $floorInputs = array_map(function($num) {
            return ['label' => "Строение {$num}"];
        }, range(1, $siteCount));
        $resultBlock = Services::resultBlockContext($state, '/api/services/oversight/calculator/send_proposal', [
            // TODO duration?
        ]);
        return [
            'apiEndpoint' => '/api/services/oversight/calculator/calculate',
            'state' => $state,
            'options' => self::options($state['data_set']['MULTIPLIERS']),
            'heading' => 'Определение стоимости<br> проведения обследования',
            'floorInputs' => $floorInputs,
            'showDistanceSelect' => $siteCount > 1,
            'showDistanceWarning' => $siteCount > 1 && $params['DISTANCE_BETWEEN_SITES'] === Services::$distanceSpecialValue,
            'showUndergroundFloors' => $params['HAS_UNDERGROUND_FLOORS'],
            'resultBlock' => $resultBlock
        ];
    }

    static function proposalParams($state, $outgoingId, $opts = []) {
        // TODO fn
    }

    static function options($entities) {
        $options = _::update(Services::entities2options($entities), 'DISTANCE_BETWEEN_SITES', function($opts) {
            return _::append($opts, [
                'value' => Services::$distanceSpecialValue,
                'text' => 'Расстояние между объектами более 3 км'
            ]);
        });
        return _::update($options, 'DURATION', function($opts) {
            return array_map(function($opt) {
                return _::update($opt, 'text', [Services::class, 'formatDuration']);
            }, $opts);
        });
    }

    static function validateParams($params) {
        $requiredId = v::notOptional();
        $validator = v::allOf(
            Services::keyValidator('SITE_COUNT', $params),
            // TODO check for self::$distanceSpecialValue
            Services::keyValidator('DISTANCE_BETWEEN_SITES', $params),
            Services::keyValidator('DESCRIPTION', $params),
            Services::keyValidator('LOCATION', $params),
            Services::keyValidator('USED_FOR', $params),
            v::key('CONSTRUCTION_TYPE', $requiredId),
            v::key('CONSTRUCTION_PHASE', $requiredId),
            Services::keyValidator('TOTAL_AREA', $params),
            Services::keyValidator('VOLUME', $params),
            Services::keyValidator('FLOORS', $params),
            Services::keyValidator('UNDERGROUND_FLOORS', $params),
            Services::keyValidator('DURATION', $params),
            Services::keyValidator('TRANSPORT_ACCESSIBILITY', $params),
            Services::keyValidator('DOCUMENTS', $params)
        );
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