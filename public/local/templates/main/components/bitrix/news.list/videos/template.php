<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use App\View as v;
?>
<div class="grid">
    <? foreach ($arResult['ITEMS'] as $item): ?>
        <div class="col col_3" id="<?= v::addEditingActions($item, $this) ?>">
            <div class="infoblock_video_item video_item" data-src="<?= $item['YOUTUBE_SRC'] ?>">
                <div class="video" style="background: url('<?= $item['PREVIEW_PICTURE']['SRC'] ?>')no-repeat center center / cover">
                    <iframe src="" frameborder="0" allowfullscreen></iframe>
                </div>
                <p class="text"><?= $item['NAME'] ?></p>
            </div>
        </div>
    <? endforeach ?>
</div>
<div class="bottom_btn">
    <? // TODO link ?>
    <a href="#" class="big_btn">
        <span class="text"><span>Смотреть все видео</span></span>
        <span class="img">
            <img src="<?= v::asset('images/arrow_right.png') ?>">
          </span>
    </a>
</div>
