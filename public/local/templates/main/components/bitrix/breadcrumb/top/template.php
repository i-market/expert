<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use App\View as v;

$showItemName = function($title) {
    ?>
    <span itemprop="name"><?= $title ?></span>
    <?
};
$showItem = function($position, $link, $title, $isLast) use ($showItemName) {
    ?>
    <li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
        <? if (!$isLast): ?>
            <a href="<?= $link ?>" itemprop="item">
                <? $showItemName($title) ?>
            </a>
        <? else: ?>
            <? // TODO add microdata url? ?>
            <span itemprop="item">
                <? $showItemName($title) ?>
            </span>
        <? endif ?>
        <meta itemprop="position" content="<?= $position ?>" />
    </li>
    <? if (!$isLast): ?>
        <span class="separator">/</span>
    <? endif ?>
    <?
};
?>
<? ob_start() ?>
<? if (!v::isEmpty($arResult)): ?>
    <section class="bread_crumbs">
        <div class="wrap">
            <ol itemscope itemtype="http://schema.org/BreadcrumbList">
                <? $showItem(1, v::path('/'), 'Главная', false) ?>
                <? foreach ($arResult as $idx => $item): ?>
                    <? $position = $idx + 2 ?>
                    <? $isLast = $idx + 1 === count($arResult) ?>
                    <? $showItem($position, $item['LINK'], $item['TITLE'], $isLast) ?>
                <? endforeach ?>
            </ol>
        </div>
    </section>
<? endif ?>
<? return ob_get_clean() ?>
