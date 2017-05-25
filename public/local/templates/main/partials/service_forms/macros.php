<?
use App\View as v;

$showInput = function($state, $name, $label, $opts) use ($getValue) {
    $value = $getValue($state['params'], $name);
    $error = $state['errors'][$name];
    ?>
    <div class="wrap_input<?= !v::isEmpty($error) ? ' error' : '' ?>">
        <input name="<?= $name ?>" type="text" value="<?= $value ?>">
        <span class="input_text<?= !v::isEmpty($value) ? ' focus' : '' ?>"><?= $label.($opts['required'] ? '<span class="red">*</span>' : '') ?></span>
        <div class="error-message"><?= $error ?></div>
    </div>
    <?
};
$showTextarea = function($state, $name, $label, $opts) use ($getValue) {
    $value = $getValue($state['params'], $name);
    $error = $state['errors'][$name];
    ?>
    <div class="wrap_input<?= !v::isEmpty($error) ? ' error' : '' ?>">
        <textarea name="<?= $name ?>"><?= $value ?></textarea>
        <span class="input_text<?= !v::isEmpty($value) ? ' focus' : '' ?>"><?= $label.($opts['required'] ? '<span class="red">*</span>' : '') ?></span>
        <div class="error-message"><?= $error ?></div>
    </div>
    <?
};
$showCheckbox = function($name, $label, $id, $opts) {
// TODO render state
    ?>
    <div class="wrap_checkbox">
        <input name="<?= $name ?>" type="checkbox" hidden="hidden" id="<?= $id ?>" <?= $opts['checked'] ? 'checked' : '' ?>>
        <label for="<?= $id ?>"><?= $label ?></label>
    </div>
    <?
};
$showFilesBlock = function($label) {
    $id = \Core\Util::uniqueId('fileupload-input');
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
};
