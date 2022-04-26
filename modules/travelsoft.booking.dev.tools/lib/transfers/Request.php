<?php

namespace travelsoft\booking\transfers;
use travelsoft\booking\abstractions\Request as AbstractRequest;
use travelsoft\booking\Validation;

/**
 * Класс запроса по трансферам
 *
 * @author dimabresky
 */
class Request extends AbstractRequest {

    public function __construct(array $parameters) {
        
        parent::__construct($parameters);
        
        Validation::checkPointA($parameters["point_A"]);
        # точка A для трансферов
        $this->_container["point_A"] = $parameters["point_A"];
        
        Validation::checkPointB($parameters["point_B"]);
        # точка B для трансферов
        $this->_container["point_B"] = $parameters["point_B"];
        
        # трансфер в обе стороны
        $this->_container["roundtrip"] = boolval($parameters["roundtrip"]);
        
        if ($this->_container["roundtrip"]) {
            Validation::checkDateTo($parameters["date_to"], $this->_container["date_from"]);
            $this->_container["date_to"] = $parameters["date_to"];
        }
      
        if ($parameters["rate_id"] > 0) {
            Validation::checkTransferRate($parameters["rate_id"]);
            $this->_container["rate_id"] = $parameters["rate_id"];
        }
    }

}
