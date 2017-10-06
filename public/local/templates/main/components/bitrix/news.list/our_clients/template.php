<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use App\View as v;

$showCard = function($item, $template) {
    ?>
    <div class="img">
        <? if ($item !== null): ?>
            <div class="img__inner"
                 style="background-image: url('<?= v::resize($item['PREVIEW_PICTURE'], 300, 300) ?>'), linear-gradient(0deg, #e7edf3, #e7edf3)"
                 title="<?= $item['PREVIEW_PICTURE']['ALT'] ?>"
                 id="<?= v::addEditingActions($item, $template) ?>"></div>
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
