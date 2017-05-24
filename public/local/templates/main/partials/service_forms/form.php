<?
use App\View as v;
?>
<form action="<?= $service['apiEndpoint'] ?>" method="post">
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