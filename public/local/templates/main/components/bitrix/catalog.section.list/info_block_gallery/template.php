<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use App\View as v;

// TODO refactor DRY. see bitrix:catalog `info_block_gallery` template.
?>
<div class="grid">
    <? foreach ($arResult['SECTIONS'] as $section): ?>
        <? $pic = !v::isEmpty($section['PICTURE']) ? $section['PICTURE'] : $section['DETAIL_PICTURE'] ?>
        <a class="item col col_4"
           href="<?= $section['SECTION_PAGE_URL'] ?>"
           id="<?= v::addEditingActions($section, $this) ?>">
            <span class="gallery_img" style="background: url('<?= v::resize($pic, 300, 300) ?>')no-repeat center center / cover"></span>
            <span class="inner">
                <span class="text"><?= $section['NAME'] ?></span>
            </span>
        </a>
    <? endforeach ?>
</div>
