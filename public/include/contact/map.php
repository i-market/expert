<?$APPLICATION->IncludeComponent("bitrix:map.yandex.view", "template1", Array(
	"CONTROLS" => array(	// Элементы управления
			0 => "ZOOM",
		),
		"INIT_MAP_TYPE" => "MAP",	// Стартовый тип карты
		"MAP_DATA" => "a:4:{s:10:\"yandex_lat\";d:55.75143431453071;s:10:\"yandex_lon\";d:37.780148896669985;s:12:\"yandex_scale\";i:15;s:10:\"PLACEMARKS\";a:1:{i:0;a:3:{s:3:\"LON\";d:37.771758946872;s:3:\"LAT\";d:55.75256581792;s:4:\"TEXT\";s:45:\"ООО \"ТехСтройЭкспертиза\"\";}}}",	// Данные, выводимые на карте
		"MAP_HEIGHT" => "480px",	// Высота карты
		"MAP_ID" => "contact_main",	// Идентификатор карты
		"MAP_WIDTH" => "100%",	// Ширина карты
		"OPTIONS" => array(	// Настройки
			0 => "ENABLE_DBLCLICK_ZOOM",
			1 => "ENABLE_DRAGGING",
		),
		"COMPONENT_TEMPLATE" => ".default"
	),
	false,
	array(
	"ACTIVE_COMPONENT" => "Y"
	)
);?>