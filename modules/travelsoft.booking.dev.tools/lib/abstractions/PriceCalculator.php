<?php

namespace travelsoft\booking\abstractions;

/**
 * Интерфейс классов по расчёту цен
 * @author dimabreky
 */
abstract class PriceCalculator {

    /**
     * @var travelsoft\booking\abstractions\AbstractDataProvider
     */
    protected $_dataProvider = null;
    
    /**
     * @param \travelsoft\booking\excursions\DataProvider $dataProvider
     */
    public function __construct(DataProvider $dataProvider) {
        $this->_dataProvider = $dataProvider;
    }
    
    abstract public function calculate ();
    abstract public function minPrice ();

}
