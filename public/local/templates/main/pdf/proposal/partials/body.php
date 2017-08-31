<? use App\View as v; ?>

<h1><?= $heading ?></h1>
<? foreach ($tables as $table): ?>
    <h2><?= $table['heading'].':' ?></h2>
    <table>
        <? if (!v::isEmpty(v::get($table, 'header'))): ?>
            <thead>
            <tr>
                <? foreach ($table['header'] as $cell): ?>
                    <th><?= $cell ?></th>
                <? endforeach ?>
            </tr>
            </thead>
        <? endif ?>
        <tbody>
        <? foreach ($table['rows'] as $row): ?>
            <tr>
                <? foreach ($row as $cell): ?>
                    <td><?= $cell ?></td>
                <? endforeach ?>
            </tr>
        <? endforeach ?>
        </tbody>
    </table>
<? endforeach ?>