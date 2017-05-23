<?php

namespace App;

use App\View as v;
use Bex\Tools\Iblock\IblockTools;
use Core\Underscore as _;

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
                "SORT_ORDER2" => "ASC",
                "CLASS_LIST" => $classList
            )
        );
    }

    static function renderServicesSection() {
        // TODO editable services
        // TODO fill in modal subheadings
        $servicesBase = [
            [
                'name' => 'Обследование конструкций, помещений, зданий, сооружений, инженерных сетей и оборудования.',
                'modalSubheading' => 'На выполнение обследования конструкций, помещений, зданий, сооружений, инженерных сетей и оборудования',
                'slug' => 'inspection'
            ],
            [
                'name' => 'Строительно-техническая экспертиза конструкций, помещений, зданий, сооружений, помещений, инженерных сетей и оборудования. Судебная экспертиза.',
                'slug' => 'examination'
            ],
            [
                'name' => 'Выполнение отдельных видов работ по экспертизе и обследованию. Экспертиза отдельных материалов, деталей, изделий, узлов, конструкций, элементов конструкций и пр.',
                'slug' => 'individual'
            ],
            [
                'name' => 'Мониторинг технического состояния зданий и сооружений',
                'slug' => 'monitoring'
            ],
            [
                'name' => 'Разработка проектных решений',
                'slug' => 'design'
            ],
            [
                'name' => 'Технический надзор. Строительный контроль',
                'slug' => 'oversight'
            ]
        ];
        $pathRoot = 'what-we-do';
        $services = array_map(function($service) use ($pathRoot) {
            $detail = $pathRoot.'/'.$service['slug'];
            return array_merge($service, [
                'detailLink' => v::path($detail),
                'calcLink' => v::path($detail.'/calculator'),
                'requestModalId' => 'request-'.$service['slug']
            ]);
        }, $servicesBase);
        return v::render('partials/services_section', ['services' => $services]);
    }
}