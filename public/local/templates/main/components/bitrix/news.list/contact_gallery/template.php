<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use App\View as v;
?>
<div class="contacts_gallery">
    <div class="grid">
        <? foreach ($arResult['ITEMS'] as $item): ?>
            <? $preview = !v::isEmpty($item['PREVIEW_PICTURE']) ? $item['PREVIEW_PICTURE'] : $item['DETAIL_PICTURE'] ?>
            <a class="gallery col col_3" href="<?= $item['DETAIL_PICTURE']['SRC'] ?>"
               style="background: url('<?= $preview['SRC'] ?>')no-repeat center center / cover;"
               id="<?= v::addEditingActions($item, $this) ?>"></a>
        <? endforeach ?>
    </div>
</div>