<?
use App\Services;
use App\Services\Monitoring;
use App\View as v;

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Калькулятор стоимости");
$APPLICATION->SetPageProperty('layout', 'bare')
?>

<? $data = Services::data('monitoring') ?>
<?= v::render('partials/calculator/monitoring_calculator', Monitoring::calculatorContext(Monitoring::initialState($data))) ?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>