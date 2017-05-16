<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use App\Iblock;

$arResult['SECTIONS'] = Iblock::groupBySection($arResult['ITEMS'], $arResult['ID']);
