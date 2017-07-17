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

$pdf = new mPDF('UTF-8');

$stylesPath = View::template('pdf/proposal/proposal.css');
$styles = file_get_contents(Util::joinPath([$_SERVER['DOCUMENT_ROOT'], $stylesPath]));
$pdf->WriteHTML($styles, 1);
// "Keep-with-table"
// automatically set $page-break-inside=avoid for any H1-H6 header that immediately precedes a table,
// thus keeping the heading together with the table.
$pdf->use_kwt = true;
$params = $_REQUEST;
$validator = v::allOf(
    v::key('type')
);
if (!$validator->validate($params)) {
    die('invalid params');
}
if ($params['example']) {
    if ($params['type'] === 'monitoring') {
        $params = array_merge($params, [
            'heading' => 'КОММЕРЧЕСКОЕ ПРЕДЛОЖЕНИЕ<br> на проведение мониторинга',
            'outgoingId' => '0611-1/16',
            'date' => '16 октября 2016 г.',
            'endingDate' => '26 октября 2017 г.',
            'totalPrice' => '150 000 руб/мес.',
            'duration' => '18 месяцев',
            'tables' => [
                [
                    'heading' => 'Сведения об объекте (объектах) мониторинга',
                    'rows' => array_map(function($pair) {
                        return ["<strong>{$pair[0]}</strong>", $pair[1]];
                    },
                        [
                            ['Описание объекта (объектов)', 'ТЦ «Щука»']
                        ]
                    )
                ]

            ]
        ]);
    }
}
$html = View::render("pdf/proposal/{$params['type']}", $params);
$pdf->WriteHTML($html, 2);
$dest = _::get($params, 'output.dest', '');
$path = $dest === ''
    ? ''
    : (_::get($params, 'output.debug', false)
        ? Util::joinPath([$_SERVER['DOCUMENT_ROOT'], 'local/proposal.pdf'])
        : tempnam(sys_get_temp_dir(), 'proposal'));
$pdf->Output($path, $dest);

if ($dest === 'F') {
    echo $path;
}
