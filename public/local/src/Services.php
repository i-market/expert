<?php

namespace App;

use Bex\Tools\Iblock\IblockTools;
use Bitrix\Iblock\SectionTable;
use Bitrix\Main\Mail\Internal\EventMessageTable;
use Bitrix\Main\Loader;
use CFile;
use CIBlockElement;
use Core\Env;
use Core\Util;
use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Validator as v;
use Core\Underscore as _;
use Core\Strings as str;

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

    // TODO move to core?
    private static function findEventMessageTemplate($eventName) {
        return EventMessageTable::query()
            ->setSelect(['*'])
            ->setFilter([
                'ACTIVE' => 'Y',
                'EVENT_NAME' => $eventName,
                'EVENT_MESSAGE_SITE.SITE_ID' => App::SITE_ID,
            ])
            ->exec()->fetch();
    }

    private function newRequestEventName($serviceCode) {
        return 'NEW_SERVICE_REQUEST_'.str::upper($serviceCode);
    }

    private static function uploadedFileArrays($fileIds) {
        return array_map(function($fileId) {
            $absPath = Util::joinPath([Api::fileuploadDir(), $fileId]);
            return CFile::MakeFileArray($absPath);
        }, $fileIds);
    }

    private function saveServiceRequest($serviceCode, $name, $message, $params, $propertyValues) {
        $iblockId = IblockTools::find(Iblock::INBOX_TYPE, Iblock::SERVICE_REQUESTS)->id();
        $section = SectionTable::query()
            ->setSelect(['ID'])
            ->setFilter([
                'IBLOCK_ID' => $iblockId,
                'CODE' => $serviceCode
            ])
            ->exec()->fetch();
        $el = new CIBlockElement();
        $fields = [
            'IBLOCK_ID' => $iblockId,
            'IBLOCK_SECTION_ID' => $section['ID'],
            'NAME' => $name,
            'PREVIEW_TEXT' => $message,
            'DETAIL_TEXT' => json_encode(['params' => $params]),
            'PROPERTY_VALUES' => $propertyValues
        ];
        $elementId = $el->Add($fields);
        if (!is_numeric($elementId)) {
            trigger_error("can't add `service_request` element: {$el->LAST_ERROR}", E_USER_WARNING);
        }
        return $elementId;
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
        $isValid = _::isEmpty($errors);
        if ($isValid) {
            $fields = array_merge(_::flatten($params, '_'), [
                // TODO service requests email_to
                'EMAIL_TO' => App::getInstance()->adminEmailMaybe(),
                'FILE_LINKS' => ''
            ]);
            $serviceCode = 'monitoring';
            $elementName = $params['CONTACT']['PERSON'];
            $eventName = self::newRequestEventName($serviceCode);
            $eventMessageTemplate = self::findEventMessageTemplate($eventName);
            assert($eventMessageTemplate !== null);
            $message = _::reduce($fields, function($result, $value, $key) {
                return str_replace('#'.$key.'#', $value, $result);
            }, $eventMessageTemplate['MESSAGE']);
            $files = self::uploadedFileArrays($params['fileIds']);
            if (App::getInstance()->env() !== Env::DEV) {
                $elementId = self::saveServiceRequest($serviceCode, $elementName, $message, $params, [
                    'FILES' => $files
                ]);
                $element = _::first(Iblock::collectElements(CIBlockElement::GetByID($elementId)));
                $savedFiles = array_map(function($fileId) {
                    return CFile::GetFileArray($fileId);
                }, $element['PROPERTIES']['FILES']['VALUE']);
            } else {
                $savedFiles = [];
            }
            $fileLinks = array_map(function($file) {
                // TODO ! full url
                $url = $file['SRC'];
                return "{$file['ORIGINAL_NAME']} — {$url}";
            }, $savedFiles);
            $fieldsWithFileLinks = array_merge($fields, [
                'FILE_LINKS' => !_::isEmpty($fileLinks)
                    ? join("\n", array_merge(['Прикрепленные файлы:'], $fileLinks))
                    : ''
            ]);
            App::getInstance()->sendMail($eventName, $fieldsWithFileLinks, App::SITE_ID);
            $state['screen'] = 'success';
        }
        return $state;
    }
}