<?php

namespace App;

use Klein\Klein;
use App\View as v;

class Api {
    static private function renderForm($templateName, $context) {
        // TODO refactor: this was supposed to be done using template inheritance,
        // but plate's `section` function causes a decoding error for some reason (gzip enabled)
        $inputs = v::render($templateName, $context);
        return v::render('partials/service_forms/form', array_merge(['inputs' => $inputs], $context));
    }

    static function router() {
        $router = new Klein();
        $router->with('/api', function () use ($router) {
            $router->respond('POST', '/services/monitoring', function($request, $response) {
                // TODO sanitize params
                $params = $request->params();
//                $state = Services::requestMonitoring();
                return self::renderForm('partials/service_forms/monitoring_form', [
                    'service' => Services::services()['monitoring']
                ]);
            });
        });
        return $router;
    }
}