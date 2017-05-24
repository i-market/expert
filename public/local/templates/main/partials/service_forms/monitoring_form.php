<?
require __DIR__.'/macros.php';
?>
<div class="wrap_input_block">
    <? $showInput('NAME', 'Наименование объекта(ов) мониторинга', ['required' => true]) ?>
    <? $showInput('LOCATION', 'Местонахождение объекта(ов)') ?>
    <? $showTextarea('MONITORING_GOAL', 'Каковы цели проведения мониторинга', ['required' => true]) ?>
    <? $showTextarea('DESCRIPTION', 'Описание объекта(ов) мониторинга (назначение, этажность, наличие подвала, площадь, год постройки и пр.)',
        ['required' => true]) ?>
    <? $showTextarea('ADDITIONAL_INFO', 'Дополнительная информация по мониторингу') ?>
</div>
<div class="wrap_checkbox_block">
    <h4>Наличие документов:</h4>
    <? // TODO fetch options ?>
<!--    --><?// $showCheckbox('some-name', 'Результаты ранее выполненых обследований', 'some-id', true) ?>
</div>
<? $showFilesBlock('<b>Документы</b> по объекту(ам) мониторинга (к заявке можно прикрепить не более 10-и файлов)') ?>
<h3 class="h3_mb">Контактная информация для ответа</h3>
<div class="wrap_input_block">
    <? $showInput('CONTACT[ORGANIZATION]', 'Наименование организации') ?>
    <? $showInput('CONTACT[PERSON]', 'Контактное лицо', ['required' => true]) ?>
    <? $showInput('CONTACT[PHONE_1]', 'Телефон 1') ?>
    <? $showInput('CONTACT[PHONE_2]', 'Телефон 2') ?>
    <? $showInput('CONTACT[EMAIL]', 'Электронная почта', ['required' => true]) ?>
</div>
