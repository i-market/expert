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

    function locations() {
        return $this->fromMultipliers('LOCATION');
    }

    function usedForItems() {
        return $this->fromMultipliers('USED_FOR');
    }

    function siteCounts() {
        return $this->fromMultipliers('SITE_COUNT');
    }

    function floors() {
        return $this->fromMultipliers('FLOORS');
    }

    function documents() {
        return $this->fromMultipliers('DOCUMENTS');
    }
}