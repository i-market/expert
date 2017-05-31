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
            <? // TODO refactor ?>
            <? $elevation = $section['ELEVATION'] ?>
            <? if ($elevation === 3): ?>
                <? $splitList(function() use ($attrs, $section) { ?>
                    <h2 <?= $attrs ?>><?= $section['NAME'] ?></h2>
                <? }) ?>
            <? elseif ($elevation === 2): ?>
                <? $splitList(function() use ($attrs, $section) { ?>
                    <h3 <?= $attrs ?>><?= $section['NAME'] ?></h3>
                <? }) ?>
            <? elseif ($elevation === 1): ?>
                <? $splitList(function() use ($attrs, $section) { ?>
                    <h4 <?= $attrs ?>><?= $section['NAME'] ?></h4>
                <? }) ?>
            <? else: ?>
                <li><a href="<?= $section['SECTION_PAGE_URL'] ?>"><?= $section['NAME'] ?></a></li>
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
			<? // TODO decorative number ?>
<!--			<div class="number_marker">1</div>-->
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