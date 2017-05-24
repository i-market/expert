<?php

use App\Api;

require($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');

Api::router()->dispatch();
