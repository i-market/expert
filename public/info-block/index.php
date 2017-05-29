<?
use App\View as v;
use App\Iblock;
use Bex\Tools\Iblock\IblockTools;

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Инфоблок");
$APPLICATION->SetPageProperty('layout', 'bare');
$APPLICATION->SetPageProperty('hide_bottom_banners', 'Y');
?>

<section class="useful_literature">
    <div class="wrap">
        <div class="wrap_title">
            <h2><?= $APPLICATION->GetTitle(false) ?></h2>
        </div>
        <div class="wrap_title">
            <? $APPLICATION->IncludeComponent(
                "bitrix:main.include",
                "",
                Array(
                    "AREA_FILE_SHOW" => "file",
                    "PATH" => v::includedArea('info-block/literature_heading.php')
                )
            ); ?>
        </div>
        <? $APPLICATION->IncludeComponent(
            "bitrix:news.list",
            "literature",
            Array(
                "ACTIVE_DATE_FORMAT" => "j F Y",
                "ADD_SECTIONS_CHAIN" => "N",
                "AJAX_MODE" => "N",
                "AJAX_OPTION_ADDITIONAL" => "",
                "AJAX_OPTION_HISTORY" => "N",
                "AJAX_OPTION_JUMP" => "N",
                "AJAX_OPTION_STYLE" => "Y",
                "CACHE_FILTER" => "N",
                "CACHE_GROUPS" => "Y",
                "CACHE_TIME" => "36000000",
                "CACHE_TYPE" => "A",
                "CHECK_DATES" => "Y",
                "DETAIL_URL" => "",
                "DISPLAY_BOTTOM_PAGER" => "Y",
                "DISPLAY_DATE" => "Y",
                "DISPLAY_NAME" => "Y",
                "DISPLAY_PICTURE" => "Y",
                "DISPLAY_PREVIEW_TEXT" => "Y",
                "DISPLAY_TOP_PAGER" => "N",
                "FIELD_CODE" => array("", ""),
                "FILTER_NAME" => "",
                "HIDE_LINK_WHEN_NO_DETAIL" => "N",
                "IBLOCK_ID" => IblockTools::find(Iblock::CONTENT_TYPE, Iblock::LITERATURE)->id(),
                "IBLOCK_TYPE" => Iblock::CONTENT_TYPE,
                "INCLUDE_IBLOCK_INTO_CHAIN" => "N",
                "INCLUDE_SUBSECTIONS" => "Y",
                "MESSAGE_404" => "",
                "NEWS_COUNT" => 4,
                "PAGER_BASE_LINK_ENABLE" => "N",
                "PAGER_DESC_NUMBERING" => "N",
                "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
                "PAGER_SHOW_ALL" => "N",
                "PAGER_SHOW_ALWAYS" => "N",
                "PAGER_TEMPLATE" => ".default",
                "PAGER_TITLE" => '',
                "PARENT_SECTION" => "",
                "PARENT_SECTION_CODE" => "",
                "PREVIEW_TRUNCATE_LEN" => "",
                "PROPERTY_CODE" => array('AUTHOR', 'FILE', 'LINK'),
                "SET_BROWSER_TITLE" => "N",
                "SET_LAST_MODIFIED" => "N",
                "SET_META_DESCRIPTION" => "N",
                "SET_META_KEYWORDS" => "N",
                "SET_STATUS_404" => "N",
                "SET_TITLE" => "N",
                "SHOW_404" => "N",
                "SORT_BY1" => "ACTIVE_FROM",
                "SORT_BY2" => "SORT",
                "SORT_ORDER1" => "DESC",
                "SORT_ORDER2" => "ASC"
            )
        ); ?>
    </div>
</section>
<section class="some_section">
    <div class="wrap">
        <div class="grid">
            <a href="#" class="col col_4" style="background: url('images/pic_4.jpg')no-repeat center center / cover"></a>
            <a href="#" class="col col_4" style="background: url('images/pic_5.jpg')no-repeat center center / cover"></a>
            <a href="#" class="col col_4" style="background: url('images/pic_6.jpg')no-repeat center center / cover"></a>
            <a href="#" class="col col_4" style="background: url('images/pic_7.jpg')no-repeat center center / cover"></a>
        </div>
    </div>
</section>
<section class="infoblock_news">
    <div class="wrap">
        <div class="wrap_title">
            <h4>аналитика, экспертные мнения</h4>
        </div>
        <div class="grid">
            <a href="#" class="col col_3 item">
                <div class="img" style="background: url('../images/pic_10.jpg')no-repeat center center / cover"></div>
                <div class="text">Не следует, однако забывать, что начало повседневной работы по формированию позиции влечет за собой процесс внедрения и модернизации существенных финансовых и административных условий. </div>
            </a>
            <a href="#" class="col col_3 item">
                <div class="img" style="background: url('../images/pic_10.jpg')no-repeat center center / cover"></div>
                <div class="text">Не следует, однако забывать, что начало повседневной работы по формированию позиции влечет за собой процесс внедрения и модернизации существенных финансовых и административных условий. </div>
            </a>
            <a href="#" class="col col_3 item">
                <div class="img" style="background: url('../images/pic_10.jpg')no-repeat center center / cover"></div>
                <div class="text">Не следует, однако забывать, что начало повседневной работы по формированию позиции влечет за собой процесс внедрения и модернизации существенных финансовых и административных условий. </div>
            </a>
            <a href="#" class="col col_3 item">
                <div class="img" style="background: url('../images/pic_10.jpg')no-repeat center center / cover"></div>
                <div class="text">Не следует, однако забывать, что начало повседневной работы по формированию позиции влечет за собой процесс внедрения и модернизации существенных финансовых и административных условий. </div>
            </a>
            <a href="#" class="col col_3 item">
                <div class="img" style="background: url('../images/pic_10.jpg')no-repeat center center / cover"></div>
                <div class="text">Не следует, однако забывать, что начало повседневной работы по формированию позиции влечет за собой процесс внедрения и модернизации существенных финансовых и административных условий. </div>
            </a>
            <a href="#" class="col col_3 item">
                <div class="img" style="background: url('../images/pic_10.jpg')no-repeat center center / cover"></div>
                <div class="text">Не следует, однако забывать, что начало повседневной работы по формированию позиции влечет за собой процесс внедрения и модернизации существенных финансовых и административных условий. </div>
            </a>
        </div>
        <div class="bottom_btn">
            <a href="#" class="big_btn">
                <span class="text"><span>Вся статьи</span></span>
                <span class="img">
            <img src="images/arrow_right.png" alt="">
          </span>
            </a>
        </div>
    </div>
</section>
<section class="some_section">
    <div class="wrap">
        <div class="grid">
            <a href="#" class="col col_4" style="background: url('images/pic_4.jpg')no-repeat center center / cover"></a>
            <a href="#" class="col col_4" style="background: url('images/pic_5.jpg')no-repeat center center / cover"></a>
            <a href="#" class="col col_4" style="background: url('images/pic_6.jpg')no-repeat center center / cover"></a>
            <a href="#" class="col col_4" style="background: url('images/pic_7.jpg')no-repeat center center / cover"></a>
        </div>
    </div>
</section>
<section class="infoblock_gallery">
    <div class="wrap">
        <div class="wrap_title">
            <h4>Кунсткамера</h4>
        </div>
        <div class="grid">
            <a class="gallery col col_4" data-fancybox="gallery" href="images/pic_10.jpg">
                <span class="gallery_img" style="background: url('images/pic_10.jpg')no-repeat center center / cover"></span>
                <span class="inner">
          <span class="text">Какое-то очень длинное название</span>
        </span>
            </a>
            <a class="gallery col col_4" data-fancybox="gallery" href="images/pic_10.jpg">
                <span class="gallery_img" style="background: url('images/pic_10.jpg')no-repeat center center / cover"></span>
                <span class="inner">
          <span class="text">Название</span>
        </span>
            </a>
            <a class="gallery col col_4" data-fancybox="gallery" href="images/pic_10.jpg">
                <span class="gallery_img" style="background: url('images/pic_10.jpg')no-repeat center center / cover"></span>
                <span class="inner">
          <span class="text">Название</span>
        </span>
            </a>
            <a class="gallery col col_4" data-fancybox="gallery" href="images/pic_10.jpg">
                <span class="gallery_img" style="background: url('images/pic_10.jpg')no-repeat center center / cover"></span>
                <span class="inner">
          <span class="text">Название</span>
        </span>
            </a>
            <a class="gallery col col_4" data-fancybox="gallery" href="images/pic_10.jpg">
                <span class="gallery_img" style="background: url('images/pic_10.jpg')no-repeat center center / cover"></span>
                <span class="inner">
          <span class="text">Название</span>
        </span>
            </a>
            <a class="gallery col col_4" data-fancybox="gallery" href="images/pic_10.jpg">
                <span class="gallery_img" style="background: url('images/pic_10.jpg')no-repeat center center / cover"></span>
                <span class="inner">
          <span class="text">Название</span>
        </span>
            </a>
            <a class="gallery col col_4" data-fancybox="gallery" href="images/pic_10.jpg">
                <span class="gallery_img" style="background: url('images/pic_10.jpg')no-repeat center center / cover"></span>
                <span class="inner">
          <span class="text">Название</span>
        </span>
            </a>
            <a class="gallery col col_4" data-fancybox="gallery" href="images/pic_10.jpg">
                <span class="gallery_img" style="background: url('images/pic_10.jpg')no-repeat center center / cover"></span>
                <span class="inner">
          <span class="text">Название</span>
        </span>
            </a>
        </div>
        <div class="bottom_btn">
            <a href="#" class="big_btn">
                <span class="text"><span>Вся фотоподборки</span></span>
                <span class="img">
            <img src="images/arrow_right.png" alt="">
          </span>
            </a>
        </div>
    </div>
</section>
<section class="infoblock_video">
    <div class="wrap">
        <div class="wrap_title">
            <h4>Видео</h4>
        </div>
        <div class="grid">
            <div class="col col_3">
                <div class="infoblock_video_item video_item" data-src="https://www.youtube.com/embed/iJ51QUQuFW8">
                    <div class="video" style="background: url('../images/pic_10.jpg')no-repeat center center / cover">
                        <iframe src="" frameborder="0" allowfullscreen></iframe>
                    </div>
                    <p class="text">Монтаж силового и осветительного электрооборудования</p>
                </div>
            </div>
            <div class="col col_3">
                <div class="infoblock_video_item video_item" data-src="https://www.youtube.com/embed/iJ51QUQuFW8">
                    <div class="video" style="background: url('../images/pic_10.jpg')no-repeat center center / cover">
                        <iframe src="" frameborder="0" allowfullscreen></iframe>
                    </div>
                    <p class="text">Монтаж силового и осветительного электрооборудования</p>
                </div>
            </div>
            <div class="col col_3">
                <div class="infoblock_video_item video_item" data-src="https://www.youtube.com/embed/iJ51QUQuFW8">
                    <div class="video" style="background: url('../images/pic_10.jpg')no-repeat center center / cover">
                        <iframe src="" frameborder="0" allowfullscreen></iframe>
                    </div>
                    <p class="text">Монтаж силового и осветительного электрооборудования</p>
                </div>
            </div>
        </div>
        <div class="bottom_btn">
            <a href="#" class="big_btn">
                <span class="text"><span>Смотреть все видео</span></span>
                <span class="img">
            <img src="images/arrow_right.png" alt="">
          </span>
            </a>
        </div>
    </div>
</section>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>