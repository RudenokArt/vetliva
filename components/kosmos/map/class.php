<?php

use Bitrix\Main\Loader,
    Bitrix\Main\Localization\Loc,
    Bitrix\Main\Engine\Contract\Controllerable;

class Map extends CBitrixComponent implements Controllerable
{
    const IBLOCK_REGION = 4;

    public function configureActions()
    {
        return [
            'getInfo' => [
                'prefilters' => [],
            ],
        ];
    }

    public function getInfoAction($post)
    {
        ob_start();

        $GLOBALS['APPLICATION']->IncludeComponent(
            'bitrix:news.detail',
            'map-info',
            Array(
            	'LANGUAGE_ID' => LANGUAGE_ID,
                'ACTIVE_DATE_FORMAT' => 'd.m.Y',
                'ADD_ELEMENT_CHAIN' => 'N',
                'ADD_SECTIONS_CHAIN' => 'N',
                'AJAX_MODE' => 'N',
                'AJAX_OPTION_ADDITIONAL' => '',
                'AJAX_OPTION_HISTORY' => 'N',
                'AJAX_OPTION_JUMP' => 'N',
                'AJAX_OPTION_STYLE' => 'Y',
                'BROWSER_TITLE' => '-',
				'CACHE_GROUPS' => 'N',//'Y',
                'CACHE_TIME' => '36000000',
				'CACHE_TYPE' => 'N',//'Y',
                'CHECK_DATES' => 'Y',
                'DETAIL_URL' => '',
                'DISPLAY_BOTTOM_PAGER' => 'N',
                'DISPLAY_DATE' => 'N',
                'DISPLAY_NAME' => 'N',
                'DISPLAY_PICTURE' => 'N',
                'DISPLAY_PREVIEW_TEXT' => 'N',
                'DISPLAY_TOP_PAGER' => 'N',
                'ELEMENT_CODE' => '',
                'ELEMENT_ID' => $post['id'],
                'FIELD_CODE' => array('PREVIEW_PICTURE', ''),
                'IBLOCK_ID' => $post['iblockId'],
                'IBLOCK_TYPE' => '',
                'IBLOCK_URL' => '',
                'INCLUDE_IBLOCK_INTO_CHAIN' => 'N',
                'MESSAGE_404' => '',
                'META_DESCRIPTION' => '-',
                'META_KEYWORDS' => '-',
                'PAGER_BASE_LINK_ENABLE' => 'N',
                'PAGER_SHOW_ALL' => 'N',
                'PAGER_TEMPLATE' => '.default',
                'PAGER_TITLE' => 'Страница',
                'PROPERTY_CODE' => array('PICTURES'),
                'SET_BROWSER_TITLE' => 'N',
                'SET_CANONICAL_URL' => 'N',
                'SET_LAST_MODIFIED' => 'N',
                'SET_META_DESCRIPTION' => 'N',
                'SET_META_KEYWORDS' => 'N',
                'SET_STATUS_404' => 'N',
                'SET_TITLE' => 'N',
                'SHOW_404' => 'N',
                'STRICT_SECTION_CHECK' => 'N',
                'USE_PERMISSIONS' => 'N',
                'USE_SHARE' => 'N'
            )
        );

        return ['html' => ob_get_clean()];
    }

    protected function checkModules()
    {
        if (!Loader::includeModule('kosmos.main')) {
            throw new \Exception(Loc::getMessage('KOSMOS_MAIN_MODULE_NOT_INSTALLED'));
        }
    }

    function getParameters()
    {
        switch(LANGUAGE_ID){
            case 'en':
                $this->arParams['MAP_LANGUAGE'] = 'en_RU';
                break;
            default:
                $this->arParams['MAP_LANGUAGE'] = 'ru_RU';
                break;
        }

        $this->arParams['MAP_API_KEY'] = \Bitrix\Main\Config\Option::get('fileman', 'yandex_map_api_key');

        $imageFolder = $this->getPath() . '/images/';

        $this->arParams['TYPES'] = [
			[
                'IBLOCK_ID' => 79, // клиники
                'ICON' => [
                    'IMAGE' => $imageFolder . 'Group 56.svg',
                    'COLOR' => '#ff66ff'
                ]
			],
            [
                'IBLOCK_ID' => 8, // санатории
                'ICON' => [
                    'IMAGE' => $imageFolder . 'Group 55.svg',
                    'COLOR' => '#3CE600'
                ]
            ],
            [
                'IBLOCK_ID' => 7, // проживание
                'ICON' => [
                    'IMAGE' => $imageFolder . 'Group 54.svg',
                    'COLOR' => '#4F9CFF'
                ],
                'TYPE' => 'ABODE'
            ],
            [
                'IBLOCK_ID' => 6, // достопримечательности
                'ICON' => [
                    'IMAGE' => $imageFolder . 'Group 53.svg',
                    'COLOR' => '#B516FF',
                ],
                'TYPE' => 'LIONS'
            ],
            [
                'IBLOCK_ID' => 30, // шоппинг
                'ICON' => [
                    'IMAGE' => $imageFolder . 'Group 51.svg',
                    'COLOR' => '#FF165C'
                ]
            ],
            [
                'IBLOCK_ID' => 31, // кафе и рестораны
                'ICON' => [
                    'IMAGE' => $imageFolder . 'Group 52.svg',
                    'COLOR' => '#FFCC16'
                ]
            ]
        ];
    }

    private static function getRequest()
    {
        $context = \Bitrix\Main\Context::getCurrent();
        $request = $context->getRequest();
        return $request->getQueryList()->toArray();
    }

    function getResult()
    {
        $this->arResult['REQUEST'] = static::getRequest();

        $this->arResult['REGION'] = static::getInfo(static::IBLOCK_REGION);

        $arFeatures = [];
        foreach($this->arParams['TYPES'] as $key => $arType){
            $arItems = static::getInfo($arType['IBLOCK_ID']);

            if(empty($arItems)){
                unset($this->arParams['TYPES'][$key]);
            }

            foreach($arItems as $arItem){

                $coordinates = explode(',', $arItem['PROPERTIES']['MAP']['VALUE']);
                if(empty($coordinates) || (count($coordinates) !== 2)){
                    continue;
                }

                $arFeature = [
                    'type' => 'Feature',
                    'id' => $arItem['ID'],
                    'geometry' => [
                        'type' => 'Point',
                        'coordinates' => $coordinates
                    ],
                    'properties' => [
                        'region' => $arItem['PROPERTIES']['REGION']['VALUE'],
                        'typeObj' => $arType['IBLOCK_ID'],
                        'subtypeObj' => (is_array($arItem['PROPERTIES']['TYPE']['VALUE'])) ? $arItem['PROPERTIES']['TYPE']['VALUE'] : [$arItem['PROPERTIES']['TYPE']['VALUE']]
                    ],
                    'options' => [
                        'iconImageHref' => $arType['ICON']['IMAGE'],
                        'iconLayout' => 'default#image',
                        'iconImageSize' => [24, 28],
                        'iconImageOffset' => [-12, -28],
                        'iconColor' => $arType['ICON']['COLOR']
                    ]
                ];

                if($arType['TYPE'] && $arItem['PROPERTIES']['TYPE']['VALUE']){
                    if(!$this->arResult['DATA'][$arType['TYPE']]){
                        $this->arResult['DATA'][$arType['TYPE']] = static::getInfo($arItem['PROPERTIES']['TYPE']['LINK_IBLOCK_ID']);
                    }
                }

                $arFeatures[] = $arFeature;
            }
        }

        $this->arResult['FEATURE_COLLECTION'] = \Bitrix\Main\Web\Json::encode([
            'type' => 'FeatureCollection',
            'features' => $arFeatures
        ]);

    }

    public function executeComponent()
    {
        try {
            $this->includeComponentLang('class.php');
            $this->checkModules();
            $this->getParameters();
            $this->getResult();
            $this->includeComponentTemplate();
        }
        catch (\Exception $exception) {
            ShowError($exception->getMessage());
        }

    }

    private static function getInfo($iblockId)
    {
        $cacheId = \Kosmos\Main\Helpers\Common::getCacheId('iblock-' . $iblockId);
        $cacheDir = '/map-info';
		$cacheTtl = 86400;

        $obCache = new \CPHPCache;
        if($obCache->InitCache($cacheTtl, $cacheId, $cacheDir)){
            $arResult = $obCache->GetVars();
        }
        elseif($obCache->StartDataCache()){
            global $CACHE_MANAGER;
            $CACHE_MANAGER->StartTagCache($cacheDir);

            \Bitrix\Main\Loader::includeModule('iblock');

            $arResult = [];

            $arSelect = [
                'ID',
                'IBLOCK_ID',
                'NAME',
                'SORT'
            ];

            $arFilter = [
                '=IBLOCK_ID' => $iblockId,
                '=ACTIVE' => 'Y'
            ];
            $arSort = ['SORT' => 'ASC'];

            $arProperties = [];
            \CIBlockElement::GetPropertyValuesArray(
                $arProperties,
                $iblockId,
                $arFilter,
                ['CODE' => [
                    'NAME_EN',
                    'NAME_BY',
                    'REGION',
                    'MAP',
                    'TYPE',
                    'COUNTRY'
                ]],
                [
                    'PROPERTY_FIELDS' => ['ID', 'CODE', 'NAME', 'VALUE', 'LINK_IBLOCK_ID']
                ]
            );

            $result = \CIBlockElement::GetList($arSort, $arFilter, false, false, $arSelect);
            while($row = $result->GetNext()){

                $row['PROPERTIES'] = $arProperties[$row['ID']];

                if($row['PROPERTIES']['COUNTRY']['VALUE'] && (int) $row['PROPERTIES']['COUNTRY']['VALUE'] !== 6){
                    continue;
                }

                if(LANGUAGE_ID !== 'ru'){
                    $lang = strtoupper(LANGUAGE_ID);
                    $row['NAME'] = ($row['PROPERTIES']['NAME_' . $lang . '']['VALUE']) ?: $row['NAME'];
                }

                $arResult[] = $row;

                $CACHE_MANAGER->RegisterTag('iblock_id_' . $row['IBLOCK_ID']);
            }

            $CACHE_MANAGER->EndTagCache();
            $obCache->EndDataCache($arResult);
        }
        else{
            $arResult = [];
        }

        return $arResult;
    }
}