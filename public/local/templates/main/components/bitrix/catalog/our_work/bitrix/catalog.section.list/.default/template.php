<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use App\View as v;

$splitList = function($contentFn) {
    ?>
    </ul><? $contentFn() ?><ul class="list">
    <?
};
$showSections = function($sections) use ($splitList) {
    ?>
    <ul class="list">
        <? $attrs = 'class="heading"' ?>
        <? foreach ($sections as $section): ?>
            <? $link = "<a href=\"{$section['SECTION_PAGE_URL']}\" data-id=\"{$section['ID']}\">{$section['NAME']}</a>" ?>
            <? $elevation = $section['ELEVATION'] ?>
            <? if ($elevation === 3): ?>
                <? $splitList(function() use ($attrs, $link) { ?>
                    <h2 <?= $attrs ?>><?= $link ?></h2>
                <? }) ?>
            <? elseif ($elevation === 2): ?>
                <? $splitList(function() use ($attrs, $link) { ?>
                    <h3 <?= $attrs ?>><?= $link ?></h3>
                <? }) ?>
            <? elseif ($elevation === 1): ?>
                <? $splitList(function() use ($attrs, $link) { ?>
                    <h4 <?= $attrs ?>><?= $link ?></h4>
                <? }) ?>
            <? else: ?>
                <li><?= $link ?></li>
            <? endif ?>
        <? endforeach ?>
    </ul>
    <?
}
?>
<? ob_start() ?>
<section class="work_examples_inner">
	<div class="wrap">
		<div class="wrap_title">
            <? if (!v::isEmpty(v::get($arResult, 'NUMBER_MARKER'))): ?>
                <div class="number_marker"><?= $arResult['NUMBER_MARKER'] ?></div>
            <? endif ?>
            <h2><?= $arResult['SECTION']['NAME'] ?></h2>
		</div>
        <? if (!v::isEmpty($arResult['ROOT_SECTIONS'])): ?>
            <div class="accordeon">
                <? foreach ($arResult['ROOT_SECTIONS'] as $root): ?>
                    <div class="accordeon_item">
                        <div class="accordeon_title">
                            <h4 class="color_blue"><?= $root['NAME'] ?></h4>
                        </div>
                        <div class="accordeon_inner">
                            <? if (!v::isEmpty($root['SECTIONS'])): ?>
                                <? $showSections($root['SECTIONS']) ?>
                            <? else: ?>
                                <? // placeholder to be replaced in component_epilog.php ?>
                                <?= "{{ placeholder:section:{$root['ID']} }}" ?>
                            <? endif ?>
                        </div>
                    </div>
                <? endforeach ?>
            </div>
		<? endif ?>
	</div>
</section>
<?
$key = 'HTML_OUTPUT';
$component->arResult[$key] = ob_get_clean();
$component->setResultCacheKeys([$key]);
?>