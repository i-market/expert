<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use App\View as v;
?>
<section class="suggestions">
    <div class="wrap">
        <div class="grid">
            <? foreach($arResult['ITEMS'] as $item): ?>
                <? $subheading = $item['PROPERTIES']['SUBHEADING']['VALUE'] ?>
                <? $smallText = $item['PROPERTIES']['SMALL_TEXT']['VALUE'] ?>
                <? $link = $item['PROPERTIES']['LINK']['VALUE'] ?>
                <div class="col col_2"
                     style="background: url('<?= $item['PREVIEW_PICTURE']['SRC'] ?>')no-repeat center center / cover"
                     id="<?= v::addEditingActions($item, $this) ?>">
                    <div class="block">
                        <div class="inner">
                            <h3><?= $item['PREVIEW_TEXT'] ?></h3>
                            <? if (!v::isEmpty($subheading)): ?>
                                <p><?= $subheading ?></p>
                            <? endif ?>
                            <? if (!v::isEmpty($smallText)): ?>
                                <span><?= $smallText ?></span>
                            <? endif ?>
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
