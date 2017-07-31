<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use App\View as v;

$showCard = function($item, $template) use ($arResult) {
    if ($item === null) {
        // fill the gap with some item in the middle
        $item = $arResult['COLUMNS'][floor(count($arResult['COLUMNS'])  / 2)]['ITEMS'][0];
    }
    ?>
    <div class="item">
        <div class="img"
             style="background: url('<?= $item['PREVIEW_PICTURE']['SRC'] ?>')no-repeat center center / cover"
             id="<?= v::addEditingActions($item, $template) ?>"></div>
        <div class="info">
            <p><?= $item['PREVIEW_TEXT'] ?></p>
        </div>
        <? $link = $item['PROPERTIES']['LINK']['VALUE'] ?>
        <? if (!v::isEmpty($link)): ?>
            <a href="<?= $link ?>"></a>
        <? endif ?>
    </div>
    <?
}
?>
<div class="our_objects">
    <div class="grid">
        <? foreach ($arResult['COLUMNS'] as $column): ?>
            <? list($first, $second) = $column['ITEMS'] ?>
            <div class="col col_3">
                <? $showCard($first, $this) ?>
                <? $showCard($second, $this) ?>
            </div>
        <? endforeach ?>
    </div>
</div>
