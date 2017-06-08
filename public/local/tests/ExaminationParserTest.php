<?php

use App\Services\ExaminationParser;
use PHPUnit\Framework\TestCase;
use Core\Underscore as _;
use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Validator as v;

class ExaminationParserTest extends TestCase {
    /** @group slow */
    function testParseWorksheet() {
        $parser = new ExaminationParser();
        $path = getcwd().'/fixtures/calculator/Экспертиза калькуляторы.xlsx';
        $result = $parser->parseFile($path);
        foreach ($result as $worksheetResult) {
            $multipliers = $worksheetResult['MULTIPLIERS'];
            // TODO assertions
            $missingSections = array_diff(_::pluck($parser->spec['sections'], 'key'), array_keys($multipliers));
            $this->assertTrue(_::isEmpty($missingSections));
//            $this->assertTrue(_::isEmpty($parser->log));
        }
        // TODO strict keySet validation
        $workSheetValidator = v::keySet(
            v::key('MULTIPLIERS', v::allOf(
                v::key('GOALS', v::allOf(
                    v::key('14.1. Установление технического состояния, исправности', v::keySet(
                        v::key('КОМПЛЕКСНЫЕ ЭКСПЕРТИЗЫ'),
                        v::key('ВЫБОРОЧНЫЕ ЭКСПЕРТИЗЫ', v::keySet(
                            v::key('Конструкции зданий, сооружений'),
                            v::key('Внутренние инженерные сети и оборудование'),
                            v::key('Дороги, дорожные покрытия')
                        ))
                    )),
                    v::key('14.2. Определение качества выполнения строительных работ', v::keySet(
                        v::key('КОМПЛЕКСНЫЕ ЭКСПЕРТИЗЫ'),
                        v::key('ВЫБОРОЧНЫЕ ЭКСПЕРТИЗЫ', v::keySet(
                            v::key('Конструкции зданий, сооружений'),
                            v::key('Внутренние инженерные сети и оборудование'),
                            v::key('Дороги, дорожные покрытия')
                        ))
                    )),
                    v::key('14.3. Определение качества выполнения работ по проектированию', v::keySet(
                        v::key('КОМПЛЕКСНЫЕ ЭКСПЕРТИЗЫ'),
                        v::key('ЭКСПЕРТИЗА РАЗДЕЛОВ ПРОЕКТНОЙ ДОКУМЕНТАЦИИ'),
                        v::key('ЭКСПЕРТИЗА РАЗДЕЛОВ РАБОЧЕЙ ДОКУМЕНТАЦИИ'),
                        v::key('ЭКСПЕРТИЗА ПРИНЯТЫХ ПРОЕКТНЫХ РЕШЕНИЙ')
                    )),
                    v::key('14.4. Установление величины физического износа' /* TODO etc. */)
                ))
            ))
        );
        $validator = v::keySet(
            v::key('SINGLE_BUILDING', $workSheetValidator),
            v::key('MULTIPLE_BUILDINGS', $workSheetValidator)
        );
        try {
            $validator->assert($result);
        } catch (NestedValidationException $e) {
            $this->fail($e->getFullMessage());
        };
    }
}
