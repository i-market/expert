<?php

namespace App;

use Bitrix\Iblock\SectionTable;
use Bitrix\Main\Loader;
use Core\NewsListLike;
use Core\ShareButtons;
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
        global $APPLICATION, $USER;
        if (!isset(self::$templates)) {
            self::$templates = new Engine($_SERVER['DOCUMENT_ROOT'].SITE_TEMPLATE_PATH);
            self::$templates->addData(['APPLICATION' => $APPLICATION, 'USER' => $USER]);
        }
        return self::$templates;
    }

    static function layoutContext() {
        global $APPLICATION;
        $sentryConfig = _::get(Configuration::getValue('app'), 'sentry');
        return [
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
    use NewsListLike;

    static function render($name, $data = array()) {
        return App::templates()->render($name, $data);
    }
}

Loader::includeModule('iblock');

class Iblock {
    const CONTENT_TYPE = 'content';
    const SLIDER = 'slider';
    const HOMEPAGE_BANNERS = 'homepage_banners';
    const OUR_SITES = 'our_sites';
    const OUR_CLIENTS = 'our_clients';
    const TESTIMONIALS = 'testimonials';
    const RESOURCE_LINKS = 'resource_links';

    static function groupBySection($elements, $iblockId) {
        $sections = _::keyBy('ID', SectionTable::query()
            ->setSelect(['ID', 'NAME'])
            ->setFilter(['IBLOCK_ID' => $iblockId])
            ->exec()->fetchAll());
        $grouped = _::groupBy($elements, 'IBLOCK_SECTION_ID');
        return _::reduce($grouped, function($acc, $items, $sectionId) use ($sections) {
            return _::append($acc, array_merge($sections[$sectionId], [
                'ITEMS' => $items
            ]));
        }, []);
    }
}