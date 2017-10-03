<?php

use Bitrix\Main\Application;
use Phinx\Migration\AbstractMigration;
use Bitrix\Main\Config\Option;

class ExcludeFromSearch extends AbstractMigration {
    function up() {
        $conn = Application::getConnection();
        $conn->startTransaction();
        try {
            $patterns = [
                '/api/*',
                '/images/*',
                '/include/*',
                '/proposals/*',
                '/search/*',
            ];
            $curr = Option::get('search', 'exclude_mask');
            Option::set('search', 'exclude_mask', $curr.';'.join(';', $patterns));
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
