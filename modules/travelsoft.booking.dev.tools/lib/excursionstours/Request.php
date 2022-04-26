<?php

namespace travelsoft\booking\excursionstours;
use travelsoft\booking\abstractions\commons\Request as CommonRequest;

/**
 * Класс запроса по эксурсиям
 *
 * @author dimabresky
 */
class Request extends CommonRequest {

    public function __construct(array $parameters) {
        parent::__construct($parameters);
    }
    
}
