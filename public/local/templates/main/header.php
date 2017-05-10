<?php

use App\App;
use Bitrix\Main\Page\Asset;

$assets = App::assets();
$asset = Asset::getInstance();
$asset->setJsToBody(true);
if (App::useBitrixAsset()) {
    foreach ($assets['styles'] as $path) {
        $asset->addCss($path);
    }
    foreach ($assets['scripts'] as $path) {
        $asset->addJs($path);
    }
}
?>
<!doctype html>
<html lang="<?= LANGUAGE_ID ?>">
<head>
    <? $APPLICATION->ShowHead() ?>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="format-detection" content="telephone=no" />
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
    <title><? $APPLICATION->ShowTitle() ?></title>
    <? if (!App::useBitrixAsset()): ?>
        <? foreach ($assets['styles'] as $path): ?>
            <link rel="stylesheet" media="screen" href="<?= $path ?>">
        <? endforeach ?>
    <? endif ?>
    <!--[if gte IE 9]>
    <style type="text/css">
        .gradient {
            filter: none;
        }
    </style>
    <![endif]-->
</head>
<body>
<? $APPLICATION->ShowPanel() ?>
