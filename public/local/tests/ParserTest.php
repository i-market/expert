<?php

use App\Services\Parser;
use PHPUnit\Framework\TestCase;

class ParserTest extends TestCase {
    function testParseNumericPredicate() {
        $pred = Parser::parseNumericPredicate('Более 30');
        $this->assertFalse($pred('invalid arg'));
        $this->assertFalse($pred(29));
        $this->assertFalse($pred(30));
        $this->assertTrue($pred(31));
        $this->assertNull(Parser::parseNumericPredicate('Менее 30'));
    }
}
