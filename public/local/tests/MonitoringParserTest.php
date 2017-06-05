<?php

use App\Services\MonitoringParser;
use App\Services\Parser;
use PHPUnit\Framework\TestCase;
use Core\Underscore as _;

class MonitoringParserTest extends TestCase {
    function testParseWorksheet() {
        $parser = new MonitoringParser();
        $filenames = [
            'monitoring-single-building.tsv',
            'monitoring-multiple-buildings.tsv'
        ];
        foreach ($filenames as $filename) {
            $path = getcwd().'/fixtures/calc/'.$filename;
            $result = $parser->parseWorksheet(Parser::rowIterator($path));
            // TODO assertions
            $missingSections = array_diff(_::pluck($parser->sections, 'KEY'), array_keys($result['MULTIPLIERS']));
            $this->assertTrue(_::isEmpty($missingSections));
//            $this->assertTrue(_::isEmpty($parser->log));
        }
    }
}
