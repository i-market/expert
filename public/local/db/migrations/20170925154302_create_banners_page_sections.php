<?php

use App\Iblock;
use Bex\Tools\Iblock\IblockTools;
use Bitrix\Main\Application;
use Phinx\Migration\AbstractMigration;

class CreateBannersPageSections extends AbstractMigration {
    function up() {
        $conn = Application::getConnection();
        $conn->startTransaction();
        try {
            $pageSections = [
                ['about', 'О компании'],
                ['what-we-do', 'Наша деятельность'],
                ['our-work', 'Примеры работ'],
                ['certificates', 'Аттестаты и допуски СРО'],
                ['equipment', 'Техническая база'],
                ['info-block', 'Инфоблок'],
                ['contact', 'Контакты'],
            ];
            $layoutSections = [
                ['top', 'Верх страницы'],
                ['bottom', 'Низ страницы']
            ];
            $iblockId = IblockTools::find(Iblock::CONTENT_TYPE, Iblock::BANNERS)->id();
            foreach ($pageSections as list($_code, $_name)) {
                $pageSect = new CIBlockSection();
                $parentId = $pageSect->Add([
                    'IBLOCK_ID' => $iblockId,
                    'CODE' => $_code,
                    'NAME' => $_name,
                ]);
                if (!is_numeric($parentId)) throw new Exception($parentId);
                foreach ($layoutSections as list($code, $name)) {
                    $sec = new CIBlockSection();
                    $sectionId = $sec->Add([
                        'IBLOCK_ID' => $iblockId,
                        'CODE' => $code,
                        'NAME' => $name,
                        'IBLOCK_SECTION_ID' => $parentId
                    ]);
                    if (!is_numeric($sectionId)) throw new Exception($sectionId);
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
