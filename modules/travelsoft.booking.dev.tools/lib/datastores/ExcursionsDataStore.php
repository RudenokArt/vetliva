<?php

namespace travelsoft\booking\datastores;
use travelsoft\booking\abstractions\Adapter2DataStore;

/**
 * Класс данных из таблицы санатории
 *
 * @author dimabresky
 */
class ExcursionsDataStore extends Adapter2DataStore {
    
    /**
     * @var string
     */
    protected static $store = "excursions";

}
