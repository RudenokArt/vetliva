<?php

namespace Kosmos\Main\Helpers;

class Guide
{
    public const IBLOCK_ID = 75;

    public static function get($userId = false)
    {
        $arResult = [];

        try{

            if(!$userId){
                $userId = (int) $GLOBALS['USER']->GetID();
            }

            if(!($userId > 0)){
                throw new \Exception('Unauthorized user');
            }

            if(!\Bitrix\Main\Loader::includeModule('iblock')){
                throw new \Exception('Include module \'iblock\' failed');
            }

            $arFilter = [
                'IBLOCK_ID' => static::IBLOCK_ID,
                '=PROPERTY_USER' => $userId
            ];

            $result = \CIBlockElement::GetList([], $arFilter, false, ['nTopCount' => 1]);
            if($row = $result->GetNextElement()){

                $arResult = $row->GetFields();
                $arResult['PROPERTIES'] = $row->GetProperties();

            }

        }
        catch(\Exception $e){

        }

        return $arResult;
    }

    public static function set()
    {
        $request = \Bitrix\Main\Context::getCurrent()->getRequest();

        $arPost = $request->getPostList();
        $arFiles = $request->getFileList();

        $userId = (int) $arPost['ID'];
        if(!($userId > 0)){
            return;
        }

        $arFields = static::assignFields($arPost, $arFiles);

        if($elementId = static::isset($userId)){
            static::update($userId, $arFields, $elementId);
        }
        else{
            static::create($userId, $arFields);
        }
    }

    public static function isset($userId)
    {
        $isset = false;

        try{
            if(!($userId > 0)){
                throw new \Exception('Unauthorized user');
            }

            $arFilter = [
                'IBLOCK_ID' => static::IBLOCK_ID,
                '=PROPERTY_USER' => $userId
            ];

            $arSelect = ['ID', 'IBLOCK_ID'];

            $result = \CIBlockElement::GetList([], $arFilter, false, ['nTopCount' => 1], $arSelect);

            if($row = $result->GetNext()){
                $isset = $row['ID'];
            }

        }
        catch(\Exception $e){

        }

        return $isset;
    }

    public static function assignFields($arPost, $arFiles) : array
    {
        $arResult = [
            'FIELDS' => [],
            'PROPERTIES' => []
        ];

        $arResult['FIELDS']['NAME'] = $arPost['ID'];

        if($arFiles['PERSONAL_PHOTO']['tmp_name']){
            $arResult['FIELDS']['PREVIEW_PICTURE'] = $arFiles['PERSONAL_PHOTO'];
            $arResult['FIELDS']['DETAIL_PICTURE'] = $arFiles['PERSONAL_PHOTO'];
        }
        elseif($arPost['PERSONAL_PHOTO_del']){
            $arResult['FIELDS']['PREVIEW_PICTURE'] = ['del' => 'Y'];
            $arResult['FIELDS']['DETAIL_PICTURE'] = ['del' => 'Y'];
        }

        $arProperties = [
            'FIRST_NAME_RU',
            'FIRST_NAME_BY',
            'FIRST_NAME_EN',
            'LAST_NAME_RU',
            'LAST_NAME_BY',
            'LAST_NAME_EN',
            'SECOND_NAME_RU',
            'SECOND_NAME_BY',
            'SECOND_NAME_EN',
            'ABOUT_SELF',
            'ABOUT_SELF_BY',
            'ABOUT_SELF_EN',
            'YOUTUBE',
            'YOUTUBE_BY',
            'YOUTUBE_EN',
            'VIMEO',
            'VIMEO_BY',
            'VIMEO_EN',
            'CERTIFICATION',
            'RESIDENCE',
            'TRANSPORT',
            'TOUR_LANGUAGE',
            'TOUR_TYPE',
            'PHONE',
            'TOUR_REGION',
            'TOUR_LOCATION',
            'TOUR_SIGHTS',
            'PICTURES'
        ];

        $arHtmlProperties = [
            'ABOUT_SELF',
            'ABOUT_SELF_BY',
            'ABOUT_SELF_EN',
        ];

        $userFields = [
            'FIRST_NAME_RU' => 'NAME',
            'LAST_NAME_RU' => 'LAST_NAME',
            'SECOND_NAME_RU' => 'SECOND_NAME'
        ];

        $fileFields = [
            'PICTURES'
        ];

        foreach($arProperties as $code){

            $value = (array_key_exists($code, $userFields)) ? $arPost[$userFields[$code]] : $arPost[$code];

            if(in_array($code, $arHtmlProperties)){
                $value = ['VALUE' => ['TYPE' => 'HTML', 'TEXT' => $value]];
            }
            else if(in_array($code, $fileFields)){

                $arFiles = [];
                foreach($value as $key => $file){

                    $arFile = (is_array($file)) ? array_merge($file, ['error' => 0, 'tmp_name' => \CTempFile::GetAbsoluteRoot() . $file['tmp_name']]) : \CFile::MakeFileArray($file);

                    if(isset($arPost[$code . '_del'][$key]) && ($arPost[$code . '_del'][$key] === 'Y')){
                        $arFile['del'] = 'Y';
                    }

                    $arFiles[] = $arFile;
                }

                $value = $arFiles;

            }


            $arResult['PROPERTIES'][$code] = $value;
        }

        return $arResult;
    }

    public static function update($userId, $arFields, $elementId) : void
    {
        \CIBlockElement::SetPropertyValuesEx($elementId, static::IBLOCK_ID, $arFields['PROPERTIES']);
        $el = new \CIBlockElement;
        $el->Update($elementId, $arFields['FIELDS']);

    }

    public static function create($userId, $arFields) : void
    {
        $el = new \CIBlockElement;

        $arFields = array_merge([
            'ACTIVE' => 'N',
            'IBLOCK_ID' => static::IBLOCK_ID,
            'PROPERTY_VALUES' => array_merge($arFields['PROPERTIES'], ['USER' => $userId]),
        ], $arFields['FIELDS']);

        $el->Add($arFields);
    }

}