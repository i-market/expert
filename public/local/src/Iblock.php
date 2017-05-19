<?php

namespace App;

use Bitrix\Iblock\SectionTable;
use Bitrix\Main\Loader;
use CIBlockResult;
use Core\Underscore as _;

Loader::includeModule('iblock');

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

    static function groupBySection($elements, $iblockId, $sections = null) {
        if ($sections === null) {
            $sections = SectionTable::query()
                ->setSelect(['ID', 'NAME'])
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
