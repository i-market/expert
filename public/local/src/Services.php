<?php

namespace App;

use Bex\Tools\Iblock\IblockTools;
use Bitrix\Main\Loader;
use CIBlockElement;
use Respect\Validation\Validator as val;
use App\View as v;
use Core\Underscore as _;

Loader::includeModule('iblock');

class Services {
    static function services() {
        $el = new CIBlockElement();
        $iblockId = IblockTools::find(Iblock::SERVICES_TYPE, Iblock::SERVICES)->id();
        $elements = Iblock::collectElements($el->GetList(['SORT' => 'ASC'], ['IBLOCK_ID' => $iblockId]));
        $pathRoot = 'what-we-do';
        return _::keyBy('code', array_map(function($element) use ($pathRoot) {
            $code = $element['CODE'];
            $detail = $pathRoot.'/'.$code;
            return [
                'code' => $code,
                'name' => $element['NAME'],
                'requestFormSubheading' => $element['PROPERTIES']['REQUEST_FORM_SUBHEADING']['VALUE'],
                'detailLink' => v::path($detail),
                'calcLink' => v::path($detail.'/calculator'),
                'requestModalId' => 'request-'.$code,
                'apiEndpoint' => '/api/services/'.$code
            ];
        }, $elements));
    }

    static function requestMonitoring($data) {
        return 'hi';
    }
}