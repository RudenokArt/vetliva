<?php

namespace travelsoft\booking\placements;

use travelsoft\booking\abstractions\commons\Request as CommonRequest;
use travelsoft\booking\Validation;

/**
 * Класс запроса по размещениям
 *
 * @author dimabresky
 */
class Request extends CommonRequest {

    public function __construct(array $parameters) {
        parent::__construct($parameters);
        $this->_container["citizen_price"] = null;
        if ($parameters["citizen_price"]) {
            Validation::checkCitizenPrice($parameters["citizen_price"]);
            $this->_container["citizen_price"] = $parameters["citizen_price"];
        }
    }

}
