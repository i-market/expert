<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use App\View as v;
?>
<div class="grid">
    <? foreach ($arResult['ITEMS'] as $item): ?>
        <? // TODO link ?>
        <a href="#" class="col col_3 item" id="<?= v::addEditingActions($item, $this) ?>">
            <div class="img" style="background: url('<?= $item['PREVIEW_PICTURE']['SRC'] ?>')no-repeat center center / cover"></div>
            <div class="text"><?= $item['PREVIEW_TEXT'] ?></div>
        </a>
    <? endforeach ?>
</div>
<div class="bottom_btn">
    <? // TODO link ?>
    <a href="#" class="big_btn">
        <span class="text"><span>Все статьи</span></span>
        <span class="img">
            <img src="<?= v::asset('images/arrow_right.png') ?>">
        </span>
    </a>
</div>
