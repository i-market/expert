<?php

namespace App;

use App\Services\DesignRequest;
use App\Services\Examination;
use App\Services\ExaminationRequest;
use App\Services\Individual;
use App\Services\IndividualRequest;
use App\Services\Inspection;
use App\Services\InspectionRequest;
use App\Services\Monitoring;
use App\Services\MonitoringRequest;
use App\Services\Oversight;
use App\Services\OversightRequest;
use App\View as v;
use Bex\Tools\Iblock\IblockTools;
use Bitrix\Iblock\ElementTable;
use Bitrix\Main\Application;
use Bitrix\Main\Config\Configuration;
use Bitrix\Main\Type as Type;
use CFile;
use CHTTP;
use CIBlockElement;
use Core\Env;
use Core\FileUpload;
use Core\MimeTypes;
use Core\MimeTypeValidator;
use Core\Strings as str;
use Core\Underscore as _;
use Core\Util;
use FileUpload\FileSystem;
use FileUpload\PathResolver;
use FileUpload\Validator;
use Klein\Klein;
use ReCaptcha\ReCaptcha;

class Api {
    static function fileuploadDir() {
        return ini_get('upload_tmp_dir') ?: sys_get_temp_dir();
    }

    static function uploadedFileArray($fileId) {
        $absPath = Util::joinPath([self::fileuploadDir(), $fileId]);
        return CFile::MakeFileArray($absPath);
    }

    static function parseInt($s) {
        return str::isEmpty($s) ? null : intval($s);
    }

    static function normalizeParams($params) {
        foreach (['SITE_COUNT', 'TOTAL_AREA', 'UNDERGROUND_FLOORS', 'VOLUME'] as $k) {
            $params = _::update($params, $k, [self::class, 'parseInt']);
        }
        $params = _::update($params, 'FLOORS', function($values) {
            return array_map([self::class, 'parseInt'], $values);
        });
        foreach (['HAS_UNDERGROUND_FLOORS', 'FOR_LEGAL_CASE', 'NEEDS_VISIT'] as $k) {
            $params = _::update($params, $k, 'boolval');
        }
        $params = array_merge([
            'DOCUMENTS' => []
        ], $params);
        return $params;
    }

    static function sendProposalEmail(callable $proposalParamsFn, $email) {
        $iblockId = IblockTools::find(Iblock::INBOX_TYPE, IBlock::PROPOSALS)->id();
        $today = new Type\DateTime(date('Y-m-d'), 'Y-m-d');
        $todayCount = ElementTable::getCount([
            'IBLOCK_ID' => $iblockId,
            '>=DATE_CREATE' => $today
        ]);
        $outgoingId = date('dm-'.strval($todayCount + 1).'/y');
        $conn = Application::getConnection();
        $conn->startTransaction();
        try {
            $response = Services::generateProposalFile($proposalParamsFn($outgoingId));
            assert($response !== false, $response);
            $path = $response;
            assert(file_exists($path), $path);
            $outgoingIdSegment = preg_replace('/\D/', '-', $outgoingId);
            $humaneFilename = "tse_{$outgoingIdSegment}.pdf";
            $fileId = CFile::SaveFile(_::set(CFile::MakeFileArray($path), 'name', $humaneFilename), 'iblock');
            $el = new CIBlockElement();
            $result = $el->Add([
                'IBLOCK_ID' => $iblockId,
                'NAME' => $outgoingId,
                'PROPERTY_VALUES' => [
                    'EMAIL' => $email,
                    'FILE' => $fileId
                ]
            ]);
            assert($result, $el->LAST_ERROR);

            $ret = Services::sendProposalEmail($email, [$fileId]);
            $conn->commitTransaction();
            return $ret;
        } catch (\Exception $e) {
            $conn->rollbackTransaction();
            throw $e;
        }
    }

    static function debugScript() {
        if (!App::getInstance()->isDebugEnabled()) {
            return '';
        }
        $json = json_encode(Services\Calculator::$debug);
        $src = v::asset('js/debug.js');
        return "<script>window._data = {$json};</script><script src='{$src}'></script>";
    }

    static function withRecaptcha(callable $continue) {
        try {
            $recaptchaResponse = _::get($_REQUEST, 'g-recaptcha-response');
            $secretKey = _::get(Configuration::getValue('app'), 'recaptcha.secret_key');
            $recaptcha = new ReCaptcha($secretKey);
            $result = $recaptcha->verify($recaptchaResponse);
            // another error code of interest is 'timeout-or-duplicate'
            $isHttpFailure = _::contains($result->getErrorCodes(), 'invalid-json');
            $isSuccess = $result->isSuccess() || $isHttpFailure;
            if (!$isSuccess) {
                // TODO log recaptcha error to sentry
                CHTTP::SetStatus('403 Forbidden');
                die('Неверная капча');
            }
            return $continue();
        } catch (\Exception $e) {
            // TODO log to sentry
            return $continue();
        }
    }

    static function dispatch() {
        try {
            return self::router()->dispatch();
        } catch (\Exception $e) {
            CHTTP::SetStatus('500 Internal Server Error');
            // TODO check that its a klein exception
            // unwrap klein exception
            throw $e->getPrevious();
        }
    }

    // TODO DRY
    // TODO sanitize params
    private static function router() {
        $router = new Klein();
        $router->with('/api', function () use ($router) {
            $router->respond('POST', '/callback-request', function($request, $response) {
                return self::withRecaptcha(function() use ($request, $response) {
                    $params = $request->params();
                    $state = App::requestCallback($params);
                    return v::render('partials/callback_request_form', [
                        'state' => $state
                    ]);
                });
            });
            $router->respond('POST', '/services/monitoring/calculator/[:action]', function($request, $response) {
                $type = 'monitoring';
                $defaults = [
                    'STRUCTURES_TO_MONITOR' => []
                ];
                $params = array_merge($defaults, self::normalizeParams($request->params()));
                $data = Services::data($type);
                $state = Monitoring::state($params, $request->action, $data, _::get($params, 'validate', true));
                $context = Monitoring::calculatorContext($state);
                if ($request->action === 'send_proposal' && _::isEmpty($context['resultBlock']['errors'])) {
                    $context = self::withRecaptcha(function() use ($context, $state, $params) {
                        $opts = App::getInstance()->env() === Env::DEV
                            ? ['output' => ['debug' => true]]
                            : [];
                        self::sendProposalEmail(function($outgoingId) use ($state, $opts) {
                            return Monitoring::proposalParams($state, $outgoingId, $opts);
                        }, $params['EMAIL']);
                        $context['resultBlock']['screen'] = 'sent';
                        return $context;
                    });
                }
                return v::render('partials/calculator/monitoring_calculator', $context).self::debugScript();
            });
            $router->respond('POST', '/services/inspection/calculator/[:action]', function($request, $response) {
                $type = 'inspection';
                $defaults = [
                    'STRUCTURES_TO_INSPECT' => []
                ];
                $params = array_merge($defaults, self::normalizeParams($request->params()));
                $data = Services::data($type);
                $state = Inspection::state($params, $request->action, $data, _::get($params, 'validate', true));
                $context = Inspection::calculatorContext($state);
                if ($request->action === 'send_proposal' && _::isEmpty($context['resultBlock']['errors'])) {
                    $context = self::withRecaptcha(function() use ($context, $state, $params) {
                        $opts = App::getInstance()->env() === Env::DEV
                            ? ['output' => ['debug' => true]]
                            : [];
                        self::sendProposalEmail(function($outgoingId) use ($state, $opts) {
                            return Inspection::proposalParams($state, $outgoingId, $opts);
                        }, $params['EMAIL']);
                        $context['resultBlock']['screen'] = 'sent';
                    });
                }
                return v::render('partials/calculator/inspection_calculator', $context).self::debugScript();
            });
            $router->respond('POST', '/services/examination/calculator/[:action]', function($request, $response) {
                $type = 'examination';
                $defaults = [
                    'GOALS' => []
                ];
                $params = array_merge($defaults, self::normalizeParams($request->params()));
                $data = Services::data($type);
                $state = Examination::state($params, $request->action, $data, _::get($params, 'validate', true));
                $context = Examination::calculatorContext($state, ['render_modals' => false]);
                if ($request->action === 'send_proposal' && _::isEmpty($context['resultBlock']['errors'])) {
                    $context = self::withRecaptcha(function() use ($context, $state, $params) {
                        $opts = App::getInstance()->env() === Env::DEV
                            ? ['output' => ['debug' => true]]
                            : [];
                        self::sendProposalEmail(function($outgoingId) use ($state, $opts) {
                            return Examination::proposalParams($state, $outgoingId, $opts);
                        }, $params['EMAIL']);
                        $context['resultBlock']['screen'] = 'sent';
                    });
                }
                return v::render('partials/calculator/examination_calculator', $context).self::debugScript();
            });
            $router->respond('POST', '/services/oversight/calculator/[:action]', function($request, $response) {
                $type = 'oversight';
                $defaults = [
                    'CONSTRUCTION_PHASE' => []
                ];
                $params = array_merge($defaults, self::normalizeParams($request->params()));
                $data = Services::data($type);
                $state = Oversight::state($params, $request->action, $data, _::get($params, 'validate', true));
                $context = Oversight::calculatorContext($state);
                if ($request->action === 'send_proposal' && _::isEmpty($context['resultBlock']['errors'])) {
                    $context = self::withRecaptcha(function() use ($context, $state, $params) {
                        $opts = App::getInstance()->env() === Env::DEV
                            ? ['output' => ['debug' => true]]
                            : [];
                        self::sendProposalEmail(function($outgoingId) use ($state, $opts) {
                            return Oversight::proposalParams($state, $outgoingId, $opts);
                        }, $params['EMAIL']);
                        $context['resultBlock']['screen'] = 'sent';
                    });
                }
                return v::render('partials/calculator/oversight_calculator', $context).self::debugScript();
            });
            $router->respond('POST', '/services/individual/calculator/[:action]', function($request, $response) {
                $type = 'individual';
                $params = $request->params(['SERVICES', 'EMAIL']);
                $params = self::normalizeParams($params);
                $data = Services::data($type);
                $state = Individual::state($params, $request->action, $data, _::get($params, 'validate', true));
                $context = Individual::calculatorContext($state);
                if ($request->action === 'send_proposal' && _::isEmpty($context['resultBlock']['errors'])) {
                    $context = self::withRecaptcha(function() use ($context, $state, $params) {
                        $opts = App::getInstance()->env() === Env::DEV
                            ? ['output' => ['debug' => true]]
                            : [];
                        self::sendProposalEmail(function($outgoingId) use ($state, $opts) {
                            return Individual::proposalParams($state, $outgoingId, $opts);
                        }, $params['EMAIL']);
                        $context['resultBlock']['screen'] = 'sent';
                    });
                }
                return v::render('partials/calculator/individual_calculator', $context).self::debugScript();
            });
            $router->respond('POST', '/services/monitoring', function($request, $response) {
                return self::withRecaptcha(function() use ($request, $response) {
                    $params = $request->params();
                    $state = MonitoringRequest::state($params, Services::data('monitoring'));
                    if (_::isEmpty($state['errors'])) {
                        $fieldsBase = array_merge(_::flatten($params, '_'), [
                            'DOCUMENTS' => _::pluck($state['model']['DOCUMENTS'], 'NAME')
                        ]);
                        $el = new CIBlockElement();
                        $elementId = $el->Add([
                            'IBLOCK_ID' => IblockTools::find(Iblock::INBOX_TYPE, Iblock::MONITORING_REQUESTS)->id(),
                            'NAME' => Services::serviceRequestName($params),
                            'PROPERTY_VALUES' => array_merge($fieldsBase, [
                                'FILES' => array_map([self::class, 'uploadedFileArray'], $params['fileIds'])
                            ])
                        ]);
                        if (!is_numeric($elementId)) {
                            trigger_error("can't add service request element: {$el->LAST_ERROR}", E_USER_WARNING);
                        }
                        $element = _::first(Iblock::collectElements(CIBlockElement::GetByID($elementId)));
                        $formattedFields = Services::markEmptyStrings(_::update($fieldsBase, 'DOCUMENTS', [Services::class, 'formatList']));
                        $eventFields = array_merge($formattedFields, [
                            'EMAIL_TO' => App::getInstance()->adminEmailMaybe(),
                            'FILE_LINKS' => Services::fileLinksSection($element['PROPERTIES']['FILES']['VALUE']),
                        ]);
                        App::getInstance()->sendMail(Events::NEW_SERVICE_REQUEST_MONITORING, $eventFields, App::SITE_ID);
                        $state['screen'] = 'success';
                    }
                    $ctx = MonitoringRequest::context($state, Services::services()['monitoring']);
                    return Components::renderServiceForm('partials/service_forms/monitoring_form', $ctx);
                });
            });
            $router->respond('POST', '/services/inspection', function($request, $response) {
                return self::withRecaptcha(function() use ($request, $response) {
                    $params = $request->params();
                    $state = InspectionRequest::state($params, Services::data('inspection'));
                    if (_::isEmpty($state['errors'])) {
                        $fieldsBase = array_merge(_::flatten($params, '_'), [
                            'DOCUMENTS' => _::pluck($state['model']['DOCUMENTS'], 'NAME')
                        ]);
                        $el = new CIBlockElement();
                        $elementId = $el->Add([
                            'IBLOCK_ID' => IblockTools::find(Iblock::INBOX_TYPE, Iblock::INSPECTION_REQUESTS)->id(),
                            'NAME' => Services::serviceRequestName($params),
                            'PROPERTY_VALUES' => array_merge($fieldsBase, [
                                'FILES' => array_map([self::class, 'uploadedFileArray'], $params['fileIds'])
                            ])
                        ]);
                        if (!is_numeric($elementId)) {
                            trigger_error("can't add service request element: {$el->LAST_ERROR}", E_USER_WARNING);
                        }
                        $element = _::first(Iblock::collectElements(CIBlockElement::GetByID($elementId)));
                        $formattedFields = Services::markEmptyStrings(_::update($fieldsBase, 'DOCUMENTS', [Services::class, 'formatList']));
                        $eventFields = array_merge($formattedFields, [
                            'EMAIL_TO' => App::getInstance()->adminEmailMaybe(),
                            'FILE_LINKS' => Services::fileLinksSection($element['PROPERTIES']['FILES']['VALUE']),
                        ]);
                        App::getInstance()->sendMail(Events::NEW_SERVICE_REQUEST_INSPECTION, $eventFields, App::SITE_ID);
                        $state['screen'] = 'success';
                    }
                    $ctx = InspectionRequest::context($state, Services::services()['inspection']);
                    return Components::renderServiceForm('partials/service_forms/inspection_form', $ctx);
                });
            });
            $router->respond('POST', '/services/examination', function($request, $response) {
                return self::withRecaptcha(function() use ($request, $response) {
                    $params = $request->params();
                    $state = ExaminationRequest::state($params, Services::data('examination'));
                    if (_::isEmpty($state['errors'])) {
                        $fieldsBase = array_merge(_::flatten($params, '_'), [
                            'DOCUMENTS' => _::pluck($state['model']['DOCUMENTS'], 'NAME')
                        ]);
                        $el = new CIBlockElement();
                        $elementId = $el->Add([
                            'IBLOCK_ID' => IblockTools::find(Iblock::INBOX_TYPE, Iblock::EXAMINATION_REQUESTS)->id(),
                            'NAME' => Services::serviceRequestName($params),
                            'PROPERTY_VALUES' => array_merge($fieldsBase, [
                                'FILES' => array_map([self::class, 'uploadedFileArray'], $params['fileIds'])
                            ])
                        ]);
                        if (!is_numeric($elementId)) {
                            trigger_error("can't add service request element: {$el->LAST_ERROR}", E_USER_WARNING);
                        }
                        $element = _::first(Iblock::collectElements(CIBlockElement::GetByID($elementId)));
                        $formattedFields = Services::markEmptyStrings(_::update($fieldsBase, 'DOCUMENTS', [Services::class, 'formatList']));
                        $eventFields = array_merge($formattedFields, [
                            'EMAIL_TO' => App::getInstance()->adminEmailMaybe(),
                            'FILE_LINKS' => Services::fileLinksSection($element['PROPERTIES']['FILES']['VALUE']),
                        ]);
                        App::getInstance()->sendMail(Events::NEW_SERVICE_REQUEST_EXAMINATION, $eventFields, App::SITE_ID);
                        $state['screen'] = 'success';
                    }
                    $ctx = ExaminationRequest::context($state, Services::services()['examination']);
                    return Components::renderServiceForm('partials/service_forms/examination_form', $ctx);
                });
            });
            $router->respond('POST', '/services/individual', function($request, $response) {
                return self::withRecaptcha(function() use ($request, $response) {
                    $params = $request->params();
                    $state = IndividualRequest::state($params, Services::data('individual'));
                    if (_::isEmpty($state['errors'])) {
                        $fieldsBase = array_merge(_::flatten($params, '_'), [
                            'DOCUMENTS' => _::pluck($state['model']['DOCUMENTS'], 'NAME')
                        ]);
                        $el = new CIBlockElement();
                        $elementId = $el->Add([
                            'IBLOCK_ID' => IblockTools::find(Iblock::INBOX_TYPE, Iblock::INDIVIDUAL_REQUESTS)->id(),
                            'NAME' => Services::serviceRequestName($params),
                            'PROPERTY_VALUES' => array_merge($fieldsBase, [
                                'FILES' => array_map([self::class, 'uploadedFileArray'], $params['fileIds'])
                            ])
                        ]);
                        if (!is_numeric($elementId)) {
                            trigger_error("can't add service request element: {$el->LAST_ERROR}", E_USER_WARNING);
                        }
                        $element = _::first(Iblock::collectElements(CIBlockElement::GetByID($elementId)));
                        $formattedFields = Services::markEmptyStrings(_::update($fieldsBase, 'DOCUMENTS', [Services::class, 'formatList']));
                        $eventFields = array_merge($formattedFields, [
                            'EMAIL_TO' => App::getInstance()->adminEmailMaybe(),
                            'FILE_LINKS' => Services::fileLinksSection($element['PROPERTIES']['FILES']['VALUE']),
                        ]);
                        App::getInstance()->sendMail(Events::NEW_SERVICE_REQUEST_INDIVIDUAL, $eventFields, App::SITE_ID);
                        $state['screen'] = 'success';
                    }
                    $ctx = IndividualRequest::context($state, Services::services()['individual']);
                    return Components::renderServiceForm('partials/service_forms/individual_form', $ctx);
                });
            });
            $router->respond('POST', '/services/design', function($request, $response) {
                return self::withRecaptcha(function() use ($request, $response) {
                    $params = $request->params();
                    $state = DesignRequest::state($params);
                    if (_::isEmpty($state['errors'])) {
                        $itemsById = _::keyBy('ID', DesignRequest::items());
                        $fieldsBase = array_merge(_::flatten($params, '_'), [
                            'ITEMS' => array_map(function($id) use ($itemsById) {
                                return _::get($itemsById, [$id, 'NAME']);
                            }, $params['ITEMS'])
                        ]);
                        $el = new CIBlockElement();
                        $elementId = $el->Add([
                            'IBLOCK_ID' => IblockTools::find(Iblock::INBOX_TYPE, Iblock::DESIGN_REQUESTS)->id(),
                            'NAME' => Services::serviceRequestName($params),
                            'PROPERTY_VALUES' => array_merge($fieldsBase, [
                                'FILES' => array_map([self::class, 'uploadedFileArray'], $params['fileIds'])
                            ])
                        ]);
                        if (!is_numeric($elementId)) {
                            trigger_error("can't add service request element: {$el->LAST_ERROR}", E_USER_WARNING);
                        }
                        $element = _::first(Iblock::collectElements(CIBlockElement::GetByID($elementId)));
                        $formattedFields = Services::markEmptyStrings(_::update($fieldsBase, 'ITEMS', [Services::class, 'formatList']));
                        $eventFields = array_merge($formattedFields, [
                            'EMAIL_TO' => App::getInstance()->adminEmailMaybe(),
                            'FILE_LINKS' => Services::fileLinksSection($element['PROPERTIES']['FILES']['VALUE']),
                        ]);
                        App::getInstance()->sendMail(Events::NEW_SERVICE_REQUEST_DESIGN, $eventFields, App::SITE_ID);
                        $state['screen'] = 'success';
                    }
                    $ctx = DesignRequest::context($state, Services::services()['design']);
                    return Components::renderServiceForm('partials/service_forms/design_form', $ctx);
                });
            });
            $router->respond('POST', '/services/oversight', function($request, $response) {
                return self::withRecaptcha(function() use ($request, $response) {
                    $params = $request->params();
                    $state = OversightRequest::state($params, Services::data('oversight'));
                    if (_::isEmpty($state['errors'])) {
                        $fieldsBase = array_merge(_::flatten($params, '_'), [
                            'DOCUMENTS' => _::pluck($state['model']['DOCUMENTS'], 'NAME')
                        ]);
                        $el = new CIBlockElement();
                        $elementId = $el->Add([
                            'IBLOCK_ID' => IblockTools::find(Iblock::INBOX_TYPE, Iblock::OVERSIGHT_REQUESTS)->id(),
                            'NAME' => Services::serviceRequestName($params),
                            'PROPERTY_VALUES' => array_merge($fieldsBase, [
                                'FILES' => array_map([self::class, 'uploadedFileArray'], $params['fileIds'])
                            ])
                        ]);
                        if (!is_numeric($elementId)) {
                            trigger_error("can't add service request element: {$el->LAST_ERROR}", E_USER_WARNING);
                        }
                        $element = _::first(Iblock::collectElements(CIBlockElement::GetByID($elementId)));
                        $formattedFields = Services::markEmptyStrings(_::update($fieldsBase, 'DOCUMENTS', [Services::class, 'formatList']));
                        $eventFields = array_merge($formattedFields, [
                            'EMAIL_TO' => App::getInstance()->adminEmailMaybe(),
                            'FILE_LINKS' => Services::fileLinksSection($element['PROPERTIES']['FILES']['VALUE']),
                        ]);
                        App::getInstance()->sendMail(Events::NEW_SERVICE_REQUEST_OVERSIGHT, $eventFields, App::SITE_ID);
                        $state['screen'] = 'success';
                    }
                    $ctx = OversightRequest::context($state, Services::services()['oversight']);
                    return Components::renderServiceForm('partials/service_forms/oversight_form', $ctx);
                });
            });
            $router->respond('POST', '/fileupload', function($request, $response) {
                // TODO handle errors
                // TODO sanitize params
                $params = $request->params();
                $state = json_decode($params['state'], true);
                $session = $state['session'];
                if ($session === null) {
                    // dots in directory name break `basename`
                    $session = str::replace(uniqid('fileupload-', true), '.', '');
                }
                // `basename` sort of sanitizes user input
                $session = basename($session);
                $uploadDir = Util::joinPath([self::fileuploadDir(), $session]);
                mkdir($uploadDir);
                $fileupload = new FileUpload($_FILES['files'], $_SERVER);
                $filesystem = new FileSystem\Simple();
                $pathResolver = new PathResolver\Simple($uploadDir);
                $fileupload->setFileSystem($filesystem);
                $fileupload->setPathResolver($pathResolver);
                $allowed = array_merge(MimeTypes::document(), ['text/*', 'image/*', 'video/*']);
                $limitMb = 25;
                // see also client side file size validation (might not be implemented)
                $sizeValidator = new Validator\SizeValidator($limitMb.'M');
                $sizeValidator->setErrorMessages([
                    Validator\SizeValidator::FILE_SIZE_IS_TOO_LARGE => "Максимальный размер файла: {$limitMb} МБ",
                    Validator\SizeValidator::FILE_SIZE_IS_TOO_SMALL => "Минимальный размер файла: 0",
                ]);
                $fileupload->addValidator($sizeValidator);
                $mimeValidator = new MimeTypeValidator($allowed, function ($isValid, $mime) {
                    if (!$isValid) {
                        // TODO log to sentry
//                        trigger_error("rejected file upload with mime type `{$mime}`", E_USER_ERROR);
                    }
                });
                $mimeValidator->setErrorMessages([
                    MimeTypeValidator::INVALID_MIMETYPE => 'Недопустимый тип файла'
                ]);
                $fileupload->addValidator($mimeValidator);
                list($files, $headers) = $fileupload->processAll();
                foreach($headers as $header => $value) {
                    header($header.': '.$value);
                }
                $fileArrays = array_map(function(\FileUpload\File $file) {
                    $absPath = $file->getRealPath();
                    $filename = $file->getFilename();
                    list($name, $ext) = Util::splitFileExtension($absPath);
                    return array_merge((array) $file, [
                        'name' => $name,
                        'filename' => $filename,
                        // TODO refactor: combine this and news.list/certificates/result_modifier.php
                        'extension' => $ext,
                        'humanSize' => Util::humanFileSize(filesize($absPath))
                    ]);
                }, $files);
                return $response->json([
                    'session' => $session,
                    'files' => $fileArrays
                ]);
            });
        });
        return $router;
    }
}