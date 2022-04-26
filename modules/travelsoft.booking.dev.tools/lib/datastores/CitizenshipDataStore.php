<?php

namespace travelsoft\booking\datastores;
use travelsoft\booking\abstractions\Adapter1DataStore;

/**
 * Класс данных из таблицы гражданства
 *
 * @author dimabresky
 */
class CitizenshipDataStore extends Adapter1DataStore {
    
    /**
     * @var string
     */
    protected static $store = "citizenship";
    
}
