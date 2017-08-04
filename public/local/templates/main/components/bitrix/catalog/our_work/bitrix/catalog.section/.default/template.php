<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use App\View as v;
?>
<div class="our-work-thumbnail-grid our-work-thumbnail-grid--page">
    <div class="grid">
        <? foreach ($arResult['ITEMS'] as $item): ?>
            <?= v::render('partials/our_work/thumbnail', array_merge(get_defined_vars(), ['template' => $this])) ?>
        <? endforeach ?>
    </div>
</div>
<? if (isset($arResult['PATH'][0]['SECTION_PAGE_URL'])): ?>
    <div class="bottom_btn bottom_btn--back">
        <a href="<?= $arResult['PATH'][0]['SECTION_PAGE_URL'].'#backFrom='.$arResult['ID'] ?>" class="big_btn">
        <span class="img">
            <img src="<?= v::asset('images/arrow_left_white.svg') ?>">
        </span>
            <span class="text"><span>Назад</span></span>
        </a>
    </div>
<? endif ?>
