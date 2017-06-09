<?php

use App\Api;

require($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');

try {
    Api::router()->dispatch();
} catch (\Exception $e) {
    CHTTP::SetStatus('500 Internal Server Error');
}
