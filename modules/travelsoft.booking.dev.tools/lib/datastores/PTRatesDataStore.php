<?php

namespace travelsoft\booking\datastores;
use travelsoft\booking\abstractions\Adapter1DataStore;
/**
 * Класс данных из таблицы тарифы + типы цен
 *
 * @author dimabresky
 */
class PTRatesDataStore extends Adapter1DataStore {
    
    protected static $store = "ptrates";
        
}
