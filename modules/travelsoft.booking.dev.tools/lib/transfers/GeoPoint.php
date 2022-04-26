<?php

namespace travelsoft\booking\transfers;
use travelsoft\booking\abstractions\Getter;


/**
 * Класс сущности геопункт
 *
 * @author dimabresky
 */
class GeoPoint extends Getter {

    protected $_container = array(
        "id" => null,
        "name" => null,
        "lat" => null,
        "lng" => null,
    );
}
