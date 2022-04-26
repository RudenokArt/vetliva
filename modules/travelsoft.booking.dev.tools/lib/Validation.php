<?php

namespace travelsoft\booking;

use travelsoft\booking\Utils as U;
use travelsoft\booking\datastores\ServicesDataStore;
use travelsoft\booking\datastores\RatesDataStore;
use travelsoft\booking\datastores\TransfersRatesDataStore;

/**
 * Валидация переменных бронирования
 *
 * @author dimabresky
 */
class Validation {

    /**
     * @param mixed $val
     * @return boolean
     */
    protected static function aboveZero($val) {
        if ($val > 0) {
            return true;
        }
        return false;
    }

    /**
     * @param mixed $val
     * @return boolean
     */
    protected static function geZero($val) {
        if ($val >= 0) {
            return true;
        }
        return false;
    }
    
    public function checkPrice ($price) {
        if (!self::geZero($price)) {
            throw new Exception(__CLASS__ . ": Price must be >= 0");
        }
    }
    
    public static function checkDuration (int $duration) {
        if (!self::geZero($duration)) {
            throw new \Exception(__CLASS__ . ": Duration must be >= 0");
        }
    } 
    
    /**
     * Проверка существования типа услуги
     * @param mixed $type
     * @throws \Exception
     */
    public static function checkType($type) {
        $stypes = U::stypes();
        if (!isset($stypes[$type]) && array_search($type, $stypes) === false) {
            throw new \Exception(__CLASS__ . ": Unknown service type (\"" . $type . "\")");
        }
    }

    /**
     * Проверка существования валюты
     * @param mixed $currency
     * @throws \Exception
     */
    public static function checkCurrency($currency) {

        $arCurrency = U::getAllCurrency();
        if (!isset($arCurrency[$currency]) && array_search($currency, array_column($arCurrency, "iso")) === false) {
            throw new \Exception(__CLASS__ . ": Unknown currency (\"" . $currency . "\")");
        }
    }

    /**
     * Проверка сущестования пункта A для трансфера
     * @param int $pointId
     * @throws \Exception
     */
    public static function checkPointA(int $pointId) {
        if (!self::checkPoint($pointId)) {
            throw new \Exception(__CLASS__ . ": Unknown point A (\"" . $pointId . "\")");
        }
    }

    /**
     * Проверка сущестования пункта B для трансфера
     * @param int $pointId
     * @throws \Exception
     */
    public static function checkPointB(int $pointId) {
        if (!self::checkPoint($pointId)) {
            throw new \Exception(__CLASS__ . ": Unknown point B (\"" . $pointId . "\")");
        }
    }

    /**
     * @param int $pointId
     * @return boolean
     */
    protected static function checkPoint(int $pointId) {
        $arPoint = U::getGeoPoint($pointId);
        if ($arPoint["ID"] > 0) {
            return true;
        }
        return false;
    }

    /**
     * Проверка даты начала
     * @param int $dateFrom
     * @throws \Exception
     */
    public static function checkDateFrom(int $dateFrom) {
        if (!self::aboveZero($dateFrom)) {
            throw new \Exception(__CLASS__ . ": Date_from must be > 0");
        }
    }
    
    /**
     * Проверка масиива целых чисел на "положительность"
     * @param array $array
     */
    public static function checkArray (array $array) {
        
        if (empty($array)) {
            throw new \Exception(__CLASS__ . ": Array with numbers > 0 is empty");
        }
        
        foreach ($array as $val) {
            if (!self::aboveZero($val)) {
                throw new \Exception(__CLASS__ . ": Some number is < or = 0");
            }
        }
        
    }
    
    /**
     * Проверка даты окончания
     * @param int $dateTo
     * @param int $dateFrom
     * @throws \Exception
     */
    public static function checkDateTo(int $dateTo, int $dateFrom) {
        if (($dateTo - $dateFrom) < 0) {
            throw new \Exception(__CLASS__ . ": Date_to (\"" . $dateTo . "\") must be > date_from (\"" . $dateFrom . "\") ");
        }
    }

    /**
     * Проверка количества взрослых
     * @param int $adults
     * @throws \Exception
     */
    public static function checkAdults(int $adults) {
//        if (!self::aboveZero($adults)) {
        if (!self::geZero($adults)) {
            throw new \Exception(__CLASS__ . ": Unknown adults count");
        }
    }

    /**
     * Проверка количества детей
     * @param int $children
     * @throws \Exception
     */
    public static function checkChildren(int $children) {
        if (!self::geZero($children)) {
            throw new \Exception(__CLASS__ . ": Unknown children count");
        }
    }

    /**
     * Проверка существования услуги
     * @param int $id
     * @throws \Exception
     */
    public static function checkService(int $id) {
        $arService = U::getServiceById($id);
        if ($arService[0]["ID"] != $id) {
            throw new \Exception(__CLASS__ . ": Unknown service");
        }
    }

    /**
     * Проверка существования тарифа
     * @param int $id
     * @throws \Exception
     */
    public static function checkRate(int $id) {
        $arRate = RatesDataStore::get(array(
                    "filter" => array("ID" => $id),
                    "select" => array("ID")
        ));

        if ($arRate[0]["ID"] != $id) {
            throw new \Exception(__CLASS__ . ": Unknown rate");
        }
    }

    /**
     * Проверка существования тарифа для трансфера
     * @param int $id
     * @throws \Exception
     */
    public static function checkTransferRate(int $id) {
        $arRate = TransfersRatesDataStore::get(array(
                    "filter" => array("ID" => $id),
                    "select" => array("ID")
        ));

        if ($arRate[0]["ID"] != $id) {
            throw new \Exception(__CLASS__ . ": Unknown transfer rate");
        }
    }
    
    public static function checkDataStoreOnEmpty (Adapter1DataStore $dataStore) {
        
        if (empty($dataStore->fetch())) {
            throw new \Exception(__CLASS__ . ": Empty data store");
        }
        
    }
    
    /**
     * Проверка существования цены для граждан
     * @param int $citizenPrice
     */
    public static function checkCitizenPrice (int $citizenPrice) {
        $arCitizenPrices = U::getCitizenPrices();

        if (!isset($arCitizenPrices["ITEMS"][$citizenPrice])) {
            throw new \Exception(__CLASS__ . ": Unknown citizen price");
        }
    }

}
