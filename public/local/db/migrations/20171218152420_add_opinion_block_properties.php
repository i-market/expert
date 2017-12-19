<?php

use Bitrix\Main\Application;
use Bitrix\Main\Loader;
use Phinx\Migration\AbstractMigration;

class AddOpinionBlockProperties extends AbstractMigration {
    function up() {
        $conn = Application::getConnection();
        $conn->startTransaction();
        try {
            Loader::includeModule('iblock');
            $iblock = CIBlock::GetList([], ['CODE' => 'opinions'])->GetNext();
            if (!$iblock) {
                throw new \Exception("can't find the iblock");
            }
            $prop = new CIBlockProperty();
            $sort = 80;
            foreach (range(20, 39) as $idx) {
                $result = $prop->Add([
                    'ACTIVE' => 'Y',
                    'IBLOCK_ID' => $iblock['ID'],
                    'NAME' => 'Текстовый блок '.($idx + 1),
                    'SORT' => $sort++,
                    'CODE' => "TEXT_BLOCK_{$idx}",
                    'MULTIPLE' => 'N',
                    'IS_REQUIRED' => 'N',
                    'SEARCHABLE' => 'N',
                    'FILTRABLE' => 'N',
                    'WITH_DESCRIPTION' => 'N',
                    'MULTIPLE_CNT' => '5',
                    'ROW_COUNT' => '1',
                    'COL_COUNT' => '30',
                    'DEFAULT_VALUE' => [
                        'TYPE' => 'html',
                        'TEXT' => '',
                    ],
                    'USER_TYPE_SETTINGS' => [
                        'height' => '200',
                    ],
                    'SECTION_PROPERTY' => 'Y',
                    'SMART_FILTER' => 'N',
                    'DISPLAY_TYPE' => 'F',
                    'DISPLAY_EXPANDED' => 'Y',
                    'USER_TYPE' => 'HTML',
                    'PROPERTY_TYPE' => 'S',
                ]);
                if (!$result) {
                    throw new \Exception($prop->LAST_ERROR);
                }
                $result = $prop->Add([
                    'ACTIVE' => 'Y',
                    'IBLOCK_ID' => $iblock['ID'],
                    'NAME' => 'Блок изображений '.($idx + 1),
                    'SORT' => $sort++,
                    'CODE' => "IMG_BLOCK_{$idx}",
                    'MULTIPLE' => 'Y',
                    'IS_REQUIRED' => 'N',
                    'SEARCHABLE' => 'N',
                    'FILTRABLE' => 'N',
                    'WITH_DESCRIPTION' => 'Y',
                    'COL_COUNT' => '30',
                    'SECTION_PROPERTY' => 'Y',
                    'PROPERTY_TYPE' => 'F',
                ]);
                if (!$result) {
                    throw new \Exception($prop->LAST_ERROR);
                }
                $result = $prop->Add([
                    'ACTIVE' => 'Y',
                    'IBLOCK_ID' => $iblock['ID'],
                    'NAME' => 'Блок со звёздочкой (описание) '.($idx + 1),
                    'SORT' => $sort++,
                    'CODE' => "STAR_BLOCK_DESCR_{$idx}",
                    'MULTIPLE' => 'N',
                    'IS_REQUIRED' => 'N',
                    'SEARCHABLE' => 'N',
                    'FILTRABLE' => 'N',
                    'WITH_DESCRIPTION' => 'N',
                    'MULTIPLE_CNT' => '5',
                    'ROW_COUNT' => '1',
                    'COL_COUNT' => '30',
                    'SECTION_PROPERTY' => 'Y',
                    'SMART_FILTER' => 'N',
                    'DISPLAY_TYPE' => 'F',
                    'DISPLAY_EXPANDED' => 'Y',
                    'PROPERTY_TYPE' => 'S',
                ]);
                if (!$result) {
                    throw new \Exception($prop->LAST_ERROR);
                }
                $result = $prop->Add([
                    'ACTIVE' => 'Y',
                    'IBLOCK_ID' => $iblock['ID'],
                    'NAME' => 'Блок со звёздочкой (Ссылка на раздел) '.($idx + 1),
                    'SORT' => $sort++,
                    'CODE' => "STAR_BLOCK_LINK_{$idx}",
                    'MULTIPLE' => 'N',
                    'IS_REQUIRED' => 'N',
                    'SEARCHABLE' => 'N',
                    'FILTRABLE' => 'N',
                    'WITH_DESCRIPTION' => 'N',
                    'MULTIPLE_CNT' => '5',
                    'ROW_COUNT' => '1',
                    'COL_COUNT' => '30',
                    'DEFAULT_VALUE' => [
                        'TYPE' => 'html',
                        'TEXT' => '',
                    ],
                    'USER_TYPE_SETTINGS' => [
                        'height' => '200',
                    ],
                    'SECTION_PROPERTY' => 'Y',
                    'SMART_FILTER' => 'N',
                    'DISPLAY_TYPE' => 'F',
                    'DISPLAY_EXPANDED' => 'Y',
                    'USER_TYPE' => 'HTML',
                    'PROPERTY_TYPE' => 'S',
                ]);
                if (!$result) {
                    throw new \Exception($prop->LAST_ERROR);
                }
            }
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
