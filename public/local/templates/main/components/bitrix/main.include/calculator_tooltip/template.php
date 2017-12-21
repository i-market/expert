<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die(); 

use App\View as v;
$content = !v::isEmpty($arResult['FILE'])
    ? file_get_contents($arResult['FILE'])
    : '';
?>
<? if (!v::isEmpty($content)): ?>
    <span class="tooltip" title="<?= htmlspecialchars($content) ?>"></span>
<? endif ?>
