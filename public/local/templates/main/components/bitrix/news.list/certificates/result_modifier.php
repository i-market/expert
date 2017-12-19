<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use App\Iblock;
use Core\Util;
use Core\Underscore as _;

$transformFile = function($file) {
    $path = $_SERVER['DOCUMENT_ROOT'].CFile::GetPath($file['ID']);
    $humanSize = Util::humanFileSize(filesize($path));
    return array_merge($file, [
        'HUMAN_SIZE' => $humanSize,
        'EXTENSION' => Util::fileExtension($path)
    ]);
};
$items = array_map(function($item) use ($transformFile) {
    $file = $item['DISPLAY_PROPERTIES']['FILE']['FILE_VALUE'];
    return _::set($item, 'FILE', $transformFile($file));
}, $arResult['ITEMS']);
$result = CIBlockSection::GetList([], ['IBLOCK_ID' => $arResult['ID']], false,
    ['IBLOCK_ID', 'ID', 'NAME', 'CODE', 'SORT', 'DESCRIPTION', 'UF_FILE', 'UF_FILE_NAME']);
$sections = array_map(function($section) use ($transformFile) {
    $file = CFile::GetFileArray($section['UF_FILE']);
    return _::set($section, 'FILE', $transformFile($file));
}, Iblock::collect($result));
$groups = Iblock::groupBySection($items, $arResult['ID'], $sections);
$arResult['SECTIONS'] = _::sort($groups, function ($x) { return $x['SORT']; });
