<?php

use App\Services\ExaminationParser;
use PHPUnit\Framework\TestCase;
use Core\Underscore as _;
use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Validator as v;

class ExaminationParserTest extends TestCase {
    /** @group slow */
    function testParseWorksheet() {
        $reduceEntities = function($acc, $xs, $f) use (&$reduceEntities) {
            if (isset($xs['ID'])) {
                return $f($acc, $xs);
            } elseif (is_array($xs)) {
                return array_reduce($xs, _::partialRight($reduceEntities, $f), $acc);
            } else {
                return $acc;
            }
        };
        $countEntities = function($xs) use ($reduceEntities) {
            return $reduceEntities(0, $xs, function($acc, $_) { return $acc + 1; });
        };
        $parser = new ExaminationParser();
        $path = __DIR__.'/../fixtures/calculator/Экспертиза калькуляторы.xlsx';
        $result = $parser->parseFile($path);
        foreach ($result as $worksheetResult) {
            $multipliers = $worksheetResult['MULTIPLIERS'];
            $missingSections = array_diff(_::pluck($parser->spec['sections'], 'key'), array_keys($multipliers));
            $this->assertTrue(_::isEmpty($missingSections));
            $maxId = $reduceEntities(0, $worksheetResult['MULTIPLIERS']['GOALS'], function($acc, $x) {
                return max($acc, $x['ID']);
            });
            $this->assertEquals($maxId, $countEntities($worksheetResult['MULTIPLIERS']['GOALS']));
        }
        // TODO basic entity validator
        $entities = v::arrayType()->notEmpty();
        // covers only the harder cases
        $workSheetValidator = v::keySet(
            v::key('TIME', v::arrayType()->notEmpty()),
            v::key('MULTIPLIERS', v::allOf(
                v::key('GOALS', v::allOf(
                    v::key('14.1. Установление технического состояния, исправности', v::keySet(
                        v::key('КОМПЛЕКСНЫЕ ЭКСПЕРТИЗЫ', $entities),
                        v::key('ВЫБОРОЧНЫЕ ЭКСПЕРТИЗЫ', v::keySet(
                            v::key('Конструкции зданий, сооружений', v::allOf(
                                $entities,
                                // first entity of a nesting table
                                v::key('12', v::equals([
                                    "ID" => "12",
                                    "NAME" => "14.1.12. - экспертиза технического состояния оснований и фундаментов",
                                    "VALUE" => [
                                        1 => 0.55,
                                        2 => 0.275,
                                        3 => 0.18333333333333,
                                        4 => 0.1375,
                                        5 => 0.11,
                                        6 => 0.091666666666667,
                                        "7-27" => 0.078571428571429,
                                    ],
                                ]))
                            )),
                            // first entity of a nested ("child") table
                            v::key('Внутренние инженерные сети и оборудование', v::allOf(
                                $entities,
                                v::key('30', v::equals([
                                    "ID" => "30",
                                    "NAME" => "14.1.30. - экспертиза технического состояния систем внутреннего горячего и(или) холодного водоснабжения",
                                    "VALUE" => [
                                        1 => 0.57,
                                        2 => 0.285,
                                        "3-9" => 0.19,
                                        4 => 0.1425,
                                        5 => 0.114,
                                        6 => 0.095,
                                        "7-27" => 0.081428571428571,
                                    ],
                                ]))
                            )),
                            v::key('Дороги, дорожные покрытия', v::allOf(
                                $entities,
                                // simple key-value pair
                                v::key('39', v::equals([
                                    "ID" => "39",
                                    "NAME" => "14.1.39. - экспертиза технического состояния конструкций дорог и(или) дорожных покрытий",
                                    "VALUE" => 0.6,
                                ]))
                            ))
                        ))
                    )),
                    v::key('14.2. Определение качества выполнения строительных работ', v::keySet(
                        v::key('КОМПЛЕКСНЫЕ ЭКСПЕРТИЗЫ', $entities),
                        v::key('ВЫБОРОЧНЫЕ ЭКСПЕРТИЗЫ', v::keySet(
                            v::key('Конструкции зданий, сооружений', $entities),
                            v::key('Внутренние инженерные сети и оборудование', $entities),
                            v::key('Дороги, дорожные покрытия', $entities)
                        ))
                    )),
                    v::key('14.3. Определение качества выполнения работ по проектированию', v::keySet(
                        v::key('КОМПЛЕКСНЫЕ ЭКСПЕРТИЗЫ', $entities),
                        v::key('ЭКСПЕРТИЗА РАЗДЕЛОВ ПРОЕКТНОЙ ДОКУМЕНТАЦИИ', $entities),
                        v::key('ЭКСПЕРТИЗА РАЗДЕЛОВ РАБОЧЕЙ ДОКУМЕНТАЦИИ', $entities),
                        v::key('ЭКСПЕРТИЗА ПРИНЯТЫХ ПРОЕКТНЫХ РЕШЕНИЙ', v::allOf(
                            $entities,
                            v::key('128', v::equals([
                                "ID" => "128",
                                "NAME" => "14.3.48. - экспертиза качества принятых проектных решений по устройству оснований и фундаментов",
                                "VALUE" => [
                                    1 => 0.4,
                                    2 => 0.2,
                                    3 => 0.13333333333333,
                                    4 => 0.1,
                                    5 => 0.08,
                                    6 => 0.066666666666667,
                                    7 => 0.057142857142857,
                                    8 => 1.0,
                                    9 => 1.0,
                                    10 => 1.0,
                                    11 => 1.0,
                                    "12-22" => 1.0,
                                ],
                            ]))
                        ))
                    )),
                    v::key('14.4. Установление величины физического износа' /* TODO etc. */),
                    v::key('14.5. Определение (оценка) величины причиненного ущерба', $entities)
                ))
            ))
        );
        $validator = v::keySet(
            v::key('SINGLE_BUILDING', $workSheetValidator),
            v::key('MULTIPLE_BUILDINGS' /* TODO validate multiple_buildings worksheet */)
        );
        try {
            $validator->assert($result);
        } catch (NestedValidationException $e) {
            $this->fail($e->getFullMessage());
        };
    }
}
