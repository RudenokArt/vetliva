<?php

namespace travelsoft\booking\abstractions;
use travelsoft\booking\Utils as U;

/**
 * Абстрактный класс адаптер для хранилища данных
 *
 * @author dimabresky
 */
abstract class Adapter1DataStore extends DataStoreInterface {

    public static function get (array $query = array()) {
        $class = get_called_class();
        return U::getFromStore($class::$store, $query);
    }
    
    public static function save (array $arSave, int $ID = null) {
        $class = get_called_class();
        return U::saveInStore($class::$store, $arSave, $ID);
    }
    
    public static function delete (int $ID) {
        $class = get_called_class();
        return U::deleteFromStore($class::$store, $ID);
    }
    
}
