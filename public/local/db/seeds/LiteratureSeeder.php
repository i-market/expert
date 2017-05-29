<?php

use App\Iblock;
use Bex\Tools\Iblock\IblockTools;
use Bitrix\Main\Application;
use Bitrix\Main\Loader;
use Phinx\Seed\AbstractSeed;

class LiteratureSeeder extends AbstractSeed {
    function run() {
        $author = 'В.В. Маркелов';
        $desc = 'В этом сундуке предостаточно мертвецов, но все же их гораздо меньше, чем могло бы быть. Освободившееся место заняли безумные ветры, заморские шаманы, страшные куманские ножи-медорубы и даже математические формулы, не к ночи будь помянуты. А что вместо бутылки рома у нас там бутылка осского аша – ну так всегда приходится делать поправку на культурный контекст.';
        $imgPath = $_SERVER['DOCUMENT_ROOT'].'/local/mockup/images/book.png';
        $conn = Application::getConnection();
        $conn->startTransaction();
        try {
            Loader::includeModule('iblock');
            $iblockId = IblockTools::find(Iblock::CONTENT_TYPE, Iblock::LITERATURE)->id();
            foreach (range(1, 10) as $num) {
                $el = new CIBlockElement();
                $result = $el->Add([
                    'IBLOCK_ID' => $iblockId,
                    'NAME' => 'Пример '.$num,
                    'SORT' => 400 + $num * 10,
                    'PREVIEW_TEXT' => $desc,
                    'PREVIEW_PICTURE' => CFile::MakeFileArray($imgPath),
                    'PROPERTY_VALUES' => [
                        'AUTHOR' => $author
                    ]
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
