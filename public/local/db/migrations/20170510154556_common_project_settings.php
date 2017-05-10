<?php

use Bitrix\Main\Config\Option;
use Phinx\Migration\AbstractMigration;

class CommonProjectSettings extends AbstractMigration {
    function up() {
        $siteId = 's1';
        $name = 'Техническая строительная экспертиза';
        $template = 'main';
        $fields = [
            'NAME' => $name,
            'TEMPLATE' => [
                [
                    'TEMPLATE' => $template,
                    'SORT' => '1',
                    'CONDITION' => null,
                ]
            ]
        ];
        assert((new CSite)->Update($siteId, $fields));
        Option::set('main', 'save_original_file_name', 'Y');
        Option::set('main', 'translit_original_file_name', 'Y');
    }
}
