<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use App\View as v;

$showCard = function($item, $template) {
    ?>
    <div class="img">
        <? if ($item !== null): ?>
            <img src="<?= v::resize($item['PREVIEW_PICTURE']['SRC'], 300, 300) ?>"
                 alt="<?= $item['PREVIEW_PICTURE']['ALT'] ?>"
                 id="<?= v::addEditingActions($item, $template) ?>">
        <? endif ?>
    </div>
    <?
}
?>
<div class="our_clients">
    <div class="grid">
        <? foreach ($arResult['COLUMNS'] as $column): ?>
            <? list($first, $second) = $column['ITEMS'] ?>
            <div class="item">
                <? $showCard($first, $this) ?>
                <? $showCard($second, $this) ?>
            </div>
        <? endforeach ?>
    </div>
</div>
