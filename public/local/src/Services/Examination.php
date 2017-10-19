<?php

namespace App\Services;

use App\App;
use App\Components;
use App\Services;
use Core\Underscore as _;
use Core\Util;
use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Validator as v;

class Examination {
    static $priceUnit = 'руб.';

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

            // TODO refactor hack: move fixed prices out of `multipliers`, see parser
            $fixedPriceEntities = array_filter($model['GOALS'], function($entity) {
                return _::get($entity, 'IS_FIXED_PRICE', false);
            });

            $calculator = new ExaminationCalculator();
            $multipliers = $calculator->multipliers(_::update($params, 'GOALS', function($ids) use ($fixedPriceEntities) {
                return array_diff($ids, _::pluck($fixedPriceEntities, 'ID'));
            }), $dataSet);
            $totalPrice = $calculator->totalPrice($model['TOTAL_AREA'], $multipliers);

            $totalPrice = array_reduce($fixedPriceEntities, function($acc, $entity) use ($dataSet) {
                $multEntity = Services::findEntity2('GOALS', $entity['ID'], $dataSet['MULTIPLIERS']['GOALS']);
                assert($multEntity !== null);
                return $acc + $multEntity['VALUE'];
            }, $totalPrice);

            $state['model'] = $model;
            $state['result'] = [
                'total_price' => $totalPrice
            ];
        }
        return $state;
    }

    static function calculatorContext($state, $opts = []) {
        $params = $state['params'];
        $siteCount = _::get($params, 'SITE_COUNT', 1);
        $floorInputs = array_map(function($num) {
            return ['label' => "Строение {$num}"];
        }, range(1, $siteCount));
        $resultBlock = Services::resultBlockContext($state, '/api/services/examination/calculator/send_proposal', self::$priceUnit, [
            'Срок выполнения' => $state['model']['TIME']
        ]);
        if (isset($state['errors']['GOALS'])) {
            $state['errors']['GOALS_FILTER'] = $state['errors']['GOALS'];
        }
        $services = !_::get($opts, 'render_modals', true)
            ? []
            : array_map(function($service) {
                // TODO optimize
                $data = Services::data('examination');
                $ctx = ExaminationRequest::context(ExaminationRequest::initialState($data), $service);
                $form = Components::renderServiceForm('partials/service_forms/examination_form', $ctx);
                return array_merge($service, ['form' => $form]);
            }, _::pick(Services::services(), ['examination']));
        return [
            'services' => $services,
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
        assert(isset($state['result']));
        $creationDate = isset($opts['creation_date'])
            ? $opts['creation_date']
            : new \DateTime();
        $d = clone $creationDate;
        $endingDate = $d->add(new \DateInterval('P3M'));
        return [
            'type' => 'examination',
            'heading' => 'КОММЕРЧЕСКОЕ ПРЕДЛОЖЕНИЕ<br> на проведение экспертизы',
            'outgoingId' => $outgoingId,
            'date' => Services::formatFullDate($creationDate),
            'endingDate' => Services::formatFullDate($endingDate),
            'totalPrice' => Services::formatTotalPrice($state['result']['total_price'], self::$priceUnit),
            'time' => $state['model']['TIME'],
            'tables' => self::proposalTables($state['model']),
            'output' => array_merge([
                'dest' => 'F'
            ], _::get($opts, 'output' ,[]))
        ];
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
        $goalsFilter = _::map(array_keys($opts['GOALS']), function($_name, $idx) {
            $name = App::getInstance()->isDebugEnabled() ?
                $_name
                : Parser::stripNumbering($_name);
            return ['value' => strval($idx + 1), 'text' => $name];
        });
        return array_merge($opts, [
            'GOALS_FILTER' => $goalsFilter,
            'GOAL_UI_ELEMENTS' => array_combine(
                _::pluck($goalsFilter, 'value'),
                array_map($mergeOptions, $uiElementsByRoot)
            ),
        ]);
    }

    static function validateParams($params) {
        // TODO check for self::$distanceSpecialValue?
        $validator = v::allOf(
            Services::keyValidator('SITE_COUNT', $params),
            Services::keyValidator('DESCRIPTION', $params),
            Services::keyValidator('SITE_CATEGORY', $params),
            $params['SITE_CATEGORY'] == '3'
                ? v::key('USED_FOR', v::alwaysValid())
                : Services::keyValidator('USED_FOR', $params),
            Services::keyValidator('TOTAL_AREA', $params),
            Services::keyValidator('VOLUME', $params),
            Services::keyValidator('FLOORS', $params),
            Services::keyValidator('UNDERGROUND_FLOORS', $params),
            !$params['NEEDS_VISIT']
                ? v::alwaysValid()
                : v::allOf(
                    Services::keyValidator('LOCATION', $params),
                    v::key('ADDRESS', v::stringType()),
                    Services::keyValidator('DISTANCE_BETWEEN_SITES', $params, false),
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
            $errors = _::update($errors, 'GOALS', _::constantly(Services::EMPTY_LIST_MESSAGE));
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
                'heading' => 'Сведения об объекте (объектах) экспертизы',
                'rows' => array_map($formatRow, [
                    ['Описание объекта (объектов)', 'DESCRIPTION'],
                    ['Количество объектов', 'SITE_COUNT'],
                    ['Категория предметов экспертизы', 'SITE_CATEGORY', $nameFn],
                    ['Необходимость выезда на объект(ы)', 'NEEDS_VISIT', $nameFn],
                    ['Местонахождение', 'LOCATION', $nameFn],
                    ['Адрес (адреса)', 'ADDRESS'],
                    ['Назначение объекта (объектов)', 'USED_FOR', $nameFn],
                    ['Общая площадь объекта (объектов)', 'TOTAL_AREA'],
                    ['Общий строительный объем объекта (объектов)', 'VOLUME'],
                    ['Количество надземных этажей', 'FLOORS', _::partial('join', ', ')],
                    ['Наличие технического подполья, подвала, подземных этажей у одного или нескольких объектов', 'HAS_UNDERGROUND_FLOORS', $nameFn],
                    ['Количество подземных этажей', 'UNDERGROUND_FLOORS'],
                    ['Удаленность объектов друг от друга', 'DISTANCE_BETWEEN_SITES', $nameFn],
                    ['Транспортная доступность', 'TRANSPORT_ACCESSIBILITY', $nameFn],
                    ['Наличие документов', 'DOCUMENTS', $listFn]
                ])
            ],
            [
                'heading' => 'Цели и задачи экспертизы',
                'rows' => [
                    [$listFn($model['GOALS'])]
                ]
            ]
        ];
    }
}