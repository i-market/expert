<?php

namespace App\Services;

use App\Services;
use Core\Underscore as _;
use DateInterval;
use DateTime;
use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Validator as v;

class Monitoring {
    // TODO deprecated
    private $repo;

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
            v::key('MONITORING_GOAL', v::notOptional()),
            Services::keyValidator('DURATION', $params),
            Services::keyValidator('TRANSPORT_ACCESSIBILITY', $params),
            v::key('STRUCTURES_TO_MONITOR', v::arrayType()->notEmpty()),
            Services::keyValidator('DOCUMENTS', $params)
        );
        $errors = [];
        try {
            $validator->assert($params);
        } catch (NestedValidationException $exception) {
            $errors = Services::getMessages($exception);
            // TODO refactor: custom messages
            $errors = _::update($errors, 'STRUCTURES_TO_MONITOR', _::constantly(Services::EMPTY_LIST_MESSAGE));
        }
        return $errors;
    }

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
            $calculator = new MonitoringCalculator();
            $multipliers = $calculator->multipliers($params, $dataSet);
            $totalPrice = $calculator->totalPrice($model['TOTAL_AREA'], $multipliers);
            $state['model'] = $model;
            $state['result'] = [
                'total_price' => $totalPrice
            ];
        }
        return $state;
    }

    static function options($entities) {
        return _::update(Services::entities2options($entities), 'DISTANCE_BETWEEN_SITES', function($opts) {
            return _::append($opts, [
                'value' => Services::$distanceSpecialValue,
                'text' => 'Расстояние между объектами более 3 км'
            ]);
        });
    }

    static function calculatorContext($state) {
        $params = $state['params'];
        $siteCount = _::get($params, 'SITE_COUNT', 1);
        $floorInputs = array_map(function($num) {
            return ['label' => "Строение {$num}"];
        }, range(1, $siteCount));
        $resultBlock = Services::resultBlockContext($state, '/api/services/monitoring/calculator/send_proposal', [
            'Продолжительность выполнения работ' => Services::formatDurationMonths($state['model']['DURATION']['NAME'])
        ]);
        $options = _::update(self::options($state['data_set']['MULTIPLIERS']), 'DURATION', function($opts) {
            // TODO I guess you can format `duration` ahead of time in `options`
            return array_map(function($opt) {
                return _::update($opt, 'text', [Services::class, 'formatDurationMonths']);
            }, $opts);
        });
        return [
            'apiEndpoint' => '/api/services/monitoring/calculator/calculate',
            'state' => $state,
            'options' => $options,
            // TODO move it to the template?
            'heading' => 'Определение стоимости<br> проведения мониторинга',
            'floorInputs' => $floorInputs,
            'showDistanceSelect' => $siteCount > 1,
            'showDistanceWarning' => $siteCount > 1 && $params['DISTANCE_BETWEEN_SITES'] === Services::$distanceSpecialValue,
            'showUndergroundFloors' => $params['HAS_UNDERGROUND_FLOORS'],
            'resultBlock' => $resultBlock
        ];
    }

    /** @deprecated */
    function __construct(MonitoringRepo $repo) {
        $this->repo = $repo;
    }

    /** @deprecated */
    function context($service, $state, $dataSet) {
        $options = $this->repo->options($dataSet);
        return [
            'service' => array_merge($service, [
                'document_options' => $this->mapOptions($options['DOCUMENTS'])
            ]),
            'state' => $state
        ];
    }

    /** @deprecated */
    function mapOptions($items) {
        $isWrapped = _::matchesAny(array_keys($items), function($x) {
            // TODO refactor: brittle
            // non-id
            return !is_numeric($x);
        });
        if ($isWrapped) {
            return array_map([$this, 'mapOptions'], $items);
        }
        return array_map(function($item) {
            return [
                'value' => $item['ID'],
                'text' => $item['NAME']
            ];
        }, $items);
    }

    static function proposalTables($model) {
        $formatRow = _::partialRight([Services::class, 'formatRow'], $model);
        $nameFn = _::partialRight([_::class, 'get'], 'NAME');
        $listFn = function($entities) use ($nameFn) {
            return Services::listHtml(array_map($nameFn, $entities));
        };
        return [
            [
                'heading' => 'Сведения об объекте (объектах) мониторинга',
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
                'heading' => 'Цели мониторинга и конструкции подлежащие мониторингу',
                'rows' => array_map($formatRow, [
                    ['Цели мониторинга', 'MONITORING_GOAL', $nameFn],
                    ['Конструкции подлежащие мониторингу', 'STRUCTURES_TO_MONITOR', $listFn]
                ])
            ]
        ];
    }

    /** @deprecated use Services::dataSet */
    static function dataSet($data, $params) {
        return Services::dataSet($data, $params);
    }

    static function proposalParams($state, $outgoingId, $opts = []) {
        assert(isset($state['result']));
        $creationDate = isset($opts['creation_date'])
            ? $opts['creation_date']
            : new \DateTime();
        $d = clone $creationDate;
        $endingDate = $d->add(new \DateInterval('P3M'));
        return [
            'type' => 'monitoring',
            'heading' => 'КОММЕРЧЕСКОЕ ПРЕДЛОЖЕНИЕ<br> на проведение мониторинга',
            'outgoingId' => $outgoingId,
            'date' => Services::formatFullDate($creationDate),
            'endingDate' => Services::formatFullDate($endingDate),
            'totalPrice' => Services::formatTotalPrice($state['result']['total_price']),
            'duration' => Services::formatDurationMonths($state['model']['DURATION']['NAME']),
            'tables' => self::proposalTables($state['model']),
            'output' => array_merge([
                'dest' => 'F'
            ], _::get($opts, 'output' ,[]))
        ];
    }
}