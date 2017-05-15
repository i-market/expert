<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use App\View as v;
?>
<nav class="main_menu">
    <div class="menu_hamburger">
        <span></span>
        <span></span>
    </div>
    <ul>
        <? // TODO menu items ?>
        <li><a class="active" href="#">Главная</a></li>
        <li><a href="#">О компании</a></li>
        <li><a href="#">Наши деятельность</a></li>
        <li><a href="#">Примеры работ </a></li>
        <li><a href="#">Аттестаты и допуски СРО</a></li>
        <li><a href="#">Техническая база</a></li>
        <li><a href="#">Инфоблок</a></li>
        <li><a href="#">Контакты</a></li>
        <li class="hidden">
            <? // TODO link ?>
            <a href="#" class="big_btn">
                <span class="text">Отправить заявку</span>
                <span class="img">
                <img src="<?= v::asset('images/calc.png') ?>">
              </span>
            </a>
        </li>
    </ul>
</nav>
