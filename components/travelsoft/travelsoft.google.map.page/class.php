<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

// include dependencies
require_once "classes/locations.php";
require_once "classes/points.php";
require_once "classes/request.php";

/**
 * travelsoft map page component
 * CBitrixComponent extension
 */
class TravelsoftMapPageComponent extends CBitrixComponent {
    
    /**
     * @var travelsoft\map\Locations
     */
    private $locations = null;
    
    /**
     * @var travelsoft\map\Points
     */
    private $points = null;
    
    /**
     * @var \CIBlockElement 
     */
    private $elementTable = null;
    
    /**
     * @var \CIBlock
     */
    private $iblockTable = null;
    
    /**
     * @var \CIBlockProperty
     */
    private $propertyTable = null;
    
    /**
     * @var array
     */
    private $requestNav = null;
    
    /**
     * @var \Bitrix\Main\Data\Cache
     */
    private $сache = null;
    
    /**
     * component body
     */
    public function executeComponent() {
        
        try {
            
            Bitrix\Main\Loader::includeModule('iblock');
            
            $this->iblockTable = new \CIBlock; 
            
            $this->elementTable = new \CIBlockElement; 
            
            $this->propertyTable = new \CIBlockProperty;
            
            $this->сache = \Bitrix\Main\Data\Cache::createInstance();
            
            $this->requestNav = \Requset::getInstance()->get('navigation');
            
            $this->__initLocationsObject();
            
            $this->arResult['navigation']['locations'] = $this->locations->getItems();
                    
            $this->__initPointsObject();
            
            $this->arResult['navigation']['points'] = $this->points->getItems();

            $this->__setMapItemsData();
            
            $this->__isAjax(array(
                    "items" => $this->arResult['items'],
                    "cntElements" => $this->arResult['cntElements']
                )
            );
            
            $this->__setDefItems();
            
            $this->arResult["__request"] = $this->requestNav;

            $this->IncludeComponentTemplate();
            
        } catch (\Exception $e) {
            
            $this->isAjax(array("error" => true));
            
            ShowError($e->getMessage());
            
        }

    }
    
    /**
     * set map all items data
     */
    private function __setMapItemsData () {
        
        $locationsFilter = $this->locations->getIbFilter();
        $pointsFilter = $this->points->getIbFilter();

        foreach ( $pointsFilter  as $_pf ) {
            
            $this->__setItems(array_merge($locationsFilter, $_pf));
            
        }
        
    }
    
    /**
     * @param array $arFilter
     */
    private function __setItems (Array $arFilter) {
        
        $arSelect = array("ID", "IBLOCK_ID", "NAME", "DETAIL_PAGE_URL", "PROPERTY_PICTURES", "PROPERTY_MAP",
            "PROPERTY_ADDRESS" . (LANGUAGE_ID != "ru" ? "_".strtoupper(LANGUAGE_ID)  : ""), "PROPERTY_NAME_" . strtoupper(LANGUAGE_ID));
        
        $cacheId = md5(serialize(array_merge($arFilter, $arSelect, $this->requestNav)));
        $cacheDir = "/travelsoft/map_page";
        
        if ($this->сache->initCache($this->arParams["CACHE_TIME"], $cacheId, $cacheDir)) {
            
            $this->arResult['items'] = $this->сache->getVars();
            
        } elseif ($this->сache->startDataCache()) {
            
            $dbRes = $this->elementTable->GetList(false, $arFilter, false, false, $arSelect);

            while ($arItem = $dbRes->GetNext()) {

                if (!$arItem['PROPERTY_MAP_VALUE']) {
                    continue;
                }

                $this->arResult['items'][$arItem['ID']] = $this->__getMapItem($arItem);

            }
            
            if ($this->arResult['items']) {
 
                if(defined("BX_COMP_MANAGED_CACHE")) {
                        global $CACHE_MANAGER;
                        $CACHE_MANAGER->StartTagCache($cacheDir);
                        $CACHE_MANAGER->RegisterTag("iblock_id_".$arFitler["IBLOCK_ID"]);                    
                        $CACHE_MANAGER->EndTagCache();
                }
                
                $this->сache->endDataCache($this->arResult['items']);
                
            } else {
                $this->сache->abortDataCache();
            }
            
        }
        
        $this->arResult["cntElements"] = count($this->arResult['items']);

    }
    
    /**
     * set default items if no items by filter
     */
    private function __setDefItems () {
        
        if (!$this->arResult['items']) {
            
            foreach ($this->arParams['defPoints'] as $ibid) {
                $this->__setItems(array('IBLOCK_ID' => $ibid, "ACTIVE" => "Y"));
            }
            
        }
        
    }
    
    /**
     * @param array $arItem
     * @return array
     */
    private function __getMapItem (Array $arItem) {

        $arMap = explode(",", $arItem['PROPERTY_MAP_VALUE']);
                
        $img = "";
        if (is_array($arItem['PROPERTY_PICTURES_VALUE']) && !empty($arItem['PROPERTY_PICTURES_VALUE'])) {

            $arImg = CFile::ResizeImageGet(
                    $arItem['PROPERTY_PICTURES_VALUE'][0],
                    array('width' => 250, 'height' => 250),
                    BX_RESIZE_IMAGE_EXACT
           );
            $img = $arImg['src'];

        }

        $arResult = array(
            "title" => $arItem["PROPERTY_NAME_" . strtoupper(LANGUAGE_ID)."_VALUE"] ? $arItem["PROPERTY_NAME_" . strtoupper(LANGUAGE_ID) . "_VALUE"] : $arItem['NAME'],
            "lat" => $arMap[0],
            "lng" => $arMap[1],
            "icon" =>  $this->arResult['navigation']['points']["map_icons"][$arItem["IBLOCK_ID"]],
            "img" => $img,
            "page" => $arItem["DETAIL_PAGE_URL"],
            "address" => $arItem['PROPERTY_ADDRESS_'.strtoupper(LANGUAGE_ID).'_VALUE'] ? $arItem['PROPERTY_ADDRESS_'.strtoupper(LANGUAGE_ID).'_VALUE'] : $arItem['PROPERTY_ADDRESS_VALUE']
        );
        
        return $arResult;
        
    }
    
    /**
     * set locations object data
     */
    private function __initLocationsObject () {
        
        $this->locations = new \travelsoft\map\Locations($this->elementTable, array(
                "iblocks" => array("region" => $this->arParams['region'], "city" => $this->arParams['city']),
                "request" => $this->requestNav['locations'],
        ), $this->сache);
        
    }
    
    /**
     * set points object data
     */
    private function __initPointsObject () {
 
        $this->points = new \travelsoft\map\Points($this->iblockTable, $this->elementTable, $this->propertyTable,
                array(
                    'iblocks' => $this->arParams['points'],
                    'request' =>  $this->requestNav['points'],
                ),  $this->сache);
  
    }
    
    /**
     * check ajax request
     * @global CMain $APPLICATION
     * @param array $result
     */
    private function __isAjax ($result) {
        
        global $APPLICATION;

        $isAjax = \Requset::getInstance()->getPost("__compid") == "__travelsoft_df96dd9a12aed7a764ab1d004e9260fd" 
                            && \Requset::getInstance()->isPost() 
                                && check_bitrix_sessid();
        
        if ($isAjax) {
            
            $APPLICATION->RestartBuffer();
            
            header('Content-Type: application/json; charset=' . SITE_CHARSET);
            
            echo Bitrix\Main\Web\Json::encode($result);
            
            require \Bitrix\Main\Application::getDocumentRoot()."/bitrix/modules/main/include/epilog_after.php";
            die();
            
        }
        
    }
       
}
