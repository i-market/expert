<?php

require $_SERVER['DOCUMENT_ROOT'].'/local/vendor/autoload.php';

use Core\Util;
use App\View;
use Respect\Validation\Validator as v;
use Core\Underscore as _;

define('SITE_TEMPLATE_PATH', '/local/templates/main');

$pdf = new mPDF('UTF-8');

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
// TODO name
$name = _::get($params, 'output.name', '');
// TODO security
$dest = _::get($params, 'output.dest', '');
$pdf->Output($name, $dest);
