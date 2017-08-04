<?php

use App\Api;
use App\App;
use Core\Env;

require($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');

try {
    Api::router()->dispatch();
} catch (\Exception $e) {
    CHTTP::SetStatus('500 Internal Server Error');
    // unwrap klein exception
    throw $e->getPrevious();
}
