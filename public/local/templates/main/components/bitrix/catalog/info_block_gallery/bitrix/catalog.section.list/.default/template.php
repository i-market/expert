<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use App\View as v;
?>
<div class="grid">
    <? foreach ($arResult['SECTIONS'] as $section): ?>
        <? $pic = !v::isEmpty($section['PICTURE']) ? $section['PICTURE'] : $section['DETAIL_PICTURE'] ?>
        <div class="col col_4">
            <a class="item"
               href="<?= $section['SECTION_PAGE_URL'] ?>"
               id="<?= v::addEditingActions($section, $this) ?>">
                <span class="gallery_img" style="background: url('<?= v::resize($pic, 300, 300) ?>')no-repeat center center / cover"></span>
                <span class="inner">
                    <span class="text"><?= $section['NAME'] ?></span>
                </span>
            </a>
            <? if (!v::isEmpty($section['DESCRIPTION'])): ?>
                <div class="description">
                    <?= $section['DESCRIPTION'] ?>
                </div>
            <? endif ?>
        </div>
    <? endforeach ?>
</div>
