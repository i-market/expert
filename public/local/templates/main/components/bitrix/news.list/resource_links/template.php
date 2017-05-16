<? if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use App\View as v;
?>
<? if (!v::isEmpty($arResult['SECTIONS'])): ?>
    <div class="wrap">
        <div class="accordeon grid">
            <? // TODO add section editing actions ?>
            <? foreach ($arResult['SECTIONS'] as $section): ?>
                <div class="accordeon_item col col_4">
                    <div class="accordeon_title"><?= $section['NAME'] ?></div>
                    <div class="accordeon_inner">
                        <? foreach ($section['ITEMS'] as $item): ?>
                            <? $link = $item['PROPERTIES']['LINK']['VALUE'] ?>
                            <p id="<?= v::addEditingActions($item, $this) ?>">
                                <a href="<?= $link ?>"><?= $item['NAME'] ?></a>
                            </p>
                        <? endforeach ?>
                    </div>
                </div>
            <? endforeach ?>
        </div>
    </div>
<? endif ?>
