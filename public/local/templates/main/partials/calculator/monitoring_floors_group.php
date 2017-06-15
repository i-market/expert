<?
require_once __DIR__.'/CalculatorMacros.php';

use App\Templates\CalculatorMacros as macros;
use App\View as v;

$macros = new macros($state);
?>
<div id="monitoring-floors-group">
    <? $macros->showInputGroup('FLOORS', $floorSelects, 'Количество надземных этажей', [
        'required' => true,
        'type' => 'number',
        'input_attrs' => v::attrs([
            'min' => 0
        ])
    ]) ?>
    <div class="loader inline" style="display: none"></div>
</div>
