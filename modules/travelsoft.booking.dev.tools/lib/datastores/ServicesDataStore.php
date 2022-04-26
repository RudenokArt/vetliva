<?php

namespace travelsoft\booking\datastores;

use travelsoft\booking\abstractions\Adapter1DataStore;

/**
 * Класс данных из таблицы услуг
 *
 * @author dima
 */
class ServicesDataStore extends Adapter1DataStore {

    /**
     * @var string
     */
    protected static $store = "services";


}
