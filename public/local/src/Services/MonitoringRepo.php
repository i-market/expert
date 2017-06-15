<?php

namespace App\Services;

use Core\Util;
use Core\Underscore as _;

class MonitoringRepo {
    private $data;

    private function dataFilePath() {
        // TODO tmp implementation for development
        return Util::joinPath([sys_get_temp_dir(), 'expert-monitoring.json']);
    }

    function save($data) {
        return file_put_contents($this->dataFilePath(), json_encode($data));
    }

    private function data() {
        if ($this->data !== null) {
            return $this->data;
        }
        $content = file_get_contents($this->dataFilePath());
        assert($content !== false);
        $this->data = json_decode($content, true);
        return $this->data;
    }

    private function fromMultipliers($key) {
        $items = _::flatMap($this->data(), function($worksheet) use ($key) {
            return array_keys($worksheet['MULTIPLIERS'][$key]);
        });
        return _::map(array_unique($items), function($name, $idx) {
            return [
                'ID' => $idx,
                'NAME' => $name
            ];
        });
    }

    // TODO refactor: kind of a bad name (collides with view layer "options" for select inputs)
    function options() {
        $keys = [
            'LOCATION',
            'USED_FOR',
            'SITE_COUNT',
            'FLOORS',
            'DOCUMENTS',
            'DISTANCE_BETWEEN_SITES',
            'MONITORING_GOAL',
            'DURATION',
            'TRANSPORT_ACCESSIBILITY'
        ];
        return array_reduce($keys, function($acc, $key) {
            return _::set($acc, $key, $this->fromMultipliers($key));
        }, []);
    }
}