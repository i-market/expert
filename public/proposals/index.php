<?php

require $_SERVER['DOCUMENT_ROOT'].'/local/vendor/autoload.php';

use Core\Util;
use App\View as v;

define('SITE_TEMPLATE_PATH', '/local/templates/main');

$pdf = new mPDF('UTF-8');

$stylesPath = v::template('pdf/proposal/proposal.css');
$styles = file_get_contents(Util::joinPath([$_SERVER['DOCUMENT_ROOT'], $stylesPath]));
$pdf->WriteHTML($styles, 1);
$ctx = [
    'outgoingId' => '0611-1/16',
    'date' => '16 октября 2016 г.',
    'endingDate' => '26 октября 2017 г.',
    'totalPrice' => '150 000 руб/мес.',
    'duration' => '18 месяцев',
    'data' => [
        'DESCRIPTION' => 'testТЦ «Щука»'
    ]
];
$html = v::render('pdf/proposal/monitoring', $ctx);
$pdf->WriteHTML($html, 2);
$pdf->Output();
