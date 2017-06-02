<?
require_once __DIR__.'/CalculatorMacros.php';

use App\Templates\CalculatorMacros as macros;

$macros = new macros($state);
?>
<div id="monitoring-floors-group">
    <? $macros->showSelectGroup('FLOORS', $floorSelects, 'Количество надземных этажей', ['required' => true]) ?>
    <div class="loader inline" style="display: none"></div>
</div>
