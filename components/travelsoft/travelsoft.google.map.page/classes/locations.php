<?php

namespace travelsoft\map;


class Locations {
    
    /**
     * @var array 
     */
    private $arItems = array();
    
    /**
     * @var array 
     */
    private $ibFilter = array();
    
    /**
     * @var array
     */
    private $request = null;
    
    /**
     * @var array 
     */
    private $iblocks = null;
    
    /**
     * @var \CIBlockElement 
     */
    private $elementTable = null;

    /**
     * @var \Bitrix\Main\Data\Cache 
     */
    private $cache = null;
    
    /**
     * @var integer
     */
    private $cacheTime = 0;
    
    /**
     * @param \CIBlockElement $elementTable
     * @param array $parameters
     */
    public function __construct(\CIBlockElement $elementTable, Array $parameters, \Bitrix\Main\Data\Cache $cache) {
   
        $this->__checkIblocks($parameters['iblocks']);
        
        $this->iblocks = $parameters['iblocks'];
        
        $this->request = $parameters['request'];
        
        $this->elementTable = $elementTable;
        
        $this->cache = $cache;
        
        $this->cacheTime = $parameters['cacheTime'];
        
        $this->__setIbFilter();
        
        $this->__setItems();
                
    }
    
    /**
     * @param array $iblocks
     * @throws Exception
     */
    private function __checkIblocks (Array $iblocks) {
        
        if ($iblocks['region'] <= 0) {
            throw new Exception('Укажите источник для регионов');
        }
        
        if ($iblocks['city'] <= 0) {
            throw new Exception('Укажите источник для городов');
        }
        
    }


    /**
     * set filter for bx iblock query
     */
    private function __setIbFilter () {
        
        $arId = null;
        
        foreach ($this->request as $id) {
            $id = intVal($id);
            if ($id > 0) {
                $arId[] = $id;
            }
        }
        
        if ($arId) {
            $this->ibFilter = array("PROPERTY_TOWN" => $arId, "PROPERTY_TOWN.ACTIVE" => "Y");
        }
        
    }

    private function __setItems () {
        
        $arFilterRegions = array("IBLOCK_ID" => $this->iblocks['region'], "ACTIVE" => "Y");
        $arFilterCities = array("IBLOCK_ID" => $this->iblocks['city'], "ACTIVE" => "Y");
        $arOrder = array("NAME" => "ASC");
        $arSelectRegions = array("ID", "NAME", "PROPERTY_NAME_".strtoupper(LANGUAGE_ID));
        $arSelectCitites = array("ID", "NAME", "PROPERTY_REGION", "IBLOCK_ID", "PROPERTY_NAME_".strtoupper(LANGUAGE_ID));
        $cacheId = serialize(array_merge($arOrder, $arFilterRegions, $arFilterCities, $arSelectRegions, $arSelectCitites));
        $cacheDir = "/travelsoft/locations-map-filter";
        
       
        if ($this->cache->initCache($this->cacheTime, $cacheId, $cacheDir)) {
            
            $this->arItems = $this->сache->getVars(); 
            
        } elseif ($this->cache->startDataCache()) {
            
            // get regions
            $dbRes = $this->elementTable->GetList($arOrder, $arFilterRegions, false, false, $arSelectRegions);
            
            while ($arRes = $dbRes->Fetch()) {
                $arRes['NAME'] = $arRes['PROPERTY_NAME_'.strtoupper(LANGUAGE_ID)."_VALUE"] ? $arRes['PROPERTY_NAME_'.strtoupper(LANGUAGE_ID)."_VALUE"] : $arRes["NAME"];
                $this->arItems['regions'][$arRes['ID']] = $arRes;
            }

            // get cities
            $dbRes = $this->elementTable->GetList($arOrder, $arFilterCities, false, false, $arSelectCitites);

            while ($arRes = $dbRes->Fetch()) {
                $arCities[] = $arRes;
            }

            // make structure
            foreach ( $arCities as $arCity ) {
                if (isset($this->arItems['regions'][$arCity['PROPERTY_REGION_VALUE']])) {
                    $this->arItems['items'][$arCity['PROPERTY_REGION_VALUE']][$arCity['ID']] = array(
                        "title" => $arCity["PROPERTY_NAME_".strtoupper(LANGUAGE_ID)."_VALUE"] ? $arCity["PROPERTY_NAME_".strtoupper(LANGUAGE_ID)."_VALUE"] : $arCity['NAME'],
                        "value" => $arCity['ID']
                    );
                }
            }
            
            if ($this->arItems['items']) {
 
                if(defined("BX_COMP_MANAGED_CACHE")) {
                        global $CACHE_MANAGER;
                        $CACHE_MANAGER->StartTagCache($cacheDir);
                        $CACHE_MANAGER->RegisterTag("iblock_id_".$this->iblocks['region']);
                        $CACHE_MANAGER->RegisterTag("iblock_id_".$this->iblocks['city']);  
                        $CACHE_MANAGER->EndTagCache();
                }
                
                $this->cache->endDataCache(
                        array(
                            "regions" => $this->arItems['regions'],
                            "items" => $this->arItems['items']
                        )
                );
                
            } else {
                $this->cache->abortDataCache();
            }
            
        } 
    }

    /**
     * get filter for bx iblock query
     * @return array
     */
    public function getIbFilter () {
        return $this->ibFilter;
    }
    
    /**
     * get items structure
     * @return array
     */
    public function getItems () {
        return $this->arItems;
    }
    
}