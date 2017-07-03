<?
use App\Services\MonitoringRepo;
use App\View as v;
use App\App;

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Калькулятор стоимости");
$APPLICATION->SetPageProperty('layout', 'bare')
?>

<? $dataSet = (new MonitoringRepo)->defaultDataSet() ?>
<? $ctx = App::getInstance()->getMonitoring()->calculatorContext([], $dataSet) ?>
<?= v::render('partials/calculator/monitoring_calculator', $ctx) ?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>