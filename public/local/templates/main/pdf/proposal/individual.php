<? use App\View as v; ?>

<div class="body">
    <?= v::render('pdf/proposal/partials/body', get_defined_vars()) ?>
    <div class="summary">
        <p>
            <strong>Общая стоимость работ составит: <?= $totalPrice ?></strong><?= v::render('pdf/proposal/partials/price_text') ?>
        </p>
        <p>
            <strong>Срок выполнения: <?= $duration ?></strong>
        </p>
    </div>
</div>
