<?php

namespace App\Services;

use App\Services;
use Core\Underscore as _;
use Core\Util;
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

    private static function multiplier($count, $multipliers) {
        return _::get($multipliers, strval($count), function () use ($multipliers, $count) {
            $maxKey = max(...array_keys($multipliers));
            return $count > $maxKey ? $multipliers[$maxKey] : 1;
        });
    }

    private static function totalPrice($prices, $multipliers) {
        return array_reduce($prices, _::operator('+'), 0) * self::multiplier(count($prices), $multipliers);
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
            $maxDuration = max(..._::append(_::pluck($model['SERVICES'], 'DURATION'), 0));
            $prices = array_map('intval', _::pluck($model['SERVICES'], 'PRICE'));
            $state['result'] = [
                'total_price' => self::totalPrice($prices, $dataSet['COUNT_MULTIPLIERS']),
                'duration' => $maxDuration
            ];
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
            'heading' => 'Стоимость и сроки выполнения отдельных видов работ по экспертизе и обследованию. Стоимость и сроки выполнения экспертизы отдельных материалов, деталей, изделий, узлов, конструкций, элементов конструкций и пр.',
            'resultBlock' => $resultBlock,
            'formatPrice' => function($num) { return Util::formatCurrency($num, ['cents' => false]); }
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