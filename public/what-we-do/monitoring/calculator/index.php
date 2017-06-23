<?
use App\View as v;
use App\App;

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Калькулятор стоимости");
$APPLICATION->SetPageProperty('layout', 'bare')
?>

<?= v::render('partials/calculator/monitoring_calculator', App::getInstance()->getMonitoring()->calculatorContext([])) ?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>