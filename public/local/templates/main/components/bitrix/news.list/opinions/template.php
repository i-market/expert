<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use App\View as v;
?>
<div class="grid">
    <? foreach ($arResult['ITEMS'] as $item): ?>
        <? // TODO link ?>
        <a href="#" class="col col_3 item" id="<?= v::addEditingActions($item, $this) ?>">
            <div class="img" style="background: url('<?= $item['PREVIEW_PICTURE']['SRC'] ?>')no-repeat center center / cover"></div>
            <div class="text"><?= $item['PREVIEW_TEXT'] ?></div>
        </a>
    <? endforeach ?>
</div>
<? if ($arParams['DISPLAY_BOTTOM_PAGER']): ?>
    <?= $arResult['NAV_STRING'] ?>
<? endif ?>
