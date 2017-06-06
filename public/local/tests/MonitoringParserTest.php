<?php

use App\Services\MonitoringParser;
use PHPUnit\Framework\TestCase;
use Core\Underscore as _;

class MonitoringParserTest extends TestCase {
    function testParseWorksheet() {
        $parser = new MonitoringParser();
        $path = getcwd().'/fixtures/calculator/Мониторинг калькуляторы.xlsx';
        $result = $parser->parseFile($path);
        foreach ($result as $worksheetResult) {
            // TODO assertions
            $missingSections = array_diff(_::pluck($parser->spec['sections'], 'key'), array_keys($worksheetResult['MULTIPLIERS']));
            $this->assertTrue(_::isEmpty($missingSections));
//            $this->assertTrue(_::isEmpty($parser->log));
        }
    }
}
