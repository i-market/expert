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
                    <input type="radio" hidden="hiden" checked name="family_2" id="some_3" data-name="underground_floors_2">
                    <label for="some_3" class="radio_label">Комплексный мониторинг состояния строительных конструкций, зданий и сооружений</label>
                    <input type="radio" hidden="hiden" name="family_2" id="some_4" data-name="underground_floors_2" class="open_block">
                    <label for="some_4" class="radio_label">Выборочное обследование</label>
                </div>
                <div class="wrap_calc_item_block wrap_calc_item_block--checkbox underground_floors_2">
                    <div class="wrap_checkbox">
                        <input type="checkbox" hidden="hidden" id="some_5">
                        <label for="some_5">Мониторинг состояния фундаментов</label>
                    </div>
                    <div class="wrap_checkbox">
                        <input type="checkbox" hidden="hidden" id="some_5">
                        <label for="some_5">Мониторинг состояния технических подпольев, цокольных помещений, подвальных помещений, подземных гаражей и стоянок</label>
                    </div>
                    <div class="wrap_checkbox">
                        <input type="checkbox" hidden="hidden" id="some_5">
                        <label for="some_5">Комплексный мониторинг состояния полов выполненных по грунтовому основанию (бетонных, железобетонных, фибробетонных)</label>
                    </div>
                    <div class="wrap_checkbox">
                        <input type="checkbox" hidden="hidden" id="some_5">
                        <label for="some_5">Мониторинг состояния стен, колонн, пилонов и пр.</label>
                    </div>
                    <div class="wrap_checkbox">
                        <input type="checkbox" hidden="hidden" id="some_5">
                        <label for="some_5">Мониторинг состояния окон, дверей, витражных и светопрозрачных конструкций</label>
                    </div>
                    <div class="wrap_checkbox">
                        <input type="checkbox" hidden="hidden" id="some_5">
                        <label for="some_5">Мониторинг состояния перекрытий, лестничных площадок и маршей, покрфтий</label>
                    </div>
                    <div class="wrap_checkbox">
                        <input type="checkbox" hidden="hidden" id="some_5">
                        <label for="some_5">Мониторинг состояния конструкций кровли</label>
                    </div>
                    <div class="wrap_checkbox">
                        <input type="checkbox" hidden="hidden" id="some_5">
                        <label for="some_5">Мониторинг состояния бассейнов, резервуаров</label>
                    </div>
                </div>
                <div class="wrap_calc_item">
                    <p class="title">Наличие документов: <span class="red">*</span></p>
                    <div class="wrap_checkbox">
                        <input type="checkbox" hidden="hidden" id="some_5">
                        <label for="some_5">Результаты выполненых обследований или экспертиз</label>
                    </div>
                    <div class="wrap_checkbox">
                        <input type="checkbox" hidden="hidden" id="some_5">
                        <label for="some_5">Результаты ранее проведенного мониторинга</label>
                    </div>
                    <div class="wrap_checkbox">
                        <input type="checkbox" hidden="hidden" id="some_5">
                        <label for="some_5">Результаты гидрогеологических изысканий</label>
                    </div>
                    <div class="wrap_checkbox">
                        <input type="checkbox" hidden="hidden" id="some_5">
                        <label for="some_5">Проектная документация</label>
                    </div>
                    <div class="wrap_checkbox">
                        <input type="checkbox" hidden="hidden" id="some_5">
                        <label for="some_5">Рабочая документация</label>
                    </div>
                    <div class="wrap_checkbox">
                        <input type="checkbox" hidden="hidden" id="some_5">
                        <label for="some_5">Планы БТИ</label>
                    </div>
                </div>
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
