<?php

namespace App\Services;

use App\Services;
use Core\Underscore as _;
use App\View;
use Core\Nullable as nil;
use Core\Util;
use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Validator as v;

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
            v::key('DISTANCE_BETWEEN_SITES', $params['SITE_COUNT'] === 1
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
            v::key('UNDERGROUND_FLOORS', $params['HAS_UNDERGROUND_FLOORS']
                ? v::intType()->positive()
                : v::alwaysValid()),
            v::key('MONITORING_GOAL', v::notOptional()),
            v::key('DURATION', v::notOptional()),
            v::key('TRANSPORT_ACCESSIBILITY', v::notOptional()),
            v::key('STRUCTURES_TO_MONITOR', $params['PACKAGE_SELECTION'] === 'PACKAGE'
                ? v::alwaysValid()
                : v::arrayType()->notEmpty()),
            v::key('DOCUMENTS', v::arrayType())
        );
        $errors = [];
        try {
            $validator->assert($params);
        } catch (NestedValidationException $exception) {
            $errors = Services::getMessages($exception);
            // TODO refactor empty checkbox list message
            $errors = _::update($errors, 'STRUCTURES_TO_MONITOR', _::constantly(Services::EMPTY_LIST_MESSAGE));
            $errors = _::update($errors, 'FLOORS', _::constantly('Каждое поле должно быть положительным числом.'));
        }
        $state = [
            'params' => $params,
            'errors' => $errors,
        ];
        $isValid = _::isEmpty($errors);
        if ($isValid) {
            $calculator = new MonitoringCalculator();
            $multipliers = $calculator->multipliers($params, (new MonitoringRepo)->data());
            $totalPrice = $calculator->totalPrice($params['TOTAL_AREA'], $multipliers);
            $state['total_price'] = $totalPrice;
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

    // TODO refactor
    function renderCalculator($state) {
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
        $durationOpt = _::find($options['DURATION'], function($opt) use ($params) {
            return $opt['value'] === $params['DURATION'];
        });
        $context = [
            'state' => $state,
            'heading' => 'Определение стоимости<br> проведения мониторинга',
            'options' => $options,
            'floorSelects' => $this->floorSelects($state),
            'showDistanceSelect' => $siteCount > 1,
            'showDistanceWarning' => $siteCount > 1 && $params['DISTANCE_BETWEEN_SITES'] === $distanceSpecialValue,
            'showUndergroundFloors' => $params['HAS_UNDERGROUND_FLOORS'],
            'duration' => $durationOpt['text'],
            'formattedTotalPrice' => nil::map($state['total_price'], function($totalPrice) {
                return Util::formatCurrency(round($totalPrice), ['cents' => false]).' руб/мес';
            })
        ];
        return View::render('partials/calculator/monitoring_calculator', $context);
    }
}