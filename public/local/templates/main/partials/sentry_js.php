<script src="https://cdn.ravenjs.com/3.22.1/raven.min.js" crossorigin="anonymous"></script>
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
