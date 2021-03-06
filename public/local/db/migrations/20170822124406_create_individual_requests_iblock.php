<?php

use Bitrix\Main\Application;
use Bitrix\Main\Loader;
use Phinx\Migration\AbstractMigration;

class CreateIndividualRequestsIblock extends AbstractMigration {
    static $iBlockData = [
        'NAME' => 'Заявки на выполнение отдельных видов работ',
        'CODE' => 'individual_requests',
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
            $propSpecs = [
                [
                    'CODE' => 'NAME',
                    'NAME' => 'Наименование предмета(ов) экспертизы или обследования',
                ],
                [
                    'CODE' => 'LOCATION',
                    'NAME' => 'Местонахождение объекта(ов)'
                ],
                [
                    'CODE' => 'GOAL',
                    'NAME' => 'Опишите цели проведения экспертизы или обследования',
                    'USER_TYPE' => 'HTML',
                ],
                [
                    'CODE' => 'ADDITIONAL_INFO',
                    'NAME' => 'Дополнительная информация по экспертизе или обследованию',
                    'USER_TYPE' => 'HTML',
                ],
                [
                    'CODE' => 'DOCUMENTS',
                    'NAME' => 'Наличие документов',
                    'PROPERTY_TYPE' => 'S',
                    'MULTIPLE' => 'Y',
                ],
                [
                    'CODE' => 'CONTACT_ORGANIZATION',
                    'NAME' => 'Наименование организации'
                ],
                [
                    'CODE' => 'CONTACT_PERSON',
                    'NAME' => 'Контактное лицо'
                ],
                [
                    'CODE' => 'CONTACT_PHONE_1',
                    'NAME' => 'Телефон 1'
                ],
                [
                    'CODE' => 'CONTACT_PHONE_2',
                    'NAME' => 'Телефон 2'
                ],
                [
                    'CODE' => 'CONTACT_EMAIL',
                    'NAME' => 'Электронная почта'
                ],
                [
                    'CODE' => 'FILES',
                    'NAME' => 'Файлы',
                    'PROPERTY_TYPE' => 'F',
                    'MULTIPLE' => 'Y',
                ]
            ];
            $propDefaults = [
                'ACTIVE' => 'Y',
                'IS_REQUIRED' => 'N',
                'SORT' => '100',
                'PROPERTY_TYPE' => 'S',
                'MULTIPLE' => 'N',
                'FILTRABLE' => 'Y',
                'IBLOCK_ID' => $iBlockId,
            ];
            foreach ($propSpecs as $spec) {
                $ibp = new CIBlockProperty();
                $result = $ibp->Add(array_merge($propDefaults, $spec));
                assert($result, $ibp->LAST_ERROR);
            }
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
            assert(CIBlock::Delete($iBlock['ID']), $cIBlock->LAST_ERROR);
        }
    }
}
