<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use App\View as v;
?>
<div class="our_objects">
    <div class="grid">
        <? foreach ($arResult['ITEMS'] as $item): ?>
            <? $link = $item['PROPERTIES']['LINK']['VALUE'] ?>
            <div class="item">
                <div class="img"
                     style="background: url('<?= $item['PREVIEW_PICTURE']['SRC'] ?>')no-repeat center center / cover"
                     id="<?= v::addEditingActions($item, $this) ?>"></div>
                <div class="info">
                    <p><?= $item['PREVIEW_TEXT'] ?></p>
                </div>
                <? if (!v::isEmpty($link)): ?>
                    <a href="<?= $link ?>"></a>
                <? endif ?>
            </div>
        <? endforeach ?>
    </div>
</div>
