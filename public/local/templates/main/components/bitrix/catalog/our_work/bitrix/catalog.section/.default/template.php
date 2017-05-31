<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use App\View as v;

//$APPLICATION->SetPageProperty('layout', 'default');
?>
<? if (!v::isEmpty($arResult['ITEMS'])): ?>
    <? var_export($arResult['ITEMS']) ?>
<? endif ?>
