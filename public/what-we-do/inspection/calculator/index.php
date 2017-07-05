<?
use App\Services\MonitoringRepo;
use App\View as v;
use App\App;

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Калькулятор стоимости");
$APPLICATION->SetPageProperty('layout', 'bare')
?>

<? // TODO tmp ctx ?>
<?
// TODO tmp
use Core\Underscore as _;
use App\Services;
$parser = new \App\Services\InspectionParser();
$path = \Core\Util::joinPath([$_SERVER['DOCUMENT_ROOT'], 'local/fixtures/calculator/Обследование калькуляторы.xlsx']);
$data = $parser->parseFile($path);
$dataSet = $data['MULTIPLE_BUILDINGS'];
$keys = [
    'SITE_COUNT',
    'DISTANCE_BETWEEN_SITES',
    'LOCATION',
    'USED_FOR',
    'FLOORS',
    'DOCUMENTS',
    'INSPECTION_GOAL',
    'TRANSPORT_ACCESSIBILITY'
];
$options = array_reduce($keys, function($acc, $key) use ($dataSet) {
    return _::set($acc, $key, Services::entities2options([$key], $dataSet));
}, []);
$options['STRUCTURES_TO_INSPECT'] = [
    'PACKAGE' => Services::entities2options(['STRUCTURES_TO_INSPECT', 'PACKAGE'], $dataSet),
    'INDIVIDUAL' => Services::entities2options(['STRUCTURES_TO_INSPECT', 'INDIVIDUAL'], $dataSet),
];
?>
<? $ctx = ['options' => $options] ?>
<?= v::render('partials/calculator/inspection_calculator', $ctx) ?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>