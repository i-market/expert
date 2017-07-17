<?php

use Bitrix\Main\Application;
use Phinx\Migration\AbstractMigration;

class CreateProposalEvent extends AbstractMigration {
    static $eventData = [
        'EVENT_NAME' => 'PROPOSAL',
        'EVENT_TITLE' => 'Коммерческое предложение',
        'MESSAGE_TITLE' => '#SITE_NAME#: Коммерческое предложение',
        'FIELDS' => [
        ]
    ];

    function up() {
        $connection = Application::getConnection();
        $connection->startTransaction();
        $description = join("\n", array_map(function($key) {
            $label = static::$eventData['FIELDS'][$key];
            return "#{$key}# - {$label}";
        }, array_keys(static::$eventData['FIELDS'])));
        $message = '';
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
                'EMAIL_TO' => '#EMAIL_TO#',
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
