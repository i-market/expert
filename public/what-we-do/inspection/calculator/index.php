<?
use App\Services;
use App\Services\Inspection;
use App\View as v;

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Калькулятор стоимости");
$APPLICATION->SetPageProperty('layout', 'bare')
?>

<? $data = Services::data('inspection') ?>
<?= v::render('partials/calculator/inspection_calculator', Inspection::calculatorContext(Inspection::initialState($data))) ?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>