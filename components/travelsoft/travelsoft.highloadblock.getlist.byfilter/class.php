<?php

/**
 * Выборка элементов highloadblock по внешнему фильтру
 * @author dimabresky
 */

use Bitrix\Highloadblock as HL;
use Bitrix\Main\Entity;

class TravelsoftHighloadBlockGetListByFilter extends CBitrixComponent {
    
    /**
     * @var array массив фильтра
     */
    protected $arFilter = null;
    
    /**
     * @var array массив полей для выборки
     */
    protected $arSelect = null;
            
    /**
     * @var array массив полей для сортировки 
     */
    protected $arSort = null;
    
    /**
     * @var array массив, содержащий ограничение выборки количества элементов
     */
    protected $arNavigation = null;
    
    /**
     * @var string папка кеша
     */
    protected $cacheDir = "/travelsoft/highloadblock_getlist_byfilter";
    
    /**
     * @var string id кеша
     */
    protected $cacheID = null;
    
    /**
     * Установка масcива сортировки
     */
    protected function setSorting () {
        
        $this->arSort = false;
        
        if ($this->arParams['SORT'] != "") {
            $this->arSort[$this->arParams['SORT']] = strtoupper($this->arParams['ORDER']) == "DESC" ? "DESC" : "ASC";           
        }
   
    }
    
    /**
     * Установка фильтра
     */
    protected function setFilter () {
        
        $this->arFilter = false;
        
        if (!EMPTY($this->arParams['FILTER']))
            $this->arFilter = $this->arParams['FILTER'];
        
        if (isset($GLOBALS[$this->arParams['FILTER_NAME']]) && !empty($GLOBALS[$this->arParams['FILTER_NAME']])) {
            
            if ($this->arFilter) {
                $this->arFilter = array_merge($GLOBALS[$this->arParams['FILTER_NAME']], $this->arFilter);
            } else
                $this->arFilter = $GLOBALS[$this->arParams['FILTER_NAME']];
            
        }
        
    }
    
    /**
     * Установка arSelect
     */
    protected function setSelect () {
        
        $this->arSelect = array("*");
        
        array_filter(
                $this->arParams['SELECT'],
                function ($el) { return ($el && !empty($el)); }
         );
        
        if (!empty($this->arParams['SELECT'])) {
            $this->arSelect = $this->arParams['SELECT'];
        }
        
    }
    
    /**
     * Устанока arNavigation
     */
    protected function setNavigation () {
        
        $this->arNavigation = false;
        if ($this->arParams['CNT'] > 0) {
             $this->arNavigation =array('nPageTop' => (int)$this->arParams['CNT']);
        }
        
    }
    
    /**
     * Устанавливаем ID кеша
     */
    protected function setCacheID () {
        
        $this->cacheID = md5(serialize($this->arParams) 
                                            . serialize($this->arFilter) 
                                                . serialize($this->arSelect) 
                                                    . serialize($this->arSort) 
                                                        . serialize($this->arNavigation));
    }
    
    /**
     * Запуск работы компонента
     */
    public function executeComponent() {
        
        if ( ! \Bitrix\Main\Loader::includeModule('iblock') ) {
            return;
        }
        
        $this->setFilter();
        
        if (empty ($this->arFilter) ) return;
        
        $this->setSelect();
        
        $this->setSorting();
        
        $this->setNavigation();
        
        $this->setCacheID();

        $сache = Bitrix\Main\Data\Cache::createInstance();
       

        // очищаем кеш, если выборка рандомная
        if (isset($this->arSort['RAND'])) {
            $сache->clearCache(true, $this->cacheDir);
        }
        
        if ($сache->initCache($this->arParams['CACHE_TIME'], $this->cacheID, $this->cacheDir)) {
            
            $this->arResult = $сache->getVars(); 
            
        } elseif ($сache->startDataCache()) {
           
            $this->arResult = array();
            
            if (!$this->arParams['FILTER']['HLB_ID']) return;
            unset($this->arFilter['HLB_ID']);
            
            $hlblock = HL\HighloadBlockTable::getById($this->arParams['FILTER']['HLB_ID'])->fetch();

            $entity = HL\HighloadBlockTable::compileEntity($hlblock);
             
            // uf info
            $fields = $GLOBALS['USER_FIELD_MANAGER']->GetUserFields('HLBLOCK_'.$hlblock['ID'], 0, LANGUAGE_ID);
            
            // execute query
            $main_query = new Entity\Query($entity);
            
            //filter
            if (!empty($this->arFilter))
                $main_query->setFilter($this->arFilter);
            
            $main_query->setSelect($this->arSelect);
            $main_query->setOrder($this->arSort);

            if ($this->arNavigation) {
                    $main_query->setLimit($this->arNavigation['nPageTop']);
            }
            
            $result = $main_query->exec();
            $result = new CDBResult($result);
            
            while ($res = $result->Fetch()) {
                $this->arResult['rows'][] = $res;
            }
            
            $this->arResult['fields'] = $fields;
            
            if (!empty($this->arResult['rows'])) {
 
                if(defined("BX_COMP_MANAGED_CACHE")) {
                        global $CACHE_MANAGER;
                        $CACHE_MANAGER->StartTagCache($this->cacheDir);
                        $CACHE_MANAGER->RegisterTag("hlgetlistcomponent_" . $this->arParams['FILTER']['HLB_ID']);                    
                        $CACHE_MANAGER->EndTagCache();
                }
                
                $сache->endDataCache($this->arResult);
                
            } else
                $сache->abortDataCache();
 
        } 

        if ($this->arParams['RETURN_RESULT'] == "Y") {
                return $this->arResult;		
        }        

        $this->includeComponentTemplate();
        
    }
    
}

