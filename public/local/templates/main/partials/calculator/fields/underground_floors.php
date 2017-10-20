<?
use App\View as v;
use App\Templates\CalculatorMacros as macros;
?>
<? $hasUndergroundFloors = 'HAS_UNDERGROUND_FLOORS' ?>
<div class="wrap_calc_item">
    <p class="title">Наличие подполья, подвала, подземных этажей</p>
    <div class="inner">
        <div class="left left--radio hidden_block">
            <input name="<?= $hasUndergroundFloors ?>" value="1"<?= $state['params'][$hasUndergroundFloors] ? ' checked' : '' ?> type="radio" hidden="hidden" id="some_111" data-name="underground_floors" class="open_block">
            <label for="some_111" class="radio_label">Да</label>
            <input name="<?= $hasUndergroundFloors ?>" value="0"<?= !$state['params'][$hasUndergroundFloors] ? ' checked' : '' ?> type="radio" hidden="hidden" id="some_222" data-name="underground_floors">
            <label for="some_222" class="radio_label">Нет</label>
        </div>
        <div class="right">
            <? $macros->showTooltip($hasUndergroundFloors) ?>
        </div>
    </div>
</div>
<? // TODO refactor ?>
<? $name = 'UNDERGROUND_FLOORS' ?>
<? $label = 'Количество подземных этажей' ?>
<? $opts = [
    'required' => true,
    'class' => 'underground_floors',
    'show' => $showUndergroundFloors,
    'type' => 'number',
    'input_attrs' => v::attrs([
        'min' => 1
    ])
] ?>
<? list($value, $error) = $macros->valueErrorPair($name); ?>
<div class="wrap_calc_item_block<?= !v::isEmpty($error) ? ' error' : '' ?><?= $opts['class'] ? ' '.$opts['class'] : '' ?>"<?= $opts['show'] ? ' style="display: block"' : '' ?>>
    <div class="top">
        <p class="title"><?= $label.($opts['required'] ? macros::$requiredMark : '') ?></p>
    </div>
    <input name="<?= $name ?>" value="<?= $value ?>" type="<?= $opts['type'] ? $opts['type'] : 'text' ?>"<?= isset($opts['input_attrs']) ? ' '.$opts['input_attrs'] : '' ?>>
    <div class="error-message"><?= $error ?></div>
    <div class="bottom">
        <div class="text text--hint">
            <? $APPLICATION->IncludeComponent(
                "bitrix:main.include",
                "",
                Array(
                    "AREA_FILE_SHOW" => "file",
                    "PATH" => v::includedArea('what-we-do/calculators/underground_floors_hint.php')
                )
            ); ?>
        </div>
    </div>
</div>
