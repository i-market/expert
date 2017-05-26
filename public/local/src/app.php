<?php

namespace App;

use Core\NewsListLike;
use Core\ShareButtons;
use League\Plates\Engine;
use Bitrix\Main\Config\Configuration;
use Core\Underscore as _;
use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Validator as v;

class App extends \Core\App {
    const SITE_ID = 's1';

    /**
     * @var Engine
     */
    private static $templates;
    private static $layoutFooter;

    static function templates() {
        global $APPLICATION, $USER;
        if (!isset(self::$templates)) {
            self::$templates = new Engine($_SERVER['DOCUMENT_ROOT'].SITE_TEMPLATE_PATH);
            self::$templates->addData(['APPLICATION' => $APPLICATION, 'USER' => $USER]);
        }
        return self::$templates;
    }

    static function renderLayoutHeader() {
        global $APPLICATION;
        $layout = $APPLICATION->GetProperty('layout', 'default');
        $placeholder = '{{ content-placeholder }}';
        $html = self::templates()->render('layouts/'.$layout, ['content' => $placeholder]);
        list($header, $footer) = explode($placeholder, $html);
        self::$layoutFooter = $footer;
        return $header;
    }

    static function renderLayoutFooter() {
        return self::$layoutFooter;
    }

    static function layoutContext() {
        global $APPLICATION;
        $sentryConfig = _::get(Configuration::getValue('app'), 'sentry');
        $isHomepage = $APPLICATION->GetCurPage() === '/';
        return [
            'showTopBannersFn' => function() use (&$APPLICATION, $isHomepage) {
                return !$isHomepage && !$APPLICATION->GetProperty('hide_top_banners', false);
            },
            'showBottomBannersFn' => function() use (&$APPLICATION, $isHomepage) {
                return !$isHomepage && !$APPLICATION->GetProperty('hide_bottom_banners', false);
            },
            'shareUrlsFn' => function() use (&$APPLICATION) {
                // defer to get the title
                return ShareButtons::shareUrls(self::requestUrl(), $APPLICATION->GetTitle());
            },
            'sentry' => [
                'enabled' => $sentryConfig['enabled'],
                'env' => self::env(),
                'publicDsn' => $sentryConfig['public_dsn']
            ],
            'copyrightYear' => date('Y')
        ];
    }

    static function requestCallback($params) {
        $validator = v::key('CONTACT_PERSON', v::stringType()->notEmpty());
        $errors = [];
        try {
            $validator->assert($params);
        } catch (NestedValidationException $exception) {
            // TODO refactor: extract validation stuff
            $errors = Services::getMessages($exception);
        }
        $state = [
            'params' => $params,
            'errors' => $errors
        ];
        $isValid = _::isEmpty($errors);
        if ($isValid) {
            // TODO side effects
            $state['screen'] = 'success';
        }
        return $state;
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
                'js/vendor/tooltipster.bundle.min.js',
                'js/vendor/core.js',
                'js/vendor/dropdown.js', // dropdown.js depends on core.js
                'js/vendor/jquery.ui.widget.js',
                'js/vendor/jquery.iframe-transport.js',
                'js/vendor/jquery.fileupload.js',
                'js/vendor/lodash.js',
                'js/vendor/intercooler.js',
                'js/script.js',
                'js/main.js',
            ])
        );
        return [
            'styles' => $styles,
            'scripts' => $scripts
        ];
    }
}

class View extends \Core\View {
    use NewsListLike;

    static function render($name, $data = array()) {
        return App::templates()->render($name, $data);
    }
}

