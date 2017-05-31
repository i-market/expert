<?
$arUrlRewrite = array(
	array(
		"CONDITION" => "#^/info-block/opinions/#",
		"RULE" => "",
		"ID" => "bitrix:news",
		"PATH" => "/info-block/opinions/index.php",
	),
	array(
		"CONDITION" => "#^/our-work/#",
		"RULE" => "",
		"ID" => "bitrix:catalog",
		"PATH" => "/our-work/index.php",
	),
	array(
		"CONDITION" => "#^/api/.*#",
		"RULE" => "",
		"ID" => "",
		"PATH" => "/api/dispatch.php",
	),
);

?>