<?php

namespace App;

use App\Services\Parser;
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
use Core\Nullable as nil;

// TODO non-ideal way to distinguish between environments
if (php_sapi_name() !== 'cli') {
    Loader::includeModule('iblock');
}

class Services {
    // fields that act the same most of the time
    static $structures = ['STRUCTURES_TO_MONITOR', 'STRUCTURES_TO_INSPECT'];
    static $distanceSpecialValue = '>3km';
    private static $data = [];

    // TODO refactor empty checkbox list message
    const EMPTY_LIST_MESSAGE = 'Пожалуйста, выберите хотя бы один элемент.';

    static function saveServiceRequest($modelFields, $fileIds) {
        $fields = self::markEmptyStrings(array_merge(_::flatten($modelFields, '_'), [
            // TODO add files prop to db migration
            'FILES' => array_map([Api::class, 'uploadedFileArray'], $fileIds)
        ]));
        $el = new CIBlockElement();
        $elementId = $el->Add($fields);
        $element = _::first(Iblock::collectElements(CIBlockElement::GetByID($elementId)));
//        $savedFiles = array_map([CFile::class, 'GetFileArray'], $element['PROPERTIES']['FILES']['VALUE']);
        return $element;
    }

    private static function dataFilePath($type) {
        // TODO
        $tmpPath = ini_get('upload_tmp_dir') ?: sys_get_temp_dir();
        return Util::joinPath([$tmpPath, "{$type}.json"]);
    }

    static function save($type, $data) {
        return file_put_contents(self::dataFilePath($type), json_encode($data));
    }

    static function data($type) {
        // TODO implement storage
        $tmp = true;
        if ($tmp || App::getInstance()->env() === Env::DEV) {
            if (file_exists(self::dataFilePath($type)) && _::get($_REQUEST, 'cache', true)) {
                $content = file_get_contents(self::dataFilePath($type));
                assert($content !== false);
                return json_decode($content, true);
            }
            // use fixtures for development convenience
            $pair = [
                'monitoring' => [Services\MonitoringParser::class, 'Мониторинг калькуляторы.xlsx'],
                'inspection' => [Services\InspectionParser::class, 'Обследование калькуляторы.xlsx'],
                'examination' => [Services\ExaminationParser::class, 'Экспертиза калькуляторы.xlsx'],
                'oversight' => [Services\OversightParser::class, 'Технадзор контроль калькуляторы.xlsx'],
                'individual' => [Services\IndividualParser::class, 'Техническая экспертиза калькуляторы.xlsx']
            ];
            list($class, $file) = $pair[$type];
            /** @var callable $parseFile */
            $parseFile = [new $class, 'parseFile'];
            $data = $parseFile(Util::joinPath([$_SERVER['DOCUMENT_ROOT'], 'local/fixtures/calculator', $file]));
            file_put_contents(self::dataFilePath($type), json_encode($data));
            return $data;
        } else {
            throw new \Exception('not implemented');
        }
//        $dataMaybe = _::get(self::$data, $type);
//        if ($dataMaybe !== null) {
//            return $dataMaybe;
//        }
//        $content = file_get_contents(self::dataFilePath($type));
//        assert($content !== false);
//        self::$data[$type] = json_decode($content, true);
//        return self::$data[$type];
    }

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
                'calcLink' => $code === 'design' ? null : View::path($detail.'/calculator'),
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

    /** @deprecated */
    private static function uploadedFileArrays($fileIds) {
        return array_map(function($fileId) {
            $absPath = Util::joinPath([Api::fileuploadDir(), $fileId]);
            return CFile::MakeFileArray($absPath);
        }, $fileIds);
    }

    // TODO unused
//    private function saveServiceRequest($serviceCode, $name, $message, $params, $propertyValues) {
//        $iblockId = IblockTools::find(Iblock::INBOX_TYPE, Iblock::SERVICE_REQUESTS)->id();
//        $section = SectionTable::query()
//            ->setSelect(['ID'])
//            ->setFilter([
//                'IBLOCK_ID' => $iblockId,
//                'CODE' => $serviceCode
//            ])
//            ->exec()->fetch();
//        $el = new CIBlockElement();
//        $fields = [
//            'IBLOCK_ID' => $iblockId,
//            'IBLOCK_SECTION_ID' => $section['ID'],
//            'NAME' => $name,
//            'PREVIEW_TEXT' => $message,
//            'DETAIL_TEXT' => json_encode(['params' => $params]),
//            'PROPERTY_VALUES' => $propertyValues
//        ];
//        $elementId = $el->Add($fields);
//        if (!is_numeric($elementId)) {
//            trigger_error("can't add `service_request` element: {$el->LAST_ERROR}", E_USER_WARNING);
//        }
//        return $elementId;
//    }

    static function formatList($items) {
        return join("\n", array_map(function($item) {
            return '✓ '.$item;
        }, $items));
    }

    static function markEmptyStrings($array) {
        return array_map(function($value) {
            return is_string($value) && str::isEmpty($value) ? '—' : $value;
        }, $array);
    }

    static function formatTotalPrice($totalPrice) {
        return Util::formatCurrency(round($totalPrice), ['cents' => false]).' руб./мес.';
    }

    static function resultBlockContext($state, $apiUri, $summaryValues) {
        $ret = [
            'apiUri' => $apiUri,
            'screen' => 'hidden'
        ];
        if (isset($state['result'])) {
            $ret = array_merge($ret, [
                'screen' => 'result',
                'result' => [
                    'total_price' => Services::formatTotalPrice($state['result']['total_price']),
                    'summary_values' => $summaryValues
                ],
                'params' => ['EMAIL' => _::get($state, 'params.EMAIL', '')],
                'errors' => _::get($state, 'action') === 'send_proposal'
                    ? Services::validateEmail($state['params'])
                    : []
            ]);
        }
        return $ret;
    }

    static function formatDuration($text) {
        return preg_replace_callback('/(\pL+\s+)?(\d+)$/u', function($matches) {
            list($match, $word, $number) = $matches;
            $units = $word !== ''
                // e.g. более n месяцев
                ? Util::units($number, 'месяца', 'месяцев', 'месяцев')
                : Util::units($number, 'месяц', 'месяца', 'месяцев');
            return $match.' '.$units;
        }, $text);
    }

    // TODO unused
    /** @deprecated */
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

    static function entities2options($x) {
        // TODO trim "x.x. text"
        if (isset($x['ID'])) {
            return ['value' => $x['ID'], 'text' => $x['NAME']];
        } elseif (is_array($x)) {
            return array_map([self::class, 'entities2options'], $x);
        } else {
            return $x;
        }
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

    static function keyValidator($key, $params, $mandatory = true) {
        $requiredId = v::notOptional();
        $validators = [
            'SITE_COUNT' => v::intType()->positive(),
            'SITE_CATEGORY' => $requiredId,
            'DISTANCE_BETWEEN_SITES' =>
                $params['SITE_COUNT'] === 1
                    ? v::alwaysValid()
                    : $requiredId,
            'DESCRIPTION' => v::stringType()->notEmpty(),
            'LOCATION' => $requiredId,
            'USED_FOR' => $requiredId,
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
            'DURATION' => $requiredId,
            'TRANSPORT_ACCESSIBILITY' => $requiredId,
            'DOCUMENTS' => v::arrayType()
        ];
        assert(isset($validators[$key]));
        return v::key($key, $validators[$key], $mandatory);
    }

    static function dereferenceParams($params, $dataSet, callable $findEntity) {
        $deref = function($val, $k) use (&$deref, $dataSet, $params, $findEntity) {
            // TODO refactor
            if (is_int($val)) {
                return $val;
            } elseif (is_array($val)) {
                return array_map(function($v) use (&$deref, $k) {
                    return $deref($v, $k);
                }, $val);
            } else {
                $entityMaybe = $findEntity($k, $val, $dataSet);
                return $entityMaybe ? _::remove($entityMaybe, 'VALUE') : $val;
            }
        };
        return _::map($params, $deref);
    }

    static function findEntity2($field, $val, $entities) {
        $findRec = function($xs, $pred) {
            $reducer = function($result, $x) use (&$reducer, $xs, $pred) {
                if (isset($x['ID']) && $pred($x)) {
                    return $x;
                } elseif (is_array($x)) {
                    return array_reduce($x, $reducer, $result);
                } else {
                    return $result;
                }
            };
            return array_reduce($xs, $reducer, null);
        };
        if (in_array($field, ['FLOORS', 'SITE_COUNT', 'UNDERGROUND_FLOORS'])) {
            $pred = function($entity) use ($val) {
                $f = Parser::parseNumericPredicate($entity['NAME']);
                return $f($val);
            };
        } elseif (in_array($field, ['HAS_UNDERGROUND_FLOORS', 'FOR_LEGAL_CASE', 'NEEDS_VISIT'])) {
            $pred = function($entity) use ($val) {
                $bool = Parser::parseBoolean($entity['NAME']);
                return $val === $bool;
            };
        } else {
            $pred = function($entity) use ($val) {
                return $entity['ID'] === $val;
            };
            return $findRec($entities, $pred);
        }
        return _::find($entities, $pred);
    }

    // TODO refactor
    /** @deprecated use `findEntity2` */
    static function findEntity($field, $val, $dataSet) {
        if (!isset($dataSet['MULTIPLIERS'][$field])) {
            return null;
        }
        return self::findEntity2($field, $val, $dataSet['MULTIPLIERS'][$field]);
    }

    /// proposal

    static function listHtml($values) {
        if (_::isEmpty($values)) {
            return '';
        }
        $items = join('', array_map(function($item) {
            return "<li>{$item}</li>";
        }, $values));
        return "<ul>{$items}</ul>";
    }

    static function formatRow($tuple, $model) {
        list($label, $key) = $tuple;
        $funcMaybe = isset($tuple[2]) ? $tuple[2] : null;
        $value = _::get($model, $key);
        $html = is_callable($funcMaybe) ? $funcMaybe($value) : $value;
        return ["<strong>{$label}</strong>", in_array($html, ['', null]) ? '—' : $html];
    }

    static function generateProposalFile($proposalParams, $host = null) {
        $host = nil::get($host, $_SERVER['SERVER_NAME']);
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

    static function sendProposalEmail($emailTo, $fileIds) {
        $event = [
            'EMAIL_TO' => $emailTo,
            'FILE' => $fileIds
        ];
        return App::getInstance()->sendMail(Events::PROPOSAL, $event, App::SITE_ID);
    }

    static function formatFullDate(\DateTime $datetime) {
        $ts = $datetime->getTimestamp();
        $month = Util::monthRu(intval(date('n', $ts)));
        return join(' ', [date('d', $ts), $month, date('Y', $ts), 'г.']);
    }

    static function outgoingId($serviceType, $id = null) {
        // TODO next outgoing id
        $nextId = _::constantly('4242');
        $prefix = [
            'monitoring' => '0611-1',
            'inspection' => '2411-5'
            // TODO the rest
        ];
        assert(in_array($serviceType, array_keys($prefix)));
        return $prefix[$serviceType].'/'.($id ?: $nextId());
    }
}