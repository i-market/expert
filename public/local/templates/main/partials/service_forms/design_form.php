<? 
require_once __DIR__.'/../FormMacros.php';

use App\View as v;
use App\Templates\FormMacros as macros;

$macros = new macros($state);
?>
<div class="wrap_input_block">
    <? $macros->showInput('NAME', 'Наименование объекта(ов)', ['required' => true]) ?>
    <? $macros->showInput('LOCATION', 'Местонахождение объекта(ов)') ?>
    <? $macros->showTextarea('ADDITIONAL_INFO', 'Дополнительная информация по обследованию') ?>
</div>
<? $name = 'ITEMS' ?>
<? list($value, $error) = $macros->valueErrorPair($name) ?>
<div class="wrap_checkbox_block<?= !v::isEmpty($error) ? ' error' : '' ?>">
    <h4>Какие проектные решения необходимо разработать? Выберите наименование раздела(ов) <span class="red">*</span>:</h4>
    <div class="error-message"><?= $error ?></div>
    <? foreach ($options[$name] as $idx => $opt): ?>
        <? $macros->showCheckbox($name.'[]', $opt['value'], $opt['text'], "item-{$idx}") ?>
    <? endforeach ?>
</div>
<? macros::showFilesBlock('<b>Документы</b> по объекту(ам) экспертизы (к заявке можно прикрепить не более 10 файлов)') ?>
<h3 class="h3_mb">Контактная информация для ответа</h3>
<div class="wrap_input_block">
    <? $macros->showInput('CONTACT[ORGANIZATION]', 'Наименование организации') ?>
    <? $macros->showInput('CONTACT[PERSON]', 'Контактное лицо', ['required' => true]) ?>
    <? $macros->showInput('CONTACT[PHONE_1]', 'Телефон 1') ?>
    <? $macros->showInput('CONTACT[PHONE_2]', 'Телефон 2') ?>
    <? $macros->showInput('CONTACT[EMAIL]', 'Электронная почта', ['required' => true]) ?>
</div>
