<?php

namespace travelsoft\booking\abstractions;

abstract class DataProvider extends Getter {
    
    protected $_container = array (
        "services" => null,
        "prices" => null,
        "priceTypesRates" => null
    );
    
    /**
     * @param array $parameters
     */
    public function __construct(array $parameters) {
        foreach ($parameters as $property => $dataStore) {
            $this->_setDataStore($dataStore, $property);
        }
    }
    
    /**
     * @param \travelsoft\booking\abstractions\Adapter1DataStore $dataStore
     * @param string $property
     */
    protected function _setDataStore (\travelsoft\booking\abstractions\DataStoreInterface $dataStore, string $property) {

        $method_name = "_set" . ucfirst($property);
        
        $this->$method_name($dataStore);
        
    }
    
    /**
     * @param \travelsoft\booking\datastores\ServicesDataStore $dataStore
     */
    protected function _setServices (\travelsoft\booking\datastores\ServicesDataStore $dataStore) {
        $this->_container["services"] = $dataStore;
    }
    
    /**
     * @param \travelsoft\booking\datastores\PricesDataStore $dataStore
     */
    protected function _setPrices (\travelsoft\booking\datastores\PricesDataStore $dataStore) {
        $this->_container["prices"] = $dataStore;
    }
    
    /**
     * @param \travelsoft\booking\datastores\PTRatesDataStore $dataStore
     */
    protected function _setPriceTypesRates (\travelsoft\booking\datastores\PTRatesDataStore $dataStore) {
        $this->_container["priceTypesRates"] = $dataStore;
    }
    
} 