<?
use App\View as v;
?>
<? if (!v::isEmpty($errors)): ?>
    <? $errorCount = count($errors) ?>
    <? $msgErrors = \Core\Util::units($errorCount, 'ошибка', 'ошибки', 'ошибок') ?>
    <? $msgAllowed = \Core\Util::units($errorCount, 'позволила', 'позволили', 'позволили') ?>
    <div class="form-message error">
        <span><?= "{$errorCount} {$msgErrors} выше не {$msgAllowed} {$action}." ?></span>
    </div>
<? endif ?>