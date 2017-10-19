<?php

namespace App\Templates;

use App\View as v;
use Core\Util;
use Core\Underscore as _;

class CalculatorMacros {
    static $requiredMark = '&nbsp;<span class="red">*</span>';
    static private $selectPlaceholder = '<option value="" hidden>Выбрать...</option>';
    private $state;

    function __construct($state) {
        // params and errors
        $this->state = $state;
    }

    function valueErrorPair($name) {
        $path = Util::formInputNamePath($name);
        $value = _::get($this->state['params'], $path);
        $error = _::get($this->state['errors'], $path);
        return [$value, $error];
    }

    static function showTooltip($name) {
        global $APPLICATION;
        $APPLICATION->IncludeComponent(
            "bitrix:main.include",
            "calculator_tooltip",
            Array(
                "AREA_FILE_RECURSIVE" => "Y",
                "AREA_FILE_SHOW" => "sect",
                "AREA_FILE_SUFFIX" => 'calculator_tooltip_'.$name,
                "EDIT_TEMPLATE" => "",
                "PATH" => ""
            )
        );
    }

    function showTextarea($name, $label, $opts = []) {
        list($value, $error) = $this->valueErrorPair($name);
        ?>
        <div class="wrap_calc_item<?= !v::isEmpty($error) ? ' error' : '' ?>">
            <p class="title"><?= $label.($opts['required'] ? self::$requiredMark : '') ?></p>
            <div class="inner">
                <div class="left<?= !v::isEmpty($error) ? ' error' : '' ?>">
                    <textarea name="<?= $name ?>" class="stretch-to-fit"><?= $value ?></textarea>
                    <div class="error-message"><?= $error ?></div>
                </div>
                <div class="right">
                    <? self::showTooltip($name) ?>
                </div>
            </div>
        </div>
        <?
    }

    function showInputInput($name, $opts = []) {
        list($value, $error) = $this->valueErrorPair($name);
        ?>
        <input name="<?= $name ?>" value="<?= $value ?>" type="<?= $opts['type'] ? $opts['type'] : 'text' ?>"<?= isset($opts['input_attrs']) ? ' '.$opts['input_attrs'] : '' ?>>
        <div class="error-message"><?= $error ?></div>
        <?
    }

    function showInput($name, $label, $opts = []) {
        list($value, $error) = $this->valueErrorPair($name);
        ?>
        <div class="wrap_calc_item<?= !v::isEmpty($error) ? ' error' : '' ?>">
            <p class="title"><?= $label.($opts['required'] ? self::$requiredMark : '') ?></p>
            <div class="inner">
                <div class="left">
                    <? $this->showInputInput($name, $opts) ?>
                </div>
                <div class="right">
                    <? self::showTooltip($name) ?>
                </div>
            </div>
        </div>
        <?
    }

    function showInputGroup($name, $inputs, $label, $opts = []) {
        list($value, $error) = $this->valueErrorPair($name);
        ?>
        <div class="wrap_calc_item group<?= !v::isEmpty($error) ? ' error' : '' ?>">
            <p class="title"><?= $label.($opts['required'] ? self::$requiredMark : '') ?></p>
            <? foreach ($inputs as $idx => $input): ?>
                <? $val = isset($value[$idx]) ? $value[$idx] : '' ?>
                <? $isLast = $idx === count($inputs) - 1 ?>
                <div class="inner inner_some">
                    <div class="left">
                        <span class="text" style="white-space: nowrap"><?= $input['label'] ?></span>
                        <input name="<?= $name ?>" value="<?= $val ?>" type="<?= $opts['type'] ? $opts['type'] : 'text' ?>"<?= isset($opts['input_attrs']) ? ' '.$opts['input_attrs'] : '' ?>>
                    </div>
                    <? if ($idx === 0): ?>
                        <div class="right">
                            <? self::showTooltip($name) ?>
                        </div>
                    <? endif ?>
                </div>
                <? if ($isLast): ?>
                    <div class="error-message"><?= $error ?></div>
                <? endif ?>
            <? endforeach ?>
        </div>
        <?
    }

    function showSelectInput($name, $options, $opts = []) {
        list($value, $error) = $this->valueErrorPair($name);
        ?>
        <select name="<?= $name ?>"<?= isset($opts['select_attrs']) ? ' '.$opts['select_attrs'] : '' ?>>
            <?= self::$selectPlaceholder ?>
            <? foreach ($options as $option): ?>
                <? $selected = $value === $option['value'] ?>
                <option value="<?= $option['value'] ?>"<?= $selected ? ' selected' : '' ?>><?= $option['text'] ?></option>
            <? endforeach ?>
        </select>
        <div class="error-message"><?= $error ?></div>
        <?
    }

    function showSelect($name, $options, $label, $opts) {
        list($value, $error) = $this->valueErrorPair($name);
        ?>
        <div class="wrap_calc_item<?= !v::isEmpty($error) ? ' error' : '' ?>">
            <p class="title"><?= $label.($opts['required'] ? self::$requiredMark : '') ?></p>
            <div class="inner">
                <div class="left">
                    <? $this->showSelectInput($name, $options, $opts) ?>
                </div>
                <div class="right">
                    <? self::showTooltip($name) ?>
                </div>
            </div>
        </div>
        <?
    }

    function showDistanceSelect($name, $options, $label, $opts) {
        list($value, $error) = $this->valueErrorPair($name);
        ?>
        <div class="top">
            <p class="title"><?= $label.($opts['required'] ? self::$requiredMark : '') ?></p>
            <? // TODO refactor: monitoring-specific warning ?>
            <? // TODO stop users from submitting the form when this condition is met ?>
            <p class="text red warning" style="display: <?= $opts['show_warning'] ? 'block' : 'none' ?>">
                При расстоянии между объектами более 3 км расчет необходимо выполнять отдельно для каждого объекта
            </p>
        </div>
        <select name="<?= $name ?>">
            <?= self::$selectPlaceholder ?>
            <? foreach ($options as $option): ?>
                <? $selected = $value === $option['value'] ?>
                <option value="<?= $option['value'] ?>"<?= $selected ? ' selected' : '' ?>><?= $option['text'] ?></option>
            <? endforeach ?>
        </select>
        <? if (!$opts['show_warning']): ?>
            <div class="error-message"><?= $error ?></div>
        <? endif ?>
        <?
    }

    function showOptionalSelect($name, $options, $label, $opts) {
        list($value, $error) = $this->valueErrorPair($name);
        ?>
        <div class="wrap_calc_item_block<?= !v::isEmpty($error) ? ' error' : '' ?><?= $opts['class'] ? ' '.$opts['class'] : '' ?>"<?= $opts['show'] ? 'style=" display: block"' : '' ?>>
            <? $this->showDistanceSelect($name, $options, $label, $opts) ?>
        </div>
        <?
    }

    function showConditionalInput($name, $label, $opts) {
        list($value, $error) = $this->valueErrorPair($name);
        ?>
        <div class="wrap_calc_item_block<?= !v::isEmpty($error) ? ' error' : '' ?><?= $opts['class'] ? ' '.$opts['class'] : '' ?>"<?= $opts['show'] ? ' style="display: block"' : '' ?>>
            <div class="top">
                <p class="title"><?= $label.($opts['required'] ? self::$requiredMark : '') ?></p>
            </div>
            <input name="<?= $name ?>" value="<?= $value ?>" type="<?= $opts['type'] ? $opts['type'] : 'text' ?>"<?= isset($opts['input_attrs']) ? ' '.$opts['input_attrs'] : '' ?>>
            <div class="error-message"><?= $error ?></div>
        </div>
        <?
    }

    function showCheckboxList($name, $options, $label, $_opts = []) {
        $opts = array_merge(['required' => false], $_opts);
        list($value, $error) = $this->valueErrorPair($name);
        $prefix = Util::uniqueId().'_'.$name;
        ?>
        <div class="wrap_calc_item<?= !v::isEmpty($error) ? ' error' : '' ?> <?= isset($opts['class']) ? $opts['class'] : '' ?>">
            <p class="title"><?= $label.($opts['required'] ? self::$requiredMark : '') ?></p>
            <? foreach ($options as $idx => $option): ?>
                <? $id = "{$prefix}_{$idx}" ?>
                <? $checked = in_array($option['value'], $value) ?>
                <div class="wrap_checkbox">
                    <input type="checkbox" name="<?= $name.'[]' ?>" value="<?= $option['value'] ?>"<?= $checked ? ' checked' : '' ?> hidden="hidden" id="<?= $id ?>">
                    <label for="<?= $id ?>"><?= $option['text'] ?></label>
                </div>
            <? endforeach ?>
            <div class="error-message"><?= $error ?></div>
        </div>
        <?
    }

    function showExpandableCheckboxList($name, $label, $options, $_opts = []) {
        $opts = array_merge([
            'state' => 'collapsed'
        ], $_opts);
        list($value, $error) = $this->valueErrorPair($name);
        $id = $name.'_'.Util::uniqueId();
        ?>
        <div class="wrap_calc_item">
            <p class="calculator__expandable-title" data-state="<?= $opts['state'] ?>" data-target="<?= '#'.$id ?>" role="button">
                <span class="text"><?= $label ?></span>
            </p>
        </div>
        <div class="wrap_calc_item_block wrap_calc_item_block--checkbox"
             id="<?= $id ?>"
             style="display: <?= $opts['state'] === 'expanded' ? 'block' : 'none' ?>">
            <? foreach ($options as $i => $option): ?>
                <? $checkboxId = $id.'_'.$i ?>
                <? $checked = in_array($option['value'], $value) ?>
                <div class="wrap_checkbox">
                    <input type="checkbox" name="<?= $name.'[]' ?>" value="<?= $option['value'] ?>"<?= $checked ? ' checked' : '' ?> hidden="hidden" id="<?= $checkboxId ?>">
                    <label for="<?= $checkboxId ?>"><?= $option['text'] ?></label>
                </div>
            <? endforeach ?>
        </div>
        <?
    }

    function showPackageSelector($name, $label, $groups) {
        list($value, $error) = $this->valueErrorPair($name);
        $rootName = 'PACKAGE_SELECTION';
        $rootValue = _::get($this->state, ['params', $rootName]);
        ?>
        <div class="wrap_calc_item">
            <div class="inner">
                <div class="left">
                    <p class="title"><?= $label.':'.self::$requiredMark ?></p>
                </div>
                <div class="right">
                    <? $this->showTooltip($rootName) ?>
                </div>
            </div>
        </div>
        <? foreach ($groups as $idx => $group): ?>
            <?
            // TODO don't check first radio button by default?
            $checked = $rootValue !== null ? $rootValue === $group['value'] : $idx === 0;
            $id = $rootName.'_'.$idx;
            $blockId = $id.'_block';
            $displayBlock = $checked;
            $classes = ['group_'.$rootName, $blockId];
            if (!v::isEmpty($error)) {
                $classes[] = 'error';
            }
            ?>
            <div class="wrap_calc_item hidden_block">
                <input type="radio"
                       hidden="hidden"
                       name="<?= $rootName ?>"
                       value="<?= $group['value'] ?>"
                       <?= $checked ? 'checked' : '' ?>
                       id="<?= $id ?>"
                       class="open_block"
                       data-name="<?= $blockId ?>"
                       data-group="<?= $rootName ?>">
                <label for="<?= $id ?>" class="radio_label"><?= $group['text'] ?></label>
            </div>
            <div class="<?= join(' ', $classes) ?> wrap_calc_item_block wrap_calc_item_block--checkbox"
                 style="display: <?= $displayBlock ? 'block' : 'none' ?>">
                <? foreach ($group['options'] as $i => $option): ?>
                    <? $checkboxId = "{$id}_{$i}" ?>
                    <? $checked = in_array($option['value'], $value) ?>
                    <div class="wrap_checkbox">
                        <input type="checkbox" name="<?= $name.'[]' ?>" value="<?= $option['value'] ?>"<?= $checked ? ' checked' : '' ?> hidden="hidden" id="<?= $checkboxId ?>">
                        <label for="<?= $checkboxId ?>"><?= $option['text'] ?></label>
                    </div>
                <? endforeach ?>
                <div class="error-message"><?= $error ?></div>
            </div>
        <? endforeach ?>
        <?
    }
}
