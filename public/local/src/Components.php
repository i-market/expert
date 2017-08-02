<?php

namespace App;

use App\Services\Monitoring;
use App\Services\MonitoringRepo;
use App\View as v;
use Bex\Tools\Iblock\IblockTools;
use Core\Underscore as _;
use Core\Util;
use App\Services\MonitoringRequest;

class Components {
    static function showBannersSection($iblockSection) {
        global $APPLICATION;
        $classLists = [
            'bottom' => ['some_section--last'],
            'footer' => ['some_section--hidden']
        ];
        $classList = _::get($classLists, $iblockSection, []);
        $APPLICATION->IncludeComponent(
            "bitrix:news.list",
            "banners_section",
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
                "IBLOCK_ID" => IblockTools::find(Iblock::CONTENT_TYPE, Iblock::BANNERS)->id(),
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
                "PARENT_SECTION_CODE" => $iblockSection,
                "PREVIEW_TRUNCATE_LEN" => "",
                "PROPERTY_CODE" => array("LINK"),
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
                "SORT_ORDER2" => "ASC",
                "CLASS_LIST" => $classList
            )
        );
    }

    static function renderServiceForm($templateName, $context) {
        $getValue = function($params, $name) {
            $path = Util::formInputNamePath($name);
            return _::get($params, join('.', $path));
        };
        // TODO refactor: this was supposed to be done using template inheritance,
        // but plate's `section` function causes a decoding error for some reason (gzip enabled)
        $inputs = v::render($templateName, array_merge($context, ['getValue' => $getValue]));
        return v::render('partials/service_forms/form', array_merge($context, ['inputs' => $inputs]));
    }

    static function renderServicesSection() {
        $services = array_map(function($service) {
            if ($service['code'] === 'monitoring') {
                $data = Services::data('monitoring');
                $ctx = MonitoringRequest::context(MonitoringRequest::initialState($data), Services::services()['monitoring']);
                $form = Components::renderServiceForm('partials/service_forms/monitoring_form', $ctx);
            } else {
                $form = 'Извините, раздел находится в разработке.';
            }
            return array_merge($service, ['form' => $form]);
        }, array_values(Services::services()));
        return v::render('partials/services_section', ['services' => $services]);
    }
}