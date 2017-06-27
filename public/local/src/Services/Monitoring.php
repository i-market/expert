<?php

namespace App\Services;

use App\Services;
use Core\Underscore as _;
use App\View;
use Core\Nullable as nil;
use Core\Util;
use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Validator as v;
use DateInterval;
use DateTime;

class Monitoring {
    private $repo;

    function __construct(MonitoringRepo $repo) {
        $this->repo = $repo;
    }

    function calculate($params) {
        // TODO improvement: validate reference values
        $validator = v::allOf(
            v::key('DESCRIPTION', v::stringType()->notEmpty()),
            v::key('LOCATION', v::notOptional()),
            v::key('SITE_COUNT', v::intType()->positive()),
            v::key('DISTANCE_BETWEEN_SITES',
                $params['SITE_COUNT'] === 1
                    ? v::alwaysValid()
                    : v::notOptional()),
            v::key('USED_FOR', v::notOptional()),
            v::key('TOTAL_AREA', v::intType()->positive()),
            v::key('VOLUME', v::optional(v::intType()->positive())),
            // have to use custom `callback` validator because e.g. built-in `each` validator hides the field name
            v::key('FLOORS', v::callback(function($values) {
                return is_array($values) && _::matches($values, function($v) {
                    return v::notOptional()->intType()->validate($v);
                });
            })),
            v::key('UNDERGROUND_FLOORS',
                $params['HAS_UNDERGROUND_FLOORS']
                    ? v::intType()->positive()
                    : v::alwaysValid()),
            v::key('MONITORING_GOAL', v::notOptional()),
            v::key('DURATION', v::notOptional()),
            v::key('TRANSPORT_ACCESSIBILITY', v::notOptional()),
            v::key('STRUCTURES_TO_MONITOR',
                $params['PACKAGE_SELECTION'] === 'PACKAGE'
                    ? v::alwaysValid()
                    : v::arrayType()->notEmpty()),
            v::key('DOCUMENTS', v::arrayType())
        );
        $errors = [];
        try {
            $validator->assert($params);
        } catch (NestedValidationException $exception) {
            $errors = Services::getMessages($exception);
            // TODO refactor: custom messages
            $errors = _::update($errors, 'STRUCTURES_TO_MONITOR', _::constantly(Services::EMPTY_LIST_MESSAGE));
            $errors = _::update($errors, 'FLOORS', _::constantly('В каждом поле должно быть положительное число.'));
        }
        $state = [
            'params' => $params,
            'errors' => $errors,
            'result' => []
        ];
        $isValid = _::isEmpty($errors);
        if ($isValid) {
            $calculator = new MonitoringCalculator();
            $multipliers = $calculator->multipliers($params, (new MonitoringRepo)->data());
            $totalPrice = $calculator->totalPrice($params['TOTAL_AREA'], $multipliers);
            $state['result'] = [
                'total_price' => $totalPrice
            ];
        }
        return $state;
    }

    function context($service, $state) {
        $options = $this->repo->options();
        return [
            'service' => array_merge($service, [
                'document_options' => array_map(function($document) {
                    return [
                        'value' => $document['ID'],
                        'label' => $document['NAME']
                    ];
                }, $options['DOCUMENTS'])
            ]),
            'state' => $state
        ];
    }

    // TODO rename to inputs
    // TODO inline
    function floorSelects($state) {
        $siteCountMaybe = $state['params']['SITE_COUNT'];
        $siteCount = nil::get($siteCountMaybe, 1);
        return array_map(function($num) {
            return [
                'label' => 'Строение '.$num,
            ];
        }, range(1, $siteCount));
    }

    function mapOptions($items) {
        // recur
        if (!_::isIndexed($items)) {
            return array_map([$this, 'mapOptions'], $items);
        }
        return array_map(function($item) {
                return [
                    'value' => $item['ID'],
                    'text' => $item['NAME']
                ];
            }, $items);
    }

    function resultBlockContext() {
        return [
            'api_uri' => '/api/services/monitoring/calculator/proposal'
        ];
    }

    function calculatorContext($state) {
        $params = $state['params'];
        $siteCount = $params['SITE_COUNT'];
        $distanceSpecialValue = '>3km';
        $options = array_map([$this, 'mapOptions'], $this->repo->options());
        // mutate
        $options = _::update($options, 'DISTANCE_BETWEEN_SITES', function($opts) use ($distanceSpecialValue) {
            return _::append($opts, [
                'value' => $distanceSpecialValue,
                'text' => 'Расстояние между объектами более 3 км'
            ]);
        });
        $options = _::update($options, 'DURATION', function($opts) {
            return array_map(function($opt) {
                return _::update($opt, 'text', function($text) {
                    return preg_replace_callback('/(\pL+\s+)?(\d+)$/u', function($matches) {
                        list($match, $word, $number) = $matches;
                        $units = $word !== ''
                            // e.g. более n месяцев
                            ? Util::units($number, 'месяца', 'месяцев', 'месяцев')
                            : Util::units($number, 'месяц', 'месяца', 'месяцев');
                        return $match.' '.$units;
                    }, $text);
                });
            }, $opts);
        });
        $resultMaybe = nil::map(_::get($state, 'result.total_price'), function($totalPrice) use ($options, $params) {
            $durationOpt = _::find($options['DURATION'], function($opt) use ($params) {
                return $opt['value'] === $params['DURATION'];
            });
            return [
                'screen' => 'result',
                'formatted_total_price' => Services::formatTotalPrice($totalPrice),
                'duration' => $durationOpt['text']
            ];
        });
        $context = [
            'state' => $state,
            'heading' => 'Определение стоимости<br> проведения мониторинга',
            'options' => $options,
            'floorSelects' => $this->floorSelects($state),
            'showDistanceSelect' => $siteCount > 1,
            'showDistanceWarning' => $siteCount > 1 && $params['DISTANCE_BETWEEN_SITES'] === $distanceSpecialValue,
            'showUndergroundFloors' => $params['HAS_UNDERGROUND_FLOORS'],
            'result' => array_merge(self::resultBlockContext(), nil::get($resultMaybe, []))
        ];
        return $context;
    }

    static function proposalTables($params) {
        $listHtml = function($values) {
            $items = join('', array_map(function($item) {
                return "<li>{$item}</li>";
            }, $values));
            return "<ul>{$items}</ul>";
        };
        $formatRow = function($tuple) use ($params) {
            list($label, $key) = $tuple;
            $funcMaybe = isset($tuple[2]) ? $tuple[2] : null;
            $value = nil::get($params[$key], '');
            return ["<strong>{$label}</strong>", is_callable($funcMaybe) ? $funcMaybe($value) : $value];
        };
        return [
            [
                'heading' => 'Сведения об объекте (объектах) мониторинга',
                'rows' => array_map($formatRow, [
                    ['Описание объекта (объектов)', 'DESCRIPTION'],
                    ['Количество зданий, сооружений, строений, помещений', 'SITE_COUNT'],
                    ['Местонахождение', 'LOCATION'],
                    ['Адрес (адреса)', 'ADDRESS'],
                    ['Назначение', 'MONITORING_GOAL'],
                    ['Общая площадь', 'TOTAL_AREA'],
                    ['Строительный объем', 'VOLUME'],
                    ['Количество надземных этажей', 'FLOORS', function($values) {
                        return Util::sum($values);
                    }],
                    ['Наличие технического подполья, подвала, подземных этажей у одного или нескольких объектов', 'HAS_UNDERGROUND_FLOORS', function($bool) {
                        return $bool ? 'Имеется' : 'Не имеется';
                    }],
                    ['Количество подземных этажей', 'UNDERGROUND_FLOORS'],
                    ['Удаленность объектов друг от друга', 'DISTANCE_BETWEEN_SITES'],
                    ['Транспортная доступность', 'TRANSPORT_ACCESSIBILITY'],
                    ['Наличие документов', 'DOCUMENTS', $listHtml]
                ])
            ],
            [
                'heading' => 'Цели мониторинга и конструкции подлежащие мониторингу',
                'rows' => array_map($formatRow, [
                    ['Цели мониторинга', 'MONITORING_GOAL'],
                    ['Конструкции подлежащие мониторингу', 'STRUCTURES_TO_MONITOR', $listHtml]
                ])
            ]
        ];
    }

    static function proposalParams($requestId, $data, $creationDate = null) {
        assert(_::isEmpty(array_diff(['total_price', 'duration', 'tables'], array_keys($data))));
        if ($creationDate === null) {
            $creationDate = new DateTime();
        }
        // TODO extract
        $nextProposalOutgoingId = function($type, $requestId) {
            $typeNumber = [
                'monitoring' => '1'
            ];
            assert(in_array($type, array_keys($typeNumber)));
            return "0611-{$typeNumber[$type]}/{$requestId}";
        };
        $formatDate = function(DateTime $datetime) {
            $monthRu = function($n) {
                $months = explode('|', '|января|февраля|марта|апреля|мая|июня|июля|августа|сентября|октября|ноября|декабря');
                return $months[$n];
            };
            $ts = $datetime->getTimestamp();
            $month = $monthRu(intval(date('n', $ts)));
            return join(' ', [date('d', $ts), $month, date('Y', $ts), 'г.']);
        };
        $d = clone $creationDate;
        $endingDate = $d->add(new DateInterval('P3M'));
        return [
            'type' => 'monitoring',
            'heading' => 'КОММЕРЧЕСКОЕ ПРЕДЛОЖЕНИЕ<br> на проведение мониторинга',
            'outgoingId' => $nextProposalOutgoingId('monitoring', $requestId),
            'date' => $formatDate($creationDate),
            'endingDate' => $formatDate($endingDate),
            'totalPrice' => Services::formatTotalPrice($data['total_price']),
            'duration' => $data['duration'],
            'tables' => $data['tables'],
            'output' => array_merge([
                'dest' => 'F'
            ], $data['output'])
        ];
    }
}