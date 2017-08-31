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
use CFile;
use CIBlockElement;
use Core\Env;
use Core\FileUpload;
use Core\Strings as str;
use Core\Underscore as _;
use Core\Util;
use FileUpload\FileSystem;
use FileUpload\PathResolver;
use Klein\Klein;

class Api {
    static function fileuploadDir() {
        // TODO ok tmp dir?
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

    static function sendProposalEmail($proposalParams, $email) {
        $response = Services::generateProposalFile($proposalParams);
        // TODO refactor: move error handling to `generateProposalFile`
        assert($response !== false, $response);
        $path = $response;
        assert(file_exists($path), $path);
        return Services::sendProposalEmail($email, [$path]);
    }

    // TODO tmp: debugging data
    static function debugScript() {
        $json = json_encode(Services\Calculator::$debug);
        return "<script>console.log({$json})</script>";
    }

    // TODO sanitize params
    static function router() {
        $router = new Klein();
        $router->with('/api', function () use ($router) {
            $router->respond('POST', '/callback-request', function($request, $response) {
                // TODO sanitize params
                $params = $request->params();
                $state = App::requestCallback($params);
                return v::render('partials/callback_request_form', [
                    'state' => $state
                ]);
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
                    $opts = App::getInstance()->env() === Env::DEV
                        ? ['output' => ['debug' => true]]
                        : [];
                    $recordId = Services::recordProposal($type, $params['EMAIL']);
                    $proposalParams = Monitoring::proposalParams($state, Services::outgoingId($type, $recordId), $opts);
                    self::sendProposalEmail($proposalParams, $params['EMAIL']);
                    $context['resultBlock']['screen'] = 'sent';
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
                    $opts = App::getInstance()->env() === Env::DEV
                        ? ['output' => ['debug' => true]]
                        : [];
                    $recordId = Services::recordProposal($type, $params['EMAIL']);
                    $proposalParams = Inspection::proposalParams($state, Services::outgoingId($type, $recordId), $opts);
                    self::sendProposalEmail($proposalParams, $params['EMAIL']);
                    $context['resultBlock']['screen'] = 'sent';
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
                $context = Examination::calculatorContext($state);
                if ($request->action === 'send_proposal' && _::isEmpty($context['resultBlock']['errors'])) {
                    $opts = App::getInstance()->env() === Env::DEV
                        ? ['output' => ['debug' => true]]
                        : [];
                    $recordId = Services::recordProposal($type, $params['EMAIL']);
                    $proposalParams = Examination::proposalParams($state, Services::outgoingId($type, $recordId), $opts);
                    self::sendProposalEmail($proposalParams, $params['EMAIL']);
                    $context['resultBlock']['screen'] = 'sent';
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
                    $opts = App::getInstance()->env() === Env::DEV
                        ? ['output' => ['debug' => true]]
                        : [];
                    $recordId = Services::recordProposal($type, $params['EMAIL']);
                    $proposalParams = Oversight::proposalParams($state, Services::outgoingId($type, $recordId), $opts);
                    self::sendProposalEmail($proposalParams, $params['EMAIL']);
                    $context['resultBlock']['screen'] = 'sent';
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
                    $opts = App::getInstance()->env() === Env::DEV
                        ? ['output' => ['debug' => true]]
                        : [];
                    $recordId = Services::recordProposal($type, $params['EMAIL']);
                    $proposalParams = Individual::proposalParams($state, Services::outgoingId($type, $recordId), $opts);
                    self::sendProposalEmail($proposalParams, $params['EMAIL']);
                    $context['resultBlock']['screen'] = 'sent';
                }
                return v::render('partials/calculator/individual_calculator', $context).self::debugScript();
            });
            $router->respond('POST', '/services/monitoring', function($request, $response) {
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
            $router->respond('POST', '/services/inspection', function($request, $response) {
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
            $router->respond('POST', '/services/examination', function($request, $response) {
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
            $router->respond('POST', '/services/individual', function($request, $response) {
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
            $router->respond('POST', '/services/design', function($request, $response) {
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
            $router->respond('POST', '/services/oversight', function($request, $response) {
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
                $filesystem = new FileSystem\Simple($uploadDir);
                $pathResolver = new PathResolver\Simple($uploadDir);
                $fileupload = new FileUpload($_FILES['files'], $_SERVER);
                $fileupload->setFileSystem($filesystem);
                $fileupload->setPathResolver($pathResolver);
                list($files, $headers) = $fileupload->processAll();
                foreach($headers as $header => $value) {
                    header($header.': '.$value);
                }
                $fileArrays = array_map(function($file) {
                    /** @var \FileUpload\File $file */
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
                return json_encode([
                    'session' => $session,
                    'files' => $fileArrays
                ]);
            });
        });
        return $router;
    }
}