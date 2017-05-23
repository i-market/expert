<?
use App\View as v;

$showInput = function($name, $label, $isRequired) {
    ?>
    <div class="wrap_input">
        <input name="<?= $name ?>" type="text">
        <span class="input_text"><?= $label.($isRequired ? '<span class="red">*</span>' : '') ?></span>
    </div>
    <?
};
$showTextarea = function($name, $label, $isRequired) {
    ?>
    <div class="wrap_input">
        <textarea name="<?= $name ?>"></textarea>
        <span class="input_text"><?= $label.($isRequired ? '<span class="red">*</span>' : '') ?></span>
    </div>
    <?
};
$showCheckbox = function($name, $label, $id, $isChecked) {
    ?>
    <div class="wrap_checkbox">
        <input name="<?= $name ?>" type="checkbox" hidden="hidden" id="<?= $id ?>" <?= $isChecked ? 'checked' : '' ?>>
        <label for="<?= $id ?>"><?= $label ?></label>
    </div>
    <?
};
?>
<? // TODO form action ?>
<form action="" method="post">
    <h2>Заявка</h2>
    <? if (!v::isEmpty($service['modalSubheading'])): ?>
        <h3><?= $service['modalSubheading'] ?></h3>
    <? endif ?>
    <p class="top_text"><? $APPLICATION->IncludeComponent(
            "bitrix:main.include",
            "",
            Array(
                "AREA_FILE_SHOW" => "file",
                "PATH" => v::includedArea('what-we-do/request_form_help.php')
            )
        ); ?> Также, Вы можете определить стоимость и сроки выполнения работ, заполнив <a href="<?= $service['calcLink'] ?>" class="red">On-line форму</a>.</p>
    <div class="wrap_input_block">
        <? $showInput('SOME_NAME', 'Наименование объекта(ов) обследования', true) ?>
        <div class="wrap_input">
            <input type="text">
            <span class="input_text">Местонахождение объекта(ов)</span>
        </div>
        <div class="wrap_input">
            <textarea></textarea>
            <span class="input_text">Опишите цели обследования<span class="red">*</span></span>
        </div>
        <div class="wrap_input">
            <textarea></textarea>
            <span class="input_text">Описание объекта(ов) обследования (назначение, этажность, наличие подвала, полщадь, год постройки и пр.)<span class="red">*</span></span>
        </div>
    </div>
    <div class="wrap_checkbox_block">
        <h4>Наличние документов:</h4>
        <? $showCheckbox('some-name', 'Результаты ранее выполненых обследований', 'some-id', true) ?>
        <div class="wrap_checkbox">
            <input type="checkbox" hidden="hidden" id="some_11">
            <label for="some_11">Проектная документация</label>
        </div>
        <div class="wrap_checkbox">
            <input type="checkbox" hidden="hidden" id="some_2">
            <label for="some_2">Рабочая документация</label>
        </div>
        <div class="wrap_checkbox">
            <input type="checkbox" hidden="hidden" id="some_22">
            <label for="some_22">Планы БТИ</label>
        </div>
        <div class="wrap_input">
            <textarea></textarea>
            <span class="input_text">Дополнительная информация по обследованию<span class="red">*</span></span>
        </div>
    </div>
    <div class="wrap_add_file">
        <div class="file pdf">
            <p class="info">PDF, 650 Кб <span class="remove red">Удалить</span></p>
            <p class="title">Чертежи и схемы объекта по адресу г. Воронеж, ул. Ленина 15</p>
        </div>
        <div class="choose_file">
            <div class="big_btn">
                <span class="text"><span>Прикрепить файл к заявке</span></span>
                <span class="img">
                <img src="<?= v::asset('images/clip.svg') ?>">
              </span>
            </div>
            <p class="choose_file_text"><b>Документы</b> по объекту(ам) технического надзора (к заявке можно прикрепить не более 10 файлов)</p>
        </div>
    </div>
    <h3 class="h3_mb">Контактная информация для ответа</h3>
    <div class="wrap_input_block">
        <div class="wrap_input">
            <input type="text">
            <span class="input_text">Контактное лицо<span class="red">*</span></span>
        </div>
        <div class="wrap_input">
            <input type="text">
            <span class="input_text">Наименование организации</span>
        </div>
        <div class="wrap_input">
            <input type="text">
            <span class="input_text">Электронная почта (e-mail)<span class="red">*</span></span>
        </div>
        <div class="wrap_input">
            <input type="text">
            <span class="input_text">Телефон 1</span>
        </div>
        <div class="wrap_input">
            <input type="text">
            <span class="input_text">Телефон 2</span>
        </div>
    </div>
    <div class="wrap_robot_block">
        <? // TODO recaptcha ?>
        <div class="robot"></div>
        <button type="submit" class="big_btn">
            <span class="text"><span>Отправить заявку</span></span>
            <span class="img">
                <img src="<?= v::asset('images/plane.svg') ?>">
              </span>
        </button>
    </div>
</form>