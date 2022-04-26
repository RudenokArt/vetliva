<?php

namespace travelsoft\booking\abstractions\commons;

use travelsoft\booking\abstractions\Request as AbstractionRequest;
use travelsoft\booking\Validation;

/**
 * Расширение абстрактного класса запроса
 *
 * @author dimabresky
 */
abstract class Request extends AbstractionRequest {

    public function __construct(array $parameters) {

        parent::__construct($parameters);
        $this->_container["id"] = null;
        
        if ($parameters["id"]) {
            Validation::checkArray($parameters["id"]);
            $this->_container["id"] = $parameters["id"];
        }
        Validation::checkChildren($parameters["children"]);
        $this->_container["children"] = $parameters["children"];
        
        $this->_container["children_age"] = null;
        if ($parameters["children_age"]) {
            Validation::checkArray($parameters["children_age"]);
            $this->_container["children_age"] = $parameters["children_age"];
        }

        Validation::checkDateTo($parameters["date_to"], $this->_container["date_from"]);
        $this->_container["date_to"] = $this->_beginTimestamp($parameters["date_to"]);
                
        if ($parameters["rate_id"] > 0) {
            Validation::checkRate($parameters["rate_id"]);
            $this->_container["rate_id"] = $parameters["rate_id"];
        }
        
    }
}
