<?
use App\View as v;
?>
<section class="activites_section">
    <div class="wrap">
        <div class="wrap_title">
            <h2><?= $APPLICATION->GetTitle(false) ?></h2>
        </div>
        <div class="wrap_title">
            <? $APPLICATION->IncludeComponent(
                "bitrix:main.include",
                "",
                Array(
                    "AREA_FILE_SHOW" => "file",
                    "PATH" => v::includedArea('what-we-do/subheading.php')
                )
            ); ?>
        </div>
        <div class="grid">
            <? foreach ($services as $idx => $service): ?>
                <div class="activites_item col col_2">
                    <span class="number_marker"><?= $idx + 1 ?></span>
                    <div class="block">
                        <div class="top">
                            <div class="inner">
                                <p class="text"><?= $service['name'] ?></p>
                                <a class="link" href="<?= $service['detailLink'] ?>">подробнее...</a>
                            </div>
                        </div>
                        <div class="bottom">
                            <a href="<?= $service['calcLink'] ?>" class="big_btn">
                                <span class="text"><span>Определить стоимость и сроки On-line</span></span>
                                <span class="img">
                                    <img src="<?= v::asset('images/calc.png') ?>">
                                </span>
                            </a>
                            <button data-modal="<?= $service['requestModalId'] ?>" class="big_btn">
                                <span class="text"><span>Отправить заявку</span></span>
                                <span class="img">
                                    <img src="<?= v::asset('images/plane.png') ?>">
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
            <? endforeach ?>
        </div>
    </div>
</section>
<? ob_start() ?>
<? foreach ($services as $service): ?>
    <div class="modal" id="<?= $service['requestModalId'] ?>">
        <div class="block">
            <span class="close">×</span>
            <?= v::render('partials/service_request_form', ['service' => $service]) ?>
        </div>
    </div>
<? endforeach ?>
<? v::appendToView('modals', ob_get_clean()) ?>
