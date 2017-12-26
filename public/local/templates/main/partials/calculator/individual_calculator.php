<?
require_once __DIR__.'/CalculatorMacros.php';

use App\View as v;
use App\Templates\CalculatorMacros as macros;

$macros = new macros($state);
?>
<section class="calculator_certain_types calculator">
    <form ic-post-to="<?= $apiEndpoint ?>"
          ic-target="closest .calculator"
          ic-replace-target="true"
          novalidate>
        <input type="hidden" name="order" value="<?= v::get($state, 'params.order') ?>">
        <div class="wrap">
            <div class="wrap_title">
                <h1 class="h2">On-line калькулятор</h1>
            </div>
        </div>
        <div class="calculator_certain_types_top">
            <div class="wrap">
                <div class="top">
                    <? $APPLICATION->IncludeComponent(
                        "bitrix:main.include",
                        "",
                        Array(
                            "AREA_FILE_SHOW" => "file",
                            "PATH" => v::includedArea('what-we-do/calculators/individual_heading.php')
                        )
                    ); ?>
                </div>
                <div class="middle">
                    <? $APPLICATION->IncludeComponent(
                        "bitrix:main.include",
                        "",
                        Array(
                            "AREA_FILE_RECURSIVE" => "Y",
                            "AREA_FILE_SHOW" => "sect",
                            "AREA_FILE_SUFFIX" => 'calculator_form_help',
                            "EDIT_TEMPLATE" => "",
                            "PATH" => ""
                        )
                    ); ?>
                </div>
                <div class="bottom">
                    <? $APPLICATION->IncludeComponent(
                        "bitrix:main.include",
                        "",
                        Array(
                            "AREA_FILE_SHOW" => "file",
                            "PATH" => v::includedArea('what-we-do/calculators/individual_bottom.php')
                        )
                    ); ?>
                </div>
            </div>
        </div>
        <? foreach ($options as $subsection => $xs): ?>
            <table class="calculator_certain_types_table" width="100%">
                <thead>
                <tr>
                    <td colspan="6" class="title"><?= $subsection ?></td>
                </tr>
                <tr class="hidden">
                    <td>Вид работ</td>
                    <td>Цель работ</td>
                    <td>Единица измерения</td>
                    <td>Стоимость, руб.</td>
                    <td>Сроки, раб. дн.</td>
                    <td>Выбрать</td>
                </tr>
                </thead>
                <tbody>
                <? foreach ($xs as $x): ?>
                    <? if ($x['type'] === 'subsection'): ?>
                        <tr class="sub_title">
                            <td colspan="6"><?= $x['value'] ?></td>
                        </tr>
                    <? elseif ($x['type'] === 'entities'): ?>
                        <? foreach($x['value'] as $entity): ?>
                            <? // TODO extract predicate ?>
                            <? if (v::isEmpty($entity['PRICE']) || $entity['PRICE'] == 1): ?>
                                <tr class="hidden">
                                    <td><?= $entity['NAME'] ?></td>
                                    <td><?= $entity['GOAL'] ?></td>
                                    <td colspan="4">
                                        <div class="info">
                                            <p class="red">Данные работы выполняются только в составе комплексной или выборочной экспертизы, обследования или мониторинга здания (ий), сооружения (ий)</p>
                                            <div class="wrap_btn">
                                                <a class="btn_table" href="<?= v::path('what-we-do/examination/calculator') ?>">Экспертиза</a>
                                                <a class="btn_table" href="<?= v::path('what-we-do/inspection/calculator') ?>">Обследование</a>
                                                <a class="btn_table" href="<?= v::path('what-we-do/monitoring/calculator') ?>">Мониторинг</a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            <? else: ?>
                                <tr>
                                    <td><?= $entity['NAME'] ?></td>
                                    <td><?= $entity['GOAL'] ?></td>
                                    <td><?= $entity['UNIT'] ?></td>
                                    <td>
                                        <? $oldPricesEnabled = false ?>
                                        <? if ($oldPricesEnabled && !v::isEmpty($entity['OLD_PRICE'])): ?>
                                            <p class="through"><?= $formatPrice($entity['OLD_PRICE']) ?></p>
                                        <? endif ?>
                                        <p><?= $formatPrice($entity['PRICE']) ?></p>
                                    </td>
                                    <td><?= $entity['DURATION'] ?></td>
                                    <td>
                                        <div class="wrap_checkbox">
                                            <? $isChecked = in_array($entity['ID'], v::get($state, 'params.SERVICES', [])) ?>
                                            <? $id = 'option_'.$entity['ID'] ?>
                                            <input ic-post-to="<?= $apiEndpoint.'?result=0' ?>"
                                                   name="SERVICES[]"
                                                   value="<?= $entity['ID'] ?>"
                                                   type="checkbox"
                                                   hidden="hidden"
                                                <?= $isChecked ? 'checked' : '' ?>
                                                   id="<?= $id ?>"
                                                   class="ordered">
                                            <label for="<?= $id ?>"></label>
                                        </div>
                                    </td>
                                </tr>
                            <? endif ?>
                        <? endforeach ?>
                    <? endif ?>
                <? endforeach ?>
                </tbody>
            </table>
        <? endforeach ?>
        <div class="calculator_content_robot">
            <div class="wrap_robot_block">
                <? // TODO refactor ?>
                <? if (!v::isEmpty(v::get($state, 'errors.SERVICES'))): ?>
                    <div class="form-message error">
                        <span>Пожалуйста, выберите вид работ выше.</span>
                    </div>
                <? endif ?>
                <?= v::render('partials/form_loader') ?>
                <?= v::render('partials/calculator/submit_button') ?>
            </div>
        </div>
        <?= v::render('partials/calculator/result_block', $resultBlock) ?>
    </form>
</section>
