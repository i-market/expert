<?php

use Bitrix\Main\Application;
use Phinx\Migration\AbstractMigration;

class CreateProposalsTable extends AbstractMigration {
    function change() {
        $table = $this->table('proposals');
        $table
            ->addColumn('type',    'string', ['limit' => 100])
            ->addColumn('email',   'string', ['limit' => 100])
            ->addColumn('created', 'datetime')
            ->save();
    }
}
