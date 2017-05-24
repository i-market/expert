<?
use App\View as v;

$showInput = function($name, $label, $opts) {
    ?>
    <div class="wrap_input">
        <input name="<?= $name ?>" type="text">
        <span class="input_text"><?= $label.($opts['required'] ? '<span class="red">*</span>' : '') ?></span>
    </div>
    <?
};
$showTextarea = function($name, $label, $opts) {
    ?>
    <div class="wrap_input">
        <textarea name="<?= $name ?>"></textarea>
        <span class="input_text"><?= $label.($opts['required'] ? '<span class="red">*</span>' : '') ?></span>
    </div>
    <?
};
$showCheckbox = function($name, $label, $id, $opts) {
    ?>
    <div class="wrap_checkbox">
        <input name="<?= $name ?>" type="checkbox" hidden="hidden" id="<?= $id ?>" <?= $opts['checked'] ? 'checked' : '' ?>>
        <label for="<?= $id ?>"><?= $label ?></label>
    </div>
    <?
};
$showFilesBlock = function($label) {
    ?>
    <div class="wrap_add_file">
        <div class="choose_file">
            <div class="big_btn">
                <span class="text"><span>Прикрепить файл к заявке</span></span>
                <span class="img">
                <img src="<?= v::asset('images/clip.svg') ?>">
            </span>
            </div>
            <p class="choose_file_text"><?= $label ?></p>
        </div>
    </div>
    <?
};
