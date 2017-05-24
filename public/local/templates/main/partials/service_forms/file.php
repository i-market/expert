<?
use App\View as v;
?>
<div class="file pdf">
    <p class="info"><?= v::upper($extension) ?>, <?= $humanSize ?> <span class="remove red">Удалить</span></p>
    <p class="title"><?= $name ?></p>
</div>