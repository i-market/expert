<?php

use App\Services\InspectionParser;
use PHPUnit\Framework\TestCase;
use Core\Underscore as _;

class InspectionParserTest extends TestCase {
    /** @group slow */
    function testParseWorksheet() {
        $parser = new InspectionParser();
        $path = __DIR__.'/../fixtures/calculator/Обследование калькуляторы.xlsx';
        $result = $parser->parseFile($path);
        foreach ($result as $worksheetResult) {
            $missingSections = array_diff(_::pluck($parser->spec['sections'], 'key'), array_keys($worksheetResult['MULTIPLIERS']));
            $this->assertEquals([], $missingSections);
        }
    }
}
