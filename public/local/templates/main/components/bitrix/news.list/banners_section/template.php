<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use App\View as v;
?>
<? if (!v::isEmpty($arResult['ITEMS'])): ?>
    <section class="<?= join(' ', $arResult['CLASS_LIST']) ?>">
        <div class="wrap">
            <div class="grid">
                <? foreach ($arResult['ITEMS'] as $item): ?>
                    <? $link = $item['PROPERTIES']['LINK']['VALUE'] ?>
                    <a href="<?= $link ?>"
                       class="col col_4"
                       style="background: url('<?= $item['PREVIEW_PICTURE']['SRC'] ?>')no-repeat center center / cover"
                       id="<?= v::addEditingActions($item, $this) ?>"></a>
                <? endforeach ?>
            </div>
        </div>
    </section>
<? endif ?>
