<?php

namespace App;

use App\Services\Examination;
use App\Services\Individual;
use App\Services\Inspection;
use App\Services\InspectionRequest;
use App\Services\Monitoring;
use App\Services\MonitoringRequest;
use App\Services\Oversight;
use App\View as v;
use CFile;
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
        $path = Services::generateProposalFile($proposalParams);
        assert($path !== false);
        assert(file_exists($path), $path);
        return Services::sendProposalEmail($email, [$path]);
    }

    // TODO debugging data
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
                $defaults = [
                    'STRUCTURES_TO_MONITOR' => []
                ];
                $params = array_merge($defaults, self::normalizeParams($request->params()));
                $data = Services::data('monitoring');
                $state = Monitoring::state($params, $request->action, $data, _::get($params, 'validate', true));
                $context = Monitoring::calculatorContext($state);
                if ($request->action === 'send_proposal' && _::isEmpty($context['resultBlock']['errors'])) {
                    $opts = App::getInstance()->env() === Env::DEV
                        ? ['output' => ['debug' => true]]
                        : [];
                    $proposalParams = Monitoring::proposalParams($state, Services::outgoingId('monitoring'), $opts);
                    self::sendProposalEmail($proposalParams, $params['EMAIL']);
                    $context['resultBlock']['screen'] = 'sent';
                }
                return v::render('partials/calculator/monitoring_calculator', $context).self::debugScript();
            });
            $router->respond('POST', '/services/inspection/calculator/[:action]', function($request, $response) {
                $defaults = [
                    'STRUCTURES_TO_INSPECT' => []
                ];
                $params = array_merge($defaults, self::normalizeParams($request->params()));
                $data = Services::data('inspection');
                $state = Inspection::state($params, $request->action, $data, _::get($params, 'validate', true));
                $context = Inspection::calculatorContext($state);
                if ($request->action === 'send_proposal' && _::isEmpty($context['resultBlock']['errors'])) {
                    $opts = App::getInstance()->env() === Env::DEV
                        ? ['output' => ['debug' => true]]
                        : [];
                    $proposalParams = Inspection::proposalParams($state, Services::outgoingId('inspection'), $opts);
                    self::sendProposalEmail($proposalParams, $params['EMAIL']);
                    $context['resultBlock']['screen'] = 'sent';
                }
                return v::render('partials/calculator/inspection_calculator', $context).self::debugScript();
            });
            $router->respond('POST', '/services/examination/calculator/[:action]', function($request, $response) {
                $defaults = [
                    'GOALS' => []
                ];
                $params = array_merge($defaults, self::normalizeParams($request->params()));
                $data = Services::data('examination');
                $state = Examination::state($params, $request->action, $data, _::get($params, 'validate', true));
                $context = Examination::calculatorContext($state);
                if ($request->action === 'send_proposal' && _::isEmpty($context['resultBlock']['errors'])) {
                    $opts = App::getInstance()->env() === Env::DEV
                        ? ['output' => ['debug' => true]]
                        : [];
                    $proposalParams = Examination::proposalParams($state, Services::outgoingId('examination'), $opts);
                    self::sendProposalEmail($proposalParams, $params['EMAIL']);
                    $context['resultBlock']['screen'] = 'sent';
                }
                return v::render('partials/calculator/examination_calculator', $context).self::debugScript();
            });
            $router->respond('POST', '/services/oversight/calculator/[:action]', function($request, $response) {
                $defaults = [
                    'CONSTRUCTION_PHASE' => []
                ];
                $params = array_merge($defaults, self::normalizeParams($request->params()));
                $data = Services::data('oversight');
                $state = Oversight::state($params, $request->action, $data, _::get($params, 'validate', true));
                $context = Oversight::calculatorContext($state);
                if ($request->action === 'send_proposal' && _::isEmpty($context['resultBlock']['errors'])) {
                    $opts = App::getInstance()->env() === Env::DEV
                        ? ['output' => ['debug' => true]]
                        : [];
                    $proposalParams = Oversight::proposalParams($state, Services::outgoingId('oversight'), $opts);
                    self::sendProposalEmail($proposalParams, $params['EMAIL']);
                    $context['resultBlock']['screen'] = 'sent';
                }
                return v::render('partials/calculator/oversight_calculator', $context).self::debugScript();
            });
            $router->respond('POST', '/services/individual/calculator/[:action]', function($request, $response) {
                $defaults = [
                    // TODO?
                ];
                $params = $request->params(['SERVICES']);
                $params = array_merge($defaults, self::normalizeParams($params));
                $data = Services::data('individual');
                $state = Individual::state($params, $request->action, $data, _::get($params, 'validate', true));
                $context = Individual::calculatorContext($state);
                if ($request->action === 'send_proposal' && _::isEmpty($context['resultBlock']['errors'])) {
                    $opts = App::getInstance()->env() === Env::DEV
                        ? ['output' => ['debug' => true]]
                        : [];
                    $proposalParams = Individual::proposalParams($state, Services::outgoingId('individual'), $opts);
                    self::sendProposalEmail($proposalParams, $params['EMAIL']);
                    $context['resultBlock']['screen'] = 'sent';
                }
                return v::render('partials/calculator/individual_calculator', $context).self::debugScript();
            });
            $router->respond('POST', '/services/monitoring', function($request, $response) {
                $params = $request->params();
                $state = MonitoringRequest::state($params, Services::data('monitoring'));
                $ctx = MonitoringRequest::context($state, Services::services()['monitoring']);
                if (_::isEmpty($state['errors'])) {
                    // TODO side effects
                }
                return Components::renderServiceForm('partials/service_forms/monitoring_form', $ctx);
            });
            $router->respond('POST', '/services/inspection', function($request, $response) {
                $params = $request->params();
                $state = InspectionRequest::state($params, Services::data('inspection'));
                $ctx = InspectionRequest::context($state, Services::services()['inspection']);
                if (_::isEmpty($state['errors'])) {
                    // TODO side effects
                }
                return Components::renderServiceForm('partials/service_forms/inspection_form', $ctx);
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