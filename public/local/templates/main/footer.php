<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use App\App;
use App\View as v;

extract(App::layoutContext(), EXTR_SKIP)
?>

<? $APPLICATION->IncludeComponent(
    "bitrix:menu",
    "footer",
    Array(
        "ALLOW_MULTI_SELECT" => "N",
        "CHILD_MENU_TYPE" => "left",
        "DELAY" => "N",
        "MAX_LEVEL" => "1",
        "MENU_CACHE_GET_VARS" => array(""),
        "MENU_CACHE_TIME" => "3600",
        "MENU_CACHE_TYPE" => "N",
        "MENU_CACHE_USE_GROUPS" => "Y",
        "ROOT_MENU_TYPE" => "top",
        "USE_EXT" => "Y"
    )
); ?>
<section class="pre_footer">
    <div class="wrap">
        <div class="accordeon grid">
            <div class="accordeon_item col col_4">
                <div class="accordeon_title">Консультация специалиста</div>
                <div class="accordeon_inner">
                    <p><a href="#">Административное право</a></p>
                    <p><a href="#">Арбитражное процессуальное право</a></p>
                    <p><a href="#">Военное право</a></p>
                    <p><a href="#">Гражданское право</a></p>
                    <p><a href="#">Жилищное право</a></p>
                    <p><a href="#">Исполнительное производство</a></p>
                    <p><a href="#">Коммерческое право</a></p>
                    <p><a href="#">Международное право</a></p>
                    <p><a href="#">Наследственное право </a></p>
                </div>
            </div>
            <div class="accordeon_item col col_4">
                <div class="accordeon_title">Наши партнёры</div>
                <div class="accordeon_inner">
                    <p><a href="#">«Российские Железные Дороги» http://www.rzd.ru</a></p>
                    <p><a href="#">«Фирма «Трансгарант» http://www.transgarant.com</a></p>
                    <p><a href="#">«Редакция журнала «РЖД-Партнер» http://www.rzd-partner.ru</a></p>
                    <p><a href="#">«Брансвик Рейл Лизинг» http://www.brunswick-capital.com</a></p>
                    <p><a href="#">"Системный транспортный сервис" http://ststrans.ru</a></p>
                </div>
            </div>
            <div class="accordeon_item col col_4">
                <div class="accordeon_title">Полезные ссылки</div>
                <div class="accordeon_inner">
                    <p><a href="#">Информация о проекте</a></p>
                    <p><a href="#">Аналитические возможности</a></p>
                    <p><a href="#">Архивы судебных решений</a></p>
                    <p><a href="#">Размещение рекламы</a></p>
                    <p><a href="#">Статистические карточки</a></p>
                    <p><a href="#">Добавление дел</a></p>
                    <p><a href="#">Юристы онлайн</a></p>
                    <p><a href="#">Видеозвонки</a></p>
                    <p>
                        <a href="#"></a>
                    </p>
                    <p>
                        <a href="#"></a>
                    </p>
                </div>
            </div>
            <div class="accordeon_item col col_4">
                <div class="accordeon_title">Нормативно-законодательная база</div>
                <div class="accordeon_inner">
                    <p><a href="#">Нормативно-законодательная база</a></p>
                    <p><a href="#">Административное право</a></p>
                    <p><a href="#">Арбитражное процессуальное право</a></p>
                    <p><a href="#">Военное право</a></p>
                    <p><a href="#">Гражданское право</a></p>
                    <p><a href="#">Жилищное право</a></p>
                    <p><a href="#">Исполнительное производство</a></p>
                    <p><a href="#">Коммерческое право</a></p>
                    <p><a href="#">Международное право</a></p>
                    <p><a href="#">Наследственное право </a></p>
                </div>
            </div>
        </div>
    </div>
    <div class="some_section some_section--hidden">
        <div class="wrap">
            <div class="grid">
                <a href="#" class="col col_4" style="background: url('<?= v::asset('images/pic_4.jpg') ?>')no-repeat center center / cover"></a>
                <a href="#" class="col col_4" style="background: url('<?= v::asset('images/pic_5.jpg') ?>')no-repeat center center / cover"></a>
                <a href="#" class="col col_4" style="background: url('<?= v::asset('images/pic_6.jpg') ?>')no-repeat center center / cover"></a>
                <a href="#" class="col col_4" style="background: url('<?= v::asset('images/pic_7.jpg') ?>')no-repeat center center / cover"></a>
            </div>
        </div>
    </div>
</section>
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
