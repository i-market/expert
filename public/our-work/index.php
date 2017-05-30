<?
use App\View as v;
use App\Iblock;
use Bex\Tools\Iblock\IblockTools;

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Примеры работ");
$APPLICATION->SetPageProperty('layout', 'bare');
?>

<section class="work_examples">
    <div class="wrap wrap--small">
        <h1><?= $APPLICATION->GetTitle(false) ?></h1>
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
            "our_work_index",
            Array(
                "ADD_SECTIONS_CHAIN" => "N",
                "CACHE_GROUPS" => "N",
                "CACHE_TIME" => "36000000",
                "CACHE_TYPE" => "A",
                "COUNT_ELEMENTS" => "N",
                "IBLOCK_ID" => IblockTools::find(Iblock::CONTENT_TYPE, Iblock::OUR_WORK)->id(),
                "IBLOCK_TYPE" => Iblock::CONTENT_TYPE,
                "SECTION_CODE" => "",
                "SECTION_FIELDS" => array("", ""),
                "SECTION_ID" => "",
                "SECTION_URL" => "#SITE_DIR#/our-work/#SECTION_CODE#/",
                "SECTION_USER_FIELDS" => array("", ""),
                "SHOW_PARENT_NAME" => "N",
                "TOP_DEPTH" => "1",
                "VIEW_MODE" => "LINE"
            )
        ); ?>
    </div>
</section>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>