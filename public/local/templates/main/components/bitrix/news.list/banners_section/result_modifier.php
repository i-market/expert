<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use App\Iblock;
use Core\Underscore as _;

$arResult['CLASS_LIST'] = array_merge(['some_section'], _::get($arParams, 'CLASS_LIST', []));
if ($arParams['CHILD_SECTION'] !== null) {
    $items = array_filter($arResult['ITEMS'], function ($item) use ($arResult, $arParams) {
        $filter = [
            'HAS_ELEMENT' => $item['ID'],
            'IBLOCK_ID' => $arResult['ID'],
            'ACTIVE' => 'Y'
        ];
        $select = ['CODE'];
        // TODO optimize: n+1 db query
        $sections = Iblock::collect(CIblockSection::GetList([], $filter, $select));
        return in_array($arParams['CHILD_SECTION'], _::pluck($sections, 'CODE'));
    });
    $arResult['ITEMS'] = $items;
}