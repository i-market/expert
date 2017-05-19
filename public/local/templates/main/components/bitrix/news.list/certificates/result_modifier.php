<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use App\Iblock;
use Core\Util;
use Core\Underscore as _;

$items = array_map(function($item) {
    $file = $item['DISPLAY_PROPERTIES']['FILE']['FILE_VALUE'];
    $path = $_SERVER['DOCUMENT_ROOT'].CFile::GetPath($file['ID']);
    $humanSize = Util::humanFileSize(filesize($path));
    return _::set($item, 'FILE', array_merge($file, [
        'HUMAN_SIZE' => $humanSize,
        'EXTENSION' => Util::fileExtension($path)
    ]));
}, $arResult['ITEMS']);
$arResult['SECTIONS'] = Iblock::groupBySection($items, $arResult['ID']);
