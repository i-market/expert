<? if (!\App\App::useBitrixAsset()): ?>
    <? foreach (\App\App::assets()['scripts'] as $path): ?>
        <script type="text/javascript" src="<?= $path ?>"></script>
    <? endforeach ?>
<? endif ?>
</body>
</html>
