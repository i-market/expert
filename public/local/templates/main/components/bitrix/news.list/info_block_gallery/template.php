<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use App\View as v;
?>
<div class="grid">
    <? foreach ($arResult['ITEMS'] as $item): ?>
        <? // TODO resize ?>
        <a class="gallery col col_4"
           data-fancybox="gallery"
           href="<?= $item['DETAIL_PICTURE']['SRC'] ?>"
           id="<?= v::addEditingActions($item, $this) ?>">
            <span class="gallery_img" style="background: url('<?= $item['DETAIL_PICTURE']['SRC'] ?>')no-repeat center center / cover"></span>
            <span class="inner">
                <span class="text"><?= $item['NAME'] ?></span>
            </span>
        </a>
    <? endforeach ?>
</div>
<div class="bottom_btn">
    <? // TODO link ?>
    <a href="#" class="big_btn">
        <span class="text"><span>Все фотоподборки</span></span>
        <span class="img">
            <img src="<?= v::asset('images/arrow_right.png') ?>">
        </span>
    </a>
</div>
