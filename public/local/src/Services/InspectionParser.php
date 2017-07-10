<?php

namespace App\Services;

use Core\Strings as str;
use Core\Underscore as _;
use Core\Nullable as nil;
use PhpOffice\PhpSpreadsheet\Worksheet;

class InspectionParser extends Parser {
    public $spec = [
        'worksheets' => [
            [
                'key' => 'SINGLE_BUILDING',
                'name' => 'Обследование одного здания'
            ],
            [
                'key' => 'MULTIPLE_BUILDINGS',
                'name' => 'Обследование нескольких зданий'
            ]
        ],
        'sections' => [
//            'Описание объекта(ов) мониторинга',
            [
                'key' => 'SITE_COUNT',
                'prefix' => 'Количество зданий сооружений, строений (шт.)'
            ],
            [
                'key' => 'LOCATION',
                'prefix' => 'Местонахождение'
            ],
//            'Адрес',
            [
                'key' => 'USED_FOR',
                'prefix' => [
                    'Назначение объекта обследования',
                    'Назначение объектов обследование'
                ],
            ],
//            'Общая площадь объекта (кв.м.)',
            [
                'key' => 'VOLUME',
                'prefix' => [
                    'Строительный объем объекта (куб. м.)',
                    'Строительный объем объектов (куб. м.)'
                ],
            ],
            [
                'key' => 'FLOORS',
                'prefix' => 'Количество надземных этажей',
            ],
            [
                'key' => 'HAS_UNDERGROUND_FLOORS',
                'prefix' => [
                    'Наличие технического подполья, подвала, подземных этажей',
                    'Наличие технических подпольев, подвалов, подземных этажей'
                ],
            ],
            [
                'key' => 'UNDERGROUND_FLOORS',
                'prefix' => 'Количество подземных этажей',
            ],
            [
                'key' => 'INSPECTION_GOAL',
                'prefix' => 'Цели обследования',
            ],
            [
                'key' => 'STRUCTURES_TO_INSPECT',
                'prefix' => 'Конструкции подлежащие обследованию',
            ],
            [
                'key' => 'DISTANCE_BETWEEN_SITES',
                'prefix' => 'Удаленность объектов друг от друга'
            ],
            [
                'key' => 'TRANSPORT_ACCESSIBILITY',
                'prefix' => 'Транспортная доуступность',
            ],
            [
                'key' => 'DOCUMENTS',
                'prefix' => 'Наличие документов',
            ]
        ]
    ];

    function parseStructuresToInspect($rows) {
        // TODO extract
        $conditional = 'ВЫБОРОЧНОЕ ОБСЛЕДОВАНИЕ';
        $rename = [
            'КОМПЛЕКСНОЕ ОБСЛЕДОВАНИЕ' => 'PACKAGE',
            $conditional => 'INDIVIDUAL'
        ];
        return $this->parseStructures($rows, $rename, $conditional);
    }

    function parseFile($path) {
        return $this->mapWorksheets($path, $this->spec['worksheets'], function(Worksheet $worksheet) {
            $sectionGroups = $this->sectionGroups($worksheet->getRowIterator(), $this->spec['sections']);
            // TODO refactor: split sections by type
            return [
                'TIME' => $sectionGroups['TIME'],
                'MULTIPLIERS' => _::map(_::remove($sectionGroups, 'TIME'), function ($rows, $sectionKey) {
                    if ($sectionKey === 'STRUCTURES_TO_INSPECT') {
                        return $this->parseStructuresToInspect($rows);
                    } elseif ($sectionKey === 'DOCUMENTS') {
                        return $this->parseDocuments($rows);
                    } elseif (in_array($sectionKey, $this->keyValueSections)) {
                        return $rows;
                    } else {
                        return $this->parseSimpleSection($rows);
                    }
                })
            ];
        });
    }
}