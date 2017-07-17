<?php

namespace App\Services;

use Core\Util;
use Core\Underscore as _;

/** @deprecated */
class MonitoringRepo {
    private $data;

    private function dataFilePath() {
        return Util::joinPath([$_SERVER['DOCUMENT_ROOT'], 'local/data/monitoring.json']);
    }

    /** @deprecated see Services */
    function save($data) {
        return file_put_contents($this->dataFilePath(), json_encode($data));
    }

    /** @deprecated see Services */
    function data() {
        if ($this->data !== null) {
            return $this->data;
        }
        $content = file_get_contents($this->dataFilePath());
        assert($content !== false);
        $this->data = json_decode($content, true);
        return $this->data;
    }

    /** @deprecated */
    function defaultDataSet() {
        return $this->data()['MULTIPLE_BUILDINGS'];
    }

    /** @deprecated see Services::entities2options */
    function fromEntities($path, $dataSet) {
        $entities = _::get($dataSet, array_merge(['MULTIPLIERS'], $path));
        return array_map(function($entity) {
            return _::pick($entity, ['ID', 'NAME']);
        }, $entities);
    }

    // TODO refactor: not repo's concern
    /** @deprecated */
    function options($dataSet) {
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
        $ret = array_reduce($keys, function($acc, $key) use ($dataSet) {
            return _::set($acc, $key, $this->fromEntities([$key], $dataSet));
        }, []);
        $ret['STRUCTURES_TO_MONITOR'] = [
            'PACKAGE' => $this->fromEntities(['STRUCTURES_TO_MONITOR', 'PACKAGE'], $dataSet),
            'INDIVIDUAL' => $this->fromEntities(['STRUCTURES_TO_MONITOR', 'INDIVIDUAL'], $dataSet),
        ];
        return $ret;
    }
}