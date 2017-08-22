<?php

namespace App;

use Bitrix\Iblock\SectionTable;
use Bitrix\Main\Loader;
use CIBlockResult;
use Core\Underscore as _;

Loader::includeModule('iblock');

// TODO some of these will be useful in core
class Iblock {
    const CONTENT_TYPE = 'content';
    const SLIDER = 'slider';
    const HOMEPAGE_BANNERS = 'homepage_banners';
    const OUR_SITES = 'our_sites';
    const OUR_CLIENTS = 'our_clients';
    const TESTIMONIALS = 'testimonials';
    const RESOURCE_LINKS = 'resource_links';
    const BANNERS = 'banners';
    const CERTIFICATES = 'certificates';
    const CONTACT_GALLERY = 'contact_gallery';
    const IMAGES = 'images';
    const EQUIPMENT = 'equipment';
    const FILES = 'files';
    const LITERATURE = 'literature';
    const OPINIONS = 'opinions';

    const SERVICES_TYPE = 'services';
    const SERVICES = 'services';
    const INFO_BLOCK_GALLERY = 'info_block_gallery';
    const VIDEOS = 'videos';
    const OUR_WORK = 'our_work';
    const SERVICE_DATA = 'service_data';

    const INBOX_TYPE = 'inbox';
    const CALLBACK_REQUESTS = 'callback_requests';
    const MONITORING_REQUESTS = 'monitoring_requests';
    const INSPECTION_REQUESTS = 'inspection_requests';
    const EXAMINATION_REQUESTS = 'examination_requests';
    const INDIVIDUAL_REQUESTS = 'individual_requests';
    const DESIGN_REQUESTS = 'design_requests';
    const OVERSIGHT_REQUESTS = 'oversight_requests';

    static function groupBySection($elements, $iblockId, $sections = null) {
        if ($sections === null) {
            $sections = SectionTable::query()
                ->setSelect(['IBLOCK_ID', 'ID', 'NAME'])
                ->setFilter(['IBLOCK_ID' => $iblockId])
                ->exec()->fetchAll();
        }
        $sections = _::keyBy('ID', $sections);
        $grouped = _::groupBy($elements, 'IBLOCK_SECTION_ID');
        return _::map($grouped, function($items, $sectionId) use ($sections) {
            return array_merge($sections[$sectionId], [
                'ITEMS' => $items
            ]);
        });
    }

    private static function inflate($roots, $children) {
        return array_map(function($section) use ($children) {
            return _::set($section, 'SECTIONS', self::inflate(_::get($children, $section['ID'], []), $children));
        }, $roots);
    }

    static function sectionTrees($sections) {
        $sections = _::keyBy('ID', $sections);
        $isRoot = function($section) use ($sections) {
            return !in_array($section['IBLOCK_SECTION_ID'], array_keys($sections));
        };
        $children = _::groupBy($sections, 'IBLOCK_SECTION_ID');
        $ret = self::inflate(array_filter($sections, $isRoot), $children);
        return $ret;
    }

    static function collect(CIBlockResult $result) {
        $ret = [];
        while($x = $result->GetNext()) {
            $ret[] = $x;
        }
        return $ret;
    }

    static function collectElements(CIBlockResult $result) {
        $ret = [];
        while($x = $result->GetNextElement()) {
            $ret[] = array_merge($x->GetFields(), [
                'PROPERTIES' => $x->GetProperties()
            ]);
        }
        return $ret;
    }
}
