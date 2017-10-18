<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use App\View as v;
?>
<? // TODO refactor ?>
<? foreach ($arResult['SECTIONS'] as $section): ?>
    <? $hasFile = !v::isEmpty($section['FILE']) ?>
    <? $fileName = !v::isEmpty($section['FILE']) ? $section['UF_FILE_NAME'] : '' ?>
    <div class="certificates_item" id="<?= $section['CODE'] ?>">
        <div id="<?= v::addEditingActions($section, $this, 'section') ?>">
            <div class="wrap_title">
                <h4 class="<?= $hasFile ? 'before-download' : '' ?>"><?= $section['NAME'] ?></h4>
            </div>
            <? if ($hasFile): ?>
                <a class="download download--top" href="<?= $section['FILE']['SRC'] ?>" target="_blank">
                    <?= 'Скачать '.$fileName ?>
                </a>
            <? endif ?>
            <div class="grid <?= count($section['ITEMS']) < 3 ? 'grid--center' : '' ?>">
                <? foreach ($section['ITEMS'] as $item): ?>
                    <? $file = $item['FILE'] ?>
                    <div class="item col col_3" id="<?= v::addEditingActions($item, $this) ?>">
                        <? // TODO add fancybox gallery id? ?>
                        <a class="gallery" href="<?= $item['PREVIEW_PICTURE']['SRC'] ?>" target="_blank">
                        <span class="wrap_img">
                            <img src="<?= v::resize($item['PREVIEW_PICTURE'], 274, 274) ?>" alt="<?= $item['PREVIEW_PICTURE']['ALT'] ?>">
                        </span>
                            <span class="text"><?= $item['NAME'] ?></span>
                        </a>

                        <? if (v::isEmpty($section['FILE'])): ?>
                            <? $extension = v::upper($file['EXTENSION']) ?>
                            <? $downloadText = "Скачать ${extension}" ?>
                            <? // TODO resize ?>
                            <a class="download_sert" href="<?= $file['SRC'] ?>" target="_blank"><?= $downloadText ?></a>
                        <? endif ?>
                    </div>
                <? endforeach ?>
            </div>
            <? if ($hasFile): ?>
                <a class="download download--bottom" href="<?= $section['FILE']['SRC'] ?>" target="_blank">
                    <?= 'Скачать '.$fileName ?>
                </a>
            <? endif ?>
        </div>
    </div>
<? endforeach ?>
