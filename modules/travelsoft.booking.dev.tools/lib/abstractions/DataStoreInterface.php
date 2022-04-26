<?php

namespace travelsoft\booking\abstractions;

/**
 * Интерфейс для хранилища данных
 *
 * @author dimabresky
 */

abstract class DataStoreInterface {
    
    /**
     * @var array 
     */
    protected $_data = null;

    protected $query;
    
    /**
     * @var string 
     */
    protected static $store = null;
    
    /**
     * @param array $query
     */
    public function __construct(array $query = null) {
        $this->query = $query;
        $class = get_class($this);
        $this->_data = $class::get($query);
    }
    
    /**
     * Возвращет массив данных (при необходимости может группировать по ключам)
     * @param array $grList
     * @return array
     */
    public function fetch(array $grList = null) {

        $arResult = array();
        if ($grList && $this->_data) {

            $arResult = $this->_group(0, $grList, $this->_data);

            return $arResult;
        } else {
            return $this->_data;
        }
    }
        
    protected function _group(int $i, array $grList, array $arData) {
        $arResult = null;
        foreach ($arData as $arVals) {
            $arResult[$arVals[$grList[$i]]][] = $arVals;
        }
        if ($grList[$i + 1]) {
            foreach ($arResult as $key => $arVals) {
                $arResult[$key] = $this->_group($i + 1, $grList, $arVals);
            }
        }
        return $arResult;
    }
    
    abstract public static function get (array $query = array());
    abstract public static function save (array $arSave, int $ID = null);
    abstract public static function delete (int $ID);
    
}