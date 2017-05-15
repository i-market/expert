<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Core\Underscore as _;

$columnCount = ceil(count($arResult['ITEMS']) / 2);
$columns = array_map(function($idx) use ($arResult, $columnCount) {
    return ['ITEMS' => array_values(_::pick($arResult['ITEMS'], [$idx, $idx + $columnCount]))];
}, range(0, $columnCount - 1));
$arResult['COLUMNS'] = $columns;

