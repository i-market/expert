<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use App\View as v;
?>
<div class="our_reviews">
    <div class="grid">
        <? foreach ($arResult['ITEMS'] as $item): ?>
            <? // TODO resize images ?>
            <a class="item gallery" href="<?= $item['DETAIL_PICTURE']['SRC'] ?>">
                <span class="img" id="<?= v::addEditingActions($item, $this) ?>">
                    <img src="<?= $item['DETAIL_PICTURE']['SRC'] ?>" alt="<?= $item['DETAIL_PICTURE']['ALT'] ?>">
                </span>
                <span class="text"><?= $item['NAME'] ?></span>
            </a>
        <? endforeach ?>
    </div>
</div>
