<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use App\View as v;

if (!$arResult['NavShowAlways']) {
    if ($arResult['NavRecordCount'] == 0 || ($arResult['NavPageCount'] == 1 && $arResult['NavShowAll'] == false)) {
        return;
    }
}
?>
<? // TODO semantic html ?>
<div class="pagination">
    <? if ($arResult['NAV']['START_PAGE'] > 1): ?>
        <a href="<?= $arResult['NAV']['URL']['FIRST_PAGE'] ?>" class="btn first">
            <span class="img">
                <img src="<?= v::asset('images/arrow_left_blue.svg') ?>">
            </span>
            <span class="text"><span>В начало</span></span>
        </a>
    <? endif ?>
    <? if ($arResult['NAV']['PAGE_NUMBER'] > 1): ?>
        <? $prevNum = $arResult['NAV']['PAGE_NUMBER'] - 1 ?>
        <a href="<?= $arResult['NAV']['URL']['SOME_PAGE'][$prevNum] ?>" class="btn prev">
            <span class="img">
                <img src="<?= v::asset('images/arrow_left_blue.svg') ?>">
            </span>
            <span class="text"><span>Назад</span></span>
        </a>
    <? endif ?>
    <? for ($PAGE_NUMBER=$arResult['NAV']['START_PAGE']; $PAGE_NUMBER<=$arResult['NAV']['END_PAGE']; $PAGE_NUMBER++):?>
        <? if ($PAGE_NUMBER == $arResult['NAV']['PAGE_NUMBER']):?>
            <div class="page active"><?= $PAGE_NUMBER ?><span class="expanded">&nbsp;/ <?= $arResult['NAV']['PAGE_COUNT'] ?></span></div>
        <? else:?>
            <a class="page" href="<?= $arResult['NAV']['URL']['SOME_PAGE'][$PAGE_NUMBER]?>"><?= $PAGE_NUMBER ?></a>
        <? endif ?>
    <? endfor ?>
    <?
    $isLastPageActive = $arResult['NAV']['PAGE_NUMBER'] == $arResult['NAV']['END_PAGE'];
    if (!$isLastPageActive):
        $nextNum = $arResult['NAV']['PAGE_NUMBER'] + 1;
    ?>
        <a href="<?= $arResult['NAV']['URL']['SOME_PAGE'][$nextNum] ?>" class="btn next">
            <span class="text"><span>Дальше</span></span>
            <span class="img">
                <img src="<?= v::asset('images/arrow_right_white.svg') ?>">
            </span>
        </a>
    <? endif ?>
</div>
