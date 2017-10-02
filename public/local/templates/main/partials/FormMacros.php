<?php

namespace App\Templates;

use App\View as v;
use Core\Util;
use Core\Underscore as _;

class FormMacros {
    // TODO wrap error messages in `if`

    static private $requiredMark = '&nbsp;<span class="red">*</span>';
    private $state;

    function __construct($state) {
        // params and errors
        $this->state = $state;
    }

    private function getValue($name) {
        $path = Util::formInputNamePath($name);
        return _::get($this->state['params'], $path);
    }

    // TODO refactor
    function valueErrorPair($name) {
        return [self::getValue($name), $this->state['errors'][$name]];
    }

    function showInput($name, $label, $opts = []) {
        list($value, $error) = $this->valueErrorPair($name);
        ?>
        <div class="wrap_input<?= !v::isEmpty($error) ? ' error' : '' ?>">
            <input name="<?= $name ?>" type="text" value="<?= $value ?>">
            <span class="input_text<?= !v::isEmpty($value) ? ' focus' : '' ?>"><?= $label.($opts['required'] ? self::$requiredMark : '') ?></span>
            <div class="error-message"><?= $error ?></div>
        </div>
        <?
    }

    function showTextarea($name, $label, $opts = []) {
        list($value, $error) = $this->valueErrorPair($name);
        ?>
        <div class="wrap_input<?= !v::isEmpty($error) ? ' error' : '' ?>">
            <textarea name="<?= $name ?>" class="stretch-to-fit"><?= $value ?></textarea>
            <span class="input_text<?= !v::isEmpty($value) ? ' focus' : '' ?>"><?= $label.($opts['required'] ? self::$requiredMark : '') ?></span>
            <div class="error-message"><?= $error ?></div>
        </div>
        <?
    }

    function showCheckbox($name, $value, $label, $id, $opts = []) {
        list($checkedValuesRaw, $_) = $this->valueErrorPair($name);
        $checkedValues = array_map('intval', $checkedValuesRaw);
        $checked = $opts['checked'] || in_array($value, $checkedValues);
        ?>
        <div class="wrap_checkbox">
            <input name="<?= $name ?>" value="<?= $value ?>" type="checkbox" hidden="hidden" id="<?= $id ?>" <?= $checked ? 'checked' : '' ?>>
            <label for="<?= $id ?>"><?= $label ?></label>
        </div>
        <?
    }

    static function showFilesBlock($label) {
        $id = 'fileupload-input-'.Util::uniqueId();
        ?>
        <div class="wrap_add_file keep">
            <input data-url="/api/fileupload"
                   id="<?= $id ?>"
                   class="fileupload"
                   style="display: none"
                   type="file"
                   name="files[]"
                   multiple>
            <div class="files"></div>
            <div class="progress" style="display: none">
                <div class="loader"></div>
                <div class="text"></div>
            </div>
            <div class="error-message" style="display: none"></div>
            <div class="choose_file">
                <label for="<?= $id ?>" class="big_btn">
                    <span class="text"><span>Прикрепить файл к заявке</span></span>
                    <span class="img">
                    <img src="<?= v::asset('images/clip.svg') ?>">
                </span>
                </label>
                <p class="choose_file_text"><?= $label ?></p>
            </div>
        </div>
        <?
    }
}

