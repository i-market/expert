<?php

use Core\Util;

$files = Util::ensureList($arResult['DISPLAY_PROPERTIES']['FILES']['FILE_VALUE']);
$arResult['FILES'] = array_map(function($file) {
    list($name, $ext) = Util::splitFileExtension($file['ORIGINAL_NAME']);
    $absPath = Util::joinPath([$_SERVER['DOCUMENT_ROOT'], $file['SRC']]);
    return array_merge($file, [
        'NAME' => $name,
        'EXTENSION' => $ext,
        'HUMAN_SIZE' => Util::humanFileSize(filesize($absPath))
    ]);
}, $files);