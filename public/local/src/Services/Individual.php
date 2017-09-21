<?php

namespace App\Services;

use App\Services;
use Core\Underscore as _;
use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Validator as v;

class Individual {
    static $priceUnit = 'руб.';

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
            ? ['Продолжительность выполнения работ' => Services::formatDurationWorkdays(strval($state['result']['duration']))]
            : [];
        $resultBlock = Services::resultBlockContext($state, '/api/services/individual/calculator/send_proposal', self::$priceUnit, $summaryValues);
        return [
            'apiEndpoint' => '/api/services/individual/calculator/calculate',
            'state' => $state,
            'options' => self::options($state['data_set']['ENTITIES']),
            'heading' => 'Определение стоимости<br> выполнения отдельных видов работ',
            'resultBlock' => $resultBlock
        ];
    }

    static function proposalParams($state, $outgoingId, $opts = []) {
        assert(isset($state['result']));
        $creationDate = isset($opts['creation_date'])
            ? $opts['creation_date']
            : new \DateTime();
        $d = clone $creationDate;
        $endingDate = $d->add(new \DateInterval('P3M'));
        return [
            'type' => 'individual',
            'heading' => 'КОММЕРЧЕСКОЕ ПРЕДЛОЖЕНИЕ<br> на выполнение отдельных видов работ',
            'outgoingId' => $outgoingId,
            'date' => Services::formatFullDate($creationDate),
            'endingDate' => Services::formatFullDate($endingDate),
            'totalPrice' => Services::formatTotalPrice($state['result']['total_price'], self::$priceUnit),
            'duration' => Services::formatDurationWorkdays($state['result']['duration']),
            'tables' => self::proposalTables($state['model']),
            'output' => array_merge([
                'dest' => 'F'
            ], _::get($opts, 'output' ,[]))
        ];
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
        return [
            [
                'heading' => 'Состав работ',
                'header' => ['Вид работ', 'Цель работ', 'Единица измерения', 'Стоимость, руб.'],
                'rows' => _::map($model['SERVICES'], function($entity) {
                    return _::map(['NAME', 'GOAL', 'UNIT', 'PRICE'], function($k) use ($entity) {
                        return Services::orNotSpecified($entity[$k]);
                    });
                })
            ]
        ];
    }
}