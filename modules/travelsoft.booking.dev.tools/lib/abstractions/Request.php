<?php

namespace travelsoft\booking\abstractions;

use travelsoft\booking\Validation;

/**
 * Интерфейс класса запроса
 * 
 * @author dimabresky
 */
abstract class Request extends Getter {

    protected $_container = array(
        "date_from" => null,
        "date_to" => null,
        "service_id" => null,
        "adults" => null,
        "rate_id" => null
    );

    public function __construct(array $parameters) {
        
        Validation::checkAdults($parameters["adults"]);
        $this->_container["adults"] = $parameters["adults"];

        Validation::checkDateFrom($parameters["date_from"]);
        $this->_container["date_from"] = $this->_beginTimestamp($parameters["date_from"]);
        $this->_container["service_id"] = null;
        if ($parameters["service_id"] > 0) {
            Validation::checkService($parameters["service_id"]);
            $this->_container["service_id"] = $parameters["service_id"];
        }
    }
    
    /**
     * @param string $timestamp
     * @return string
     */
    protected function _beginTimestamp (string $timestamp) {
        return strtotime(date('d.m.Y', $timestamp));
    }
}
