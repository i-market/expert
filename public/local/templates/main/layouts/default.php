<? // TODO refactor pass $sectionClass through the template context ?>
<? $sectionClass = $APPLICATION->GetProperty('section_class') ?>
<section class="editable-section main-content<?= $sectionClass ? ' '.$sectionClass : '' ?>">
    <div class="wrap">
        <div class="wrap_title">
            <h2><? $APPLICATION->ShowTitle(false) ?></h2>
        </div>
        <?= $content ?>
    </div>
</section>