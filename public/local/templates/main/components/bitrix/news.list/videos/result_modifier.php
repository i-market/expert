<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use App\Videos;

$arResult['ITEMS'] = array_map(function($item) {
    $link = $item['PROPERTIES']['LINK']['VALUE'];
    $videoId = Videos::youtubeIdMaybe($link);
    return array_merge($item, [
        'YOUTUBE_SRC' => 'https://www.youtube.com/embed/'.$videoId
    ]);
}, $arResult['ITEMS']);
