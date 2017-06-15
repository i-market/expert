<?php

namespace App\Services;

use Core\Underscore as _;
use App\View as v;
use Core\Nullable as nil;

class Monitoring {
    private $repo;

    function __construct(MonitoringRepo $repo) {
        $this->repo = $repo;
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
        $siteCountMaybe = nil::map($state['params']['SITE_COUNT'], function($siteCount) {
            return intval($siteCount);
        });
        $siteCount = nil::get($siteCountMaybe, 1);
        return array_map(function($num) {
            return [
                'label' => 'Строение '.$num,
            ];
        }, range(1, $siteCount));
    }

    function mapOptions($items) {
        return array_map(function($item) {
                return [
                    'value' => $item['ID'],
                    'text' => $item['NAME']
                ];
            }, $items);
    }

    function renderCalculator($params) {
        $siteCount = intval($params['SITE_COUNT']);
        // TODO state
        $state = [
            'params' => $params,
            'errors' => [
                'DESCRIPTION' => 'some error'
            ]
        ];
        $distanceSpecialValue = '>3km';
        $options = array_map([$this, 'mapOptions'], $this->repo->options());
        $context = [
            'state' => $state,
            'floorsApiUri' => '/api/services/monitoring/calculate/floors',
            'heading' => 'Определение стоимости и сроков Обследования конструкций, помещений, зданий, сооружений, инженерных сетей и оборудования',
            'options' => _::update($options, 'DISTANCE_BETWEEN_SITES', function($options) use ($distanceSpecialValue) {
                return _::append($options, [
                    'value' => $distanceSpecialValue,
                    'text' => 'Расстояние между объектами более 3 км'
                ]);
            }),
            'floorSelects' => $this->floorSelects($state),
            'showDistanceSelect' => $siteCount > 1,
            'showDistanceWarning' => $siteCount > 1 && $params['DISTANCE_BETWEEN_SITES'] === $distanceSpecialValue
        ];
        return v::render('partials/calculator/monitoring_calculator', $context);
    }
}