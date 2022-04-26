<?php

namespace travelsoft\booking\transfers;
use travelsoft\booking\transfers\Transfer;
use travelsoft\booking\datastores\TransfersDataStore as TDS;
use travelsoft\booking\Utils as U;
/**
 * "Строилтель" сущности трансфера
 *
 * @author dimabresky
 */
class TransfersBuilder {
    
    /**
     * Строит сущность трансфера
     * @param int $point_A
     * @param int $point_B
     * @return Transfer
     * @throws \Exception
     */
    public static function build (int $point_A, int $point_B) {

        $parameters["point_A"] = GeoPointBuilder::build($point_A);
        $parameters["point_B"] = GeoPointBuilder::build($point_B);
        $parameters["name"] = $parameters["point_A"]->name . " - " . $parameters["point_B"]->name;
        
        $arTransfers = TDS::get(array("filter" => array(
                        array(
                            "LOGIC" => "OR",
                            array("UF_POINT_A" => $parameters["point_A"]->id, "UF_POINT_B" => $parameters["point_A"]->id),
                            array("UF_POINT_A" => $parameters["point_B"]->id, "UF_POINT_B" => $parameters["point_B"]->id)
                        )
        )));

        if ($arTransfers[0]["UF_DISTANCE"]) {
            $parameters["id"] = $arTransfers[0]["ID"];
            $parameters["distance"] = $arTransfers[0]["UF_DISTANCE"];
            $parameters["travelTime"] = $arTransfers[0]["UF_TRAVEL_TIME"];
        } else {
            $arInfo = U::getTransferInfo(
                            implode(",", array($parameters["point_A"]->lat, $parameters["point_A"]->lng)), 
                    implode(",", array($parameters["point_B"]->lat, $parameters["point_B"]->lng))
            );

            if ($arInfo["distance"]) {
                $arSave["UF_DISTANCE"] = $parameters["distance"] = $arInfo["distance"];
                $arSave["UF_TRAVEL_TIME"] = $parameters["travelTime"] = $arInfo["travel_time"];
                if ($arTransfers[0]["ID"]) {
                    TDS::save($arSave, $arTransfers[0]["ID"]);
                } else {
                    $arSave["UF_POINT_A"] = $parameters["point_A"]->id;
                    $arSave["UF_POINT_B"] = $parameters["point_B"]->id;
                    $parameters["id"] = TDS::save($arSave);
                    if (!$parameters["id"]) {
                        throw new \Exception("Transfer can not be created");
                    }
                }
            }
        }
        
        return new Transfer($parameters);
    }
    
}
