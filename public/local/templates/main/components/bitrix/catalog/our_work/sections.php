<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use App\View as v;
?>
<section class="work_examples">
    <div class="wrap wrap--small">
        <h1><? $APPLICATION->ShowTitle(false) ?></h1>
        <h4 class="blue_color">
            <? $APPLICATION->IncludeComponent(
                "bitrix:main.include",
                "",
                Array(
                    "AREA_FILE_SHOW" => "file",
                    "PATH" => v::includedArea('our-work/subheading.php')
                )
            ); ?>
        </h4>
        <? $APPLICATION->IncludeComponent(
            "bitrix:catalog.section.list",
            "index",
            array(
                "IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
                "IBLOCK_ID" => $arParams["IBLOCK_ID"],
                "CACHE_TYPE" => $arParams["CACHE_TYPE"],
                "CACHE_TIME" => $arParams["CACHE_TIME"],
                "CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
                "COUNT_ELEMENTS" => $arParams["SECTION_COUNT_ELEMENTS"],
                "TOP_DEPTH" => $arParams["SECTION_TOP_DEPTH"],
                "SECTION_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["section"],
                "VIEW_MODE" => $arParams["SECTIONS_VIEW_MODE"],
                "SHOW_PARENT_NAME" => $arParams["SECTIONS_SHOW_PARENT_NAME"],
                "HIDE_SECTION_NAME" => (isset($arParams["SECTIONS_HIDE_SECTION_NAME"]) ? $arParams["SECTIONS_HIDE_SECTION_NAME"] : "N"),
                "ADD_SECTIONS_CHAIN" => (isset($arParams["ADD_SECTIONS_CHAIN"]) ? $arParams["ADD_SECTIONS_CHAIN"] : '')
            ),
            $component,
            array("HIDE_ICONS" => "Y")
        ); ?>
    </div>
</section>
