<? foreach ($services as $service): ?>
    <div class="modal service-request modal--has-recaptcha" id="<?= $service['requestModalId'] ?>">
        <div class="block">
            <span class="close">×</span>
            <form data-api-endpoint="<?= $service['apiEndpoint'] ?>" novalidate>
                <?= $service['form'] ?>
            </form>
        </div>
    </div>
<? endforeach ?>
