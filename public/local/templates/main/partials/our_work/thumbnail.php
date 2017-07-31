<? use App\View as v; ?>

<? // TODO 4 columns as in the psd mockup? ?>
<div class="item col col_3">
    <? // TODO add fancybox gallery id? ?>
    <? $link = v::get($item, 'DISPLAY_PROPERTIES.FILE.FILE_VALUE.SRC', $item['DETAIL_PICTURE']['SRC']) ?>
    <a class="gallery" href="<?= $link ?>" target="_blank">
                    <span class="wrap_img">
                        <img src="<?= $item['DETAIL_PICTURE']['SRC'] ?>" alt="<?= $item['DETAIL_PICTURE']['ALT'] ?>">
                    </span>
        <span class="text"><?= $item['DETAIL_TEXT'] ?></span>
    </a>
</div>
