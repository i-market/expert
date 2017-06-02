<?php

namespace App\Calc;

use Core\Underscore as _;

class MonitoringForm {
    static function floorSelects($state) {
        $siteCountId = intval($state['params']['SITE_COUNT']);
        $items = _::keyBy('ID', MonitoringCalc::siteCounts());
        // TODO handle "более x" case
        $siteCount = intval($items[$siteCountId]['NAME']);
        $floorOptions = array_map(function($item) {
            return [
                'value' => $item['ID'],
                'text' => $item['NAME']
            ];
        }, MonitoringCalc::floors());
        return array_map(function($num) use ($floorOptions) {
            return [
                'label' => 'Строение '.$num,
                'options' => $floorOptions
            ];
        }, range(1, $siteCount));
    }

    static function context($state) {
        return [
            'floorsApiUri' => '/api/services/monitoring/calculate/floors',
            'heading' => 'Определение стоимости и сроков Обследования конструкций, помещений, зданий, сооружений, инженерных сетей и оборудования',
            // TODO options should depend on the site count (one or more)
            'locationOptions' => array_map(function($location) {
                return [
                    'value' => $location['ID'],
                    'text' => $location['NAME']
                ];
            }, MonitoringCalc::locations()),
            'usedForOptions' => array_map(function($item) {
                return [
                    'value' => $item['ID'],
                    'text' => $item['NAME']
                ];
            }, MonitoringCalc::usedForItems()),
            // TODO sort
            'siteCountOptions' => array_map(function($item) {
                return [
                    'value' => $item['ID'],
                    'text' => $item['NAME']
                ];
            }, MonitoringCalc::siteCounts()),
            'floorSelects' => self::floorSelects($state)
        ];
    }
}