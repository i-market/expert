<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use App\Iblock;
use App\Videos;
use Bex\Tools\Iblock\IblockTools;
use Core\Underscore as _;
use Core\Util;

$ibMeta = function($item) {
    if ($item['MODULE_ID'] !== 'iblock') {
        return null;
    }
    return [
        'ID' => $item['ITEM_ID'],
        'IBLOCK_ID' => $item['PARAM2']
    ];
};
$getId = function($code) {
    return IblockTools::find(Iblock::CONTENT_TYPE, $code)->id();
};
// TODO refactor mutation
$seenIdsRef = [];
$arResult['BY_TYPE'] = _::map([
    'VIDEOS' => [Iblock::VIDEOS],
    'IMAGES' => [Iblock::IMAGES, Iblock::CONTACT_GALLERY]
], function($codes) use (&$seenIdsRef, $arResult, $ibMeta, $getId) {
    $matching = array_filter($arResult['SEARCH'], function($item) use ($codes, $ibMeta, $getId) {
        return in_array($ibMeta($item)['IBLOCK_ID'], array_map($getId, $codes));
    });
    $seenIdsRef += _::pluck($matching, 'ID');
    return _::map($matching, function($item) use ($codes, $ibMeta, $getId) {
        $result = CIBlockElement::GetByID($ibMeta($item)['ID']);
        $element = _::first(Iblock::collectElements($result));
        foreach (['PREVIEW_PICTURE', 'DETAIL_PICTURE'] as $picKey) {
            if (!_::isEmpty($element[$picKey])) {
                $element[$picKey] = CFile::GetFileArray($element[$picKey]);
            }
        }
        if ($ibMeta($item)['IBLOCK_ID'] === $getId(Iblock::VIDEOS)) {
            $videoId = Videos::youtubeIdMaybe($element['PROPERTIES']['LINK']['VALUE']);
            $element['YOUTUBE_SRC'] = 'https://www.youtube.com/embed/'.$videoId;
        }
        return _::set($item, 'ELEMENT', $element);
    });
});
$arResult['BY_TYPE']['DEFAULT'] = array_filter($arResult['SEARCH'], function($item) use ($seenIdsRef) {
    return !in_array($item['ID'], $seenIdsRef);
});
if (count($arResult['SEARCH']) !== array_reduce(array_map('count', $arResult['BY_TYPE']), _::operator('+'), 0)) {
    trigger_error('something went wrong while grouping search results by media type', E_USER_WARNING);
}
