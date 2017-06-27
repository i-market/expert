<?php

use App\Services\Calculator;
use PHPUnit\Framework\TestCase;

class CalculatorTest extends TestCase {
    function testParseNumericPredicate() {
        $calc = new Calculator();
        $pred = $calc->parseNumericPredicate('Более 30');
        $this->assertFalse($pred('invalid arg'));
        $this->assertFalse($pred(29));
        $this->assertFalse($pred(30));
        $this->assertTrue($pred(31));
        $this->assertNull($calc->parseNumericPredicate('Менее 30'));
    }
}
