<?
require_once __DIR__.'/CalculatorMacros.php';

use App\View as v;
use App\Templates\CalculatorMacros as macros;

$macros = new macros($state);
?>
<section class="calculator_certain_types calculator--monitoring">
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
                <? $macros->showTextarea('DESCRIPTION', 'Описание объекта(ов) мониторинга', ['required' => true]) ?>
                <? $macros->showSelect('LOCATION', $options['LOCATION'], 'Местонахождение', ['required' => true]) ?>
                <? $macros->showTextarea('ADDRESS', 'Адрес(а)') ?>
                <? $macros->showInput('SITE_COUNT', 'Количество объектов мониторинга', [
                    'required' => true,
                    'type' => 'number',
                    'input_attrs' => v::attrs([
                        'class' => 'site-count',
                        'min' => 1,
                        'ic-post-to' => $floorsApiUri,
                        'ic-target' => '#monitoring-floors-group',
                        'ic-replace-target' => 'true',
                        'ic-indicator' => '#monitoring-floors-group .loader'
                    ])
                ]) ?>
                <? $macros->showOptionalSelect('DISTANCE_BETWEEN_SITES', $options['DISTANCE_BETWEEN_SITES'], 'Удаленность объектов друг от друга', [
                    'required' => true,
                    'show' => $showDistanceSelect,
                    'class' => 'distance-between-sites',
                    'show_warning' => $showDistanceWarning
                ]) ?>
                <? $macros->showSelect('USED_FOR', $options['USED_FOR'], 'Назначение объекта(ов) мониторинга', ['required' => true]) ?>
                <? $macros->showInput('TOTAL_AREA', 'Общая площадь объекта(ов), кв.м', ['required' => true]) ?>
                <? $macros->showInput('VOLUME', 'Строительный объем объекта(ов), куб.м') ?>
                <?= v::render('partials/calculator/monitoring_floors_group', ['floorSelects' => $floorSelects]) ?>
                <? $hasUndergroundFloors = 'HAS_UNDERGROUND_FLOORS' ?>
                <div class="wrap_calc_item">
                    <p class="title">Наличие подполья, подвала, подземных этажей</p>
                    <div class="inner">
                        <div class="left left--radio hidden_block">
                            <input name="<?= $hasUndergroundFloors ?>" value="1" type="radio" hidden="hidden" id="some_111" data-name="underground_floors" class="open_block">
                            <label for="some_111" class="radio_label">Да</label>
                            <input name="<?= $hasUndergroundFloors ?>" value="0" type="radio" hidden="hidden" checked id="some_222" data-name="underground_floors">
                            <label for="some_222" class="radio_label">Нет</label>
                        </div>
                        <div class="right">
                            <? $macros->showTooltip($hasUndergroundFloors) ?>
                        </div>
                    </div>
                </div>
                <? $macros->showConditionalInput('UNDERGROUND_FLOORS', 'Количество подземных этажей', [
                    'required' => true,
                    'class' => 'underground_floors',
                    'show' => $showUndergroundFloors,
                    'type' => 'number',
                    'input_attrs' => v::attrs([
                        'min' => 1
                    ])
                ]) ?>
                <? $macros->showSelect('MONITORING_GOAL', $options['MONITORING_GOAL'], 'Цели мониторинга', ['required' => true]) ?>
                <? $macros->showSelect('DURATION', $options['DURATION'], 'Продолжительность мониторинга', ['required' => true]) ?>
                <? $macros->showSelect('TRANSPORT_ACCESSIBILITY', $options['TRANSPORT_ACCESSIBILITY'], 'Транспортная доуступность', ['required' => true]) ?>
            </div>
            <div class="right_side">
                <? $packageSelection = 'PACKAGE_SELECTION' ?>
                <div class="wrap_calc_item">
                    <div class="inner">
                        <div class="left">
                            <p class="title">Конструкции подлежащие мониторингу: <span class="red">*</span></p>
                        </div>
                        <div class="right">
                            <span class="tooltip" title="Lorem ipsum dolor sit amet, consectetur adipisicing elit. Molestias omnis eveniet dolorem maxime architecto fuga perspiciatis illo, voluptatibus numquam vel similique iste pariatur placeat nobis assumenda soluta voluptas aliquid laudantium."></span>
                        </div>
                    </div>
                </div>
                <div class="wrap_calc_item hidden_block">
                    <? // TODO checked state ?>
                    <input type="radio" hidden="hidden" checked name="<?= $packageSelection ?>" value="PACKAGE" id="some_3" data-name="package-selection-individual">
                    <label for="some_3" class="radio_label">Комплексный мониторинг состояния строительных конструкций, зданий и сооружений</label>
                    <input type="radio" hidden="hidden" name="<?= $packageSelection ?>" value="INDIVIDUAL" id="some_4" data-name="package-selection-individual" class="open_block">
                    <label for="some_4" class="radio_label">Выборочное обследование</label>
                </div>
                <div class="wrap_calc_item_block wrap_calc_item_block--checkbox package-selection-individual">
                    <? $prefix = \Core\Util::uniqueId().'_STRUCTURES_TO_MONITOR_INDIVIDUAL' ?>
                    <? foreach ($options['STRUCTURES_TO_MONITOR']['INDIVIDUAL'] as $idx => $option): ?>
                        <? $id = "{$prefix}_{$idx}" ?>
                        <div class="wrap_checkbox">
                            <input type="checkbox" name="STRUCTURES_TO_MONITOR[]" value="<?= $option['value'] ?>" hidden="hidden" id="<?= $id ?>">
                            <label for="<?= $id ?>"><?= $option['text'] ?></label>
                        </div>
                    <? endforeach ?>
                </div>
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
    <img src="<?= v::asset('images/calc.svg') ?>" alt="">
  </span>
            </button>
        </div>
    </div>
    <div class="total_price_block">
        <div class="inner">
            <div class="block">
                <div class="total_price">
                    <p>Стоимость работ: <span>150 000 руб/мес</span></p>
                    <p>Продолжительность выполнения работ: <span>18 месяцев</span></p>
                </div>
                <h4>Получите коммерческое предложение на почту</h4>
                <div class="commercial_proposal">
                    <input type="text" placeholder="Введите ваш E-mail">
                    <label>
                        <button type="submit">Получить предложение</button>
                        <span class="ico"></span>
                    </label>
                </div>
            </div>
        </div>
    </div>
</section>
