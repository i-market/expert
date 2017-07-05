<?
use App\View as v;
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
<? $macros->showConditionalInput('UNDERGROUND_FLOORS', 'Количество подземных этажей', [
    'required' => true,
    'class' => 'underground_floors',
    'show' => $showUndergroundFloors,
    'type' => 'number',
    'input_attrs' => v::attrs([
        'min' => 1
    ])
]) ?>
