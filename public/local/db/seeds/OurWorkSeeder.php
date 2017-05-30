<?php

use App\Iblock;
use Bex\Tools\Iblock\IblockTools;
use Bitrix\Main\Application;
use Bitrix\Main\Loader;
use Phinx\Seed\AbstractSeed;

class OurWorkSeeder extends AbstractSeed {
    function run() {
        $conn = Application::getConnection();
        $conn->startTransaction();
        try {
            Loader::includeModule('iblock');
            $text = 'Равным образом рамки и место обучения кадров влечет за собой процесс внедрения и модернизации новых предложений. Повседневная практика показывает.';
            $imgPath = $_SERVER['DOCUMENT_ROOT'].'/local/mockup/images/pic_10.jpg';
            assert(file_exists($imgPath), "{$imgPath} doesn't exist");
            $iblockId = IblockTools::find(Iblock::CONTENT_TYPE, Iblock::OUR_WORK)->id();
            $addSection = function($name, $parentId = null) use ($iblockId) {
                $fields = [
                    'IBLOCK_ID' => $iblockId,
                    'NAME' => $name,
                    'CODE' => CUtil::translit($name, 'ru', [
                        'replace_space' => '-',
                        'replace_other' => '-'
                    ])
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
            $addSectionRec = function($_section, $parentId = null) use (&$addSectionRec, $addSection, &$leafSectionIdsRef) {
                // string as a shorthand for section
                $section = is_string($_section)
                    ? ['name' => $_section, 'sections' => []]
                    : $_section;
                $sectionId = $addSection($section['name'], $parentId);
                foreach ($section['sections'] as $s) {
                    $addSectionRec($s, $sectionId);
                }
                if (count($section['sections']) === 0) {
                    $leafSectionIdsRef[] = $sectionId;
                }
            };
            // TODO good url slugs (codes)
            $sections = [
                // design
                [
                    'name' => 'Примеры разработанных проектных решений',
                    'sections' => [
                        'Фундаменты',
                        'Плиты перекрытия'
                    ]
                ],
                // monitoring
                [
                    'name' => 'Примеры отчетов и заключений по результатам проведенного мониторинга',
                    'sections' => [
                        [
                            'name' => 'Сортировка по целям мониторинга',
                            'sections' => [
                                'Реконструкция или капитальный ремонт',
                                'Оценка возможности дальнейшей безаварийной эксплуатации, необходимости восстановления, усиления и пр.'
                            ]
                        ],
                        [
                            'name' => 'Сортировка по назначению объектов мониторинга',
                            'sections' => [
                                [
                                    'name' => 'Помещения, здания',
                                    'sections' => [
                                        'Одноквартирные жилые здания',
                                        'Многоквартирные жилые здания'
                                    ]
                                ],
                                [
                                    'name' => 'Сооружения',
                                    'sections' => [
                                        'Подземные гаражи и стоянки',
                                        'Бомбоубежища'
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ];
            foreach ($sections as $section) {
                $addSectionRec($section);
            }
            $el = new CIBlockElement();
            $elementId = $el->Add([
                'IBLOCK_ID' => $iblockId,
                'NAME' => 'Пример',
                'PREVIEW_TEXT' => $text,
                'DETAIL_PICTURE' => CFile::MakeFileArray($imgPath)
            ]);
            assert($el->SetElementSection($elementId, $leafSectionIdsRef), $el->LAST_ERROR);
            $conn->commitTransaction();
        } catch (Exception $e) {
            $conn->rollbackTransaction();
            throw $e;
        }
    }
}
