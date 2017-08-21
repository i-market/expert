<?php

namespace App\Services;

use App\App;
use App\Services;
use Core\Underscore as _;
use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Validator as v;

class MonitoringRequest {
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
            v::key('MONITORING_GOAL', v::stringType()->notEmpty()),
            v::key('DESCRIPTION', v::stringType()->notEmpty()),
            v::key('ADDITIONAL_INFO', v::stringType())
//            v::key('CONTACT', $contactValidator)
        );
        $errors = [];
        try {
            $validator->assert($params);
        } catch (NestedValidationException $exception) {
            $errors = Services::getMessages($exception);
        }
        // TODO refactor nested params/messages
        try {
            $contactValidator->assert($params['CONTACT']);
        } catch (NestedValidationException $exception) {
            foreach (Services::getMessages($exception) as $name => $msg) {
                $errors["CONTACT[${name}]"] = $msg;
            }
        }
        return $errors;
    }

    static function initialState($data) {
        return [
            'data_set' => $data['SINGLE_BUILDING'],
            'params' => [],
            'errors' => [],
        ];
    }

    static function state($params, $data) {
        $errors = self::validateParams($params);
        $state = array_merge(self::initialState($data), [
            'params' => $params,
            'errors' => $errors
        ]);
        if (_::isEmpty($errors)) {
            $state['model'] = Services::dereferenceParams($params, $state['data_set'], [Services::class, 'findEntity']);
        }
        return $state;
    }

    static function context($state, $service) {
        return array_merge($service, [
            'state' => $state,
            'options' => [
                'DOCUMENTS' => Services::entities2options($state['data_set']['MULTIPLIERS']['DOCUMENTS'])
            ]
        ]);
    }

    // TODO legacy code
//    static function send($model) {
//        $formatEntityList = function($entities) {
//            return Services::formatList(_::pluck($entities, 'NAME'));
//        };
//        $modelFields = _::flatten(_::update($model, 'DOCUMENTS', $formatEntityList), '_');
//        $fields = Services::markEmptyStrings(array_merge($modelFields, [
//            // TODO service requests email_to
//            'EMAIL_TO' => App::getInstance()->adminEmailMaybe(),
//            'FILE_LINKS' => '',
//        ]));
//        $serviceCode = 'monitoring';
//        $elementName = $model['CONTACT']['PERSON'];
//        $eventName = self::newRequestEventName($serviceCode);
//        $eventMessageTemplate = self::findEventMessageTemplate($eventName);
//        assert($eventMessageTemplate !== null);
//        $message = _::reduce($fields, function($result, $value, $key) {
//            return str_replace('#'.$key.'#', $value, $result);
//        }, $eventMessageTemplate['MESSAGE']);
//        $files = self::uploadedFileArrays($params['fileIds']);
//        if (App::getInstance()->env() !== Env::DEV) {
//            $elementId = self::saveServiceRequest($serviceCode, $elementName, $message, $params, [
//                'FILES' => $files
//            ]);
//            $element = _::first(Iblock::collectElements(CIBlockElement::GetByID($elementId)));
//            $savedFiles = array_map(function($fileId) {
//                return CFile::GetFileArray($fileId);
//            }, $element['PROPERTIES']['FILES']['VALUE']);
//        } else {
//            $savedFiles = [];
//        }
//        $fileLinks = array_map(function($file) {
//            // TODO ! full url
//            $url = $file['SRC'];
//            return "{$file['ORIGINAL_NAME']} — {$url}";
//        }, $savedFiles);
//        $fieldsWithFileLinks = array_merge($fields, [
//            'FILE_LINKS' => !_::isEmpty($fileLinks)
//                ? join("\n", array_merge(['Прикрепленные файлы:'], $fileLinks))
//                : ''
//        ]);
//        App::getInstance()->sendMail($eventName, $fieldsWithFileLinks, App::SITE_ID);
//        $state['screen'] = 'success';
//    }
}