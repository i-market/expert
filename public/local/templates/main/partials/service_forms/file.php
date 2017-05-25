<?
use App\View as v;
?>
<? // same as js template ?>
<div class="file pdf">
    <p class="info"><?= !v::isEmpty($extension) ? v::upper($extension).', ' : '' ?><?= $humanSize ?> <span class="remove red">Удалить</span></p>
    <p class="title"><?= $name ?></p>
</div>