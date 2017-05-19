<?php

namespace App;

use Bitrix\Iblock\SectionTable;
use Bitrix\Main\Loader;
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

    static function groupBySection($elements, $iblockId) {
        $sections = _::keyBy('ID', SectionTable::query()
            ->setSelect(['ID', 'NAME'])
            ->setFilter(['IBLOCK_ID' => $iblockId])
            ->exec()->fetchAll());
        $grouped = _::groupBy($elements, 'IBLOCK_SECTION_ID');
        return _::reduce($grouped, function($acc, $items, $sectionId) use ($sections) {
            return _::append($acc, array_merge($sections[$sectionId], [
                'ITEMS' => $items
            ]));
        }, []);
    }
}
