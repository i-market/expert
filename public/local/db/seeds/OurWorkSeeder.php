<?php

use App\Iblock;
use Bex\Tools\Iblock\IblockTools;
use Bitrix\Main\Application;
use Bitrix\Main\Loader;
use Phinx\Seed\AbstractSeed;
use Core\Util;
use Core\Strings as str;
use Core\Underscore as _;

class OurWorkSeeder extends AbstractSeed {
    function run() {
        $conn = Application::getConnection();
        $conn->startTransaction();
        try {
            Loader::includeModule('iblock');
            $iblockId = IblockTools::find(Iblock::CONTENT_TYPE, Iblock::OUR_WORK)->id();
            $addSection = function($name, $idx, $parentId = null) use ($iblockId) {
                $fields = [
                    'IBLOCK_ID' => $iblockId,
                    'NAME' => $name,
                    'CODE' => CUtil::translit($name, 'ru', [
                        'replace_space' => '-',
                        'replace_other' => '-'
                    ]),
                    'SORT' => $idx * 10
                ];
                if ($parentId !== null) {
                    $fields['IBLOCK_SECTION_ID'] = $parentId;
                }
                $section = new CIBlockSection();
                $result = $section->Add($fields);
                assert($result, $section->LAST_ERROR);
                return $result;
            };
            // TODO refactor
            $leafSectionIdsRef = [];
            $addSectionRec = function($section, $idx, $parentId = null) use (&$addSectionRec, $addSection, &$leafSectionIdsRef) {
                $sectionId = $addSection($section['name'], $idx, $parentId);
                foreach ($section['sections'] as $i => $s) {
                    $addSectionRec($s, $i, $sectionId);
                }
                if (count($section['sections']) === 0) {
                    $leafSectionIdsRef[] = $sectionId;
                }
            };
            $path = Util::joinPath([__DIR__, 'our_work.json']);
            $tree = json_decode(file_get_contents($path), true);
            $xformName = function($s) {
                return str::capitalize($s);
            };
            $f = function($v, $k) use (&$f, $xformName) {
                if (is_string($v)) {
                    return ['name' => $xformName($v), 'sections' => []];
                } else {
                    assert(is_array($v));
                    return ['name' => $xformName($k), 'sections' => array_values(_::map($v, $f))];
                }
            };
            $exclude = 'Примеры технических отчетов по результатам проведенных обследований конструкций, помещений, зданий, сооружений, инженерных сетей и оборудования';
            $sections = array_values(_::remove(_::map($tree, $f), $exclude));
            foreach ($sections as $idx => $section) {
                $addSectionRec($section, $idx);
            }
//            $text = 'Равным образом рамки и место обучения кадров влечет за собой процесс внедрения и модернизации новых предложений. Повседневная практика показывает.';
//            $imgPath = $_SERVER['DOCUMENT_ROOT'].'/local/mockup/images/pic_10.jpg';
//            assert(file_exists($imgPath), "{$imgPath} doesn't exist");
//            $el = new CIBlockElement();
//            $elementId = $el->Add([
//                'IBLOCK_ID' => $iblockId,
//                'NAME' => 'Пример',
//                'PREVIEW_TEXT' => $text,
//                'DETAIL_PICTURE' => CFile::MakeFileArray($imgPath)
//            ]);
//            assert($el->SetElementSection($elementId, $leafSectionIdsRef), $el->LAST_ERROR);
            $conn->commitTransaction();
        } catch (Exception $e) {
            $conn->rollbackTransaction();
            throw $e;
        }
    }
}
