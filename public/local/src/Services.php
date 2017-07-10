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

// TODO non-ideal way to distinguish between environments
if (php_sapi_name() !== 'cli') {
    Loader::includeModule('iblock');
}

class Services {
    private static $data = [];

    // TODO refactor empty checkbox list message
    const EMPTY_LIST_MESSAGE = 'Пожалуйста, выберите хотя бы один элемент.';

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

    // TODO review
    static function initialState() {
        return [
            'params' => [],
            'errors' => []
        ];
    }

    // TODO unused?
    static function floorInputs($params) {
        $siteCount = _::get($params, 'SITE_COUNT', 1);
        return array_map(function($num) {
            return [
                'label' => 'Строение '.$num,
            ];
        }, range(1, $siteCount));
    }

    // TODO refactor
    static function translateMessage($template) {
        $ru = [
            '{{name}} must not be empty' => 'Поле не может быть пустым.',
            '{{name}} must be valid email' => 'Пожалуйста, введите действительный адрес электронной почты.',
            '{{name}} must be positive' => 'Должно быть положительным числом.',
            '{{name}} must not be optional' => 'Обязательное поле.'
        ];
        // TODO some sort of default error message?
        return _::get($ru, $template, $template);
    }

    // TODO refactor
    static function getMessages(NestedValidationException $exception) {
        $exception->setParam('translator', self::class.'::translateMessage');
        $ret = array_reduce(iterator_to_array($exception->getIterator()), function($acc, $e) {
            /** @var $e \Respect\Validation\Exceptions\ValidationException */
            // TODO full path name (contact[person] instead of person)
            return _::set($acc, $e->getName(), $e->getMessage());
        }, []);
        $ret = _::update($ret, 'FLOORS', _::constantly('В каждом поле должно быть положительное число.'));
        return $ret;
    }

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

    // TODO should save structured data. no need for template rendering, etc.
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

    private static function formatList($items) {
        return join("\n", array_map(function($item) {
            return '✓ '.$item;
        }, $items));
    }

    private static function markEmptyStrings($array) {
        return array_map(function($value) {
            return str::isEmpty($value) ? '—' : $value;
        }, $array);
    }

    static function formatTotalPrice($totalPrice) {
        return Util::formatCurrency(round($totalPrice), ['cents' => false]).' руб./мес.';
    }

    // TODO unused?
    // TODO refactor: move to `Monitoring`
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
            $documentOptions = _::keyBy('ID', App::getInstance()->container['monitoring_repo']->documents());
            $documentNames = array_map(function($documentId) use ($documentOptions) {
                return $documentOptions[$documentId]['NAME'];
            }, array_map('intval', $params['DOCUMENTS']));
            $fields = self::markEmptyStrings(array_merge(_::flatten($params, '_'), [
                // TODO service requests email_to
                'EMAIL_TO' => App::getInstance()->adminEmailMaybe(),
                'FILE_LINKS' => '',
                'DOCUMENTS' => self::formatList($documentNames)
            ]));
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

    static function entities2options($path, $dataSet) {
        $entities = _::get($dataSet, array_merge(['MULTIPLIERS'], $path));
        return array_map(function($entity) {
            return [
                'value' => $entity['ID'],
                'text' => $entity['NAME']
            ];
        }, $entities);
    }

    // TODO unused?
    static function dataSet($data, $params) {
        return $params['SITE_COUNT'] > 1
            ? $data['MULTIPLE_BUILDINGS']
            : $data['SINGLE_BUILDING'];
    }

    // TODO refactor
    static function validateEmail($params) {
        $errors = [];
        try {
            v::key('EMAIL', v::email())->assert($params);
        } catch (NestedValidationException $exception) {
            $errors = Services::getMessages($exception);
        }
        return $errors;
    }

    static function keyValidator($key, $params) {
        $validators = [
            'SITE_COUNT' => v::intType()->positive(),
            'DISTANCE_BETWEEN_SITES' =>
                $params['SITE_COUNT'] === 1
                    ? v::alwaysValid()
                    : v::notOptional(),
            'DESCRIPTION' => v::stringType()->notEmpty(),
            'LOCATION' => v::notOptional(),
            'USED_FOR' => v::notOptional(),
            'TOTAL_AREA' => v::intType()->positive(),
            'VOLUME' => v::optional(v::intType()->positive()),
            // have to use custom `callback` validator because e.g. built-in `each` validator hides the field name
            'FLOORS' => v::callback(function($values) {
                return is_array($values) && _::matches($values, function($v) {
                    return v::notOptional()->intType()->validate($v);
                });
            }),
            'UNDERGROUND_FLOORS' =>
                $params['HAS_UNDERGROUND_FLOORS']
                    ? v::intType()->positive()
                    : v::alwaysValid(),
            'DURATION' => v::notOptional(),
            'TRANSPORT_ACCESSIBILITY' => v::notOptional(),
            'DOCUMENTS' => v::arrayType()
        ];
        assert(isset($validators[$key]));
        return v::key($key, $validators[$key]);
    }

    /// proposal

    static function listHtml($values) {
        $items = join('', array_map(function($item) {
            return "<li>{$item}</li>";
        }, $values));
        return "<ul>{$items}</ul>";
    }

    static function formatRow($tuple, $model) {
        list($label, $key) = $tuple;
        $funcMaybe = isset($tuple[2]) ? $tuple[2] : null;
        $value = _::get($model, $key);
        $formattedValue = is_callable($funcMaybe)
            ? $funcMaybe($value)
            : (in_array($value, ['', null]) ? '—' : $value);
        return ["<strong>{$label}</strong>", $formattedValue];
    }

    // TODO unused?
    static function dereferenceParams($params, $dataSet, callable $findEntity) {
        $deref = function($val, $k) use (&$deref, $dataSet, $params, $findEntity) {
            if (is_int($val)) {
                return $val;
            } elseif (is_array($val)) {
                return array_map(function($v) use (&$deref, $k) {
                    return $deref($v, $k);
                }, $val);
            } else {
                $entityMaybe = $findEntity($k, $val, $dataSet);
                return _::get($entityMaybe, 'NAME', $val);
            }
        };
        return _::map($params, $deref);
    }

    static function generateProposalFile($proposalParams, $host = 'localhost') {
        $requestCtx = stream_context_create([
            'http' => [
                'method'  => 'POST',
                'header'  => 'Content-type: application/x-www-form-urlencoded',
                'content' => http_build_query($proposalParams)
            ]
        ]);
        // have to do it through http request because pdf generation requires bitrix-incompatible php configuration
        $response = file_get_contents("http://{$host}/proposals/", false, $requestCtx);
        return $response;
    }

    static function sendProposalEmail($emailTo, $attachmentPaths) {
        $event = [
            'EMAIL_TO' => $emailTo,
            'FILE' => $attachmentPaths
        ];
        return App::getInstance()->sendMail(Events::PROPOSAL, $event, App::SITE_ID);
    }

    static function formatFullDate(\DateTime $datetime) {
        $ts = $datetime->getTimestamp();
        $month = Util::monthRu(intval(date('n', $ts)));
        return join(' ', [date('d', $ts), $month, date('Y', $ts), 'г.']);
    }

    private static function dataFilePath($type) {
        return Util::joinPath([$_SERVER['DOCUMENT_ROOT'], "local/data/{$type}.json"]);
    }

    function save($type, $data) {
        return file_put_contents(self::dataFilePath($type), json_encode($data));
    }

    function data($type) {
        $dataMaybe = _::get(self::$data, $type);
        if ($dataMaybe !== null) {
            return $dataMaybe;
        }
        $content = file_get_contents(self::dataFilePath($type));
        assert($content !== false);
        self::$data[$type] = json_decode($content, true);
        return self::$data[$type];
    }
}