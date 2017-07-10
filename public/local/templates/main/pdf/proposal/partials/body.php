<h1><?= $heading ?></h1>
<? foreach ($tables as $table): ?>
    <h2><?= $table['heading'].':' ?></h2>
    <table>
        <? foreach ($table['rows'] as $row): ?>
            <tr>
                <? foreach ($row as $cell): ?>
                    <td><?= $cell ?></td>
                <? endforeach ?>
            </tr>
        <? endforeach ?>
    </table>
<? endforeach ?>