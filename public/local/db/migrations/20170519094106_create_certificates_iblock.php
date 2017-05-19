<?php

use Bitrix\Main\Application;
use Bitrix\Main\Loader;
use Phinx\Migration\AbstractMigration;

class CreateCertificatesIblock extends AbstractMigration {
    static $iBlockData = [
        'NAME' => 'Аттестаты и допуски СРО',
        'CODE' => 'certificates',
        'TYPE' => 'content',
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
                'SITE_ID' => ['s1'],
                'GROUP_ID' => ['2' => 'R'],
            ]);
            if (false === $iBlockId) {
                throw new Exception($cIBlock->LAST_ERROR);
            }
            $fields = CIBlock::GetFields($iBlockId);
            $fields['PREVIEW_PICTURE']['IS_REQUIRED'] = 'Y';
            CIBlock::SetFields($iBlockId, $fields);
            $ibp = new CIBlockProperty();
            $result = $ibp->Add([
                'NAME' => 'Файл',
                'ACTIVE' => 'Y',
                'IS_REQUIRED' => 'Y',
                'SORT' => '100',
                'CODE' => 'FILE',
                'PROPERTY_TYPE' => 'F',
                'FILTRABLE' => 'N',
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
