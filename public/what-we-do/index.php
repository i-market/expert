<?
use App\Components;

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Стоимость и сроки ON-line");
$APPLICATION->SetPageProperty('layout', 'bare')
?>

<?= Components::renderServicesSection() ?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>