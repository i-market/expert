<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

$showSectionLink = function($section) {
    ?>
    <a href="<?= $section['SECTION_PAGE_URL'] ?>"><?= $section['NAME'] ?></a>
    <?
}
?>
<section class="work_examples_inner">
	<div class="wrap">
		<div class="wrap_title">
			<? // TODO number ?>
			<div class="number_marker">1</div>
			<h2><?= $arResult['SECTION']['NAME'] ?></h2>
		</div>
		<div class="accordeon">
			<? foreach ($arResult['ROOT_SECTIONS'] as $root): ?>
				<div class="accordeon_item">
					<div class="accordeon_title">
						<h4 class="color_blue"><?= $root['NAME'] ?></h4>
					</div>
					<div class="accordeon_inner">
						<ul class="list">
							<? foreach ($root['SECTIONS'] as $section): ?>
                                <? $elevation = $section['ELEVATION'] ?>
                                <? $attrs = 'class="heading"' ?>
                                <? if ($elevation === 3): ?>
                                    <h2 <?= $attrs ?>><? $showSectionLink($section) ?></h2>
                                <? elseif ($elevation === 2): ?>
                                    <h3 <?= $attrs ?>><? $showSectionLink($section) ?></h3>
                                <? elseif ($elevation === 1): ?>
                                    <h4 <?= $attrs ?>><? $showSectionLink($section) ?></h4>
                                <? else: ?>
                                    <li><? $showSectionLink($section) ?></li>
                                <? endif ?>
                            <? endforeach ?>
						</ul>
					</div>
				</div>
			<? endforeach ?>
            <?/*
			<div class="accordeon_item">
				<div class="accordeon_title">
					<h4 class="color_blue">Балки перекрытия</h4>
				</div>
				<div class="accordeon_inner">
					<div class="grid">
						<div class="work_examples_news col col_4">
							<a href="#" class="img" style="background: url('../images/pic_10.jpg')no-repeat center center / cover"></a>
							<a href="#" class="text">Равным образом рамки и место обучения кадров влечет за собой процесс внедрения и модернизации новых предложений. Повседневная практика показывает.</a>
						</div>
						<div class="work_examples_news col col_4">
							<a href="#" class="img" style="background: url('../images/pic_10.jpg')no-repeat center center / cover"></a>
							<a href="#" class="text">Равным образом рамки и место обучения кадров влечет за собой процесс внедрения и модернизации новых предложений. Повседневная практика показывает.</a>
						</div>
						<div class="work_examples_news col col_4">
							<a href="#" class="img" style="background: url('../images/pic_10.jpg')no-repeat center center / cover"></a>
							<a href="#" class="text">Равным образом рамки и место обучения кадров влечет за собой процесс внедрения и модернизации новых предложений. Повседневная практика показывает.</a>
						</div>
						<div class="work_examples_news col col_4">
							<a href="#" class="img" style="background: url('../images/pic_10.jpg')no-repeat center center / cover"></a>
							<a href="#" class="text">Равным образом рамки и место обучения кадров влечет за собой процесс внедрения и модернизации новых предложений. Повседневная практика показывает.</a>
						</div>
					</div>
				</div>
			</div>
            */?>
		</div>
	</div>
</section>