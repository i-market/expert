<?
require_once __DIR__.'/FormMacros.php';

use App\View as v;
use App\Templates\FormMacros as macros;

$macros = new macros($state);
?>
<? if ($state['screen'] === 'success'): ?>
    <div class="success-screen">
        <img class="icon" src="<?= v::asset('images/plane-gray.svg') ?>">
        <div>
            <? $APPLICATION->IncludeComponent(
                "bitrix:main.include",
                "",
                Array(
                    "AREA_FILE_SHOW" => "file",
                    "PATH" => v::includedArea('forms/callback_success_screen_message.php')
                )
            ); ?>
        </div>
    </div>
<? else: ?>
    <h2>Обратный звонок</h2>
    <div class="wrap_input_block">
        <? $macros->showInput('CONTACT_PERSON', 'Контактное лицо', ['required' => true]) ?>
        <? $macros->showInput('PHONE', 'Номер телефона', ['required' => true]) ?>
    </div>
    <div class="wrap_robot_block">
        <?= v::render('partials/form_loader') ?>
        <button type="submit" class="recaptcha big_btn" data-sitekey="<?= \App\App::recaptchaKey() ?>">
            <span class="text"><span>Перезвоните мне</span></span>
            <span class="img">
                <img src="<?= v::asset('images/telephone.svg') ?>">
              </span>
        </button>
    </div>
<? endif ?>
