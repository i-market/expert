<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use App\View as v;
?>
<section class="suggestions">
    <div class="wrap">
        <div class="grid">
            <? foreach($arResult['ITEMS'] as $item): ?>
                <div class="col col_2"
                     style="background: url('<?= $item['PREVIEW_PICTURE']['SRC'] ?>')no-repeat center center / cover"
                     id="<?= v::addEditingActions($item, $this) ?>">
                    <div class="block">
                        <div class="inner">
                            <?= $item['PREVIEW_TEXT'] ?>
                            <? $link = $item['PROPERTIES']['LINK']['VALUE'] ?>
                            <? if (!v::isEmpty($link)): ?>
                                <a href="<?= $link ?>"></a>
                            <? endif ?>
                        </div>
                    </div>
                </div>
            <? endforeach ?>
        </div>
    </div>
</section>
