<?
use App\Calc\MonitoringForm;
use App\View as v;

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Калькулятор стоимости");
$APPLICATION->SetPageProperty('layout', 'bare')
?>

<?
// TODO stub
$state = [];
?>
<form ic-post-to="/api/services/monitoring/calculate">
    <?= v::render('partials/calculator/calculator', MonitoringForm::context($state)) ?>
</form>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>