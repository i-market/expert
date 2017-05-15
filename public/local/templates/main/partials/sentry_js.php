<script type="text/javascript" src="https://cdn.ravenjs.com/3.12.1/raven.min.js"></script>
<script type="text/javascript">
    Raven.config('<?= $publicDsn ?>', {
        environment: '<?= $env ?>'
    }).install();
    <? $userId = $USER->GetID() ?>
    <? if ($userId !== null): ?>
    Raven.setUserContext({
        id: '<?= $userId ?>',
        username: '<?= $USER->GetLogin() ?>',
        email: '<?= $USER->GetEmail() ?>'
    });
    <? endif ?>
</script>
