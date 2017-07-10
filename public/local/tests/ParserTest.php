<?php

use App\Services\Parser;
use Core\Underscore as _;
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

    function testParseRangeText() {
        $expected = [
            [
                'min' => 0,
                'max' => 10000,
            ],
            [
                'min' => 10000,
                'max' => 30000,
            ],
            [
                'min' => 30000,
                'max' => 60000,
            ],
            [
                'min' => 60000,
                'max' => 100000,
            ],
            [
                'min' => 100000,
            ],
        ];
        $actual = array_map(_::partialRight([Parser::class, 'parseRangeText'], ['min' => 0]), [
            'до 10 000 кв.м.',
            'от 10 000 до 30 000 кв.м.',
            'от 30 000 до 60 000 кв.м.',
            'от 60 000 до 100 000 кв.м.',
            'более 100 000 кв.м.'
        ]);
        $this->assertEquals($expected, $actual);
    }
}
