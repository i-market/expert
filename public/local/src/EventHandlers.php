<?php

namespace App;

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
        if ($fieldsRef['IBLOCK_ID'] === IblockTools::find(Iblock::CONTENT_TYPE, Iblock::VIDEOS)->id()) {
            return self::beforeVideoAddOrUpdate($fieldsRef);
        }
        return true;
    }

    static function beforeVideoAddOrUpdate(&$fieldsRef) {
        global $APPLICATION;
        // TODO refactor: optimize query
        $props = _::keyBy('CODE', PropertyTable::query()
            ->setSelect(['ID', 'CODE'])
            ->setFilter(['IBLOCK_ID' => $fieldsRef['IBLOCK_ID']])
            ->exec()->fetchAll());
        $linkPropId = $props['LINK']['ID'];
        $link = _::first($fieldsRef['PROPERTY_VALUES'][$linkPropId])['VALUE'];
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
                foreach (nil::iterator($thumbnailMaybe) as $thumbnail) {
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