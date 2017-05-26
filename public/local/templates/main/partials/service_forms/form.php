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
    <? if (!v::isEmpty($service['requestFormSubheading'])): ?>
        <h3><?= $service['requestFormSubheading'] ?></h3>
    <? endif ?>
    <p class="top_text"><? $APPLICATION->IncludeComponent(
            "bitrix:main.include",
            "",
            Array(
                "AREA_FILE_SHOW" => "file",
                "PATH" => v::includedArea('what-we-do/request_form_help.php')
            )
        ); ?> Также, Вы можете определить стоимость и сроки выполнения работ, заполнив <a href="<?= $service['calcLink'] ?>" class="red">On-line форму</a>.</p>
    <?= $inputs ?>
    <div class="wrap_robot_block">
        <? if (!v::isEmpty($state['errors'])): ?>
            <? $errorCount = count($state['errors']) ?>
            <? $msgErrors = \Core\Util::units($errorCount, 'ошибка', 'ошибки', 'ошибок') ?>
            <? $msgAllowed = \Core\Util::units($errorCount, 'позволила', 'позволили', 'позволили') ?>
            <div class="form-message error">
                <span><?= "{$errorCount} {$msgErrors} не {$msgAllowed} отправить эту заявку." ?></span>
            </div>
        <? endif ?>
        <div class="loader form-loader" style="display: none"></div>
        <button type="submit" class="big_btn">
            <span class="text"><span>Отправить заявку</span></span>
            <span class="img">
                <img src="<?= v::asset('images/plane.svg') ?>">
            </span>
        </button>
    </div>
<? endif ?>
