<?php

namespace App\Templates;

use App\View as v;
use Core\Util;
use Core\Underscore as _;

class CalculatorMacros {
    static private $requiredMark = ' <span class="red">*</span>';
    static private $selectPlaceholder = '<option value="">Выбрать...</option>';
    private $state;

    function __construct($state) {
        // params and errors
        $this->state = $state;
    }

    private function valueErrorPair($name) {
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
                    <textarea name="<?= $name ?>"><?= $value ?></textarea>
                    <div class="error-message"><?= $error ?></div>
                </div>
                <div class="right">
                    <? self::showTooltip($name) ?>
                </div>
            </div>
        </div>
        <?
    }

    function showInput($name, $label, $opts = []) {
        list($value, $error) = $this->valueErrorPair($name);
        ?>
        <div class="wrap_calc_item<?= !v::isEmpty($error) ? ' error' : '' ?>">
            <p class="title"><?= $label.($opts['required'] ? self::$requiredMark : '') ?></p>
            <div class="inner">
                <div class="left">
                    <input name="<?= $name ?>" value="<?= $value ?>" type="<?= $opts['type'] ? $opts['type'] : 'text' ?>"<?= isset($opts['input_attrs']) ? ' '.$opts['input_attrs'] : '' ?>>
                    <div class="error-message"><?= $error ?></div>
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

    function showSelect($name, $options, $label, $opts) {
        list($value, $error) = $this->valueErrorPair($name);
        ?>
        <div class="wrap_calc_item<?= !v::isEmpty($error) ? ' error' : '' ?>">
            <p class="title"><?= $label.($opts['required'] ? self::$requiredMark : '') ?></p>
            <div class="inner">
                <div class="left">
                    <select name="<?= $name ?>"<?= isset($opts['select_attrs']) ? ' '.$opts['select_attrs'] : '' ?>>
                        <?= self::$selectPlaceholder ?>
                        <? foreach ($options as $option): ?>
                            <? $selected = $value === $option['value'] ?>
                            <option value="<?= $option['value'] ?>"<?= $selected ? ' selected' : '' ?>><?= $option['text'] ?></option>
                        <? endforeach ?>
                    </select>
                    <div class="error-message"><?= $error ?></div>
                </div>
                <div class="right">
                    <? self::showTooltip($name) ?>
                </div>
            </div>
        </div>
        <?
    }

    function showOptionalSelect($name, $options, $label, $opts) {
        list($value, $error) = $this->valueErrorPair($name);
        ?>
        <div class="wrap_calc_item_block<?= !v::isEmpty($error) ? ' error' : '' ?><?= $opts['class'] ? ' '.$opts['class'] : '' ?>"<?= $opts['show'] ? 'style=" display: block"' : '' ?>>
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

    function showCheckboxList($name, $options, $label, $opts) {
        // TODO errors
        list($value, $error) = $this->valueErrorPair($name);
        $prefix = Util::uniqueId().'_'.$name;
        ?>
        <div class="wrap_calc_item">
            <p class="title"><?= $label.($opts['required'] ? self::$requiredMark : '') ?></p>
            <? foreach ($options as $idx => $option): ?>
                <? $id = "{$prefix}_{$idx}" ?>
                <? $checked = in_array($option['value'], $value) ?>
                <div class="wrap_checkbox">
                    <input type="checkbox" name="<?= $name.'[]' ?>" value="<?= $option['value'] ?>"<?= $checked ? ' checked' : '' ?> hidden="hidden" id="<?= $id ?>">
                    <label for="<?= $id ?>"><?= $option['text'] ?></label>
                </div>
            <? endforeach ?>
        </div>
        <?
    }
}
