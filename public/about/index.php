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
        <? // TODO about page org card ?>
        <div class="wrap_organization_card_table">
            <table class="organization_card_table">
                <tr>
                    <td>Полное наименование организации</td>
                    <td>ООО «Техническая строительная экспертиза»</td>
                </tr>
                <tr>
                    <td>Регистрационный номер</td>
                    <td>текст</td>
                </tr>
                <tr>
                    <td>Дата государственной регистрации</td>
                    <td>текст</td>
                </tr>
                <tr>
                    <td>Регистрирующий орган</td>
                    <td>текст</td>
                </tr>
                <tr>
                    <td>Юридический адрес</td>
                    <td>текст</td>
                </tr>
                <tr>
                    <td>Фактический адрес</td>
                    <td>текст</td>
                </tr>
                <tr>
                    <td>Идентификационный номер налогоплательщика (ИНН)</td>
                    <td>текст</td>
                </tr>
                <tr>
                    <td>Код причины постановки на налоговый учет (КПП)</td>
                    <td>текст</td>
                </tr>
                <tr>
                    <td>Основной государственный регистрационный номер</td>
                    <td>текст</td>
                </tr>
                <tr>
                    <td>Код отрасли по ОКВЭД</td>
                    <td>текст</td>
                </tr>
                <tr>
                    <td>Код организации по ОКПО</td>
                    <td>текст</td>
                </tr>
                <tr>
                    <td>Наименование должности руководителя</td>
                    <td>текст</td>
                </tr>
                <tr>
                    <td>Генеральный директор</td>
                    <td>текст</td>
                </tr>
                <tr>
                    <td>Главный бухгалтер</td>
                    <td>текст</td>
                </tr>
                <tr>
                    <td>WEB адрес</td>
                    <td>текст</td>
                </tr>
                <tr>
                    <td>Электронный адрес</td>
                    <td>текст</td>
                </tr>
                <tr>
                    <td>Приемная</td>
                    <td>текст</td>
                </tr>
            </table>
        </div>
    </div>
</section>
<? // TODO download org card files ?>
<section class="download_document">
    <div class="wrap">
        <div class="grid">
            <a href="#" class="col col_2 item pdf">
                <p class="text">Скачать PDF, 650 Кб</p>
                <p class="title">Карточка организации</p>
            </a>
            <a href="#" class="col col_2 item doc">
                <p class="text">Скачать DOC, 650 Кб</p>
                <p class="title">Карточка организации</p>
            </a>
        </div>
    </div>
</section>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>