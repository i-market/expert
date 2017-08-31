<?php

namespace App;

use App\Services\Parser;
use Bex\Tools\Iblock\IblockTools;
use Bitrix\Main\Application;
use Bitrix\Main\Loader;
use CFile;
use CIBlockElement;
use Core\Env;
use Core\Nullable as nil;
use Core\Strings as str;
use Core\Underscore as _;
use Core\Util;
use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Validator as v;

// TODO non-ideal way to distinguish between environments
if (php_sapi_name() !== 'cli') {
    Loader::includeModule('iblock');
}

class Services {
    // fields that act the same most of the time
    static $structures = ['STRUCTURES_TO_MONITOR', 'STRUCTURES_TO_INSPECT'];
    static $distanceSpecialValue = '>3km';

    // TODO refactor empty checkbox list message
    const EMPTY_LIST_MESSAGE = 'Пожалуйста, выберите хотя бы один элемент.';

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

    static function serviceRequestName($params) {
        $parts = array_map(function($contactKey) use ($params) {
            return _::get($params, ['CONTACT', $contactKey]);
        }, ['ORGANIZATION', 'PERSON', 'EMAIL']);
        return join(' - ', _::clean($parts));
    }

    static function fileLinksSection($fileIds) {
        $files = array_map([CFile::class, 'GetFileArray'], $fileIds);
        $fileLinks = array_map([App::class, 'url'], _::pluck($files, 'SRC'));
        return !_::isEmpty($fileLinks)
            ? join("\n", array_merge(['Прикрепленные файлы:'], $fileLinks))
            : '';
    }

    static function formatList($items) {
        return join("\n", array_map(function($item) {
            return '✓ '.$item;
        }, $items));
    }

    static function orNotSpecified($str) {
        return str::isEmpty($str) ? '—' : $str;
    }

    /** @deprecated use orNotSpecified */
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

    static function formatDurationMonths($text) {
        return preg_replace_callback('/(\pL+\s+)?(\d+)$/u', function($matches) {
            list($match, $word, $number) = $matches;
            $units = $word !== ''
                // e.g. более n месяцев
                ? Util::units($number, 'месяца', 'месяцев', 'месяцев')
                : Util::units($number, 'месяц', 'месяца', 'месяцев');
            return $match.' '.$units;
        }, $text);
    }

    static function formatDurationWorkdays($text) {
        assert(is_numeric($text), $text);
        $number = intval($text);
        $units = Util::units($number, 'рабочий день', 'рабочих дня', 'рабочих дней');
        return strval($number).' '.$units;
    }

    static function entities2options($x) {
        if (isset($x['ID'])) {
            // TODO tmp: don't strip anything for testing purposes
//            return ['value' => $x['ID'], 'text' => str::capitalize(Parser::stripNumbering($x['NAME']), false)];
            return ['value' => $x['ID'], 'text' => str::capitalize($x['NAME'], false)];
        } elseif (is_array($x)) {
            return array_map([self::class, 'entities2options'], $x);
        } else {
            return $x;
        }
    }

    /** @deprecated */
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
        // TODO refactor: optimize
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

    static function sendProposalEmail($emailTo, $filePaths) {
        return App::getInstance()->sendMail(Events::PROPOSAL, ['EMAIL_TO' => $emailTo], App::SITE_ID, [
            'event' => ['FILE' => $filePaths]
        ]);
    }

    static function formatFullDate(\DateTime $datetime) {
        $ts = $datetime->getTimestamp();
        $month = Util::monthRu(intval(date('n', $ts)));
        return join(' ', [date('d', $ts), $month, date('Y', $ts), 'г.']);
    }

    static function recordProposal($_type, $_recipientEmail) {
        $conn = Application::getConnection();
        // TODO refactor: use mysqli which supports binding/prepared statements
        $type = $conn->getSqlHelper()->forSql($_type);
        $email = $conn->getSqlHelper()->forSql($_recipientEmail);
        $conn->query("INSERT INTO proposals (type, email, created) VALUES ('{$type}', '{$email}', NOW());");
        return $conn->getInsertedId();
    }

    static function outgoingId($serviceType, $recordId) {
        $prefix = [
            'monitoring' => '0611-1',
            'inspection' => '2411-5',
            // TODO duplicate prefix
            'oversight' => '0611-1',
            'individual' => '2331-5',
            // TODO the rest: e.g. examination
        ];
        assert(in_array($serviceType, array_keys($prefix)));
        return $prefix[$serviceType].'/'.$recordId;
    }
}