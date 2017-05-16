<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Core\Underscore as _;

$arResult['CLASS_LIST'] = array_merge(['some_section'], _::get($arParams, 'CLASS_LIST', []));