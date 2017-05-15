<?
use App\Iblock;
use App\View as v;
use Bex\Tools\Iblock\IblockTools;

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Техническая строительная экспертиза");
?>

<? $APPLICATION->IncludeComponent(
	"bitrix:news.list",
	"homepage_banners",
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
		"IBLOCK_ID" => IblockTools::find(Iblock::CONTENT_TYPE, Iblock::HOMEPAGE_BANNERS)->id(),
		"IBLOCK_TYPE" => Iblock::CONTENT_TYPE,
		"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
		"INCLUDE_SUBSECTIONS" => "Y",
		"MESSAGE_404" => "",
		"NEWS_COUNT" => 2,
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
		"PROPERTY_CODE" => array('LINK'),
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
<section class="hello">
    <div class="wrap">
        <h2>Добро пожаловать на наш сайт!</h2>
        <p>Функционирование значительного количества предприятий связано с различными рисками, обусловленными спецификой их деятельности, например, использованием вредных химических веществ, сложного оборудования и т.д. Задачей первоочередной важности является обеспечение безопасности процесса производства, что возможно только при комплексном подходе к решению проблемы. Оказывая квалифицированную помощь по вопросам подготовки проектной документации и проведении всевозможных экспертиз, ООО «ТехСтройЭкспертиза» также занимается разработкой решений по организации промышленной технической безопасности на объектах в разнообразных регионах страны. В отличие от многочисленных компаний, предлагающих аналогичные услуги, мы в своей работе охватываем все аспекты поддержания безопасности производственных сооружений. </p>
    </div>
</section>
<section class="some_section">
    <div class="wrap">
        <div class="grid">
            <a href="#" class="col col_4" style="background: url('<?= v::asset('images/pic_4.jpg') ?>')no-repeat center center / cover"></a>
            <a href="#" class="col col_4" style="background: url('<?= v::asset('images/pic_5.jpg') ?>')no-repeat center center / cover"></a>
            <a href="#" class="col col_4" style="background: url('<?= v::asset('images/pic_6.jpg') ?>')no-repeat center center / cover"></a>
            <a href="#" class="col col_4" style="background: url('<?= v::asset('images/pic_7.jpg') ?>')no-repeat center center / cover"></a>
        </div>
    </div>
</section>
<? // (construction) sites ?>
<section class="our_objects_section">
    <div class="wrap">
        <div class="wrap_title">
            <? $APPLICATION->IncludeComponent(
            	"bitrix:main.include",
            	"",
            	Array(
            		"AREA_FILE_SHOW" => "file",
            		"PATH" => v::includedArea('homepage/our_sites_heading.php')
            	)
            ); ?>
            <div class="arrows">
                <span class="arrow prev"></span>
                <span class="arrow next"></span>
            </div>
        </div>
        <? $APPLICATION->IncludeComponent(
        	"bitrix:news.list",
        	"our_sites",
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
        		"IBLOCK_ID" => IblockTools::find(Iblock::CONTENT_TYPE, Iblock::OUR_SITES)->id(),
        		"IBLOCK_TYPE" => Iblock::CONTENT_TYPE,
        		"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
        		"INCLUDE_SUBSECTIONS" => "Y",
        		"MESSAGE_404" => "",
        		"NEWS_COUNT" => PHP_INT_MAX,
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
        		"PROPERTY_CODE" => array('LINK'),
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
<section class="our_clients_section">
    <div class="wrap">
        <div class="wrap_title">
            <h2>Наши клиенты</h2>
            <div class="arrows arrows--visible">
                <span class="arrow prev"></span>
                <span class="arrow next"></span>
            </div>
        </div>
        <div class="our_clients">
            <div class="grid">
                <div class="item col col_4">
                    <div class="img">
                        <img src="<?= v::asset('images/ico_1.png') ?>" alt="">
                    </div>
                    <div class="img">
                        <img src="<?= v::asset('images/ico_2.png') ?>" alt="">
                    </div>
                </div>
                <div class="item col col_4">
                    <div class="img">
                        <img src="<?= v::asset('images/ico_3.png') ?>" alt="">
                    </div>
                    <div class="img">
                        <img src="<?= v::asset('images/ico_4.png') ?>" alt="">
                    </div>
                </div>
                <div class="item col col_4">
                    <div class="img">
                        <img src="<?= v::asset('images/ico_5.png') ?>" alt="">
                    </div>
                    <div class="img">
                        <img src="<?= v::asset('images/ico_6.png') ?>" alt="">
                    </div>
                </div>
                <div class="item col col_4">
                    <div class="img">
                        <img src="<?= v::asset('images/ico_7.png') ?>" alt="">
                    </div>
                    <div class="img">
                        <img src="<?= v::asset('images/ico_8.png') ?>" alt="">
                    </div>
                </div>
                <div class="item col col_4">
                    <div class="img">
                        <img src="<?= v::asset('images/ico_1.png') ?>" alt="">
                    </div>
                    <div class="img">
                        <img src="<?= v::asset('images/ico_2.png') ?>" alt="">
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="our_reviews_section">
    <div class="wrap">
        <div class="wrap_title">
            <h2>Отзывы о нашей деятельности</h2>
            <div class="arrows arrows--visible">
                <span class="arrow prev"></span>
                <span class="arrow next"></span>
            </div>
        </div>
        <div class="our_reviews">
            <div class="grid">
                <a class="item gallery col col_5" href="<?= v::asset('images/sert.jpg') ?>">
      <span class="img">
        <img src="<?= v::asset('images/sert.jpg') ?>" alt="">
      </span>
                    <span class="text">Российские Железные дороги</span>
                </a>
                <a class="item gallery col col_5" href="<?= v::asset('images/sert.jpg') ?>">
      <span class="img">
        <img src="<?= v::asset('images/sert.jpg') ?>" alt="">
      </span>
                    <span class="text">ООО “Южмашпромбыт”</span>
                </a>
                <a class="item gallery col col_5" href="<?= v::asset('images/sert.jpg') ?>">
      <span class="img">
        <img src="<?= v::asset('images/sert.jpg') ?>" alt="">
      </span>
                    <span class="text">Российские Железные дороги</span>
                </a>
                <a class="item gallery col col_5" href="<?= v::asset('images/sert.jpg') ?>">
      <span class="img">
        <img src="<?= v::asset('images/sert.jpg') ?>" alt="">
      </span>
                    <span class="text">ООО “Южмашпромбыт”</span>
                </a>
                <a class="item gallery col col_5" href="<?= v::asset('images/sert.jpg') ?>">
      <span class="img">
        <img src="<?= v::asset('images/sert.jpg') ?>" alt="">
      </span>
                    <span class="text">Российские Железные дороги</span>
                </a>
                <a class="item gallery col col_5" href="<?= v::asset('images/sert.jpg') ?>">
      <span class="img">
        <img src="<?= v::asset('images/sert.jpg') ?>" alt="">
      </span>
                    <span class="text">ООО “Южмашпромбыт”</span>
                </a>
            </div>
        </div>
    </div>
</section>
<section class="pre_footer_menu">
    <div class="wrap">
        <ul>
            <li><a href="#">Главная</a></li>
            <li><a href="#">О компании</a></li>
            <li><a href="#">Наши деятельность</a></li>
            <li><a href="#">Примеры работ </a></li>
            <li><a href="#">Аттестаты и допуски СРО</a></li>
            <li><a href="#">Техническая база</a></li>
            <li><a href="#">Инфоблок</a></li>
            <li><a href="#">Контакты</a></li>
        </ul>
    </div>
</section>
<section class="pre_footer">
    <div class="wrap">
        <div class="accordeon grid">
            <div class="accordeon_item col col_4">
                <div class="accordeon_title">Консультация специалиста</div>
                <div class="accordeon_inner">
                    <p><a href="#">Административное право</a></p>
                    <p><a href="#">Арбитражное процессуальное право</a></p>
                    <p><a href="#">Военное право</a></p>
                    <p><a href="#">Гражданское право</a></p>
                    <p><a href="#">Жилищное право</a></p>
                    <p><a href="#">Исполнительное производство</a></p>
                    <p><a href="#">Коммерческое право</a></p>
                    <p><a href="#">Международное право</a></p>
                    <p><a href="#">Наследственное право </a></p>
                </div>
            </div>
            <div class="accordeon_item col col_4">
                <div class="accordeon_title">Наши партнёры</div>
                <div class="accordeon_inner">
                    <p><a href="#">«Российские Железные Дороги» http://www.rzd.ru</a></p>
                    <p><a href="#">«Фирма «Трансгарант» http://www.transgarant.com</a></p>
                    <p><a href="#">«Редакция журнала «РЖД-Партнер» http://www.rzd-partner.ru</a></p>
                    <p><a href="#">«Брансвик Рейл Лизинг» http://www.brunswick-capital.com</a></p>
                    <p><a href="#">"Системный транспортный сервис" http://ststrans.ru</a></p>
                </div>
            </div>
            <div class="accordeon_item col col_4">
                <div class="accordeon_title">Полезные ссылки</div>
                <div class="accordeon_inner">
                    <p><a href="#">Информация о проекте</a></p>
                    <p><a href="#">Аналитические возможности</a></p>
                    <p><a href="#">Архивы судебных решений</a></p>
                    <p><a href="#">Размещение рекламы</a></p>
                    <p><a href="#">Статистические карточки</a></p>
                    <p><a href="#">Добавление дел</a></p>
                    <p><a href="#">Юристы онлайн</a></p>
                    <p><a href="#">Видеозвонки</a></p>
                    <p>
                        <a href="#"></a>
                    </p>
                    <p>
                        <a href="#"></a>
                    </p>
                </div>
            </div>
            <div class="accordeon_item col col_4">
                <div class="accordeon_title">Нормативно-законодательная база</div>
                <div class="accordeon_inner">
                    <p><a href="#">Нормативно-законодательная база</a></p>
                    <p><a href="#">Административное право</a></p>
                    <p><a href="#">Арбитражное процессуальное право</a></p>
                    <p><a href="#">Военное право</a></p>
                    <p><a href="#">Гражданское право</a></p>
                    <p><a href="#">Жилищное право</a></p>
                    <p><a href="#">Исполнительное производство</a></p>
                    <p><a href="#">Коммерческое право</a></p>
                    <p><a href="#">Международное право</a></p>
                    <p><a href="#">Наследственное право </a></p>
                </div>
            </div>
        </div>
    </div>
    <div class="some_section some_section--hidden">
        <div class="wrap">
            <div class="grid">
                <a href="#" class="col col_4" style="background: url('<?= v::asset('images/pic_4.jpg') ?>')no-repeat center center / cover"></a>
                <a href="#" class="col col_4" style="background: url('<?= v::asset('images/pic_5.jpg') ?>')no-repeat center center / cover"></a>
                <a href="#" class="col col_4" style="background: url('<?= v::asset('images/pic_6.jpg') ?>')no-repeat center center / cover"></a>
                <a href="#" class="col col_4" style="background: url('<?= v::asset('images/pic_7.jpg') ?>')no-repeat center center / cover"></a>
            </div>
        </div>
    </div>
</section>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>