<?
use App\View as v;
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
                    "PATH" => v::includedArea('service_forms/success_screen_message.php')
                )
            ); ?>
        </div>
    </div>
<? else: ?>
    <? // TODO implement removing files ?>
    <style>
        .wrap_add_file .remove {
            display: none;
        }
    </style>
    <h2>Заявка</h2>
    <? $APPLICATION->IncludeComponent(
        "bitrix:main.include",
        "",
        Array(
            "AREA_FILE_SHOW" => "file",
            "PATH" => v::includedArea("what-we-do/request_forms/{$code}.php")
        )
    ); ?>
    <p class="top_text"><? $APPLICATION->IncludeComponent(
            "bitrix:main.include",
            "",
            Array(
                "AREA_FILE_SHOW" => "file",
                "PATH" => v::includedArea('what-we-do/request_form_help.php')
            )
        ); ?> <? if (!v::isEmpty($calcLink)): ?>Также, Вы можете определить стоимость и сроки выполнения работ, воспользовавшись
            <a href="<?= $calcLink ?>" class="red">On-line калькулятором</a>.<? endif ?></p>
    <?= $inputs ?>
    <div class="wrap_robot_block">
        <?= v::render('partials/form_error_message', [
            'errors' => $state['errors'],
            'action' => 'отправить заявку'
        ]) ?>
        <?= v::render('partials/form_loader') ?>
        <button type="submit" class="recaptcha big_btn" data-sitekey="<?= $recaptchaKey ?>">
            <span class="text"><span>Отправить заявку</span></span>
            <span class="img">
                <img src="<?= v::asset('images/plane.svg') ?>">
            </span>
        </button>
    </div>
<? endif ?>
