<?php

namespace App;

use App\Services\Inspection;
use App\Services\InspectionParser;
use App\Services\Monitoring;
use App\Services\MonitoringParser;
use App\Services\MonitoringRequest;
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
        $params = _::update($params, 'HAS_UNDERGROUND_FLOORS', 'boolval');
        $params = array_merge([
            'DOCUMENTS' => []
        ], $params);
        return $params;
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
                // TODO tmp data
                $data = (new MonitoringParser)->parseFile(Util::joinPath([$_SERVER['DOCUMENT_ROOT'], 'local/fixtures/calculator/Мониторинг калькуляторы.xlsx']));
                $state = Monitoring::state($params, $request->action, $data, _::get($params, 'validate', true));
                $context = Monitoring::calculatorContext($state);
                if ($request->action === 'send_proposal' && _::isEmpty($context['resultBlock']['errors'])) {
                    $opts = App::getInstance()->env() === Env::DEV
                        ? ['output' => ['debug' => true]]
                        : [];
                    $proposalParams = Monitoring::proposalParams($state, Services::outgoingId('monitoring'), $opts);
                    $path = Services::generateProposalFile($proposalParams);
                    assert($path !== false);
                    Services::sendProposalEmail($params['EMAIL'], [$path]);
                    $context['resultBlock']['screen'] = 'sent';
                }
                return v::render('partials/calculator/monitoring_calculator', $context);
            });
            $router->respond('POST', '/services/inspection/calculator/[:action]', function($request, $response) {
                $defaults = [
                    'STRUCTURES_TO_INSPECT' => []
                ];
                $params = array_merge($defaults, self::normalizeParams($request->params()));
                // TODO tmp data
                $data = (new InspectionParser)->parseFile(Util::joinPath([$_SERVER['DOCUMENT_ROOT'], 'local/fixtures/calculator/Обследование калькуляторы.xlsx']));
                $state = Inspection::state($params, $request->action, $data, _::get($params, 'validate', true));
                $context = Inspection::calculatorContext($state);
                if ($request->action === 'send_proposal' && _::isEmpty($context['resultBlock']['errors'])) {
                    $opts = App::getInstance()->env() === Env::DEV
                        ? ['output' => ['debug' => true]]
                        : [];
                    $proposalParams = Inspection::proposalParams($state, Services::outgoingId('inspection'), $opts);
                    $path = Services::generateProposalFile($proposalParams);
                    assert($path !== false);
                    Services::sendProposalEmail($params['EMAIL'], [$path]);
                    $context['resultBlock']['screen'] = 'sent';
                }
                return v::render('partials/calculator/inspection_calculator', $context);
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