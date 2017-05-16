<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use App\App;
use App\Iblock;
use Bex\Tools\Iblock\IblockTools;
use Bitrix\Main\Page\Asset;
use App\View as v;

extract(App::layoutContext(), EXTR_SKIP);

$assets = App::assets();
$asset = Asset::getInstance();
$asset->setJsToBody(true);
if (App::useBitrixAsset()) {
    foreach ($assets['styles'] as $path) {
        $asset->addCss($path);
    }
    foreach ($assets['scripts'] as $path) {
        $asset->addJs($path);
    }
}
?>
<!doctype html>
<html lang="<?= LANGUAGE_ID ?>">
<head>
    <? $APPLICATION->ShowHead() ?>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="format-detection" content="telephone=no" />
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
    <title><? $APPLICATION->ShowTitle() ?></title>
    <? if (!App::useBitrixAsset()): ?>
        <? foreach ($assets['styles'] as $path): ?>
            <link rel="stylesheet" media="screen" href="<?= $path ?>">
        <? endforeach ?>
    <? endif ?>
    <!--[if gte IE 9]>
    <style type="text/css">
        .gradient {
            filter: none;
        }
    </style>
    <![endif]-->
</head>
<body>
<? $APPLICATION->ShowPanel() ?>
<? $APPLICATION->AddBufferContent(function() use ($shareUrlsFn) {
    return v::render('partials/share_buttons', $shareUrlsFn());
}) ?>
<!--прокрутка вверх-->
<a class="scroll_top" href="#"></a>
<!-- HEADER START -->
<header class="header">
    <div class="top">
        <div class="wrap">
            <div class="left">
                <a class="logo" href="<?= v::path('/') ?>">
                    <img src="<?= v::asset('images/logo.png') ?>" alt="Техническая строительная экспертиза">
                    <p>
                        <span>Техническая</span>
                        <span>строительная экспертиза</span>
                    </p>
                </a>
                <div class="inner">
                    <div class="operating_schedule">
                        <? $APPLICATION->IncludeComponent(
                            "bitrix:main.include",
                            "",
                            Array(
                                "AREA_FILE_SHOW" => "file",
                                "PATH" => v::includedArea('operating_schedule.php')
                            )
                        ); ?>
                    </div>
                    <? // TODO implement search ?>
                    <form action="" method="post" id="">
                        <input type="text" placeholder="Найти">
                        <button type="submit"></button>
                    </form>
                </div>
                <div class="hamburger">
                    <span></span><span></span><span></span>
                </div>
            </div>
            <div class="right">
                <div class="btns">
                    <? // TODO modals ?>
                    <div class="blue_btn re_call">Заказать <span class="hidden">обратный</span> звонок</div>
                    <div class="blue_btn calculate_cost">Рассчитать стоимость</div>
                </div>
                <? // TODO contact details ?>
                <div class="info">
                    <div class="info_top">
                        <p><a href="tel:+7(499) 340-34-73">+7(495) 641-70-69</a></p>
                        <p><a href="tel:+7(499) 340-34-73">+7(499) 340-34-73</a></p>
                    </div>
                    <div class="info_bottom">
                        <p>
                            <span>E-mail:</span>
                            <a href="mailto:6417069@bk.ru">6417069@bk.ru</a>
                        </p>
                        <p>
                            <span>E-mail:</span>
                            <a href="mailto:6417069@bk.ru">6417069@bk.ru</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="bottom">
        <? $APPLICATION->IncludeComponent(
            "bitrix:menu",
            "header",
            Array(
                "ALLOW_MULTI_SELECT" => "N",
                "CHILD_MENU_TYPE" => "left",
                "DELAY" => "N",
                "MAX_LEVEL" => "1",
                "MENU_CACHE_GET_VARS" => array(""),
                "MENU_CACHE_TIME" => "3600",
                "MENU_CACHE_TYPE" => "N",
                "MENU_CACHE_USE_GROUPS" => "Y",
                "ROOT_MENU_TYPE" => "top",
                "USE_EXT" => "Y"
            )
        ); ?>
    </div>
</header>
<!-- CONTENT START -->
<main class="content">
    <? $APPLICATION->IncludeComponent(
        "bitrix:news.list",
        "slider",
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
            "IBLOCK_ID" => IblockTools::find(Iblock::CONTENT_TYPE, Iblock::SLIDER)->id(),
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
            "PROPERTY_CODE" => array("", ""),
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
    <? $APPLICATION->IncludeComponent(
        "bitrix:breadcrumb",
        "top",
        array(
            "PATH" => "",
            "SITE_ID" => App::SITE_ID,
            "START_FROM" => "0"
        )
    ); ?>
