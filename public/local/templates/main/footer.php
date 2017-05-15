<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use App\App;
use App\View as v;

extract(App::layoutContext(), EXTR_SKIP)
?>
</main>
<!-- FOOTER START -->
<footer class="footer">
    <div class="wrap">
        <div class="top">
            <a class="logo_footer" href="<?= v::path('/') ?>">
                <img src="<?= v::asset('images/logo_footer.png') ?>" alt="Техническая строительная экспертиза">
                <p>
                    <span>Техническая</span>
                    <span>строительная экспертиза</span>
                </p>
            </a>
            <? // TODO link ?>
            <a class="advertisers_hidden" href="#">Рекламодателям</a>
            <div class="adress">
                <p>111141 г. Москва, 3-й проезд Перова Поля, <br> дом 8, строение 11, офис 402 <br> Станция метро "Перово", <br> последний вагон из центра (10 мин. пешком)</p>
            </div>
            <div class="info">
                <p><a href="tel:+7 (495) 641-70-69">+7 (495) 641-70-69</a></p>
                <p><a href="tel:+7 (499) 340-34-73">+7 (499) 340-34-73</a></p>
                <p><span>E-mail:</span><a href="mailto:6417069@bk.ru">6417069@bk.ru</a></p>
                <p><span>E-mail:</span><a href="mailto:6417069@bk.ru">6417069@bk.ru</a></p>
            </div>
            <div class="btns">
                <div class="blue_btn re_call">Заказать <span class="hidden">обратный</span> звонок</div>
                <div class="blue_btn calculate_cost">Рассчитать стоимость</div>
            </div>
        </div>
        <div class="bottom">
            <? // TODO includize copyright? ?>
            <span><?= '© 2015–'.$copyrightYear ?></span> <span>ООО «Техническая строительная экспертиза»</span> <a href="https://i-market.ru/" target="_blank" class="create">Создание и продвижение сайта I-Market</a>
            <? // TODO link ?>
            <a class="advertisers" href="#">Рекламодателям</a>
        </div>
    </div>
</footer>
<? if ($sentry['enabled']): ?>
    <?= v::render('partials/sentry_js', $sentry) ?>
<? endif ?>
<? if (!App::useBitrixAsset()): ?>
    <? foreach (App::assets()['scripts'] as $path): ?>
        <script type="text/javascript" src="<?= $path ?>"></script>
    <? endforeach ?>
<? endif ?>
</body>
</html>
