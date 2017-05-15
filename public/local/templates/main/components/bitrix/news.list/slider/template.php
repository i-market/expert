<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use App\View as v;
?>
<section class="wrap_banner_slider">
    <span class="arrow prev"></span>
    <span class="arrow next"></span>
    <div class="banner_slider">
        <? foreach ($arResult['ITEMS'] as $item): ?>
            <div class="slide"
                 style="background: url('<?= $item['PREVIEW_PICTURE']['SRC'] ?>')no-repeat center center / cover"
                 id="<?= v::addEditingActions($item, $this) ?>">
                <div class="wrap">
                    <div class="left">
                        <?= $item['PREVIEW_TEXT'] ?>
                    </div>
                    <div class="right">
                        <p><?= $item['DETAIL_TEXT'] ?></p>
                    </div>
                </div>
            </div>
        <? endforeach ?>
    </div>
</section>