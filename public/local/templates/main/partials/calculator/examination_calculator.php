<?
require_once __DIR__.'/CalculatorMacros.php';

use App\View as v;
use App\Templates\CalculatorMacros as macros;

$macros = new macros($state);
?>
<section class="calculator_certain_types calculator">
    <form ic-post-to="<?= $apiEndpoint ?>"
          ic-target="closest calculator"
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
                    <? // TODO radio FOR_LEGAL_CASE ?>
                    <div class="wrap_calc_item">
                        <p class="title">Для суда</p>
                        <div class="inner hidden_block">
                            <div class="left left--radio">
                                <input type="radio" hidden="hidden" checked name="family" id="some_1">
                                <label for="some_1" class="radio_label">Да</label>
                                <input type="radio" hidden="hidden" name="family" id="some_2">
                                <label for="some_2" class="radio_label">Нет</label>
                            </div>
                            <div class="right">
                                <span class="tooltip" title="Lorem ipsum dolor sit amet, consectetur adipisicing elit. Molestias omnis eveniet dolorem maxime architecto fuga perspiciatis illo, voluptatibus numquam vel similique iste pariatur placeat nobis assumenda soluta voluptas aliquid laudantium."></span>
                            </div>
                        </div>
                    </div>
                    <? $macros->showSelect('SITE_CATEGORY', $options['SITE_CATEGORY'], 'Категория экспертизы', ['required' => true]) ?>
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
                    <? // TODO needs_visit and its dropdown block ?>
                    <div class="wrap_calc_item">
                        <p class="title">Необходимость выезда на объект(ы) <span class="red"></span></p>
                        <div class="inner">
                            <div class="left left--radio hidden_block">
                                <input type="radio" hidden="hidden" name="family_11" id="some_11" class="open_block" data-name="some_block_1">
                                <label for="some_11" class="radio_label">Да</label>
                                <input type="radio" hidden="hidden" checked name="family_11" id="some_22" data-name="some_block_1">
                                <label for="some_22" class="radio_label">Нет</label>
                            </div>
                            <div class="right">
                                <span class="tooltip" title="Lorem ipsum dolor sit amet, consectetur adipisicing elit. Molestias omnis eveniet dolorem maxime architecto fuga perspiciatis illo, voluptatibus numquam vel similique iste pariatur placeat nobis assumenda soluta voluptas aliquid laudantium."></span>
                            </div>
                        </div>
                    </div>
                    <div class="wrap_calc_item_block wrap_calc_item_block--some some_block_1">
                        <div class="top">
                            <p class="title">Местонахождение <span class="red">*</span></p>
                        </div>
                        <select name="" id="">
                            <option value="">текст</option>
                            <option value="">текст</option>
                            <option value="">текст</option>
                            <option value="">текст</option>
                            <option value="">текст</option>
                        </select>
                        <div class="top">
                            <p class="title">Адрес(a) <span class="red">*</span></p>
                        </div>
                        <select name="" id="">
                            <option value="">текст</option>
                            <option value="">текст</option>
                            <option value="">текст</option>
                            <option value="">текст</option>
                            <option value="">текст</option>
                        </select>
                        <div class="top">
                            <p class="title">Удаленность объектов друг от друга</p>
                            <p class="text red">При расстоянии между объектами более 3 км расчет необходимо выполнять отдельно для каждого объекта</p>
                        </div>
                        <select name="" id="">
                            <option value="">текст</option>
                            <option value="">текст</option>
                            <option value="">текст</option>
                            <option value="">текст</option>
                            <option value="">текст</option>
                        </select>
                        <div class="top">
                            <p class="title">Транспортная доступность </p>
                        </div>
                        <select name="" id="">
                            <option value="">текст</option>
                            <option value="">текст</option>
                            <option value="">текст</option>
                            <option value="">текст</option>
                            <option value="">текст</option>
                        </select>
                    </div>
                    <? /* TODO

                    <?= v::render('partials/calculator/fields/site_count_and_distance', array_merge(get_defined_vars(), [
                        'site_count' => [
                            'label' => 'Количество объектов мониторинга'
                        ]
                    ])) ?>
                    <? $macros->showTextarea('DESCRIPTION', 'Описание объекта(ов) мониторинга', ['required' => true]) ?>
                    <? $macros->showSelect('LOCATION', $options['LOCATION'], 'Местонахождение', ['required' => true]) ?>
                    <? $macros->showSelect('LOCATION', $options['LOCATION'], 'Местонахождение', ['required' => true]) ?>
                    <? $macros->showTextarea('ADDRESS', 'Адрес(а)') ?>
                    <? $macros->showSelect('USED_FOR', $options['USED_FOR'], 'Назначение объекта(ов) мониторинга', ['required' => true]) ?>
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
                    <? // TODO ?>
                    <? $macros->showSelect('examination_GOAL', $options['examination_GOAL'], 'Цели мониторинга', ['required' => true]) ?>
                    <? $macros->showSelect('DURATION', $options['DURATION'], 'Продолжительность мониторинга', ['required' => true]) ?>
                    <? $macros->showSelect('TRANSPORT_ACCESSIBILITY', $options['TRANSPORT_ACCESSIBILITY'], 'Транспортная доступность', ['required' => true]) ?>
                */ ?>
                </div>
                <div class="right_side">
                    <? $macros->showSelect('GOALS_FILTER', $options['GOALS_FILTER'], 'Цели и задачи экспертизы', ['required' => true]) ?>
                    <? foreach ($options['GOAL_UI_ELEMENTS'] as $filterValue => $elements): ?>
                        <? foreach ($elements as $i => $element): ?>
                            <? if ($element['type'] === 'subsection'): ?>
                                <? $id = "goal_{$filterValue}_{$i}" ?>
                                <div class="wrap_calc_item hidden_block">
                                    <? // TODO checked state ?>
                                    <input type="radio" hidden="hidden" name="family_2" id="<?= $id ?>" data-name="some_block_3">
                                    <label for="<?= $id ?>" class="radio_label"><?= v::capitalize($element['value']) ?></label>
                                </div>
                            <? elseif ($element['type'] === 'options'): ?>
                                <? // TODO tmp style display ?>
                                <div style="display: block;" class="wrap_calc_item_block wrap_calc_item_block--checkbox some_block_3">
                                    <? foreach ($element['value'] as $j => $el): ?>
                                        <? if ($el['type'] === 'subsection'): ?>
                                            <p class="bold"><?= $el['value'] ?></p>
                                        <? elseif ($el['type'] === 'option'): ?>
                                            <? $opt = $el['value'] ?>
                                            <? $id = "goal_{$filterValue}_{$i}_{$j}" ?>
                                            <div class="wrap_checkbox">
                                                <input value="<?= $opt['value'] ?>" type="checkbox" hidden="hidden" id="<?= $id ?>">
                                                <label for="<?= $id ?>"><?= $opt['text'] ?></label>
                                            </div>
                                        <? endif ?>
                                    <? endforeach ?>
                                </div>
                            <? endif ?>
                        <? endforeach ?>
                    <? endforeach ?>
                    <? $macros->showCheckboxList('DOCUMENTS', $options['DOCUMENTS'], 'Наличие документов', ['required' => true]) ?>
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
                <button type="submit" class="big_btn">
                    <span class="text"><span>Выполнить расчет</span></span>
                    <span class="img">
    <img src="<?= v::asset('images/calc.svg') ?>">
  </span>
                </button>
            </div>
        </div>
        <?= v::render('partials/calculator/result_block', $resultBlock) ?>
    </form>
</section>
