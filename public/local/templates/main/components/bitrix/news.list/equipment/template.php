<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use App\View as v;
?>
<div class="wrap wrap--small">
    <div class="block">
        <? foreach ($arResult['ITEMS'] as $item): ?>
            <div class="technical_base_item" id="<?= v::addEditingActions($item, $this) ?>">
                <div class="info">
                    <p class="title"><?= $item['NAME'] ?></p>
                    <? // TODO proper editable area ?>
                    <p class="text"><?= $item['PREVIEW_TEXT'] ?></p>
                </div>
                <div class="img">
                    <a class="gallery" href="<?= $item['PREVIEW_PICTURE']['SRC'] ?>">
                        <img src="<?= v::resize($item['PREVIEW_PICTURE'], 270, 140) ?>" alt="<?= $item['PREVIEW_PICTURE']['ALT'] ?>">
                    </a>
                </div>
            </div>
        <? endforeach ?>
    </div>
</div>
