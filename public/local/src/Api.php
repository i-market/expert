<?php

namespace App;

use App\Services\Monitoring;
use App\Services\MonitoringRepo;
use Core\Env;
use Core\Underscore as _;
use Core\Util;
use Klein\Klein;
use App\View as v;
use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Validator as val;
use Core\FileUpload;
use FileUpload\FileSystem;
use FileUpload\PathResolver;
use Core\Strings as str;

class Api {
    static function fileuploadDir() {
        // TODO ok tmp dir?
        return ini_get('upload_tmp_dir')
            ? ini_get('upload_tmp_dir')
            : sys_get_temp_dir();
    }

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
                // TODO sanitize params
                // TODO refactor
                $params = $request->params();
                // TODO extract
                $parseInt = function($s) {
                    return str::isEmpty($s) ? null : intval($s);
                };
                foreach (['SITE_COUNT', 'TOTAL_AREA', 'UNDERGROUND_FLOORS', 'VOLUME'] as $k) {
                    $params = _::update($params, $k, $parseInt);
                }
                $params = _::update($params, 'FLOORS', function($values) use ($parseInt) {
                    return array_map($parseInt, $values);
                });
                $params = _::update($params, 'HAS_UNDERGROUND_FLOORS', 'boolval');
                $params = array_merge([
                    // defaults
                    'STRUCTURES_TO_MONITOR' => [],
                    'DOCUMENTS' => []
                ], $params);
                $data = (new MonitoringRepo)->data();
                $dataSet = Monitoring::dataSet($data, $params);
                $monitoring = App::getInstance()->getMonitoring();
                $state = $monitoring->calculate($params, $dataSet);
                $context = $monitoring->calculatorContext($state);
                if ($request->action === 'calculate') {
                    return v::render('partials/calculator/monitoring_calculator', $context);
                } elseif ($request->action === 'proposal') {
                    $resultCtx = $context['result'];
                    if (_::get($resultCtx, 'screen') === 'result') {
                        $errors = [];
                        try {
                            val::key('EMAIL', val::email())->assert($params);
                        } catch (NestedValidationException $exception) {
                            $errors = Services::getMessages($exception);
                        }
                        $resultCtx['errors'] = $errors;
                        if (_::isEmpty($errors)) {
                            // TODO
                            $requestId = 42;
                            $deref = function($val, $k) use (&$deref, $dataSet, $params) {
                                if (is_int($val)) {
                                    return $val;
                                } elseif (is_array($val)) {
                                    return array_map(function($v) use (&$deref, $k) {
                                        return $deref($v, $k);
                                    }, $val);
                                } else {
                                    $entityMaybe = Monitoring::findEntity($k, $val, $dataSet);
                                    return _::get($entityMaybe, 'NAME', $val);
                                }
                            };
                            $derefedParams = _::map($params, $deref);
                            $tables = Monitoring::proposalTables($derefedParams);
                            $path = App::getInstance()->env() !== Env::DEV
                                ? tempnam(sys_get_temp_dir(), 'proposal')
                                // for easier debugging
                                : Util::joinPath([$_SERVER['DOCUMENT_ROOT'], 'local/proposal.pdf']);
                            $proposalParams = Monitoring::proposalParams($requestId, [
                                'total_price' => $state['result']['total_price'],
                                'duration' => $resultCtx['duration'],
                                'tables' => $tables,
                                'output' => [
                                    'name' => $path
                                ]
                            ]);
                            $requestCtx  = stream_context_create([
                                'http' => [
                                    'method'  => 'POST',
                                    'header'  => 'Content-type: application/x-www-form-urlencoded',
                                    'content' => http_build_query($proposalParams)
                                ]
                            ]);
                            // TODO respond with some indication of success
                            $response = file_get_contents('http://localhost/proposals/', false, $requestCtx);
                            assert(filesize($path) !== 0);
                            $event = [
                                'EMAIL_TO' => $params['EMAIL'],
                                'FILE' => [$path]
                            ];
                            App::getInstance()->sendMail(Events::PROPOSAL, $event, App::SITE_ID);
                            $resultCtx['screen'] = 'sent';
                        }
                    }
                    return v::render('partials/calculator/result_block', [
                        'result' => $resultCtx,
                        'email' => $params['EMAIL']
                    ]);

                } else {
                    // TODO unsupported action
                    assert(false);
                    return '';
                }
            });
            $router->respond('POST', '/services/monitoring', function($request, $response) {
                // TODO sanitize params
                $params = $request->params();
                $state = Services::requestMonitoring($params);
                $ctx = App::getInstance()->getMonitoring()->context(Services::services()['monitoring'], $state);
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