<?php

namespace App;

use Core\Util;
use Klein\Klein;
use App\View as v;
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
            $router->respond('POST', '/services/monitoring/calculate', function($request, $response) {
                // TODO sanitize params
                $params = $request->params();
                $monitoring = App::getInstance()->getMonitoring();
                return $monitoring->renderCalculator($params);
            });
            $router->respond('POST', '/services/monitoring/calculate/floors', function($request, $response) {
                // TODO sanitize params
                $params = $request->params();
                // TODO state
                $state = [
                    'params' => $params
                ];
                $monitoring = App::getInstance()->getMonitoring();
                return v::render('partials/calculator/monitoring_floors_group', [
                    'floorSelects' => $monitoring->floorSelects($state)
                ]);
            });
            $router->respond('POST', '/services/monitoring', function($request, $response) {
                // TODO sanitize params
                $params = $request->params();
                $state = Services::requestMonitoring($params);
                return Components::renderServiceForm('partials/service_forms/monitoring_form', [
                    'service' => Services::services()['monitoring'],
                    'state' => $state
                ]);
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