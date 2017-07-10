<?php

namespace App\Services;

use App\Services;
use Core\Underscore as _;
use Core\Util;
use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Validator as v;

class Inspection {
    private static $distanceSpecialValue = '>3km';

    static function initialState($data) {
        return [
            'data_set' => $data['SINGLE_BUILDING'],
            'params' => [],
            'errors' => [],
        ];
    }

    static function state($params, $data) {
        $dataSet = $params['SITE_COUNT'] > 1
            ? $data['MULTIPLE_BUILDINGS']
            : $data['SINGLE_BUILDING'];
        // TODO refactor deref
        $deref = function($val, $k) use (&$deref, $dataSet, $params) {
            if (is_int($val)) {
                return $val;
            } elseif (is_array($val)) {
                return array_map(function($v) use (&$deref, $k) {
                    return $deref($v, $k);
                }, $val);
            } else {
                $entityMaybe = self::findEntity($k, $val, $dataSet);
                return $entityMaybe ? _::pick($entityMaybe, ['ID' , 'NAME']) : $val;
            }
        };
        $errors = self::validateParams($params);
        $state = [
            'data_set' => $dataSet,
            'params' => $params,
            'errors' => $errors
        ];
        if (_::isEmpty($errors)) {
            $model = _::map($params, $deref);
            // TODO extract?
            $model['TIME'] = _::find($dataSet['TIME'], function($_, $rangeText) use ($model) {
                $range = Parser::parseRangeText($rangeText, ['min' => 0, 'max' => PHP_INT_MAX]);
                return Util::inRange($model['TOTAL_AREA'], $range['min'], $range['max']);
            });
            $calculator = new InspectionCalculator();
            // TODO use the model to get multipliers
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
        $resultBlock = isset($state['result'])
            ? [
                'screen' => 'result',
                'result' => [
                    'total_price' => Services::formatTotalPrice($state['result']['total_price']),
                    'summary_values' => [
                        'Срок выполнения' => $state['model']['TIME']
                    ]
                ],
                'params' => _::pick($params, ['EMAIL']),
                'errors' => Services::validateEmail($params)
            ]
            : [
                'screen' => 'hidden',
            ];
        $resultBlock['apiUri'] = '/api/services/inspection/calculator/proposal';
        return [
            'apiEndpoint' => '/api/services/inspection/calculator/calculate',
            'state' => $state,
            'options' => self::options($state['data_set']),
            // TODO move it to the template?
            'heading' => 'Определение стоимости<br> проведения обследования',
            'floorInputs' => $floorInputs,
            'showDistanceSelect' => $siteCount > 1,
            'showDistanceWarning' => $siteCount > 1 && $params['DISTANCE_BETWEEN_SITES'] === self::$distanceSpecialValue,
            'showUndergroundFloors' => $params['HAS_UNDERGROUND_FLOORS'],
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
            'type' => 'inspection',
            // TODO move it to the template?
            'heading' => 'КОММЕРЧЕСКОЕ ПРЕДЛОЖЕНИЕ<br> на проведение обследования',
            'outgoingId' => $outgoingId,
            'date' => Services::formatFullDate($creationDate),
            'endingDate' => Services::formatFullDate($endingDate),
            'totalPrice' => Services::formatTotalPrice($state['result']['total_price']),
            'time' => $state['model']['TIME'],
            'tables' => self::proposalTables($state['model']),
            'output' => array_merge([
                'dest' => 'F'
            ], _::get($opts, 'output' ,[]))
        ];
    }

    static function options($dataSet) {
        // TODO refactor: could be simpler. just use entities directly
        $keys = [
            'SITE_COUNT',
            'DISTANCE_BETWEEN_SITES',
            'LOCATION',
            'USED_FOR',
            'FLOORS',
            'DOCUMENTS',
            'INSPECTION_GOAL',
            'TRANSPORT_ACCESSIBILITY'
        ];
        $options = array_reduce($keys, function($acc, $key) use ($dataSet) {
            return _::set($acc, $key, Services::entities2options([$key], $dataSet));
        }, []);
        $options['STRUCTURES_TO_INSPECT'] = [
            'PACKAGE' => Services::entities2options(['STRUCTURES_TO_INSPECT', 'PACKAGE'], $dataSet),
            'INDIVIDUAL' => Services::entities2options(['STRUCTURES_TO_INSPECT', 'INDIVIDUAL'], $dataSet),
        ];
        $options = _::update($options, 'DISTANCE_BETWEEN_SITES', function($opts) {
            return _::append($opts, [
                'value' => self::$distanceSpecialValue,
                'text' => 'Расстояние между объектами более 3 км'
            ]);
        });
        return $options;
    }

    // TODO refactor: extract common cases
    static function findEntity($field, $val, $dataSet) {
        if (!isset($dataSet['MULTIPLIERS'][$field])) {
            return null;
        }
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

    static function validateParams($params) {
        $validator = v::allOf(
            Services::keyValidator('SITE_COUNT', $params),
            // TODO check for self::$distanceSpecialValue
            Services::keyValidator('DISTANCE_BETWEEN_SITES', $params),
            Services::keyValidator('DESCRIPTION', $params),
            Services::keyValidator('LOCATION', $params),
            Services::keyValidator('USED_FOR', $params),
            Services::keyValidator('TOTAL_AREA', $params),
            Services::keyValidator('VOLUME', $params),
            Services::keyValidator('FLOORS', $params),
            Services::keyValidator('UNDERGROUND_FLOORS', $params),
            v::key('INSPECTION_GOAL', v::notOptional()),
            Services::keyValidator('TRANSPORT_ACCESSIBILITY', $params),
            v::key('STRUCTURES_TO_INSPECT', v::arrayType()->notEmpty()),
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
        $formatRow = _::partialRight([Services::class, 'formatRow'], $model);
        $nameFn = _::partialRight([_::class, 'get'], 'NAME');
        $listFn = function($entities) use ($nameFn) {
            return Services::listHtml(array_map($nameFn, $entities));
        };
        return [
            [
                'heading' => 'Сведения об объекте (объектах) обследования',
                'rows' => array_map($formatRow, [
                    ['Описание объекта (объектов)', 'DESCRIPTION'],
                    ['Количество зданий, сооружений, строений, помещений', 'SITE_COUNT'],
                    ['Местонахождение', 'LOCATION', $nameFn],
                    ['Адрес (адреса)', 'ADDRESS'],
                    ['Назначение', 'USED_FOR', $nameFn],
                    ['Общая площадь', 'TOTAL_AREA'],
                    ['Строительный объем', 'VOLUME'],
                    ['Количество надземных этажей', 'FLOORS', _::partial('join', ', ')],
                    ['Наличие технического подполья, подвала, подземных этажей у одного или нескольких объектов', 'HAS_UNDERGROUND_FLOORS', $nameFn],
                    ['Количество подземных этажей', 'UNDERGROUND_FLOORS'],
                    ['Удаленность объектов друг от друга', 'DISTANCE_BETWEEN_SITES', $nameFn],
                    ['Транспортная доступность', 'TRANSPORT_ACCESSIBILITY', $nameFn],
                    ['Наличие документов', 'DOCUMENTS', $listFn]
                ])
            ],
            [
                'heading' => 'Цели обследования и конструкции подлежащие обследованию',
                'rows' => array_map($formatRow, [
                    ['Цели обследования', 'INSPECTION_GOAL', $nameFn],
                    ['Конструкции подлежащие обследованию', 'STRUCTURES_TO_INSPECT', $listFn]
                ])
            ]
        ];
    }
}