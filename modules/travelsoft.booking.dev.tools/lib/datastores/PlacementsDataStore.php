<?php

namespace travelsoft\booking\datastores;
use travelsoft\booking\abstractions\Adapter2DataStore;

/**
 * Класс данных из таблицы объекты размещения
 *
 * @author dimabresky
 */
class PlacementsDataStore extends Adapter2DataStore {
    
    /**
     * @var string
     */
    protected static $store = "placements";

}
