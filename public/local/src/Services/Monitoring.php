<?php

namespace App\Services;

use Core\Underscore as _;
use App\View as v;

class Monitoring {
    private $repo;
    
    function __construct(MonitoringRepo $repo) {
        $this->repo = $repo;
    }
    
    function floorSelects($state) {
        $siteCountId = intval($state['params']['SITE_COUNT']);
        $items = _::keyBy('ID', $this->repo->siteCounts());
        // TODO handle "более x" case
        $siteCount = intval($items[$siteCountId]['NAME']);
        $floorOptions = array_map(function($item) {
            return [
                'value' => $item['ID'],
                'text' => $item['NAME']
            ];
        }, $this->repo->floors());
        return array_map(function($num) use ($floorOptions) {
            return [
                'label' => 'Строение '.$num,
                'options' => $floorOptions
            ];
        }, range(1, $siteCount));
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
            // TODO options should depend on the site count (one or more)
            'locationOptions' => array_map(function($location) {
                return [
                    'value' => $location['ID'],
                    'text' => $location['NAME']
                ];
            }, $this->repo->locations()),
            'usedForOptions' => array_map(function($item) {
                return [
                    'value' => $item['ID'],
                    'text' => $item['NAME']
                ];
            }, $this->repo->usedForItems()),
            // TODO sort
            'siteCountOptions' => array_map(function($item) {
                return [
                    'value' => $item['ID'],
                    'text' => $item['NAME']
                ];
            }, $this->repo->siteCounts()),
            'floorSelects' => $this->floorSelects($state)
        ];
        return v::render('partials/calculator/calculator', $context);
    }
}