<?php

use Phinx\Migration\AbstractMigration;

class DropProposalsTable extends AbstractMigration {
    function up() {
        $this->dropTable('proposals');
    }
}
