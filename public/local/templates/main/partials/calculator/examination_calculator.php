<?
require_once __DIR__.'/CalculatorMacros.php';

use App\View as v;
use App\Templates\CalculatorMacros as macros;

$macros = new macros($state);
?>
<section class="calculator_certain_types calculator calculator--examination">
    <form ic-post-to="<?= $apiEndpoint ?>"
          ic-target="closest .calculator"
          ic-replace-target="true"
          novalidate>
        <div class="wrap">
            <div class="wrap_title">
                <h2>On-line калькулятор</h2>
            </div>
        </div>
        <div class="calculator_certain_types_top">
            <div class="wrap">
                <div class="top">
                    <p><?= $heading ?></p>
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
            </div>
        </div>
        <div class="calculator_content">
            <div class="wrap">
                <div class="left_side">
                    <? $macros->showInput('SITE_COUNT', 'Количество объектов экспертизы', [
                        'required' => true,
                        'type' => 'number',
                        'input_attrs' => v::attrs([
                            'min' => 1,
                            'ic-post-to' => $apiEndpoint.'?validate=0'
                        ])
                    ]) ?>
                    <? $macros->showTextarea('DESCRIPTION', 'Описание объекта(ов) экспертизы', ['required' => true]) ?>
                    <? $macros->showSelect('SITE_CATEGORY', $options['SITE_CATEGORY'], 'Категория предметов экспертизы', ['required' => true]) ?>
                    <div class="wrap_calc_item">
                        <? $name = 'NEEDS_VISIT' ?>
                        <? $needsVisit = v::get($state, ['params', $name], false) ?>
                        <p class="title">Необходимость выезда на объект(ы) <span class="red"></span></p>
                        <div class="inner">
                            <div class="left left--radio hidden_block">
                                <input type="radio" hidden="hidden" value="1" <?= $needsVisit ? 'checked' : '' ?> name="<?= $name ?>" id="<?= $name.'_true' ?>" class="open_block" data-name="needs-visit-dropdown">
                                <label for="<?= $name.'_true' ?>" class="radio_label">Да</label>
                                <input type="radio" hidden="hidden" value="0" <?= !$needsVisit ? 'checked' : '' ?> name="<?= $name ?>" id="<?= $name.'_false' ?>" data-name="needs-visit-dropdown">
                                <label for="<?= $name.'_false' ?>" class="radio_label">Нет</label>
                            </div>
                            <div class="right">
                                <? $macros->showTooltip($name) ?>
                            </div>
                        </div>
                    </div>
                    <? // needs_visit expanding block ?>
                    <div <?= $needsVisit ? 'style="display: block"' : '' ?> class="wrap_calc_item_block wrap_calc_item_block--some needs-visit-dropdown">
                        <?
                        $wrapInput = function($name, $contentFn) use ($macros) {
                            list($_, $error) = $macros->valueErrorPair($name);
                            ?>
                            <div class="<?= !v::isEmpty($error) ? 'error' : '' ?>">
                                <? $contentFn($name) ?>
                            </div>
                            <?
                        };
                        ?>
                        <? $wrapInput('LOCATION', function($name) use ($macros, $options) { ?>
                            <div class="top">
                                <p class="title">Местонахождение <span class="red">*</span></p>
                            </div>
                            <? $macros->showSelectInput($name, $options[$name]) ?>
                        <? }) ?>
                        <? $wrapInput('ADDRESS', function($name) use ($macros) { ?>
                            <div class="top">
                                <p class="title">Адрес(a)</p>
                            </div>
                            <? $macros->showInputInput($name) ?>
                        <? }) ?>
                        <? if (v::get($state, ['params', 'SITE_COUNT'], 1) > 1): ?>
                            <? $wrapInput('DISTANCE_BETWEEN_SITES', function($name) use ($macros, $options) { ?>
                                <? $macros->showDistanceSelect($name, $options[$name], 'Удаленность объектов друг от друга', [
                                    'required' => true,
                                    // TODO show warning? see requirements
                                    'show_warning' => false
                                ]) ?>
                            <? }) ?>
                        <? endif ?>
                        <? $wrapInput('TRANSPORT_ACCESSIBILITY', function($name) use ($macros, $options) { ?>
                            <div class="top">
                                <p class="title">Транспортная доступность <span class="red">*</span></p>
                            </div>
                            <? $macros->showSelectInput($name, $options[$name]) ?>
                        <? }) ?>
                    </div>
                    <div class="wrap_calc_item">
                        <? $name = 'FOR_LEGAL_CASE' ?>
                        <p class="title">Для суда</p>
                        <div class="inner hidden_block">
                            <div class="left left--radio">
                                <? $value = v::get($state, ['params', $name], false) ?>
                                <input type="radio" hidden="hidden" value="1" <?= $value ? 'checked' : '' ?> name="<?= $name ?>" id="<?= $name.'_true' ?>">
                                <label for="<?= $name.'_true' ?>" class="radio_label">Да</label>
                                <input type="radio" hidden="hidden" value="0" <?= !$value ? 'checked' : '' ?> name="<?= $name ?>" id="<?= $name.'_false' ?>">
                                <label for="<?= $name.'_false' ?>" class="radio_label">Нет</label>
                            </div>
                            <div class="right">
                                <? $macros->showTooltip($name) ?>
                            </div>
                        </div>
                    </div>
                    <? $macros->showSelect('USED_FOR', $options['USED_FOR'], 'Назначение объекта(ов) экспертизы', ['required' => true]) ?>
                    <? $macros->showInput('TOTAL_AREA', 'Общая площадь объекта(ов), кв.м', [
                        'required' => true,
                        'type' => 'number',
                        'input_attrs' => v::attrs([
                            'min' => 1
                        ])
                    ]) ?>
                    <? $macros->showInput('VOLUME', 'Строительный объем объекта(ов), куб.м', [
                        'type' => 'number',
                        'input_attrs' => v::attrs([
                            'min' => 0
                        ])
                    ]) ?>
                    <div id="examination-floors-group">
                        <? $macros->showInputGroup('FLOORS[]', $floorInputs, 'Количество надземных этажей', [
                            'required' => true,
                            'type' => 'number',
                            'input_attrs' => v::attrs([
                                'min' => 0
                            ])
                        ]) ?>
                    </div>
                    <?= v::render('partials/calculator/fields/underground_floors', get_defined_vars()) ?>
                </div>
                <div class="right_side">
                    <? $macros->showSelect('GOALS_FILTER', $options['GOALS_FILTER'], 'Цели и задачи экспертизы', [
                        'required' => true,
                        'select_attrs' => v::attrs([
                            'class' => 'goals-filter'
                        ])
                    ]) ?>
                    <? list($value, $error) = $macros->valueErrorPair('GOALS') ?>
                    <div class="goals">
                        <? foreach ($options['GOAL_UI_ELEMENTS'] as $idx => $elements): ?>
                            <? $localState = ['last_expandable_id' => ''] ?>
                            <? $filterVal = strval($idx) ?>
                            <? $group = "goals_{$filterVal}" ?>
                            <? $isActive = $state['params']['GOALS_FILTER'] === $filterVal ?>
                            <div data-goals-filter="<?= $filterVal ?>" style="display: <?= $isActive ? 'block' : 'none' ?>">
                                <? foreach ($elements as $i => $element): ?>
                                    <? if ($element['type'] === 'subsection'): ?>
                                        <? $id = "goals_{$filterVal}_{$i}" ?>
                                        <? $localState['last_expandable_id'] = $id ?>
                                        <div class="wrap_calc_item">
                                            <p class="calculator__expandable-title" data-state="collapsed" data-target="<?= '#'.$id ?>" role="button">
                                                <span class="text"><?= v::capitalize($element['value']) ?></span>
                                            </p>
                                        </div>
                                    <? elseif ($element['type'] === 'options'): ?>
                                        <? $isActive = v::get($state, ['params', $group]) === $localState['last_expandable_id'] ?>
                                        <? $noAssocExpandable = v::isEmpty($localState['last_expandable_id']) ?>
                                        <? $classes = ["group_{$group}"] ?>
                                        <div class="<?= join(' ', $classes) ?> wrap_calc_item_block wrap_calc_item_block--checkbox"
                                             id="<?= $id ?>"
                                            <? // display if there is no expandable button to reveal this block ?>
                                             style="<?= $isActive || $noAssocExpandable ? 'display: block' : '' ?>">
                                            <? foreach ($element['value'] as $j => $el): ?>
                                                <? if ($el['type'] === 'subsection'): ?>
                                                    <p class="bold"><?= $el['value'] ?></p>
                                                <? elseif ($el['type'] === 'option'): ?>
                                                    <? $opt = $el['value'] ?>
                                                    <? $id = "goal_{$filterVal}_{$i}_{$j}" ?>
                                                    <? $isChecked = in_array($opt['value'], v::get($state, 'params.GOALS', [])) ?>
                                                    <div class="wrap_checkbox">
                                                        <input name="GOALS[]"
                                                               value="<?= $opt['value'] ?>"
                                                               type="checkbox"
                                                               hidden="hidden"
                                                               id="<?= $id ?>"
                                                               <?= $isChecked ? ' checked' : '' ?>>
                                                        <label for="<?= $id ?>"><?= $opt['text'] ?></label>
                                                    </div>
                                                <? endif ?>
                                            <? endforeach ?>
                                        </div>
                                    <? endif ?>
                                <? endforeach ?>
                            </div>
                        <? endforeach ?>
                    </div>
                    <? $macros->showCheckboxList('DOCUMENTS', $options['DOCUMENTS'], 'Наличие документов') ?>
                </div>
            </div>
        </div>
        <div class="calculator_content_robot">
            <div class="wrap_robot_block">
                <?= v::render('partials/form_error_message', [
                    'errors' => $state['errors'],
                    'action' => 'рассчитать стоимость'
                ]) ?>
                <?= v::render('partials/form_loader') ?>
                <?= v::render('partials/calculator/submit_button') ?>
            </div>
        </div>
        <?= v::render('partials/calculator/result_block', $resultBlock) ?>
    </form>
</section>
<? v::appendToView('modals', v::render('partials/services_modals', ['services' => $services])) ?>
