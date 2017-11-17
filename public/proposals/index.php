<?php

require $_SERVER['DOCUMENT_ROOT'].'/local/vendor/autoload.php';

use Core\Util;
use App\View;
use Respect\Validation\Validator as v;
use Core\Underscore as _;

$config = require realpath(__DIR__.'/../bitrix/.settings.php');
$appConfig = _::get($config, 'app.value');
if (_::get($appConfig, 'sentry.enabled', false)) {
    $dsn = _::get($appConfig, 'sentry.dsn');
    /** @noinspection PhpUnnecessaryFullyQualifiedNameInspection */
    $client = new Raven_Client($dsn, [
        'environment' => _::get($appConfig, 'env')
    ]);
    $handler = new Raven_ErrorHandler($client);
    $handler->registerExceptionHandler();
    $handler->registerErrorHandler();
    $handler->registerShutdownFunction();
}

define('SITE_TEMPLATE_PATH', '/local/templates/main');

$pdf = new mPDF(...array_values([
    'mode' => 'UTF-8',
    'format' => 'A4',
    'default_font_size' => 0,
    'default_font' => '',
    // margins
    'mgl' => 15,
    'mgr' => 15,
    'mgt' => 16,
    'mgb' => 16 * 1.8, // make space for footer
    'mgh' => 9,
    'mgf' => 9,
    'orientation' => 'P'
]));

// "Keep-with-table"
// automatically set $page-break-inside=avoid for any H1-H6 header that immediately precedes a table,
// thus keeping the heading together with the table.
$pdf->use_kwt = false; // buggy: for some headings rests their styles

$stylesPath = View::template('pdf/proposal/proposal.css');
$styles = file_get_contents(Util::joinPath([$_SERVER['DOCUMENT_ROOT'], $stylesPath]));
$pdf->WriteHTML($styles, 1);
$params = $_REQUEST;
$validator = v::allOf(
    v::key('type')
);
if (!$validator->validate($params)) {
    die('invalid params');
}
if ($params['example']) {
    $params = array_merge($params, [
        'outgoingId' => '0611-1/16',
        'date' => '16 октября 2016 г.',
        'endingDate' => '26 октября 2017 г.',
        'totalPrice' => '150 000 руб/мес.',
        'duration' => '18 месяцев',
        'time' => '18 месяцев',
        'tables' => [
            [
                'heading' => 'Сведения об объекте (объектах)',
                'rows' => _::map(range(1, 20), function ($n) {
                    return ['field '.$n, 'value '.$n];
                })
            ]

        ]
    ]);
    switch ($params['type']) {
        case 'monitoring':
            $params = array_merge($params, [
                'heading' => 'КОММЕРЧЕСКОЕ ПРЕДЛОЖЕНИЕ<br> на проведение мониторинга',
            ]);
            break;
        case 'examination':
            $params = array_merge($params, [
                'heading' => 'КОММЕРЧЕСКОЕ ПРЕДЛОЖЕНИЕ<br> на проведение экспертизы',
                'partial' => 'pdf/proposal/partials/examination/14.1',
                'tables' => [
                    [
                        'heading' => 'Сведения об объекте (объектах)',
                        'rows' => [['field', str_repeat('value ', 2000)]]
                    ]

                ]
            ]);
            break;
    }
}
$ctx = $params;
$html = join("\n", [
    View::render('pdf/proposal/partials/header', $ctx),
    View::render("pdf/proposal/{$params['type']}", $ctx),
]);
$pdf->defaultfooterline = false;
$pdf->SetFooter(View::render('pdf/proposal/partials/footer', $ctx));
$pdf->WriteHTML($html, 2);
$dest = _::get($params, 'output.dest', '');
// TODO ok tmp dir?
$tmpPath = ini_get('upload_tmp_dir') ?: sys_get_temp_dir();
$path = $dest === ''
    ? ''
    : (_::get($params, 'output.debug', false)
        ? Util::joinPath([$_SERVER['DOCUMENT_ROOT'], 'local/proposal.pdf'])
        : tempnam($tmpPath, 'proposal-').'.pdf');
$pdf->Output($path, $dest);

if ($dest === 'F') {
    echo $path;
}
