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
                    <?= v::render('partials/calculator/fields/site_count_and_distance', array_merge(get_defined_vars(), [
                        'site_count' => [
                            'label' => 'Количество объектов надзора'
                        ]
                    ])) ?>
                    <? $macros->showTextarea('DESCRIPTION', 'Описание объекта(ов) надзора', ['required' => true]) ?>
                    <? $macros->showSelect('LOCATION', $options['LOCATION'], 'Местонахождение', ['required' => true]) ?>
                    <? $macros->showTextarea('ADDRESS', 'Адрес(а)') ?>
                    <? $macros->showSelect('USED_FOR', $options['USED_FOR'], 'Назначение объекта(ов)', ['required' => true]) ?>
                    <? $macros->showSelect('CONSTRUCTION_TYPE', $options['CONSTRUCTION_TYPE'], 'Вид строительства', ['required' => true]) ?>
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
                    <? $macros->showInputGroup('FLOORS[]', $floorInputs, 'Количество надземных этажей', [
                        'required' => true,
                        'type' => 'number',
                        'input_attrs' => v::attrs([
                            'min' => 0
                        ])
                    ]) ?>
                    <?= v::render('partials/calculator/fields/underground_floors', get_defined_vars()) ?>
                    <? $macros->showSelect('DURATION', $options['DURATION'], 'Продолжительность технического надзора', ['required' => true]) ?>
                    <? $macros->showSelect('TRANSPORT_ACCESSIBILITY', $options['TRANSPORT_ACCESSIBILITY'], 'Транспортная доступность', ['required' => true]) ?>
                </div>
                <div class="right_side">
                    <? $macros->showCheckboxList('CONSTRUCTION_PHASE', $options['CONSTRUCTION_PHASE'], 'Этап строительства', [
                        'required' => true,
                        'class' => 'construction-phase'
                    ]) ?>
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
