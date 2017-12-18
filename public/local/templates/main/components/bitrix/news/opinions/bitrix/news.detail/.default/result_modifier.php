<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

global $APPLICATION;

$cp = $this -> __component; // объект компонента

if (!is_object($cp)) {
    die();
}

/*получаем данные о изображениях*/
/*собираем IDs изображений*/
$imgIdindex = 0;
$resArImgIds = Array();

for ($imgIndex = 0; $imgIndex < 20; $imgIndex++) {

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