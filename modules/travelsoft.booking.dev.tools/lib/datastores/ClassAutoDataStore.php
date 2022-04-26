<?php

namespace travelsoft\booking\datastores;
use travelsoft\booking\abstractions\Adapter1DataStore;

/**
 * Класс данных из таблицы классы авто
 *
 * @author dimabresky
 */
class ClassAutoDataStore extends Adapter1DataStore {
    
    /**
     * @var string
     */
    protected static $store = "classauto";
    
}
