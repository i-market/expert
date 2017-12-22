<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

global $APPLICATION;

$cp = $this -> __component; // объект компонента

if (!is_object($cp)) {
    die();
}

$arResult['BLOCK_COUNT'] = 40;

/*получаем данные о изображениях*/
/*собираем IDs изображений*/
$imgIdindex = 0;
$resArImgIds = Array();

for ($imgIndex = 0; $imgIndex < $arResult['BLOCK_COUNT']; $imgIndex++) {

    if (!empty($arResult['DISPLAY_PROPERTIES']['IMG_BLOCK_' . $imgIndex]['VALUE'])) {

        $arImgIDs = $arResult['DISPLAY_PROPERTIES']['IMG_BLOCK_' . $imgIndex]['VALUE'];

        foreach ($arImgIDs as $imgIdValue) {
            $resArImgIds [$imgIdindex] = $imgIdValue;
            $imgIdindex++;
        }
    }
}

$arOrder = array(
    'SORT' => 'ASC'
);

$arFilter = array(
    '@ID' => $resArImgIds
);

/*Получаем картинки из БД*/
$res = CFile::GetList($arOrder, $arFilter);

$i = 0;
/*Добавляем результат в $arResult (по ключам - ID)*/
while ($arImgItem = $res -> GetNext()) {
    $arResult['RES_AR_IMG'][$arImgItem['ID']] = $arImgItem;
    $i++;
}