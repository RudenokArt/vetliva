<?php

namespace travelsoft\booking\transfers;
use travelsoft\booking\abstractions\Getter;

/**
 * Класс сущности трансфера
 *
 * @author dimabresky
 */
class Transfer extends Getter {
    
    protected $_container = array(
        "id" => null,
        "distance" => null,
        "travelTime" => null,
        "point_A" => null,
        "point_B" => null,
        "name" => null
    );

    /**
     * Возвращает отформатированное по шаблону время в пути
     * @param string $template
     * @return string
     */
    public function formattedTravelTime (string $template = "#HH# hours #MM# min") {
        
        $time  = $this->_container["travelTime"] / 3600;
        $HH = intVal($time);
        $MM = round( abs($time - $HH) * 60 );
        if ($MM < 10) {
            $MM = "0" . $MM;
        }
        
        echo str_replace (array("#HH#", "#MM#"), array($HH, $MM), $template);
        
    }
    
    /**
     * Возвращает отформатированное по шаблону расстояние
     * @param string $template
     * @return string
     */
    public function formattedDistance (string $template = "#KM# км. #M# м.") {
        
        $distance  = $this->_container["distance"] / 1000;
        $KM = intVal($distance);
        $M = round( abs($distance - $KM) * 1000 );
        
        echo str_replace (array("#KM#", "#M#"), array($KM, $M), $template);
        
    }

}
