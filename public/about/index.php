<?
use App\Iblock;
use App\View as v;
use Bex\Tools\Iblock\IblockTools;

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("О компании");
$APPLICATION->SetPageProperty('layout', 'bare');
?>

<section class="text_section">
    <div class="wrap">
        <h2><?= $APPLICATION->GetTitle(false) ?></h2>
        <? $APPLICATION->IncludeComponent(
            "bitrix:main.include",
            "",
            Array(
                "AREA_FILE_SHOW" => "file",
                "PATH" => v::includedArea('about/text.php')
            )
        ); ?>
    </div>
</section>
<? $APPLICATION->IncludeComponent(
    "bitrix:catalog.section.list",
    "certificates",
    Array(
        "ADD_SECTIONS_CHAIN" => "N",
        "CACHE_GROUPS" => "N",
        "CACHE_TIME" => "36000000",
        "CACHE_TYPE" => "A",
        "COUNT_ELEMENTS" => "Y",
        "IBLOCK_ID" => IblockTools::find(Iblock::CONTENT_TYPE, Iblock::CERTIFICATES)->id(),
        "IBLOCK_TYPE" => Iblock::CONTENT_TYPE,
        "SECTION_CODE" => "",
        "SECTION_FIELDS" => array("", ""),
        "SECTION_ID" => "",
        "SECTION_URL" => "#SITE_DIR#/certificates/##SECTION_CODE#",
        "SECTION_USER_FIELDS" => array("", ""),
        "SHOW_PARENT_NAME" => "N",
        "TOP_DEPTH" => "1",
        "VIEW_MODE" => "LINE"
    )
); ?>
<section class="organization_card">
    <div class="wrap">
        <? $APPLICATION->IncludeComponent(
            "bitrix:main.include",
            "",
            Array(
                "AREA_FILE_SHOW" => "file",
                "PATH" => v::includedArea('about/card_heading.php')
            )
        ); ?>
        <div class="wrap_organization_card_table">
            <? $APPLICATION->IncludeComponent(
                "bitrix:main.include",
                "",
                Array(
                    "AREA_FILE_SHOW" => "file",
                    "PATH" => v::includedArea('about/card_table.php')
                )
            ); ?>
        </div>
    </div>
</section>
<? $APPLICATION->IncludeComponent(
    "bitrix:news.detail",
    "files_section",
    Array(
        "ACTIVE_DATE_FORMAT" => "d.m.Y",
        "ADD_ELEMENT_CHAIN" => "N",
        "ADD_SECTIONS_CHAIN" => "N",
        "AJAX_MODE" => "N",
        "AJAX_OPTION_ADDITIONAL" => "",
        "AJAX_OPTION_HISTORY" => "N",
        "AJAX_OPTION_JUMP" => "N",
        "AJAX_OPTION_STYLE" => "N",
        "BROWSER_TITLE" => "-",
        "CACHE_GROUPS" => "Y",
        "CACHE_TIME" => "36000000",
        "CACHE_TYPE" => "A",
        "CHECK_DATES" => "Y",
        "DETAIL_URL" => "",
        "DISPLAY_BOTTOM_PAGER" => "N",
        "DISPLAY_DATE" => "N",
        "DISPLAY_NAME" => "N",
        "DISPLAY_PICTURE" => "Y",
        "DISPLAY_PREVIEW_TEXT" => "N",
        "DISPLAY_TOP_PAGER" => "N",
        "ELEMENT_CODE" => "about_card",
        "ELEMENT_ID" => "",
        "FIELD_CODE" => array("", ""),
        "IBLOCK_ID" => IblockTools::find(Iblock::CONTENT_TYPE, Iblock::FILES)->id(),
        "IBLOCK_TYPE" => Iblock::CONTENT_TYPE,
        "IBLOCK_URL" => "",
        "INCLUDE_IBLOCK_INTO_CHAIN" => "N",
        "MESSAGE_404" => "",
        "META_DESCRIPTION" => "-",
        "META_KEYWORDS" => "-",
        "PAGER_BASE_LINK_ENABLE" => "N",
        "PAGER_SHOW_ALL" => "N",
        "PAGER_TEMPLATE" => ".default",
        "PAGER_TITLE" => "",
        "PROPERTY_CODE" => array('FILES'),
        "SET_BROWSER_TITLE" => "Y",
        "SET_CANONICAL_URL" => "N",
        "SET_LAST_MODIFIED" => "N",
        "SET_META_DESCRIPTION" => "N",
        "SET_META_KEYWORDS" => "Y",
        "SET_STATUS_404" => "N",
        "SET_TITLE" => "N",
        "SHOW_404" => "N",
        "USE_PERMISSIONS" => "N",
        "USE_SHARE" => "N"
    )
); ?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>