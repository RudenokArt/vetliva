<?php

namespace travelsoft\booking\transfers;

use travelsoft\booking\abstractions\DataProvider as AbstaractDataProvider;

/**
 * Поставщик данных по трансферам для расчёта цен
 *
 * @author dimabresky
 */
class DataProvider extends AbstaractDataProvider {

    public function __construct(array $parameters) {
        $this->_container["transfers"] = null;
        $this->_container["transfersRates"] = null;
        $this->_container["transfer"] = null;
        
        $this->_setTransfer($parameters["transfer"]);
        unset($parameters["transfer"]);
        
        parent::__construct($parameters);
    }
    
    /**
     * @param \travelsoft\booking\datastores\TransfersDataStore $dataStore
     */
    protected function _setTransfers (\travelsoft\booking\datastores\TransfersDataStore $dataStore) {
        $this->_container["transfers"] = $dataStore;
    }
    
    /**
     * @param \travelsoft\booking\datastores\TransfersRatesDataStore $dataStore
     */
    protected function _setTransfersRates (\travelsoft\booking\datastores\TransfersRatesDataStore $dataStore) {
        $this->_container["transfersRates"] = $dataStore;
    }
    
    /**
     * @param \travelsoft\booking\transfers\Transfer $transfer
     */
    protected function _setTransfer (Transfer $transfer) {
        $this->_container["transfer"] = $transfer;
    }
    
}
