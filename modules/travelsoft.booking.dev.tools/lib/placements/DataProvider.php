<?php

namespace travelsoft\booking\placements;

use travelsoft\booking\abstractions\commons\DataProvider as CommonDataProvider;

/**
 * Поставщик данных для расчёта цен по размещениям
 *
 * @author dimabresky
 */
class DataProvider extends CommonDataProvider {

    /**
     * @param array $parameters
     */
    public function __construct(array $parameters) {
        
        $this->_container["duration"] = null;
        if (isset($parameters["duration"]) && $parameters["duration"] >= 0) {
            $this->_setDuration($parameters["duration"]);
            unset($parameters["duration"]);
        }
        
        $this->_container['life_periods'] = null;
        
        if (is_array($parameters['life_periods'])) {
            $this->_container['life_periods'] = $parameters['life_periods'];
            unset($parameters['life_periods']);
        }
        
        parent::__construct($parameters);
    }
    
    protected function _setDuration (int $duration) {
        $this->_container["duration"] = $duration;
    }

}
