<?php

namespace App\Services;

use App\Services;
use Core\Underscore as _;
use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Validator as v;

class DesignRequest {
    static function validateParams($params) {
        $contactValidator = v::allOf(
            v::key('ORGANIZATION', v::stringType()),
            v::key('PERSON', v::stringType()->notEmpty()),
            v::key('PHONE_1', v::stringType()),
            v::key('PHONE_2', v::stringType()),
            v::key('EMAIL', v::stringType()->notEmpty())
        );
        $validator = v::allOf(
            v::key('NAME', v::stringType()->notEmpty()),
            v::key('LOCATION', v::stringType()),
            v::key('ADDITIONAL_INFO', v::stringType()),
            v::key('ITEMS', v::arrayType()->notEmpty())
        );
        $errors = [];
        try {
            $validator->assert($params);
        } catch (NestedValidationException $exception) {
            $errors = Services::getMessages($exception);
            // TODO refactor: custom messages
            $errors = _::update($errors, 'ITEMS', _::constantly(Services::EMPTY_LIST_MESSAGE));
        }
        try {
            $contactValidator->assert($params['CONTACT']);
        } catch (NestedValidationException $exception) {
            foreach (Services::getMessages($exception) as $name => $msg) {
                $errors["CONTACT[${name}]"] = $msg;
            }
        }
        return $errors;
    }

    static function initialState() {
        return [
            'params' => [],
            'errors' => [],
        ];
    }

    static function state($params) {
        $errors = self::validateParams($params);
        $state = [
            'params' => $params,
            'errors' => $errors
        ];
        return $state;
    }

    static function items() {
        $texts = [
            'Конструктивные решения. Железобетонные конструкции',
            'Конструктивные решения. Металлические конструкции',
            'Конструктивные решения. Деревянные конструкции',
            'Конструктивные решения. Статический расчет',
            'Система электроснабжения. Наружное электроснабжение',
            'Система электроснабжения. Силовое электрооборудование',
            'Система электроснабжения. Электроосвещение',
            'Система электроснабжения. Электроосвещение наружно',
            'Электроснабжение инженерных систем',
            'Система водоснабжения. Наружные сети',
            'Система водоотведения. Наружные сети',
            'Система водоснабжения и(или) водоотведения. Наружные сети',
            'Система водоснабжения и(или) водоотведения. Внутренние сети',
            'Отопление, вентиляция и(или) кондиционирование',
            'Система отопления',
            'Вентиляция и(или) кондиционирование',
            'Теплоснабжение',
            'Телефония, радиофикация, телеприём'
        ];
        return _::map($texts, function($text, $idx) {
            return ['ID' => $idx + 1, 'NAME' => $text];
        });
    }

    static function context($state, $service) {
        return array_merge($service, [
            'state' => $state,
            'options' => [
                'ITEMS' => Services::entities2options(self::items())
            ]
        ]);
    }
}