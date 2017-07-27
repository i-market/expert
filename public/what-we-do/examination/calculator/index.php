<?
use App\Services;
use App\Services\Examination;
use App\View as v;

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Калькулятор стоимости");
$APPLICATION->SetPageProperty('layout', 'bare')
?>

<? $data = Services::data('examination') ?>
<? $ctx = Examination::calculatorContext(Examination::initialState($data)) ?>
<?= v::render('partials/calculator/examination_calculator', $ctx) ?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>