<?php

use App\Services\MonitoringParser;
use PHPUnit\Framework\TestCase;
use Core\Underscore as _;

class MonitoringParserTest extends TestCase {
    function testParseWorksheet() {
        $parser = new MonitoringParser();
        $filenames = [
            'Мониторинг калькуляторы.xlsx'
        ];
        foreach ($filenames as $filename) {
            $path = getcwd().'/fixtures/calculator/'.$filename;
            foreach ($parser->worksheetIterator($path) as $worksheet) {
                $result = $parser->parseWorksheet($worksheet->getRowIterator());
                // TODO assertions
                $missingSections = array_diff(_::pluck($parser->sections, 'KEY'), array_keys($result['MULTIPLIERS']));
                $this->assertTrue(_::isEmpty($missingSections));
//            $this->assertTrue(_::isEmpty($parser->log));
            }
        }
    }
}
