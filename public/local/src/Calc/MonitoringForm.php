<?php

namespace App\Calc;

class MonitoringForm {
    static function context() {
        return [
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
            }, MonitoringCalc::siteCounts())
        ];
    }
}