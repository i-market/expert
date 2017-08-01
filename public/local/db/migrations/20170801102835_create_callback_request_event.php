<?php

use Bitrix\Main\Application;
use Phinx\Migration\AbstractMigration;

class CreateCallbackRequestEvent extends AbstractMigration {
    static $eventData = [
        'EVENT_NAME' => 'CALLBACK_REQUEST',
        'EVENT_TITLE' => 'Заявка на обратный звонок',
        'MESSAGE_TITLE' => '#SITE_NAME#: Заявка на обратный звонок',
        'FIELDS' => [
            'CONTACT_PERSON' => 'Контактное лицо',
            'PHONE' => 'Номер телефона'
        ]
    ];

    function up() {
        $connection = Application::getConnection();
        $connection->startTransaction();
        $description = join("\n", array_map(function($key) {
            $label = static::$eventData['FIELDS'][$key];
            return "#{$key}# - {$label}";
        }, array_keys(static::$eventData['FIELDS'])));
        $message = join("\n\n", array_map(function($key) {
            $label = static::$eventData['FIELDS'][$key];
            return "{$label}:\n#{$key}#";
        }, array_keys(static::$eventData['FIELDS'])));
        CEventType::Add([
            'EVENT_NAME' => static::$eventData['EVENT_NAME'],
            'NAME' => static::$eventData['EVENT_TITLE'],
            'LID' => 'ru',
            'DESCRIPTION' => $description,
        ]);
        $cEventMessage = new CEventMessage();
        $addResult = $cEventMessage->Add([
            'ACTIVE' => 'Y',
            'EVENT_NAME' => static::$eventData['EVENT_NAME'],
            'LID' => ['s1'],
            'EMAIL_FROM' => '#DEFAULT_EMAIL_FROM#',
            'EMAIL_TO' => '#EMAIL_TO#',
            'BCC' => '',
            'SUBJECT' => static::$eventData['MESSAGE_TITLE'],
            'BODY_TYPE' => 'text',
            'MESSAGE' => $message,
        ]);
        if (false === $addResult) {
            $connection->rollbackTransaction();
            throw new Exception('Failed to add EventMessage: '.$cEventMessage->LAST_ERROR);
        }
        $connection->commitTransaction();
    }

    function down() {
        $connection = Application::getConnection();
        $connection->startTransaction();
        CEventType::Delete(static::$eventData['EVENT_NAME']);
        $dbEventMessages = \Bitrix\Main\Mail\Internal\EventMessageTable::getList([
            'filter' => ['EVENT_NAME' => static::$eventData['EVENT_NAME']],
            'select' => ['ID'],
        ]);
        while ($eventMessage = $dbEventMessages->fetch()) {
            if (!CEventMessage::Delete(intval($eventMessage['ID']))) {
                $connection->rollbackTransaction();
                throw new Exception('Failed to delete EventMessage');
            }
        }
        $connection->commitTransaction();
    }
}
