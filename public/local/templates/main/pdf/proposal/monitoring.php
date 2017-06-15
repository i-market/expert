<div class="header">
    <table>
        <tr>
            <td>
                <img src="logo.png" width="96pt">
            </td>
            <td class="right">
                contact<br>
                details
            </td>
        </tr>
    </table>
    <p>
        Исх. № <?= $outgoingId ?><br>
        От <?= $date ?><br>
        Действительно до: <?= $endingDate ?><br>
    </p>
</div>
<div class="body">
    <h1>
        КОММЕРЧЕСКОЕ ПРЕДЛОЖЕНИЕ<br>
        на проведение мониторинга
    </h1>
    <h2>Сведения об объекте (объектах) мониторинга:</h2>
    <table>
        <tr>
            <td><strong>Описание объекта (объектов)</strong></td>
            <td><?= $data['DESCRIPTION'] ?></td>
        </tr>
    </table>
    <p>
        <strong>Стоимость работ составит: <?= $totalPrice ?></strong>, НДС не облагается согласно п.2. ст. 346.11 гл. 26.2 НК Российской Федерации (Уведомление о возможности применения упрощенной системы налогообложения от 12.03.04 г. № 717 ИФНС № 19 г. Москвы).
    </p>
    <p>
        <strong>Продолжительность выполнения работ: <?= $duration ?>.</strong>
    </p>
    <p class="extra-top-margin">
        <strong>Ссылка на просмотрщик:</strong><br>
        <strong style="color: red">http://www.autodesk.ru/free-trials</strong>
    </p>
</div>
