<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
?>
<div class="inner">
    <? foreach ($arResult['SECTIONS'] as $idx => $section): ?>
        <a class="item" href="<?= $section['SECTION_PAGE_URL'] ?>">
            <span class="number_marker"><?= $idx + 1 ?></span>
            <span class="text"><?= $section['NAME'] ?></span>
        </a>
    <? endforeach ?>
</div>
