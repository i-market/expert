<?php

namespace App\Services;

use Core\Util;
use Core\Underscore as _;

class MonitoringRepo {
    private $parser;

    function __construct(MonitoringParser $parser) {
        $this->parser = $parser;
    }

    // TODO persist locations
    function locations() {
        $filenames = [
            'monitoring-single-building.tsv',
            'monitoring-multiple-buildings.tsv'
        ];
        $locations = _::flatMap($filenames, function($filename) {
            $path = Util::joinPath([$_SERVER['DOCUMENT_ROOT'], 'local/fixtures/calc', $filename]);
            $result = $this->parser->parseWorksheet(Parser::rowIterator($path));
            return array_keys($result['MULTIPLIERS']['LOCATION']);
        });
        return _::map(array_unique($locations), function($name, $idx) {
            return [
                'ID' => $idx,
                'NAME' => $name
            ];
        });
    }

    // TODO persist items
    function usedForItems() {
        $filenames = [
            'monitoring-single-building.tsv',
            'monitoring-multiple-buildings.tsv'
        ];
        $items = _::flatMap($filenames, function($filename) {
            $path = Util::joinPath([$_SERVER['DOCUMENT_ROOT'], 'local/fixtures/calc', $filename]);
            $result = $this->parser->parseWorksheet(Parser::rowIterator($path));
            return array_keys($result['MULTIPLIERS']['USED_FOR']);
        });
        return _::map(array_unique($items), function($name, $idx) {
            return [
                'ID' => $idx,
                'NAME' => $name
            ];
        });
    }

    // TODO persist items
    function siteCounts() {
        $filenames = [
            'monitoring-single-building.tsv',
            'monitoring-multiple-buildings.tsv'
        ];
        $items = _::flatMap($filenames, function($filename) {
            $path = Util::joinPath([$_SERVER['DOCUMENT_ROOT'], 'local/fixtures/calc', $filename]);
            $result = $this->parser->parseWorksheet(Parser::rowIterator($path));
            return array_keys($result['MULTIPLIERS']['SITE_COUNT']);
        });
        return _::map(array_unique($items), function($name, $idx) {
            return [
                'ID' => $idx,
                'NAME' => $name
            ];
        });
    }

    // TODO persist items
    function floors() {
        $filenames = [
            'monitoring-single-building.tsv',
            'monitoring-multiple-buildings.tsv'
        ];
        $items = _::flatMap($filenames, function($filename) {
            $path = Util::joinPath([$_SERVER['DOCUMENT_ROOT'], 'local/fixtures/calc', $filename]);
            $result = $this->parser->parseWorksheet(Parser::rowIterator($path));
            return array_keys($result['MULTIPLIERS']['FLOORS']);
        });
        return _::map(array_unique($items), function($name, $idx) {
            return [
                'ID' => $idx,
                'NAME' => $name
            ];
        });
    }
}