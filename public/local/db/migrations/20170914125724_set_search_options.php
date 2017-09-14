<?php

use Bitrix\Main\Application;
use Bitrix\Main\Config\Option;
use Phinx\Migration\AbstractMigration;

class SetSearchOptions extends AbstractMigration {
    function up() {
        $conn = Application::getConnection();
        $conn->startTransaction();
        try {
            $curr = Option::get('search', 'exclude_mask');
            Option::set('search', 'exclude_mask', $curr.';/local/*');
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
