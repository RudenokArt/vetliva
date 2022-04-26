<?php

namespace travelsoft\booking\abstractions;

/**
 * Абстрактный класс для строителей провайдеров данных
 * 
 * @author dimabresky
 */
abstract class DataProviderBuilder {

    /**
     * @var travelsoft\booking\abstractions\Request
     */
    protected $request = null;
        
    /**
     * @param \travelsoft\booking\abstractions\Request $request
     */
    public function __construct(Request $request) {
        $this->request = $request;
    }

    abstract public function build();
}
