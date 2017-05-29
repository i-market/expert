<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use App\View as v;
?>
<div class="grid">
    <? foreach ($arResult['ITEMS'] as $item): ?>
        <? list($tag, $attributes) = $item['LINK_MAYBE'] === null
            ? ['div', '']
            : ['a', ' href="'.$item['LINK_MAYBE'].'" target="_blank"'] ?>
        <div class="col col_2">
            <<?= $tag.$attributes ?> class="item" id="<?= v::addEditingActions($item, $this) ?>">
                <div class="img">
                    <img src="<?= $item['PREVIEW_PICTURE']['SRC'] ?>" alt="<?= $item['PREVIEW_PICTURE']['ALT'] ?>">
                </div>
                <div class="info">
                    <p class="title"><?= $item['NAME'] ?> <span class="author"><?= "({$item['AUTHOR']})" ?></span></p>
                    <p class="text"><?= $item['PREVIEW_TEXT'] ?></p>
                </div>
            </<?= $tag ?>>
        </div>
    <? endforeach ?>
</div>
<div class="bottom_btn">
    <a href="<?= v::path('info-block/literature') ?>" class="big_btn">
        <span class="text"><span>Вся литература</span></span>
        <span class="img">
            <img src="<?= v::asset('images/arrow_right.png') ?>">
          </span>
    </a>
</div>
