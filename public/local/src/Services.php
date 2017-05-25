<?php

namespace App;

use Bex\Tools\Iblock\IblockTools;
use Bitrix\Main\Loader;
use CIBlockElement;
use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Validator as v;
use Core\Underscore as _;

Loader::includeModule('iblock');

class Services {
    static function services() {
        $el = new CIBlockElement();
        $iblockId = IblockTools::find(Iblock::SERVICES_TYPE, Iblock::SERVICES)->id();
        $elements = Iblock::collectElements($el->GetList(['SORT' => 'ASC'], ['IBLOCK_ID' => $iblockId]));
        $pathRoot = 'what-we-do';
        return _::keyBy('code', array_map(function($element) use ($pathRoot) {
            $code = $element['CODE'];
            $detail = $pathRoot.'/'.$code;
            return [
                'code' => $code,
                'name' => $element['NAME'],
                'requestFormSubheading' => $element['PROPERTIES']['REQUEST_FORM_SUBHEADING']['VALUE'],
                'detailLink' => View::path($detail),
                'calcLink' => View::path($detail.'/calculator'),
                'requestModalId' => 'request-'.$code,
                'apiEndpoint' => '/api/services/'.$code
            ];
        }, $elements));
    }

    static function initialState() {
        return [
            'params' => [],
            'errors' => []
        ];
    }

    // TODO refactor
    static function translateMessage($template) {
        $ru = [
            '{{name}} must not be empty' => 'Поле не может быть пустым.',
            '{{name}} must be valid email' => 'Пожалуйста, введите действительный адрес электронной почты.'
        ];
        // TODO some sort of default error message?
        return _::get($ru, $template, $template);
    }

    static function getMessages(NestedValidationException $exception) {
        $exception->setParam('translator', self::class.'::translateMessage');
        return array_reduce(iterator_to_array($exception->getIterator()), function($acc, $e) {
            /** @var $e \Respect\Validation\Exceptions\ValidationException */
            // TODO full path name (contact[person] instead of person)
            return _::set($acc, $e->getName(), $e->getMessage());
        }, []);
    }

    static function requestMonitoring($params) {
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
            v::key('MONITORING_GOAL', v::stringType()->notEmpty()),
            v::key('DESCRIPTION', v::stringType()->notEmpty()),
            v::key('ADDITIONAL_INFO', v::stringType())
//            v::key('CONTACT', $contactValidator)
        );
        $errors = [];
        try {
            $validator->assert($params);
        } catch (NestedValidationException $exception) {
            $errors = self::getMessages($exception);
        }
        // TODO refactor nested params/messages
        try {
            $contactValidator->assert($params['CONTACT']);
        } catch (NestedValidationException $exception) {
            foreach (self::getMessages($exception) as $name => $msg) {
                // TODO refactor
                $errors["CONTACT[${name}]"] = $msg;
            }
        }
        $state = [
            'params' => $params,
            'errors' => $errors
        ];
        return $state;
    }
}