<?php

namespace travelsoft\booking\sanatorium;

use travelsoft\booking\placements\PriceCalculator as PlacementsPriceCalculator;
use travelsoft\booking\placements\DataProvider;

/**
 * Класс расчёта цен по размещениям (санатории)
 * 
 * @author dima
 */
class PriceCalculator extends PlacementsPriceCalculator {

    /**
     * @param \travelsoft\booking\sanatorium\DataProvider $dataProvider
     */
    public function __construct(DataProvider $dataProvider) {
        parent::__construct($dataProvider);
    }
}
