<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use App\View as v;
?>
<? if (!v::isEmpty($arResult)): ?>
    <section class="download_document">
        <div class="wrap">
            <div class="grid">
                <? foreach ($arResult['FILES'] as $file): ?>
                    <a href="<?= $file['SRC'] ?>" target="_blank" class="col col_2 item">
                        <div class="left">
                            <img src="/images/file-icon.svg.php?extension=<?= $file['EXTENSION'] ?>">
                        </div>
                        <div class="right">
                            <? $extension = v::upper($file['EXTENSION']) ?>
                            <p class="text"><?= "Скачать {$extension}, {$file['HUMAN_SIZE']}" ?></p>
                            <p class="title"><?= $file['NAME'] ?></p>
                        </div>
                    </a>
                <? endforeach ?>
            </div>
        </div>
    </section>
<? endif ?>