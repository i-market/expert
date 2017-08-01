<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use App\View as v;
?>
<div class="grid">
    <? foreach ($arResult['SECTIONS'] as $section): ?>
        <a class="item col col_4"
           href="<?= $section['SECTION_PAGE_URL'] ?>"
           id="<?= v::addEditingActions($section, $this) ?>">
            <span class="gallery_img" style="background: url('<?= v::resize($section['DETAIL_PICTURE'], 300, 300) ?>')no-repeat center center / cover"></span>
            <span class="inner">
                <span class="text"><?= $section['NAME'] ?></span>
            </span>
        </a>
    <? endforeach ?>
</div>
