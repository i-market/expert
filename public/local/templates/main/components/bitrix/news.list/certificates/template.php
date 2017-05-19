<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use App\View as v;
?>
<? foreach ($arResult['SECTIONS'] as $section): ?>
    <div class="certificates_item">
        <div class="wrap_title">
            <h4><?= $section['NAME'] ?></h4>
            <? if (!v::isEmpty($section['FILE'])): ?>
                <? $extension = v::upper($section['FILE']['EXTENSION']) ?>
                <? $downloadText = 'Скачать '.$extension.', '.$section['FILE']['HUMAN_SIZE'] ?>
                <a class="download_doc" href="<?= $section['FILE']['SRC'] ?>" target="_blank"><?= $downloadText ?></a>
            <? endif ?>
        </div>
        <div class="grid">
            <? foreach ($section['ITEMS'] as $item): ?>
                <? $file = $item['FILE'] ?>
                <div class="item col col_3">
                    <a class="gallery" href="<?= $item['PREVIEW_PICTURE']['SRC'] ?>" target="_blank">
                        <span class="wrap_img">
                            <img src="<?= $item['PREVIEW_PICTURE']['SRC'] ?>" alt="<?= $item['PREVIEW_PICTURE']['ALT'] ?>">
                        </span>
                        <span class="text"><?= $item['NAME'] ?></span>
                    </a>
                    <? $extension = v::upper($file['EXTENSION']) ?>
                    <? $downloadText = "Скачать ${extension}, ${file['HUMAN_SIZE']}" ?>
                    <a class="download_sert" href="<?= $file['SRC'] ?>" target="_blank"><?= $downloadText ?></a>
                </div>
            <? endforeach ?>
        </div>
    </div>
<? endforeach ?>
