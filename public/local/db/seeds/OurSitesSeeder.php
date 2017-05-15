<?php

use App\Iblock;
use Bex\Tools\Iblock\IblockTools;
use Bitrix\Main\Application;
use Bitrix\Main\Loader;
use Phinx\Seed\AbstractSeed;

class OurSitesSeeder extends AbstractSeed {
    function run() {
        $items = json_decode(file_get_contents(dirname(__FILE__).'/our_sites.json'), true);
        $conn = Application::getConnection();
        $conn->startTransaction();
        try {
            assert(Loader::includeModule('iblock'));
            $iblockId = IblockTools::find(Iblock::CONTENT_TYPE, Iblock::OUR_SITES)->id();
            assert(is_numeric($iblockId));
            foreach ($items as $idx => $item) {
                $imgPath = $_SERVER['DOCUMENT_ROOT'].'/local/mockup/'.$item['img'];
                assert(file_exists($imgPath));
                $el = new CIBlockElement();
                $result = $el->Add([
                    'IBLOCK_ID' => $iblockId,
                    'NAME' => $item['text'],
                    'SORT' => 400 + ($idx + 1) * 10,
                    'PREVIEW_TEXT' => $item['text'],
                    'PREVIEW_PICTURE' => CFile::MakeFileArray($imgPath)
                ]);
                assert($result, $el->LAST_ERROR);
            }
            $conn->commitTransaction();
        } catch (Exception $e) {
            $conn->rollbackTransaction();
            throw $e;
        }
    }
}
