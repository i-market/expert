<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use App\View as v;
?>
<nav class="main_menu">
    <div class="menu_hamburger">
        <span></span>
        <span></span>
    </div>
    <ul>
        <? foreach ($arResult as $item): ?>
            <? $class = $item['SELECTED'] ? 'active' : '' ?>
            <li><a class="<?= $class ?>" href="<?= $item['LINK'] ?>"><?= $item['TEXT'] ?></a></li>
        <? endforeach ?>
        <li class="hidden">
            <a href="<?= v::path('what-we-do') ?>" class="big_btn">
                <span class="text"><span>Отправить заявку</span></span>
                <span class="img">
                <img src="<?= v::asset('images/calc.png') ?>">
              </span>
            </a>
        </li>
    </ul>
</nav>
