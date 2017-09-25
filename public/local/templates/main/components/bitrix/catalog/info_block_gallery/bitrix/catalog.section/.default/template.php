<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use App\View as v;
?>
<div class="grid">
    <? foreach ($arResult['ITEMS'] as $item): ?>
        <? $preview = !v::isEmpty($item['PREVIEW_PICTURE']) ? $item['PREVIEW_PICTURE'] : $item['DETAIL_PICTURE'] ?>
        <? $text = !v::isEmpty($item['PREVIEW_TEXT']) ? $item['PREVIEW_TEXT'] : $item['DETAIL_TEXT'] ?>
        <div class="col col_4">
            <a class="gallery item"
               data-fancybox="gallery"
               href="<?= $item['DETAIL_PICTURE']['SRC'] ?>"
               id="<?= v::addEditingActions($item, $this) ?>">
                <span class="gallery_img" style="background: url('<?= v::resize($preview, 300, 300) ?>')no-repeat center center / cover"></span>
                <span class="inner">
                    <span class="text"><?= $item['NAME'] ?></span>
                </span>
            </a>
            <? if (!v::isEmpty($text)): ?>
                <div class="description">
                    <?= $text ?>
                </div>
            <? endif ?>
        </div>
    <? endforeach ?>
</div>
<? // TODO pagination ?>
<? if (isset($arResult['LIST_PAGE_URL'])): ?>
    <div class="bottom_btn bottom_btn--back">
        <a href="<?= $arResult['LIST_PAGE_URL'] ?>" class="big_btn">
        <span class="img">
            <img src="<?= v::asset('images/arrow_left_white.svg') ?>">
        </span>
            <span class="text"><span>Назад</span></span>
        </a>
    </div>
<? endif ?>
