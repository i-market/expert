<?php

use Bitrix\Main\Application;
use Phinx\Migration\AbstractMigration;

class CreateOversightRequestEvent extends AbstractMigration {
    static $eventData = [
        'EVENT_NAME' => 'NEW_SERVICE_REQUEST_OVERSIGHT',
        'EVENT_TITLE' => 'Новая заявка на проведение технического надзора, строительного контроля',
        'MESSAGE_TITLE' => '#SITE_NAME#: Новая заявка на проведение технического надзора, строительного контроля',
        'FIELDS' => [
            'NAME' => 'Наименование предмета(ов)',
            'LOCATION' => 'Местонахождение',
            'DESCRIPTION' => 'Описание объекта(ов) технического надзора',
            'DOCUMENTS' => 'Наличие документов',
            'CONTACT_ORGANIZATION' => 'Наименование организации',
            'CONTACT_PERSON' => 'Контактное лицо',
            'CONTACT_PHONE_1' => 'Телефон 1',
            'CONTACT_PHONE_2' => 'Телефон 2',
            'CONTACT_EMAIL' => 'Электронная почта',
            'FILE_LINKS' => 'Прикрепленные файлы'
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
            return $key === 'FILE_LINKS'
                ? "#{$key}#" // file links are optional
                : "{$label}:\n#{$key}#";
        }, array_keys(static::$eventData['FIELDS'])));
        CEventType::Add([
            'EVENT_NAME' => static::$eventData['EVENT_NAME'],
            'NAME' => static::$eventData['EVENT_TITLE'],
            'LID' => 'ru',
            'DESCRIPTION' => $description,
        ]);
        $cEventMessage = new CEventMessage();
        $addResult = $cEventMessage->Add(
            [
                'ACTIVE' => 'Y',
                'EVENT_NAME' => static::$eventData['EVENT_NAME'],
                'LID' => ['s1'],
                'EMAIL_FROM' => '#DEFAULT_EMAIL_FROM#',
                'EMAIL_TO' => '#EMAIL#',
                'BCC' => '',
                'SUBJECT' => static::$eventData['MESSAGE_TITLE'],
                'BODY_TYPE' => 'text',
                'MESSAGE' => $message,
            ]
        );
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
