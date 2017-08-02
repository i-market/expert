<?
use App\Services;
use App\Services\Oversight;
use App\View as v;

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Калькулятор стоимости");
$APPLICATION->SetPageProperty('layout', 'bare')
?>

<? $data = Services::data('oversight') ?>
<? $ctx = Oversight::calculatorContext(Oversight::initialState($data)) ?>
<?= v::render('partials/calculator/oversight_calculator', $ctx) ?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>