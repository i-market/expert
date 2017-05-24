<?php

use App\Iblock;
use Bex\Tools\Iblock\IblockTools;
use Bitrix\Main\Application;
use Bitrix\Main\Loader;
use Phinx\Seed\AbstractSeed;

class ServicesSeeder extends AbstractSeed {
    function run() {
        $items = [
            [
                'name' => 'Обследование конструкций, помещений, зданий, сооружений, инженерных сетей и оборудования.',
                'id' => 'inspection'
            ],
            [
                'name' => 'Строительно-техническая экспертиза конструкций, помещений, зданий, сооружений, помещений, инженерных сетей и оборудования. Судебная экспертиза.',
                'id' => 'examination'
            ],
            [
                'name' => 'Выполнение отдельных видов работ по экспертизе и обследованию. Экспертиза отдельных материалов, деталей, изделий, узлов, конструкций, элементов конструкций и пр.',
                'id' => 'individual'
            ],
            [
                'name' => 'Мониторинг технического состояния зданий и сооружений',
                'id' => 'monitoring'
            ],
            [
                'name' => 'Разработка проектных решений',
                'id' => 'design'
            ],
            [
                'name' => 'Технический надзор. Строительный контроль',
                'id' => 'oversight'
            ]
        ];
        $conn = Application::getConnection();
        $conn->startTransaction();
        try {
            assert(Loader::includeModule('iblock'));
            $iblockId = IblockTools::find(Iblock::SERVICES_TYPE, Iblock::SERVICES)->id();
            assert(is_numeric($iblockId));
            foreach ($items as $idx => $item) {
                $el = new CIBlockElement();
                $fields = [
                    'IBLOCK_ID' => $iblockId,
                    'NAME' => $item['name'],
                    'CODE' => $item['id'],
                    'SORT' => 400 + ($idx + 1) * 10
                ];
                $result = $el->Add($fields);
                assert($result, $el->LAST_ERROR);
            }
            $conn->commitTransaction();
        } catch (Exception $e) {
            $conn->rollbackTransaction();
            throw $e;
        }
    }
}
