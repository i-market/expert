<? 
require_once __DIR__.'/../FormMacros.php';

use App\Templates\FormMacros as macros;

$macros = new macros($state);
?>
<div class="wrap_input_block">
    <? $macros->showInput('NAME', 'Наименование объекта(ов) обследования', ['required' => true]) ?>
    <? $macros->showInput('LOCATION', 'Местонахождение объекта(ов)') ?>
    <? $macros->showTextarea('INSPECTION_GOAL', 'Опишите цели обследования', ['required' => true]) ?>
    <? $macros->showTextarea('DESCRIPTION', 'Описание объекта(ов) обследования (назначение, этажность, наличие подвала, площадь, год постройки и пр.)',
        ['required' => true]) ?>
    <? $macros->showTextarea('ADDITIONAL_INFO', 'Дополнительная информация по обследованию') ?>
</div>
<div class="wrap_checkbox_block">
    <h4>Наличие документов:</h4>
    <? foreach ($options['DOCUMENTS'] as $idx => $opt): ?>
        <? $macros->showCheckbox('DOCUMENTS[]', $opt['value'], $opt['text'], "inspection-document-{$idx}") ?>
    <? endforeach ?>
</div>
<? macros::showFilesBlock('<b>Документы</b> по объекту(ам) технического надзора (к заявке можно прикрепить не более 10 файлов)') ?>
<h3 class="h3_mb">Контактная информация для ответа</h3>
<div class="wrap_input_block">
    <? $macros->showInput('CONTACT[ORGANIZATION]', 'Наименование организации') ?>
    <? $macros->showInput('CONTACT[PERSON]', 'Контактное лицо', ['required' => true]) ?>
    <? $macros->showInput('CONTACT[PHONE_1]', 'Телефон 1') ?>
    <? $macros->showInput('CONTACT[PHONE_2]', 'Телефон 2') ?>
    <? $macros->showInput('CONTACT[EMAIL]', 'Электронная почта', ['required' => true]) ?>
</div>
