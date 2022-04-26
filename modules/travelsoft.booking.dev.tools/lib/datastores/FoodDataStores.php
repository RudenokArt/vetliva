<?php

namespace travelsoft\booking\datastores;
use travelsoft\booking\abstractions\Adapter1DataStore;

/**
 * Класс данных из таблицы питания
 *
 * @author dimabresky
 */
class FoodDataStores extends Adapter1DataStore {
    
    /**
     * @var string
     */
    protected static $store = "food";
    
}