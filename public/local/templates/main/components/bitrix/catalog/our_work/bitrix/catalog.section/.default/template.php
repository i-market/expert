<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use App\View as v;
?>
<? if (!v::isEmpty($arResult['ITEMS'])): ?>
    <div class="our-work-thumbnail-grid our-work-thumbnail-grid--page">
        <div class="grid">
            <? foreach ($arResult['ITEMS'] as $item): ?>
                <? // TODO 4 columns as in the psd mockup? ?>
                <div class="item col col_3">
                    <? // TODO add fancybox gallery id? ?>
                    <a class="gallery" href="<?= $item['DETAIL_PICTURE']['SRC'] ?>" target="_blank">
                    <span class="wrap_img">
                        <img src="<?= $item['DETAIL_PICTURE']['SRC'] ?>" alt="<?= $item['DETAIL_PICTURE']['ALT'] ?>">
                    </span>
                        <span class="text"><?= $item['DETAIL_TEXT'] ?></span>
                    </a>
                </div>
            <? endforeach ?>
        </div>
    </div>
    <? if (isset($arResult['PATH'][0]['SECTION_PAGE_URL'])): ?>
        <div class="bottom_btn bottom_btn--back">
            <a href="<?= $arResult['PATH'][0]['SECTION_PAGE_URL'] ?>" class="big_btn">
        <span class="img">
            <img src="<?= v::asset('images/arrow_left_white.svg') ?>">
        </span>
                <span class="text"><span>Назад</span></span>
            </a>
        </div>
    <? endif ?>
<? endif ?>
