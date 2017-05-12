<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use App\App;
use Bitrix\Main\Page\Asset;
use App\View as v;

extract(App::layoutContext(), EXTR_SKIP);

$assets = App::assets();
$asset = Asset::getInstance();
$asset->setJsToBody(true);
if (App::useBitrixAsset()) {
    foreach ($assets['styles'] as $path) {
        $asset->addCss($path);
    }
    foreach ($assets['scripts'] as $path) {
        $asset->addJs($path);
    }
}
?>
<!doctype html>
<html lang="<?= LANGUAGE_ID ?>">
<head>
    <? $APPLICATION->ShowHead() ?>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="format-detection" content="telephone=no" />
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
    <title><? $APPLICATION->ShowTitle() ?></title>
    <? if (!App::useBitrixAsset()): ?>
        <? foreach ($assets['styles'] as $path): ?>
            <link rel="stylesheet" media="screen" href="<?= $path ?>">
        <? endforeach ?>
    <? endif ?>
    <!--[if gte IE 9]>
    <style type="text/css">
        .gradient {
            filter: none;
        }
    </style>
    <![endif]-->
</head>
<body>
<? $APPLICATION->ShowPanel() ?>
<!--социалки-->
<ul class="social">
    <? // TODO ?>
    <li>
        <a href="#" class="vk"></a>
    </li>
    <li>
        <a href="#" class="fb"></a>
    </li>
    <li>
        <a href="#" class="od"></a>
    </li>
    <li>
        <a href="#" class="tw"></a>
    </li>
    <li>
        <a href="#" class="gp"></a>
    </li>
</ul>
<!--прокрутка вверх-->
<a class="scroll_top" href="#"></a>
<!-- HEADER START -->
<header class="header">
    <div class="top">
        <div class="wrap">
            <div class="left">
                <a class="logo" href="<?= v::path('/') ?>">
                    <img src="<?= v::asset('images/logo.png') ?>" alt="Техническая строительная экспертиза">
                    <p>
                        <span>Техническая</span>
                        <span>строительная экспертиза</span>
                    </p>
                </a>
                <div class="inner">
                    <div class="operating_schedule">
                        <? $APPLICATION->IncludeComponent(
                        	"bitrix:main.include",
                        	"",
                        	Array(
                        		"AREA_FILE_SHOW" => "file",
                        		"PATH" => v::includedArea('operating_schedule.php')
                        	)
                        ); ?>
                    </div>
                    <? // TODO implement search ?>
                    <form action="" method="post" id="">
                        <input type="text" placeholder="Найти">
                        <button type="submit"></button>
                    </form>
                </div>
                <div class="hamburger">
                    <span></span><span></span><span></span>
                </div>
            </div>
            <div class="right">
                <div class="btns">
                    <? // TODO modals ?>
                    <div class="blue_btn re_call">Заказать <span class="hidden">обратный</span> звонок</div>
                    <div class="blue_btn calculate_cost">Рассчитать стоимость</div>
                </div>
                <? // TODO contact details ?>
                <div class="info">
                    <div class="info_top">
                        <p><a href="tel:+7(499) 340-34-73">+7(495) 641-70-69</a></p>
                        <p><a href="tel:+7(499) 340-34-73">+7(499) 340-34-73</a></p>
                    </div>
                    <div class="info_bottom">
                        <p>
                            <span>E-mail:</span>
                            <a href="mailto:6417069@bk.ru">6417069@bk.ru</a>
                        </p>
                        <p>
                            <span>E-mail:</span>
                            <a href="mailto:6417069@bk.ru">6417069@bk.ru</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="bottom">
        <? $APPLICATION->IncludeComponent(
            "bitrix:menu",
            "header",
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
    </div>
</header>
<!-- CONTENT START -->
<main class="content">
    <? // TODO slider ?>
    <section class="wrap_banner_slider">
        <span class="arrow prev"></span>
        <span class="arrow next"></span>
        <div class="banner_slider">
            <div class="slide" style="background: url('<?= v::asset('images/pic_1.jpg') ?>')no-repeat center center / cover">
                <div class="wrap">
                    <div class="left">
                        <p>Техническая</p>
                        <strong>строительная экспертиза</strong>
                    </div>
                    <div class="right">
                        <p>Мы предоставляем услуги по проведению независимой строительно-технических экспертизы, а также строительной судебной и досудебной экспертизы. Наша деятельность осуществляется в соответствии с требованиями строительных норм, и в соответствии с законодательством Российской Федерации.</p>
                    </div>
                </div>
            </div>
            <div class="slide" style="background: url('<?= v::asset('images/pic_1.jpg') ?>')no-repeat center center / cover">
                <div class="wrap">
                    <div class="left">
                        <p>Техническая</p>
                        <strong>строительная экспертиза</strong>
                    </div>
                    <div class="right">
                        <p>Мы предоставляем услуги по проведению независимой строительно-технических экспертизы, а также строительной судебной и досудебной экспертизы. Наша деятельность осуществляется в соответствии с требованиями строительных норм, и в соответствии с законодательством Российской Федерации.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
