<?php

namespace travelsoft\booking\sanatorium;
use travelsoft\booking\placements\DataProviderBuilder as PlacementsDataProviderBuilder;
use travelsoft\booking\placements\Request;

/**
 * "Строитель" поставщика данных по экскурсиям
 *
 * @author dimabresky
 */
class DataProviderBuilder extends PlacementsDataProviderBuilder {

    public function __construct (Request $request) {
        parent::__construct($request);
    }
}
