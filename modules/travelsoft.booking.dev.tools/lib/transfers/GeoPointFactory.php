<?php

namespace travelsoft\booking\transfers;
use travelsoft\booking\transfers\GeoPoint;
use travelsoft\booking\Utils as U;

/**
 * "Строитель" сущности геопункт
 *
 * @author dimabresky
 */
class GeoPointFactory {
    
    /**
     * Строит сущность геопункт 
     * @param int $pointId
     * @throws \Exception
     */
    public static function build (int $pointId) {
        
        $arPoint = U::getGeoPoint($pointId);

        if ($arPoint["ID"] <= 0) {
            throw new \Exception(__CLASS__ . ": Unknown id of point (\"" . $pointId . "\")");
        }
        
        return new GeoPoint(array(
            "id" => $arPoint["ID"],
            "name" => $arPoint["NAME"],
            "lat" => $arPoint["LAT"],
            "lng" => $arPoint["LNG"]
        ));
    }
    
}
