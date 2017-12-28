<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/raven.js/3.12.1/raven.min.js"></script>
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
