<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use App\Iblock;
use Bitrix\Iblock\SectionTable;
use Core\Util;
use Core\Underscore as _;

$sectionSorter = function($section) use ($arResult) {
    // reuse component's section ordering
    return array_search($section, $arResult['SECTIONS']);
};

$arResult['ROOT_SECTIONS'] = array_map(function($root) use ($sectionSorter) {
    // flatten subsections
    $descendants = Util::descendants([$root], 'SECTIONS');
    $deepestLevel = intval(max(_::map($descendants, 'DEPTH_LEVEL')));
    $sections = array_map(function($section) use ($deepestLevel) {
        // we are going to render subsections inside out
        return _::set($section, 'ELEVATION', $deepestLevel - intval($section['DEPTH_LEVEL']));
    }, $descendants);
    return _::set($root, 'SECTIONS', _::sort($sections, $sectionSorter));
}, Iblock::sectionTrees($arResult['SECTIONS']));

if (intval($arResult['SECTION']['DEPTH_LEVEL']) === 1) {
    $roots = SectionTable::query()->setSelect(['ID', 'SORT'])->setFilter([
        'IBLOCK_ID' => $arResult['SECTION']['IBLOCK_ID'],
        'DEPTH_LEVEL' => 1
    ])->setOrder(['SORT' => 'ASC'])->exec()->fetchAll();
    $idx = _::findKey($roots, function($x) use ($arResult) {
        return $x['ID'] === $arResult['SECTION']['ID'];
    });
    $arResult['NUMBER_MARKER'] = $idx + 1;
}
