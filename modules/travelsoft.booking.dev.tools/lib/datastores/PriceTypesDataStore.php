<?php

namespace travelsoft\booking\datastores;
use travelsoft\booking\abstractions\Adapter1DataStore;

/**
 * Класс данных из таблицы тарифов
 *
 * @author dimabresky
 */
class PriceTypesDataStore extends Adapter1DataStore {
    
    /**
     * @var string
     */
    protected static $store = "pricetypes";
    
}
