<?php

namespace App;

use Bitrix\Main\Config\Configuration;
use Raven_Client;
use Core\Underscore as _;

class ExceptionHandlerLog extends \Bitrix\Main\Diag\ExceptionHandlerLog {
    private $client = null;

    public function write($exception, $logType) {
        global $USER;
        if (function_exists('curl_init')) {
            if (is_object($USER)) {
                $this->client->user_context(array(
                    'id' => $USER->GetID(),
                    'username' => $USER->GetLogin(),
                    'email' => $USER->GetEmail()
                ));
            }
            $this->client->captureException($exception, array(
                'logType' => $logType
            ));
        }
    }

    public function initialize(array $options) {
        $dsn = _::get(Configuration::getValue('app'), 'sentry.dsn');
        /** @noinspection PhpUnnecessaryFullyQualifiedNameInspection */
        $this->client = new Raven_Client($dsn, array(
            'environment' => \App\App::env()
        ));
    }
}
