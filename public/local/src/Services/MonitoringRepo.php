<?php

namespace App\Services;

use Core\Util;
use Core\Underscore as _;

class MonitoringRepo {
    private $data;

    private function dataFilePath() {
        return Util::joinPath([$_SERVER['DOCUMENT_ROOT'], 'local/data/monitoring.json']);
    }

    function save($data) {
        return file_put_contents($this->dataFilePath(), json_encode($data));
    }

    function data() {
        if ($this->data !== null) {
            return $this->data;
        }
        $content = file_get_contents($this->dataFilePath());
        assert($content !== false);
        $this->data = json_decode($content, true);
        return $this->data;
    }

    function defaultDataSet() {
        return $this->data()['MULTIPLE_BUILDINGS'];
    }

    // TODO refactor: kind of a bad name (collides with view layer "options" for select inputs)
    function options($dataSet) {
        $fromEntities = function($path) use ($dataSet) {
            $entities = _::get($dataSet, array_merge(['MULTIPLIERS'], $path));
            return array_map(function($entity) {
                return _::pick($entity, ['ID', 'NAME']);
            }, $entities);
        };
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
        $ret = array_reduce($keys, function($acc, $key) use ($fromEntities) {
            return _::set($acc, $key, $fromEntities([$key]));
        }, []);
        $ret['STRUCTURES_TO_MONITOR'] = [
            'PACKAGE' => $fromEntities(['STRUCTURES_TO_MONITOR', 'PACKAGE']),
            'INDIVIDUAL' => $fromEntities(['STRUCTURES_TO_MONITOR', 'INDIVIDUAL']),
        ];
        return $ret;
    }
}