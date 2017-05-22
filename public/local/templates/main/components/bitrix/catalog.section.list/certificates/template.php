<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die(); 

use App\View as v;
?>
<section class="three_btns">
    <div class="wrap">
        <div class="grid">
            <? foreach ($arResult['SECTIONS'] as $section): ?>
                <? if ($section['ELEMENT_CNT'] > 0): ?>
                    <div class="col col_3">
                        <? // TODO .small_text? ?>
                        <a href="<?= $section['SECTION_PAGE_URL'] ?>" class="big_btn">
                            <span class="text"><span><?= $section['NAME'] ?></span></span>
                            <span class="img">
                                <img src="<?= v::asset('images/arrow_right.png') ?>">
                            </span>
                        </a>
                    </div>
                <? endif ?>
            <? endforeach ?>
        </div>
    </div>
</section>
