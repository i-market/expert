<?php

namespace App\Services;

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
        // TODO state
        $state = [
            'params' => $params,
            'errors' => [
                'DESCRIPTION' => 'some error'
            ]
        ];
        $context = [
            'state' => $state,
            'floorsApiUri' => '/api/services/monitoring/calculate/floors',
            'heading' => 'Определение стоимости и сроков Обследования конструкций, помещений, зданий, сооружений, инженерных сетей и оборудования',
            'options' => array_map([$this, 'mapOptions'], $this->repo->options()),
            'floorSelects' => $this->floorSelects($state),
            'showDistanceSelect' => intval($params['SITE_COUNT']) > 1
        ];
        return v::render('partials/calculator/monitoring_calculator', $context);
    }
}