<? use App\View as v; ?>

<div class="body">
    <?= v::render('pdf/proposal/partials/body', get_defined_vars()) ?>
    <? if (isset($partial)): ?>
        <?= v::render($partial, get_defined_vars()) ?>
    <? endif ?>
    <div class="summary">
        <p>
            <strong>Общая стоимость работ составит: <?= $totalPrice ?></strong><?= v::render('pdf/proposal/partials/price_text') ?>
        </p>
        <p>
            <strong>Срок выполнения: <?= $time ?></strong>
        </p>
    </div>
</div>
