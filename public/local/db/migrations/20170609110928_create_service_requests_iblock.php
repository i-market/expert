<?php

use Bitrix\Main\Application;
use Bitrix\Main\Loader;
use Phinx\Migration\AbstractMigration;

class CreateServiceRequestsIblock extends AbstractMigration {
    static $iBlockData = [
        'NAME' => 'Заявки',
        'CODE' => 'service_requests',
        'TYPE' => 'inbox',
    ];

    function up() {
        // TODO set appropriate indexing options
        $conn = Application::getConnection();
        $conn->startTransaction();
        try {
            Loader::includeModule('iblock');
            $cIBlock = new CIBlock();
            $dbIBlock = $cIBlock->GetList(
                [],
                ['CODE' => static::$iBlockData['CODE']]
            );
            if ($dbIBlock->Fetch()) {
                return;
            }
            $iBlockId = $cIBlock->Add([
                'NAME' => static::$iBlockData['NAME'],
                'CODE' => static::$iBlockData['CODE'],
                'IBLOCK_TYPE_ID' => static::$iBlockData['TYPE'],
                'VERSION' => 1,
                'SITE_ID' => ['s1']
            ]);
            if (false === $iBlockId) {
                throw new Exception($cIBlock->LAST_ERROR);
            }
            $ibp = new CIBlockProperty();
            $result = $ibp->Add([
                'NAME' => 'Файлы',
                'ACTIVE' => 'Y',
                'IS_REQUIRED' => 'N',
                'SORT' => '100',
                'CODE' => 'FILES',
                'PROPERTY_TYPE' => 'F',
                'MULTIPLE' => 'Y',
                'FILTRABLE' => 'Y',
                'IBLOCK_ID' => $iBlockId,
            ]);
            assert($result, $ibp->LAST_ERROR);
            $conn->commitTransaction();
        } catch (Exception $e) {
            $conn->rollbackTransaction();
            throw $e;
        }
    }

    function down() {
        Loader::includeModule('iblock');
        $cIBlock = new CIBlock();
        $dbIBlock = $cIBlock->GetList(
            [],
            ['CODE' => static::$iBlockData['CODE']]
        );
        if ($iBlock = $dbIBlock->Fetch()) {
            CIBlock::Delete($iBlock['ID']);
        }
    }
}
