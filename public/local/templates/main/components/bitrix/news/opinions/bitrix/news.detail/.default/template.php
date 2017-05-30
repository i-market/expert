<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die(); 

use App\View as v;

$APPLICATION->SetPageProperty('layout', 'default');
$APPLICATION->SetPageProperty('section_class', 'opinion-detail');
?>
<?= $arResult['DETAIL_TEXT'] ?>
<div class="bottom_btn">
    <a href="<?= $arResult['LIST_PAGE_URL'] ?>" class="big_btn">
        <span class="img">
            <img src="<?= v::asset('images/arrow_left_white.svg') ?>">
        </span>
        <span class="text"><span>Назад</span></span>
    </a>
</div>
