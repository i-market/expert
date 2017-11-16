<?php

namespace App;

use Bitrix\Main\Config\Configuration;
use Raven_Client;
use Core\Underscore as _;

class ExceptionHandlerLog extends \Bitrix\Main\Diag\ExceptionHandlerLog {
    /** @var Raven_Client */
    private $client = null;
    private $enabled = false;

    public function write($exception, $logType) {
        global $USER;
        if ($this->enabled && function_exists('curl_init') && $this->client !== null) {
            if (is_object($USER) && $USER->IsAuthorized()) {
                $this->client->user_context([
                    'id' => $USER->GetID(),
                    'username' => $USER->GetLogin(),
                    'email' => $USER->GetEmail()
                ]);
            }
            $this->client->captureException($exception, [
                'logType' => $logType
            ]);
        }
    }

    public function initialize(array $options) {
        $appConfig = Configuration::getValue('app');
        $this->enabled = _::get($appConfig, 'sentry.enabled', false);
        if ($this->enabled) {
            $dsn = _::get($appConfig, 'sentry.dsn');
            /** @noinspection PhpUnnecessaryFullyQualifiedNameInspection */
            $this->client = new Raven_Client($dsn, [
                'environment' => \App\App::env()
            ]);
        }
    }
}
