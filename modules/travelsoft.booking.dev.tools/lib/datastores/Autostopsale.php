<?php
namespace travelsoft\booking\datastores;
use travelsoft\booking\abstractions\Adapter1DataStore;
/**
 * Класс для работы с таблицей autostopsale
 *
 * @author dimabresky
 */
class Autostopsale extends Adapter1DataStore{
    
    /**
     * @var string
     */
    protected static $store = "autostopsale";
}
