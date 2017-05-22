<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use App\View as v;
?>
<div class="contacts_gallery">
    <div class="grid">
        <? foreach ($arResult['ITEMS'] as $item): ?>
            <a class="gallery col col_3" href="<?= $item['DETAIL_PICTURE']['SRC'] ?>"
               style="background: url('<?= $item['DETAIL_PICTURE']['SRC'] ?>')no-repeat center center / cover;"
               id="<?= v::addEditingActions($item, $this) ?>"></a>
        <? endforeach ?>
    </div>
</div>