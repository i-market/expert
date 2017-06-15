<?php

namespace App\Templates;

use App\View as v;
use Core\Util;
use Core\Underscore as _;

class CalculatorMacros {
    static private $requiredMark = ' <span class="red">*</span>';
    private $state;

    function __construct($state) {
        // params and errors
        $this->state = $state;
    }

    private function getValue($name) {
        $path = Util::formInputNamePath($name);
        return _::get($this->state['params'], join('.', $path));
    }

    // TODO refactor
    private function valueErrorPair($name) {
        return [self::getValue($name), $this->state['errors'][$name]];
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
        // TODO values and errors
        list($value, $error) = $this->valueErrorPair($name);
        ?>
        <div class="wrap_calc_item">
            <p class="title"><?= $label.($opts['required'] ? self::$requiredMark : '') ?></p>
            <div class="inner">
                <div class="left<?= !v::isEmpty($error) ? ' error' : '' ?>">
                    <textarea name="<?= $name ?>"></textarea>
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
        // TODO values and errors
        list($value, $error) = $this->valueErrorPair($name);
        ?>
        <div class="wrap_calc_item">
            <p class="title"><?= $label.($opts['required'] ? self::$requiredMark : '') ?></p>
            <div class="inner">
                <div class="left">
                    <input name="<?= $name ?>" type="<?= $opts['type'] ? $opts['type'] : 'text' ?>"<?= isset($opts['input_attrs']) ? ' '.$opts['input_attrs'] : '' ?>>
                </div>
                <div class="right">
                    <? self::showTooltip($name) ?>
                </div>
            </div>
        </div>
        <?
    }

    function showSelectGroup($name, $selects, $label, $opts = []) {
        // TODO values and errors
        list($value, $error) = $this->valueErrorPair($name);
        ?>
        <div class="wrap_calc_item">
            <p class="title"><?= $label.($opts['required'] ? self::$requiredMark : '') ?></p>
            <? foreach ($selects as $idx => $select): ?>
                <div class="inner inner_some">
                    <div class="left">
                        <span class="text" style="white-space: nowrap"><?= $select['label'] ?></span>
                        <select name="<?= "{$name}[{$idx}]" ?>">
                            <? foreach ($select['options'] as $option): ?>
                                <option value="<?= $option['value'] ?>"><?= $option['text'] ?></option>
                            <? endforeach ?>
                        </select>
                    </div>
                    <? if ($idx === 0): ?>
                        <div class="right">
                            <? self::showTooltip($name) ?>
                        </div>
                    <? endif ?>
                </div>
            <? endforeach ?>
        </div>
        <?
    }

    function showInputGroup($name, $inputs, $label, $opts = []) {
        // TODO values and errors
        list($value, $error) = $this->valueErrorPair($name);
        ?>
        <div class="wrap_calc_item">
            <p class="title"><?= $label.($opts['required'] ? self::$requiredMark : '') ?></p>
            <? foreach ($inputs as $idx => $input): ?>
                <div class="inner inner_some">
                    <div class="left">
                        <span class="text" style="white-space: nowrap"><?= $input['label'] ?></span>
                        <input name="<?= $name ?>" type="<?= $opts['type'] ? $opts['type'] : 'text' ?>"<?= isset($opts['input_attrs']) ? ' '.$opts['input_attrs'] : '' ?>>
                    </div>
                    <? if ($idx === 0): ?>
                        <div class="right">
                            <? self::showTooltip($name) ?>
                        </div>
                    <? endif ?>
                </div>
            <? endforeach ?>
        </div>
        <?
    }

    function showSelect($name, $options, $label, $opts) {
        // TODO values and errors
        ?>
        <div class="wrap_calc_item">
            <p class="title"><?= $label.($opts['required'] ? self::$requiredMark : '') ?></p>
            <div class="inner">
                <div class="left">
                    <select name="<?= $name ?>"<?= isset($opts['select_attrs']) ? ' '.$opts['select_attrs'] : '' ?>>
                        <? foreach ($options as $option): ?>
                            <option value="<?= $option['value'] ?>"><?= $option['text'] ?></option>
                        <? endforeach ?>
                    </select>
                </div>
                <div class="right">
                    <? self::showTooltip($name) ?>
                </div>
            </div>
        </div>
        <?
    }

    function showOptionalSelect($name, $options, $label, $opts) {
        // TODO values and errors
        // TODO display on subsequent renderings (state)
        ?>
        <div class="wrap_calc_item_block<?= $opts['class'] ? ' '.$opts['class'] : '' ?>"<?= $opts['show'] ? 'style=" display: block"' : '' ?>>
            <div class="top">
                <p class="title"><?= $label.($opts['required'] ? self::$requiredMark : '') ?></p>
                <? // TODO refactor: monitoring-specific warning ?>
                <? // TODO stop users from submitting the form when this condition is met ?>
                <p class="text red warning" style="display: <?= $opts['show_warning'] ? 'block' : 'none' ?>">
                    При расстоянии между объектами более 3 км расчет необходимо выполнять отдельно для каждого объекта
                </p>
            </div>
            <select name="<?= $name ?>">
                <? foreach ($options as $option): ?>
                    <option value="<?= $option['value'] ?>"><?= $option['text'] ?></option>
                <? endforeach ?>
            </select>
        </div>
        <?
    }

    // TODO rename to something like conditional select
    function showSelectBlock($name, $options, $label, $opts) {
        // TODO values and errors
        // TODO display on subsequent renderings (state)
        ?>
        <div class="wrap_calc_item_block<?= $opts['class'] ? ' '.$opts['class'] : '' ?>"<?= $opts['show'] ? ' style="display: block"' : '' ?>>
            <div class="top">
                <p class="title"><?= $label.($opts['required'] ? self::$requiredMark : '') ?></p>
            </div>
            <select name="<?= $name ?>">
                <? foreach ($options as $option): ?>
                    <option value="<?= $option['value'] ?>"><?= $option['text'] ?></option>
                <? endforeach ?>
            </select>
        </div>
        <?
    }

    function showConditionalInput($name, $label, $opts) {
        // TODO values and errors
        // TODO display on subsequent renderings (state)
        ?>
        <div class="wrap_calc_item_block<?= $opts['class'] ? ' '.$opts['class'] : '' ?>"<?= $opts['show'] ? ' style="display: block"' : '' ?>>
            <div class="top">
                <p class="title"><?= $label.($opts['required'] ? self::$requiredMark : '') ?></p>
            </div>
            <input name="<?= $name ?>" type="<?= $opts['type'] ? $opts['type'] : 'text' ?>"<?= isset($opts['input_attrs']) ? ' '.$opts['input_attrs'] : '' ?>>
        </div>
        <?
    }
}
