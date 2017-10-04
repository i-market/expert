<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use App\View as v;
?>
<section class="search_section">
    <div class="wrap wrap--small">
        <div class="wrap_search_form">
            <span class="text">Вы искали:</span>
            <form action="" class="search_form">
                <input name="q" type="text" value="<?= $arResult['REQUEST']['QUERY'] ?>" autocomplete="off">
                <button type="submit">Найти</button>
            </form>
        </div>
        <div class="wrap_search_items">
            <? if (isset($arResult['REQUEST']['ORIGINAL_QUERY'])): ?>
                <div class="search_item">
                    <p class="text">
                        Исправлена раскладка клавиатуры в «<a href="<?= $arResult['ORIGINAL_QUERY_URL'] ?>"><?= $arResult['REQUEST']['ORIGINAL_QUERY'] ?></a>»
                    </p>
                </div>
            <? endif ?>
            <? if (v::isEmpty($arResult['SEARCH'])): ?>
                <div class="search_item">
                    <? // TODO improve ux ?>
                    <p class="text">По вашему запросу ничего не найдено.</p>
                </div>
            <? endif ?>
            <? foreach ($arResult['BY_TYPE']['DEFAULT'] as $item): ?>
                <div class="search_item">
                    <a class="title" href="<?= $item['URL'] ?>"><?= $item['TITLE_FORMATED'] ?></a>
                    <p class="text"><?= $item['BODY_FORMATED'] ?></p>
                </div>
            <? endforeach ?>
        </div>
        <? if (!v::isEmpty($arResult['BY_TYPE']['IMAGES'])): ?>
            <div class="wrap_search_items_foto">
                <h4>ФОТО</h4>
                <? foreach ($arResult['BY_TYPE']['IMAGES'] as $item): ?>
                    <? $el = $item['ELEMENT'] ?>
                    <? $pic = !v::isEmpty($el['DETAIL_PICTURE']) ? $el['DETAIL_PICTURE'] : $el['PREVIEW_PICTURE'] ?>
                    <div class="search_item_foto">
                        <div class="img">
                            <a class="gallery" href="<?= $pic['SRC'] ?>">
                                <img src="<?= $pic['SRC'] ?>" alt="<?= $pic['ALT'] ?>">
                            </a>
                        </div>
                        <div class="info">
                            <p class="text"><?= !v::isEmpty($item['BODY_FORMATED']) ? $item['BODY_FORMATED'] : $item['TITLE_FORMATED'] ?></p>
                        </div>
                    </div>
                <? endforeach ?>
            </div>
        <? endif ?>
        <? if (!v::isEmpty($arResult['BY_TYPE']['VIDEOS'])): ?>
            <div class="wrap_search_items_video">
                <h4>ВИДЕО</h4>
                <? foreach ($arResult['BY_TYPE']['VIDEOS'] as $item): ?>
                    <div class="search_item_video video_item" data-src="<?= $item['ELEMENT']['YOUTUBE_SRC'] ?>">
                        <div class="video" style="background: url('<?= $item['ELEMENT']['PREVIEW_PICTURE']['SRC'] ?>')no-repeat center center / cover">
                            <iframe src="" frameborder="0" allowfullscreen></iframe>
                        </div>
                        <div class="info">
                            <p class="text"><?= $item['TITLE_FORMATED'] ?></p>
                        </div>
                    </div>
                <? endforeach ?>
            </div>
        <? endif ?>
        <?= $arResult['NAV_STRING'] ?>
    </div>
</section>

