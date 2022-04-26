<?php

namespace travelsoft\booking\abstractions;
use travelsoft\booking\Utils as U;

/**
 * Абстрактный класс для хранилища данных (iblock)
 *
 * @author dimabresky
 */
abstract class Adapter2DataStore extends DataStoreInterface {

    public static function get (array $query = array()) {
        $class = get_called_class();
        return U::getFromIBStore($class::$store, $query);
    }
    
    public static function save (array $arSave, int $ID = null) {
        return U::saveInIBStore($arSave, $ID);
    }
    
    public static function delete (int $ID) {
        return U::deleteFromIBStore($ID);
    }
    
}
