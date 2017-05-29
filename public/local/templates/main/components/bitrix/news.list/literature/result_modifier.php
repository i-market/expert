<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use App\View as v;
use Core\Underscore as _;

$arResult['ITEMS'] = array_map(function($item) {
    $file = $item['DISPLAY_PROPERTIES']['FILE']['FILE_VALUE'];
    $link = $item['PROPERTIES']['LINK']['VALUE'];
    return array_merge($item, [
        'AUTHOR' => $item['PROPERTIES']['AUTHOR']['VALUE'],
        'LINK_MAYBE' => !v::isEmpty($link)
            ? $link
            : _::get($file, 'SRC', null)
    ]);
}, $arResult['ITEMS']);
