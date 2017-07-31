<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use App\View as v;
?>
<? if (!v::isEmpty($arResult['ITEMS'])): ?>
    <div class="our-work-thumbnail-grid">
        <div class="grid">
            <? foreach ($arResult['ITEMS'] as $item): ?>
                <?= v::render('partials/our_work/thumbnail', array_merge(get_defined_vars(), ['template' => $this])) ?>
            <? endforeach ?>
        </div>
    </div>
<? endif ?>
