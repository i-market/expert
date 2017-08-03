<?
use App\Services;
use App\Services\Individual;
use App\View as v;

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Калькулятор стоимости");
$APPLICATION->SetPageProperty('layout', 'bare')
?>

<? $data = Services::data('individual') ?>
<? $ctx = Individual::calculatorContext(Individual::initialState($data)) ?>
<?= v::render('partials/calculator/individual_calculator', $ctx) ?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>