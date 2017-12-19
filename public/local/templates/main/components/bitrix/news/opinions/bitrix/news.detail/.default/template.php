<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use App\View as v;

$APPLICATION -> SetPageProperty('layout', 'default');
$APPLICATION -> SetPageProperty('section_class', 'opinion-detail');
?>

<div class="opinion-detail-page">
    <? for ($indexBlock = 0; $indexBlock < 40; $indexBlock++) : ?>

        <? if (!empty($arResult['DISPLAY_PROPERTIES']['TEXT_BLOCK_' . $indexBlock]['~VALUE']['TEXT'])
            || $indexBlock == 0) : ?>
            <div class="detail-section-<?= $indexBlock ?>">
        <? endif; ?>

        <? if ($indexBlock == 0) : ?>
            <?= $arResult['DETAIL_TEXT'] ?>
        <? endif; ?>

        <? if ($indexBlock > 0 && !empty($arResult['DISPLAY_PROPERTIES']['TEXT_BLOCK_' . $indexBlock]['~VALUE']['TEXT'])) : ?>
            <?= $arResult['DISPLAY_PROPERTIES']['TEXT_BLOCK_' . $indexBlock]['~VALUE']['TEXT'] ?>
        <? endif; ?>

        <? if (!empty($arResult['DISPLAY_PROPERTIES']['IMG_BLOCK_' . $indexBlock]['VALUE'])) : ?>
            <div class="info-images-cont">
                <? foreach ($arResult['DISPLAY_PROPERTIES']['IMG_BLOCK_' . $indexBlock]['VALUE'] as $key => $imgID) : ?>
                    <div class="info-img-cont">
                        <a class="info-img-img opinion-galery wrap_img"
                           rel="opinion-galery"
                           data-caption="Галерея"
                           href="<?= '/upload/' . $arResult['RES_AR_IMG'][$imgID]['SUBDIR']
                           . '/'
                           . $arResult['RES_AR_IMG'][$imgID]['FILE_NAME'] ?>"
                        >
                            <img src="
                            <?= '/upload/' . $arResult['RES_AR_IMG'][$imgID]['SUBDIR'] . '/' .
                            $arResult['RES_AR_IMG'][$imgID]['FILE_NAME'] ?>
                        ">
                        </a>
                        <div class="info-img-title"><?= $arResult['RES_AR_IMG'][$imgID]['DESCRIPTION'] ?></div>
                    </div>
                <? endforeach; ?>
            </div>
        <? endif; ?>

        <? if (!empty($arResult['DISPLAY_PROPERTIES']['STAR_BLOCK_DESCR_' . $indexBlock]['VALUE'])
        || !empty($arResult['DISPLAY_PROPERTIES']['STAR_BLOCK_LINK_' . $indexBlock]['VALUE']['TEXT'])) : ?>
        <div class="quote-block">
        <div class="quote-txt-cont">
    <? endif; ?>

        <? if (!empty($arResult['DISPLAY_PROPERTIES']['STAR_BLOCK_DESCR_' . $indexBlock]['VALUE'])) : ?>
            <span class="quote-txt">
            <?= $arResult['DISPLAY_PROPERTIES']['STAR_BLOCK_DESCR_' . $indexBlock]['VALUE'] ?>
        </span>
        <? endif; ?>

        <? if (!empty($arResult['DISPLAY_PROPERTIES']['STAR_BLOCK_LINK_' . $indexBlock]['VALUE']['TEXT'])) : ?>
            <span class="quote-txt-quote">
            <?= $arResult['DISPLAY_PROPERTIES']['STAR_BLOCK_LINK_' . $indexBlock]['~VALUE']['TEXT'] ?>
        </span>
        <? endif; ?>

        <? if (!empty($arResult['DISPLAY_PROPERTIES']['STAR_BLOCK_DESCR_' . $indexBlock]['VALUE'])
        || !empty($arResult['DISPLAY_PROPERTIES']['STAR_BLOCK_LINK_' . $indexBlock]['VALUE'])) : ?>
        </div>
        </div>
    <? endif; ?>

        <? if (!empty($arResult['DISPLAY_PROPERTIES']['TEXT_BLOCK_' . $indexBlock]['VALUE']['TEXT'])
            || $indexBlock == 0) : ?>
            </div>
        <? endif; ?>

    <? endfor; ?>

    <? if (!empty($arResult['DISPLAY_PROPERTIES']['READ_MORE_BLOCK']['~VALUE']['TEXT'])) : ?>
        <div class="read-more-cont">
            <div class="read-more-title">
                <?= $arResult['DISPLAY_PROPERTIES']['READ_MORE_BLOCK']['NAME'] . ':' ?>
            </div>
            <div class="read-more-links">
                <?= $arResult['DISPLAY_PROPERTIES']['READ_MORE_BLOCK']['~VALUE']['TEXT'] ?>
            </div>
        </div>
    <? endif; ?>

    <div class="bottom_btn bottom_btn--back">
        <a href="<?= $arResult['LIST_PAGE_URL'] ?>" class="big_btn">
        <span class="img">
            <img src="<?= v ::asset('images/arrow_left_white.svg') ?>">
        </span>
            <span class="text"><span>Назад</span></span>
        </a>
    </div>

</div>
