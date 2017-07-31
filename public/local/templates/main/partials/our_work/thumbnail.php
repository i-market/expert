<? use App\View as v; ?>

<? // TODO editing actions ?>
<? // TODO 4 columns as in the psd mockup? ?>
<div class="item col col_3" id="<?= v::addEditingActions($item, $template) ?>">
    <? // TODO add fancybox gallery id? ?>
    <? $link = v::get($item, 'DISPLAY_PROPERTIES.FILE.FILE_VALUE.SRC', $item['DETAIL_PICTURE']['SRC']) ?>
    <a class="gallery" href="<?= $link ?>" target="_blank">
                    <span class="wrap_img">
                        <? // TODO image size ?>
                        <img src="<?= v::resize($item['DETAIL_PICTURE'], 370, 192) ?>" alt="<?= $item['DETAIL_PICTURE']['ALT'] ?>">
                    </span>
        <span class="text"><?= !v::isEmpty($item['PREVIEW_TEXT']) ? $item['PREVIEW_TEXT'] : $item['DETAIL_TEXT'] ?></span>
    </a>
</div>
