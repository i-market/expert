<?
use App\Services\Monitoring;
use App\Services\MonitoringParser;
use App\View as v;
use Core\Util;

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Калькулятор стоимости");
$APPLICATION->SetPageProperty('layout', 'bare')
?>

<? // TODO tmp ?>
<? $data = (new MonitoringParser)->parseFile(Util::joinPath([$_SERVER['DOCUMENT_ROOT'], 'local/fixtures/calculator/Мониторинг калькуляторы.xlsx'])); ?>
<?= v::render('partials/calculator/monitoring_calculator', Monitoring::calculatorContext(Monitoring::initialState($data))) ?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>