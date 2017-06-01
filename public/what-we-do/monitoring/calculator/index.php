<?
use App\View as v;

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Калькулятор стоимости");
$APPLICATION->SetPageProperty('layout', 'bare')
?>

<? // TODO stub ?>
<form ic-post-to="/api/services/monitoring/calculate">
    <?= v::render('partials/calculator/calculator', [
        'heading' => 'Определение стоимости и сроков Обследования конструкций, помещений, зданий, сооружений, инженерных сетей и оборудования'
    ]) ?>
</form>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>