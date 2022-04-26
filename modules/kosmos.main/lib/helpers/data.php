<?php

namespace Kosmos\Main\Helpers;

abstract class Data
{
    protected const CACHE_DIR = '/data';
    protected const CACHE_TTL = 86000;

    public static function getList()
    {
        $arResult = [];

        $cacheId = \Kosmos\Main\Helpers\Common::getCacheId(static::CACHE_CODE);
        $cacheDir = static::CACHE_DIR;
        $cacheTtl = static::CACHE_TTL;

        $obCache = new \CPHPCache;
        if($obCache->InitCache($cacheTtl, $cacheId, $cacheDir)){
            $arResult = $obCache->GetVars();
        }
        elseif($obCache->StartDataCache()){
            global $CACHE_MANAGER;
            $CACHE_MANAGER->StartTagCache($cacheDir);

            \Bitrix\Main\Loader::includeModule('iblock');

            $arSort = ['NAME' => 'ASC'];

            $arFilter = ['=ACTIVE' => 'Y', '=IBLOCK_ID' => static::IBLOCK_ID];

            $arSelect = [
                'ID',
                'IBLOCK_ID',
                'NAME'
            ];

            $arLang = ['BY', 'EN'];

            $result = \CIBlockElement::GetList($arSort, $arFilter, false, false, $arSelect);
            while($row = $result->GetNext()){

                foreach($arLang as $code){
                    $res = \CIBlockElement::GetProperty($row['IBLOCK_ID'], $row['ID'], [], ['CODE' => 'NAME_' . $code]);
                    if($r = $res->Fetch()){
                        $row['NAME_' . $code] = $r['VALUE'];
                    }
                }

                if(
                    (LANGUAGE_ID !== 'ru') &&
                    ($row['NAME' . POSTFIX_PROPERTY])
                ){
                    $row['NAME'] = $row['NAME' . POSTFIX_PROPERTY];
                }

                $arResult[] = $row;
                $CACHE_MANAGER->RegisterTag('iblock_id_' . $row['IBLOCK_ID']);
            }

            $CACHE_MANAGER->EndTagCache();
            $obCache->EndDataCache($arResult);
        }

        return $arResult;
    }
}