<?php

namespace App\Services;

use Core\Underscore as _;
use PhpOffice\PhpSpreadsheet\Worksheet;

class OversightParser extends Parser {
    public $spec = [
        'worksheets' => [
            [
                'key' => 'SINGLE_BUILDING',
                'name' => 'Технадзор одного здания'
            ],
            [
                'key' => 'MULTIPLE_BUILDINGS',
                'name' => 'Технадзор нескольких зданий'
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
                    'Назначение объекта',
                    'Назначение объектов'
                ],
            ],
            [
                'key' => 'CONSTRUCTION_TYPE',
                'prefix' => 'Вид строительства'
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
                'key' => 'CONSTRUCTION_PHASE',
                'prefix' => 'Этап строительства'
            ],
            [
                'key' => 'DURATION',
                'prefix' => 'Продолжительность технического надзора (мес.)'
            ],
            [
                'key' => 'DISTANCE_BETWEEN_SITES',
                'prefix' => 'Удаленность объектов друг от друга'
            ],
            [
                'key' => 'TRANSPORT_ACCESSIBILITY',
                'prefix' => 'Транспортная доступность',
            ],
            [
                'key' => 'DOCUMENTS',
                'prefix' => 'Наличие документов',
            ]
        ]
    ];

    function parseFile($path) {
        return $this->mapWorksheets($path, $this->spec['worksheets'], function(Worksheet $worksheet) {
            $sectionGroups = $this->sectionGroups($worksheet->getRowIterator(), $this->spec['sections']);
            return [
                'MULTIPLIERS' => _::map($sectionGroups, function ($rows, $sectionKey) {
                    if ($sectionKey === 'DOCUMENTS') {
                        return $this->parseDocuments($rows);
                    } else {
                        return $this->parseSimpleSection($rows);
                    }
                })
            ];
        });
    }
}