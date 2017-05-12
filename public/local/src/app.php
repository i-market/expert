<?php

namespace App;

use League\Plates\Engine;
use Bitrix\Main\Config\Configuration;
use Core\Underscore as _;

class App extends \Core\App {
    const SITE_ID = 's1';

    /**
     * @var Engine
     */
    private static $templates;

    static function templates() {
        if (!self::$templates) {
            self::$templates = new Engine($_SERVER['DOCUMENT_ROOT'].SITE_TEMPLATE_PATH);
        }
        return self::$templates;
    }

    static function layoutContext() {
        $sentryConfig = _::get(Configuration::getValue('app'), 'sentry');
        return [
            'sentry' => [
                'enabled' => $sentryConfig['enabled'],
                'env' => self::env(),
                'publicDsn' => $sentryConfig['public_dsn']
            ],
            'copyrightYear' => date('Y')
        ];
    }

    static function assets() {
        $styles = array_map(function($path) {
            return View::asset($path);
        }, [
            'css/lib/normalize.min.css',
            'css/lib/jquery.fancybox.min.css',
            'css/lib/slick.css',
            'css/main.css'
        ]);
        $scripts = array_merge(
            [
                '//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js',
            ],
            array_map(function($path) {
                return View::asset($path);
            }, [
                'js/vendor/jquery.fancybox.min.js',
                'js/vendor/slick.min.js',
                'js/script.js',
            ])
        );
        return [
            'styles' => $styles,
            'scripts' => $scripts
        ];
    }
}

class View extends \Core\View {
    static function render($name, $data = array()) {
        return App::templates()->render($name, $data);
    }
}