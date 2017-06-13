<?php

use Bitrix\Main\Loader;
use Bitrix\Main\Application;
use Phinx\Migration\AbstractMigration;

class CreateInboxIblockType extends AbstractMigration {
    function up() {
        $conn = Application::getConnection();
        $conn->startTransaction();
        try {
            Loader::includeModule('iblock');
            $ibType = new CIBlockType();
            $fields = array (
                'ID' => 'inbox',
                'EDIT_FILE_BEFORE' => '',
                'EDIT_FILE_AFTER' => '',
                'IN_RSS' => NULL,
                'SECTIONS' => 'Y',
                'SORT' => '500',
                'LANG' =>
                    array (
                        'ru' =>
                            array (
                                'NAME' => 'Входящие',
                                'SECTION_NAME' => '',
                                'ELEMENT_NAME' => '',
                            ),
                        'en' =>
                            array (
                                'NAME' => 'Inbox',
                                'SECTION_NAME' => '',
                                'ELEMENT_NAME' => '',
                            ),
                    ),
            );
            $result = $ibType->Add($fields);
            assert($result, $ibType->LAST_ERROR);
            $conn->commitTransaction();
        } catch (Exception $e) {
            $conn->rollbackTransaction();
            throw $e;
        }
    }

    function down() {
        // TODO implement down migration
    }
}
