<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use App\App;
use App\Components;
use App\Iblock;
use App\View as v;
use Bex\Tools\Iblock\IblockTools;

extract(App::getInstance()->layoutContext(), EXTR_SKIP);
?>
<? $APPLICATION->AddBufferContent(function() {
    return App::renderLayoutFooter();
}) ?>
<? $APPLICATION->AddBufferContent(function() use (&$APPLICATION, $showBottomBannersFn) {
    ob_start();
    if ($showBottomBannersFn()) {
        $parent = $APPLICATION->GetProperty('banners_section_code', 'default');
        Components::showBannersSection($parent, 'bottom');
    }
    return ob_get_clean();
}) ?>
<? $APPLICATION->IncludeComponent(
    "bitrix:menu",
    "footer",
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
<section class="pre_footer">
    <? $APPLICATION->IncludeComponent(
        "bitrix:news.list",
        "resource_links",
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
            "IBLOCK_ID" => IblockTools::find(Iblock::CONTENT_TYPE, Iblock::RESOURCE_LINKS)->id(),
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
    <? Components::showBannersSection('footer') ?>
</section>
</main>
<!-- FOOTER START -->
<footer class="footer">
    <div class="wrap">
        <div class="top">
            <a class="logo_footer" href="<?= v::path('/') ?>">
                <img src="<?= v::asset('images/logo_footer.png') ?>" alt="Техническая строительная экспертиза">
                <p>
                    <span>Техническая</span>
                    <span>строительная экспертиза</span>
                </p>
            </a>
            <a class="advertisers_hidden" href="<?= v::path('for-advertisers') ?>">Рекламодателям</a>
            <div class="adress">
                <? // TODO <p> tag messes up editing ?>
                <p>
                    <? $APPLICATION->IncludeComponent(
                        "bitrix:main.include",
                        "",
                        Array(
                            "AREA_FILE_SHOW" => "file",
                            "PATH" => v::includedArea('layout/address.php')
                        )
                    ); ?>
                </p>
            </div>
            <? // TODO contact details ?>
            <div class="info">
                <p><a href="tel:+7 (495) 641-70-69">+7 (495) 641-70-69</a></p>
                <p><a href="tel:+7 (499) 340-34-73">+7 (499) 340-34-73</a></p>
                <p><span>E-mail:</span><a href="mailto:6417069@bk.ru">6417069@bk.ru</a></p>
                <p><span>E-mail:</span><a href="mailto:6417069@bk.ru">6417069@bk.ru</a></p>
            </div>
            <div class="btns">
                <div class="blue_btn re_call" data-modal="re_call">Заказать <span class="hidden">обратный</span> звонок</div>
                <a href="<?= v::path('what-we-do') ?>" class="blue_btn calculate_cost">Рассчитать стоимость</a>
            </div>
        </div>
        <div class="bottom">
            <? // TODO includize copyright? ?>
            <span><?= '© 2015–'.$copyrightYear ?></span> <span>ООО «Техническая строительная экспертиза»</span> <a href="https://i-market.ru/" target="_blank" class="create">Создание и продвижение сайта I-Market</a>
            <a class="advertisers" href="<?= v::path('for-advertisers') ?>">Рекламодателям</a>
        </div>
    </div>
</footer>
<? $APPLICATION->ShowViewContent('modals') ?>
<!--Обратный звонок-->
<div class="modal" id="re_call">
    <div class="block">
        <span class="close">×</span>
        <form ic-post-to="/api/callback-request">
            <?= v::render('partials/callback_request_form', ['state' => []]) ?>
        </form>
    </div>
</div>
<? if ($sentry['enabled']): ?>
    <?= v::render('partials/sentry_js', $sentry) ?>
<? endif ?>
<? if (!App::useBitrixAsset()): ?>
    <? foreach (App::assets()['scripts'] as $path): ?>
        <script type="text/javascript" src="<?= $path ?>"></script>
    <? endforeach ?>
<? endif ?>
</body>
</html>
