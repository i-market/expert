<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die(); 

use App\View as v;
?>
<? if (!v::isEmpty($arResult['FILE'])): ?>
    <span class="tooltip" title="<?= htmlspecialchars(file_get_contents($arResult['FILE'])) ?>"></span>
<? endif ?>
