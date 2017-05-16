<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
?>
<section class="pre_footer_menu">
    <div class="wrap">
        <ul>
            <? foreach ($arResult as $item): ?>
                <? $class = $item['SELECTED'] ? 'active' : '' ?>
                <li><a class="<?= $class ?>" href="<?= $item['LINK'] ?>"><?= $item['TEXT'] ?></a></li>
            <? endforeach ?>
        </ul>
    </div>
</section>
