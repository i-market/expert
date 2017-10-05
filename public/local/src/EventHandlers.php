<?php

namespace App;

use App\Services\Parser;
use Bitrix\Main\Data\Cache;
use CIBlockElement;
use Core\Util;
use Exception;
use Bex\Tools\Iblock\IblockTools;
use Bitrix\Iblock\PropertyTable;
use CFile;
use Core\Underscore as _;
use Core\Nullable as nil;

class EventHandlers {
    static function attach() {
        AddEventHandler('iblock', 'OnBeforeIBlockElementAdd', self::class.'::dispatchBeforeAddOrUpdate');
        AddEventHandler('iblock', 'OnBeforeIBlockElementUpdate', self::class.'::dispatchBeforeAddOrUpdate');
    }
    
    static function dispatchBeforeAddOrUpdate(&$fieldsRef) {
        $iblockId = $fieldsRef['IBLOCK_ID'];
        if ($iblockId === IblockTools::find(Iblock::CONTENT_TYPE, Iblock::VIDEOS)->id()) {
            return self::beforeVideoAddOrUpdate($fieldsRef);
        } elseif ($iblockId === IblockTools::find(Iblock::SERVICES_TYPE, Iblock::SERVICE_DATA)->id()) {
            return self::beforeServiceDataAddOrUpdate($fieldsRef);
        }
        return true;
    }

    static function beforeServiceDataAddOrUpdate(&$fieldsRef) {
        global $APPLICATION;
        $fileProp = PropertyTable::query()->setSelect(['ID'])->setFilter([
            'IBLOCK_ID' => $fieldsRef['IBLOCK_ID'],
            'CODE' => 'FILE'
        ])->exec()->fetch();
        $file = _::first($fieldsRef['PROPERTY_VALUES'][$fileProp['ID']]);
        $path = _::get($file, 'VALUE.tmp_name', function() use ($fieldsRef) {
            $element = _::first(Iblock::collectElements(CIBlockElement::GetByID($fieldsRef['ID'])));
            $fileId = $element['PROPERTIES']['FILE']['VALUE'];
            $file = CFile::GetFileArray($fileId);
            return Util::joinPath([$_SERVER['DOCUMENT_ROOT'], $file['SRC']]);
        });
        $type = $fieldsRef['CODE'];
        $parser = Parser::forType($type);
        try {
            $data = $parser->parseFile($path);
            if (!is_array($data) || _::isEmpty($data)) {
                throw new Exception('parsing error');
            }
            $cache = Cache::createInstance();
            if ($cache->startDataCache(Services::$cacheTtl, 'service-data:'.$type, App::CACHE_DIR)) {
                $cache->endDataCache($data);
            }
            // TODO log caching issues to sentry
            return true;
        } catch (\Exception $e) {
            // TODO log to sentry
            $errors = _::reduce($parser->log, function($acc, $pair) {
                list($type, $msg) = $pair;
                return $type === 'error' ? _::append($acc, $msg) : $acc;
            }, []);
            $sentences = _::prepend($errors, 'Ошибка парсинга: неожиданная структура файла.');
            $APPLICATION->ThrowException(join(' ', $sentences));
            return false;
        }
    }

    static function beforeVideoAddOrUpdate(&$fieldsRef) {
        global $APPLICATION;
        $linkProp = PropertyTable::query()->setSelect(['ID'])->setFilter([
            'IBLOCK_ID' => $fieldsRef['IBLOCK_ID'],
            'CODE' => 'LINK'
        ])->exec()->fetch();
        $link = _::first($fieldsRef['PROPERTY_VALUES'][$linkProp['ID']])['VALUE'];
        $videoId = Videos::youtubeIdMaybe($link);
        if ($videoId === null) {
            $APPLICATION->ThrowException('Неизвестный формат ссылки на YouTube видео.');
            return false;
        }
        if (!isset($fieldsRef['PREVIEW_PICTURE']) || $fieldsRef['PREVIEW_PICTURE']['del'] === 'Y') {
            try {
                $snippetMaybe = Videos::youtubeSnippetMaybe($videoId);
                $thumbnailMaybe = nil::map($snippetMaybe, function ($snippet) {
                    return _::get($snippet, 'items.0.snippet.thumbnails.high.url');
                });
                if ($thumbnailMaybe === null) {
                    trigger_error("can't fetch the thumbnail for a youtube video", E_USER_WARNING);
                }
                foreach (nil::iter($thumbnailMaybe) as $thumbnail) {
                    $file = CFile::MakeFileArray($thumbnail);
                    if (is_array($file)) {
                        $fieldsRef['PREVIEW_PICTURE'] = $file;
                    } else {
                        trigger_error("can't save the thumbnail file", E_USER_WARNING);
                    }
                }
            } catch (Exception $e) {
                trigger_error($e->getMessage(), E_USER_WARNING);
            }
        }
        return true;
    }
}