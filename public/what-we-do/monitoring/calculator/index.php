<?
use App\App;

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Калькулятор стоимости");
$APPLICATION->SetPageProperty('layout', 'bare')
?>

<?= App::getInstance()->getMonitoring()->renderCalculator([]) ?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>