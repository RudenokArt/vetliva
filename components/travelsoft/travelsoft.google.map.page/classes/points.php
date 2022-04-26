<?php

namespace travelsoft\map;

use Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);

class Points {
    
    /**
     * property code of type point
     */
    const PROPERTY_TYPE_CODE = "TYPE";
    
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
     * @var \CIBlock 
     */
    private $iblockTable = null;
    
    /**
     * @var \CIBlockProperty 
     */
    private $propertyTable = null;
    
    /**
     * @var \Bitrix\Main\Data\Cache 
     */
    private $cache = null;
    
    /**
     * @var integer
     */
    private $cacheTime = 0;
    
    /**
     * @param \CIblock $iblockTable
     * @param \CIBlockElement $elementTable
     * @param \CIBlockProperty $propertyTable
     * @param array $parameters
     */
    public function __construct(\CIBlock $iblockTable, \CIBlockElement $elementTable,
            \CIBlockProperty $propertyTable, Array $parameters, \Bitrix\Main\Data\Cache $cache) {
        
        $this->__checkIblocks($parameters['iblocks']);
        
        $this->iblocks = $parameters['iblocks'];
        
        $this->request = $parameters['request'];
        
        $this->iblockTable = $iblockTable;
        
        $this->elementTable = $elementTable;
        
        $this->propertyTable = $propertyTable;
        
        $this->cache = $cache;
        
        $this->cacheTime = $parameters['cacheTime'];
        
        $this->__setIbFilter();
        
        $this->__setItems();

    }
    
    /**
     * set items of navigation points
     */
    private function __setItems () {
        
        foreach ($this->iblocks as $iblock) {
            
            $arIblock = $this->iblockTable->GetList(
                        array(),
                        array("ID" => $iblock, "ACTIVE" => "Y"),
                        false
                    )->Fetch();
                        
            if (!$arIblock['ID']) {
                continue;
            }
            
            $iblock_lang_name = Loc::getMessage("IBLOCK_" . $arIblock['ID']);
            
            if ($arIblock["PICTURE"] > 0) {
                $icon = \CFile::GetFileArray($arIblock["PICTURE"]);
                $marker_icon = $icon["SRC"];
            } elseif (defined("MAP_MARKER_PATH")) {
                $marker_icon = MAP_MARKER_PATH;
            }
            
            $this->arItems["map_icons"][$arIblock['ID']] = $marker_icon;
            
            $this->arItems['groups'][$arIblock['ID']] = array("ID" => $arIblock['ID'], "NAME" => $iblock_lang_name ? $iblock_lang_name : $arIblock['NAME']);
            
            $this->arItems['items'][$arIblock['ID']] = array();
            
            $arProperty = $this->propertyTable->GetList(
                    array("SORT" => "ASC"),
                    array("ACTIVE" => "Y", "IBLOCK_ID" => $arIblock['ID'], "CODE" => self::PROPERTY_TYPE_CODE)
            )->Fetch();

            if ($arProperty['LINK_IBLOCK_ID']) {
                
                $this->__setTypes($arProperty['LINK_IBLOCK_ID'], $arIblock['ID']);
                
            }
  
        }
        
    }
    
    /**
     * @param integer $iblockId
     */
    private function __setTypes ($iblockId, $key) {
        $dbRes = $this->elementTable->GetList(array("NAME" => "ASC"), array("ACTIVE" => "Y", "IBLOCK_ID" => $iblockId),
                        false, false, array("ID", "NAME", "IBLOCK_ID", "PROPERTY_NAME_" . strtoupper(LANGUAGE_ID)));
                
        while ($arRes = $dbRes->Fetch()) {
            $this->arItems['items'][$key][] = array(
                "title" => $arRes['PROPERTY_NAME_' . strtoupper(LANGUAGE_ID)."_VALUE"] ? $arRes['PROPERTY_NAME_' . strtoupper(LANGUAGE_ID)."_VALUE"] : $arRes['NAME'],
                "value" => $arRes['ID']
            );
        }
    }
    
    /**
     * set filter for iblock query
     */
    private function __setIbFilter () {
      
        foreach ($this->request as $val) {
            
            $arVal = explode("_", $val);
            
            $iblockId = intVal($arVal[0]);
            
            $typeId = intVal($arVal[1]);
            
            if ($iblockId > 0 && in_array($iblockId, $this->iblocks)) {
                
                    if (!isset($this->ibFilter[$iblockId])) {
                        $this->ibFilter[$iblockId]["IBLOCK_ID"] = $iblockId;
                        $this->ibFilter[$iblockId]["ACTIVE"] = "Y";
                    }
                    
                    if ($typeId > 0 && !in_array($typeId, $this->ibFilter[$iblockId]["PROPERTY_TYPE"])) {
                        $this->ibFilter[$iblockId]["PROPERTY_TYPE"][] = $typeId;
                    }
                    
            }

        }

    }
    
    /**
     * @param array $arTypeId
     */
    private function __getTypeFilter (Array $arTypeId) {
        
        if (!empty($arTypeId)) {
            array("PROPERTY_TYPE" => $arTypeId);
        }
        
        return array();
        
    }
    
    /**
     * @param array $iblocks
     * @throws Exception
     */
    private function __checkIblocks (Array $iblocks) {
        
        $noIblocks = true;
        foreach ($iblocks as $iblock) {
            if ($iblock > 0) {
                $noIblocks = false;
            }
        }
        
        if ($noIblocks) {
            throw new Exception("Укажите хотя бы один источник для точек на карте");
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