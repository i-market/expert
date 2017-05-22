<?
include_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/urlrewrite.php');

CHTTP::SetStatus('404 Not Found');
@define('ERROR_404', 'Y');

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

$APPLICATION->SetTitle("Ошибка 404");
$APPLICATION->SetPageProperty('NOT_SHOW_NAV_CHAIN', 'Y')
?>

<p>Страница не найдена или была удалена. Пожалуйста, проверьте URL-адрес или воспользуйтесь поиском.</p>

<? require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
