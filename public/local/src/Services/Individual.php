<?php

namespace App\Services;

use App\Services;
use Core\Underscore as _;
use Core\Util;
use Core\Strings as str;
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

    static function state($params, $action, $data, $_opts = []) {
        $opts = array_merge(['validate' => true, 'result' => true], $_opts);
        $dataSet = $data['MULTIPLE_BUILDINGS'];
        $state = [
            'data_set' => $dataSet,
            'params' => $params,
            'errors' => [],
            'action' => $action
        ];
        if (!$opts['validate']) {
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
            if ($opts['result']) {
                $state['result'] = [
                    'total_price' => self::totalPrice($prices, $dataSet['COUNT_MULTIPLIERS']),
                    'duration' => $maxDuration
                ];
            }
        }
        return $state;
    }

    static function calculatorContext($state) {
        $summaryValues = $state['result']['duration'] > 0
            ? ['Продолжительность выполнения работ' => Services::formatDurationWorkdays(strval($state['result']['duration']))]
            : [];
        $resultBlock = array_merge(Services::resultBlockContext($state, '/api/services/individual/calculator/send_proposal', self::$priceUnit, $summaryValues), [
            'hasDiscount' => 1 > self::multiplier(count($state['model']['SERVICES']), $state['data_set']['COUNT_MULTIPLIERS'])
        ]);
        return [
            'apiEndpoint' => '/api/services/individual/calculator/calculate',
            'state' => $state,
            'options' => self::options($state),
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

    static function options($state) {
        $order = _::filter(explode(',', _::get($state, 'params.order')), _::complement([str::class, 'isEmpty']));
        $services = _::keyBy('ID', $state['model']['SERVICES']);
        // services by selection order
        $selected = _::map($order, function ($id) use ($services) {
            return $services[$id];
        });
        $mults = $state['data_set']['COUNT_MULTIPLIERS'];

        // TODO tmp
        if (isset($_REQUEST['alt'])) {
            $_SESSION['alt'] = $_REQUEST['alt'];
        }
        if (isset($_SESSION['alt'])) {
            // this version of `nextPrice` doesn't change already selected prices, but can return a negative price
            $nextPrice = function ($price, $selected) use ($mults) {
                return (
                    self::totalPrice(_::append($selected, $price), $mults)
                    - self::totalPrice($selected, $mults)
                );
            };
            $selectedNextPrices = _::reduce($selected, function ($acc, $ent, $idx) use ($nextPrice, $selected) {
                // TODO refactor: inline into `updatePrice`
                return _::set($acc, $ent['ID'], $nextPrice($ent['PRICE'], _::pluck(_::take($selected, $idx), 'PRICE')));
            }, []);
        } else {
            // this version changes already selected prices
            $nextPrice = function ($price, $selected) use ($mults) {
                return $price * self::multiplier(count($selected) + 1, $mults);
            };
            $selectedNextPrices = _::reduce($selected, function ($acc, $ent) use ($nextPrice, $selected) {
                // TODO refactor: inline into `updatePrice`
                $notCurr = function ($e) use ($ent) { return $e['ID'] !== $ent['ID']; };
                return _::set($acc, $ent['ID'], $nextPrice($ent['PRICE'], _::pluck(_::filter($selected, $notCurr), 'PRICE')));
            }, []);
        }

        $updatePrice = function ($ent) use ($selected, $nextPrice, $selectedNextPrices) {
            // TODO extract predicate, see also the template
            if ($ent['PRICE'] == 1) {
                return $ent;
            }
            return _::update($ent, 'PRICE', function ($price) use ($selected, $ent, $nextPrice, $selectedNextPrices) {
                if (_::isEmpty($selectedNextPrices)) {
                    return $price;
                }
                return _::get($selectedNextPrices, $ent['ID'], function () use ($ent, $selected, $nextPrice) {
                    return $nextPrice($ent['PRICE'], _::pluck($selected, 'PRICE'));
                });
            });
        };

        $isEntities = function($x) {
            return _::has(_::first($x), 'ID');
        };
        $wrapEntities = function($entities) use ($updatePrice) {
            return ['type' => 'entities', 'value' => array_map($updatePrice, $entities)];
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
        $roots = $state['data_set']['ENTITIES'];
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