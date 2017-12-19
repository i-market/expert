<?php

namespace App;

use App\Services\Monitoring;
use App\Services\MonitoringParser;
use App\Services\MonitoringRepo;
use Bex\Tools\Iblock\IblockTools;
use Bitrix\Main\Loader;
use CIBlockElement;
use Core\NewsListLike;
use Core\ShareButtons;
use League\Plates\Engine;
use Bitrix\Main\Config\Configuration;
use Core\Underscore as _;
use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Validator as v;

// TODO non-ideal way to distinguish between environments
if (class_exists('Bitrix\Main\Loader')) {
    Loader::includeModule('iblock');
}

class App extends \Core\App {
    const SITE_ID = 's1';
    const CACHE_DIR = 'app';

    /**
     * @var Engine
     */
    private static $templates;
    private static $layoutFooter;

    function init() {
        EventHandlers::attach();
    }

    function isDebugEnabled() {
        if (php_sapi_name() === 'cli') return false; // TODO hack
        return _::get(Configuration::getValue('app'), 'debug', false);
    }

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
        assert(isset(self::$layoutFooter));
        return self::$layoutFooter;
    }

    function layoutContext() {
        global $APPLICATION;
        $sentryCfg = _::get(Configuration::getValue('app'), 'sentry', [
            'enabled' => false
        ]);
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
                return ShareButtons::shareUrls(self::requestUrl(), $APPLICATION->GetTitle(false));
            },
            'adminEmailMaybe' => $this->adminEmailMaybe(),
            'sentry' => [
                'enabled' => _::get($sentryCfg, 'enabled'),
                'env' => self::env(),
                'publicDsn' => _::get($sentryCfg, 'public_dsn')
            ]
        ];
    }

    static function requestCallback($params) {
        $validator = v::key('CONTACT_PERSON', v::stringType()->notEmpty());
        $errors = [];
        try {
            $validator->assert($params);
        } catch (NestedValidationException $exception) {
            $errors = Services::getMessages($exception);
        }
        $state = [
            'params' => $params,
            'errors' => $errors
        ];
        $isValid = _::isEmpty($errors);
        if ($isValid) {
            $el = new CIBlockElement();
            $fields = _::pick($params, ['CONTACT_PERSON', 'PHONE'], true);
            $result = $el->Add([
                'IBLOCK_ID' => IblockTools::find(Iblock::INBOX_TYPE, Iblock::CALLBACK_REQUESTS)->id(),
                'NAME' => $params['CONTACT_PERSON'],
                'PROPERTY_VALUES' => $fields
            ]);
            if ($result === false) {
                trigger_error("can't save the callback request", E_USER_WARNING);
            }
            App::getInstance()->sendMail(Events::CALLBACK_REQUEST, array_merge($fields, [
                'EMAIL_TO' => App::getInstance()->adminEmailMaybe()
            ]), App::SITE_ID);
            $state['screen'] = 'success';
        }
        return $state;
    }

    static function assets() {
        // using css and js from different versions of slick. fingers crossed.
        $styles = array_merge(
            [
                'https://fonts.googleapis.com/css?family=Open+Sans:300,400,700,800&amp;subset=cyrillic-ext'
            ],
            array_map(function($path) {
                return View::asset($path);
            }, [
                'css/lib/normalize.min.css',
                'css/lib/jquery.fancybox.min.css',
                'css/lib/slick.css',
                'css/lib/tooltipster.bundle.min.css',
                'css/lib/dropdown.css',
                'css/main.css'
            ])
        );
        $scripts = array_merge(
            [
                '//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js',
            ],
            array_map(function($path) {
                return View::asset($path);
            }, [
                'js/vendor/jquery.fancybox.min.js',
                'js/vendor/jquery.easing-1.3.pack.js',
                'js/vendor/jquery.mousewheel-3.0.4.pack.js',
                'js/vendor/slick.js',
                'js/vendor/tooltipster.bundle.min.js',
                'js/vendor/core.js',
                'js/vendor/dropdown.js', // dropdown.js depends on core.js

                // fileupload
                'js/vendor/jquery.ui.widget.js',
                'js/vendor/jquery.iframe-transport.js',
                'js/vendor/jquery.fileupload.js',
//                'js/vendor/jquery.fileupload-process.js',
//                'js/vendor/jquery.fileupload-validate.js', // depends on jquery.fileupload-process.js

                'js/vendor/lodash.js',
                'js/vendor/intercooler.js',
                'js/script.js',
                'js/main.js',
            ]),
            [
                // make sure recaptcha loads after onload callback is defined
                'https://www.google.com/recaptcha/api.js?onload=initRecaptcha&render=explicit'
            ]
        );
        return [
            'styles' => $styles,
            'scripts' => $scripts
        ];
    }

    static function recaptchaKey() {
        return _::get(Configuration::getValue('app'), 'recaptcha.site_key');
    }
}

class View extends \Core\View {
    use NewsListLike;

    static function render($name, $data = []) {
        return App::templates()->render($name, $data);
    }
}

class Events {
    const PROPOSAL = 'PROPOSAL';
    const CALLBACK_REQUEST = 'CALLBACK_REQUEST';
    const NEW_SERVICE_REQUEST_MONITORING  = 'NEW_SERVICE_REQUEST_MONITORING';
    const NEW_SERVICE_REQUEST_INSPECTION  = 'NEW_SERVICE_REQUEST_INSPECTION';
    const NEW_SERVICE_REQUEST_EXAMINATION = 'NEW_SERVICE_REQUEST_EXAMINATION';
    const NEW_SERVICE_REQUEST_INDIVIDUAL  = 'NEW_SERVICE_REQUEST_INDIVIDUAL';
    const NEW_SERVICE_REQUEST_DESIGN      = 'NEW_SERVICE_REQUEST_DESIGN';
    const NEW_SERVICE_REQUEST_OVERSIGHT   = 'NEW_SERVICE_REQUEST_OVERSIGHT';
}

class Videos {
    static function youtubeSnippetMaybe($videoId) {
        $query = http_build_query([
            'key' => _::get(Configuration::getValue('app'), 'youtube_data_api.key'),
            'part' => 'snippet',
            'id' => $videoId
        ]);
        $result = file_get_contents('https://www.googleapis.com/youtube/v3/videos?'.$query);
        if ($result === false) {
            return null;
        } else {
            return json_decode($result);
        }
    }

    static function youtubeIdMaybe($url) {
        $matchesRef = array();
        // https://github.com/mpratt/Embera/blob/master/Lib/Embera/Providers/Youtube.php#L30
        if (preg_match('~(?:v=|youtu\.be/|youtube\.com/embed/)([a-z0-9_-]+)~i', $url, $matchesRef)) {
            return $matchesRef[1];
        } else {
            return null;
        }
    }
}
