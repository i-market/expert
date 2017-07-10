<?
use App\Services\Inspection;
use App\Services\InspectionParser;
use App\View as v;
use Core\Util;

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Калькулятор стоимости");
$APPLICATION->SetPageProperty('layout', 'bare')
?>

<? // TODO tmp ?>
<? $data = (new InspectionParser)->parseFile(Util::joinPath([$_SERVER['DOCUMENT_ROOT'], 'local/fixtures/calculator/Обследование калькуляторы.xlsx'])); ?>
<?= v::render('partials/calculator/inspection_calculator', Inspection::calculatorContext(Inspection::initialState($data))) ?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>