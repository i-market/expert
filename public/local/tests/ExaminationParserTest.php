<?php

use App\Services\ExaminationParser;
use PHPUnit\Framework\TestCase;
use Core\Underscore as _;

class ExaminationParserTest extends TestCase {
    function testParseWorksheet() {
        $parser = new ExaminationParser();
        $path = getcwd().'/fixtures/calculator/Экспертиза калькуляторы.xlsx';
        $result = $parser->parseFile($path);
        foreach ($result as $worksheetResult) {
            // TODO assertions
            $missingSections = array_diff(_::pluck($parser->spec['sections'], 'key'), array_keys($worksheetResult['multipliers']));
            $this->assertTrue(_::isEmpty($missingSections));
//            $this->assertTrue(_::isEmpty($parser->log));
        }
    }
}
