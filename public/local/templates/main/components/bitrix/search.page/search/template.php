<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use App\View as v;
?>
<section class="search_section">
    <div class="wrap wrap--small">
        <div class="wrap_search_form">
            <span class="text">Вы искали:</span>
            <form action="" class="search_form">
                <input name="q" type="text" value="<?= $arResult['REQUEST']['QUERY'] ?>">
                <button type="submit">Найти</button>
            </form>
        </div>
        <div class="wrap_search_items">
            <? if (isset($arResult['REQUEST']['ORIGINAL_QUERY'])): ?>
                <div class="search_item">
                    <p class="text">
                        В запросе "<a href="<?= $arResult['ORIGINAL_QUERY_URL'] ?>"><?= $arResult['REQUEST']['ORIGINAL_QUERY'] ?></a>" восстановлена раскладка клавиатуры.
                    </p>
                </div>
            <? endif ?>
            <? if (v::isEmpty($arResult['SEARCH'])): ?>
                <div class="search_item">
                    <? // TODO improve ux ?>
                    <p class="text">По вашему запросу ничего не найдено.</p>
                </div>
            <? endif ?>
            <? foreach ($arResult['SEARCH'] as $item): ?>
                <div class="search_item">
                    <a class="title" href="<?= $item['URL'] ?>"><?= $item['TITLE_FORMATED'] ?></a>
                    <p class="text"><?= $item['BODY_FORMATED'] ?></p>
                </div>
                <? // TODO tmp ?>
                <div class="search_item">
                    <p class="title"><?= $item['TITLE_FORMATED'] ?></p>
                    <p class="text"><?= $item['BODY_FORMATED'] ?></p>
                </div>
            <? endforeach ?>
        </div>
        <div class="wrap_search_items_foto">
            <h4>ФОТО</h4>
            <div class="search_item_foto">
                <div class="img">
                    <a class="gallery" href="images/pic_7.jpg">
                        <img src="images/pic_7.jpg" alt="">
                    </a>
                </div>
                <div class="info">
                    <p class="text"> <span class="search_word">Электрооборудование</span> в действии чрезвычайных ситуаций</p>
                </div>
            </div>
            <div class="search_item_foto">
                <div class="img">
                    <a class="gallery" href="images/pic_7.jpg">
                        <img src="images/pic_7.jpg" alt="">
                    </a>
                </div>
                <div class="info">
                    <p class="text"> <span class="search_word">Электрооборудование</span> в действии чрезвычайных ситуаций</p>
                </div>
            </div>
        </div>
        <div class="wrap_search_items_video">
            <h4>ВИДЕО</h4>
            <div class="search_item_video video_item" data-src="https://www.youtube.com/embed/iJ51QUQuFW8">
                <div class="video" style="background: url('../images/pic_10.jpg')no-repeat center center / cover">
                    <iframe src="" frameborder="0" allowfullscreen></iframe>
                </div>
                <div class="info">
                    <p class="text">Ход строительства <span class="search_word">электрооборудования</span> EXPO-2017, Астана, Казахстан</p>
                </div>
            </div>
            <div class="search_item_video video_item" data-src="https://www.youtube.com/embed/iJ51QUQuFW8">
                <div class="video" style="background: url('../images/pic_10.jpg')no-repeat center center / cover">
                    <iframe src="" frameborder="0" allowfullscreen></iframe>
                </div>
                <div class="info">
                    <p class="text">Ход строительства <span class="search_word">электрооборудования</span> EXPO-2017, Астана, Казахстан</p>
                </div>
            </div>
        </div>
    </div>
</section>

