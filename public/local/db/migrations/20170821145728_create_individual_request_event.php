<?php

use Bitrix\Main\Application;
use Phinx\Migration\AbstractMigration;

class CreateIndividualRequestEvent extends AbstractMigration {
    static $eventData = [
        'EVENT_NAME' => 'NEW_SERVICE_REQUEST_INDIVIDUAL',
        'EVENT_TITLE' => 'Новая заявка на выполнение отдельных видов работ',
        'MESSAGE_TITLE' => '#SITE_NAME#: Новая заявка на выполнение отдельных видов работ',
        'FIELDS' => [
            'NAME' => 'Наименование предмета(ов) экспертизы или обследования',
            'LOCATION' => 'Местонахождение объекта(ов)',
            'GOAL' => 'Опишите цели проведения экспертизы или обследования',
            'ADDITIONAL_INFO' => 'Дополнительная информация по экспертизе или обследованию',
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
