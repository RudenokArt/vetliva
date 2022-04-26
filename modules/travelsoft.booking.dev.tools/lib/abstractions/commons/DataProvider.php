<?php

namespace travelsoft\booking\abstractions\commons;

use travelsoft\booking\abstractions\DataProvider as AbstractDataProvider;

/**
 * Расширения абстрактного класса провайдера данных
 *
 * @author dimabresky
 */
abstract class DataProvider extends AbstractDataProvider {

    public function __construct(array $parameters) {

        $this->_container["rates"] = null;
        $this->_container["quotas"] = null;
        $this->_container["priceTypes"] = null;
        $this->_container["request"] = null;

        if ($parameters['request']) {
            
            $this->_setRequest($parameters['request']);
            unset($parameters['request']);
        }

        parent::__construct($parameters);
    }

    /**
     * @param \travelsoft\booking\datastores\RatesDataStore $dataStore
     */
    protected function _setRates(\travelsoft\booking\datastores\RatesDataStore $dataStore) {
        $this->_container["rates"] = $dataStore;
    }

    /**
     * @param \travelsoft\booking\abstractions\Adapter1DataStore
     */
    protected function _setQuotas(\travelsoft\booking\abstractions\Adapter1DataStore $dataStore) {
        $this->_container["quotas"] = $dataStore;
    }

    /**
     * @param \travelsoft\booking\datastores\PriceTypesDataStore $dataStore
     */
    protected function _setPriceTypes(\travelsoft\booking\datastores\PriceTypesDataStore $dataStore) {
        $this->_container["priceTypes"] = $dataStore;
    }
    
    /**
     * @param \travelsoft\booking\abstractions\Request $request
     */
    protected function _setRequest (\travelsoft\booking\abstractions\Request $request) {
        
        $this->_container['request'] = $request;
    }

}
