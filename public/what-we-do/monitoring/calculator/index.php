<?
use App\App;

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Калькулятор стоимости");
$APPLICATION->SetPageProperty('layout', 'bare')
?>

<?
// TODO stub
$state = [];
?>
<form ic-post-to="/api/services/monitoring/calculate">
    <?= App::getInstance()->getMonitoring()->renderCalculator([]) ?>
</form>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>