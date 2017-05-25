<? require __DIR__.'/macros.php' ?>

<div class="wrap_input_block">
    <? $showInput($state, 'NAME', 'Наименование объекта(ов) мониторинга', ['required' => true]) ?>
    <? $showInput($state, 'LOCATION', 'Местонахождение объекта(ов)') ?>
    <? $showTextarea($state, 'MONITORING_GOAL', 'Каковы цели проведения мониторинга', ['required' => true]) ?>
    <? $showTextarea($state, 'DESCRIPTION', 'Описание объекта(ов) мониторинга (назначение, этажность, наличие подвала, площадь, год постройки и пр.)',
        ['required' => true]) ?>
    <? $showTextarea($state, 'ADDITIONAL_INFO', 'Дополнительная информация по мониторингу') ?>
</div>
<div class="wrap_checkbox_block">
    <h4>Наличие документов:</h4>
    <? // TODO fetch options ?>
<!--    --><?// $showCheckbox('some-name', 'Результаты ранее выполненых обследований', 'some-id', true) ?>
</div>
<? $showFilesBlock('<b>Документы</b> по объекту(ам) мониторинга (к заявке можно прикрепить не более 10-и файлов)') ?>
<h3 class="h3_mb">Контактная информация для ответа</h3>
<div class="wrap_input_block">
    <? $showInput($state, 'CONTACT[ORGANIZATION]', 'Наименование организации') ?>
    <? $showInput($state, 'CONTACT[PERSON]', 'Контактное лицо', ['required' => true]) ?>
    <? $showInput($state, 'CONTACT[PHONE_1]', 'Телефон 1') ?>
    <? $showInput($state, 'CONTACT[PHONE_2]', 'Телефон 2') ?>
    <? $showInput($state, 'CONTACT[EMAIL]', 'Электронная почта', ['required' => true]) ?>
</div>
