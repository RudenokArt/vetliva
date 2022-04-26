<?php

namespace travelsoft\booking\placements;
use travelsoft\booking\abstractions\commons\DataProviderBuilder as CommonDataProvider;
use travelsoft\booking\placements\Request;
use travelsoft\booking\Utils as U;

/**
 * "Строитель" поставщика данных по экскурсиям
 *
 * @author dimabresky
 */
class DataProviderBuilder extends CommonDataProvider {

    public function __construct (Request $request) {
        parent::__construct($request);
    }
    
    public function build () {

        $parameters = parent::_getParameters();
        $parameters["duration"] = ($this->request->date_to - $this->request->date_from) / 86400;
        
        if ($parameters["prices"] && !empty($parameters["prices"]->fetch())) {
            
            $parameters['life_periods'] = $parameters["prices"]->getLifePeriods($this->request->date_from);

            $parameters["prices"]->filterByLifePeriod ($parameters["duration"], $this->request->date_from);
            $parameters["prices"]->filterByLifePeriodStay ($parameters["duration"]);

            $parameters['prices']->filterByCalcForPlacesRates($parameters);
            
            $parameters["prices"]->filterByEmptyPricesPerRange (
                    $this->request->date_from,
                    $parameters["duration"], 
                    $parameters["quotas"]->fetch(array("UF_SERVICE_ID", "UF_DATE")),
                    U::filterServicesByParameter((array)array_keys($parameters["prices"]->fetch(array('UF_SERVICE_ID'))), 'CALC_BY_DAY')
            );
            
            $parameters["prices"]->filterByNoArrivals($this->request->date_from);
        }
        
        return new DataProvider($parameters);
    }
    
    /**
     * ФОРМИРОВАНИЕ ПАРАМЕТРОВ ДЛЯ ПОИСКА ПО ТАРИФАМ
     * @param array $SID
     * @return array
     */
    protected function _qfr(array $SID) {
        $parameters = parent::_qfr($SID);
        $filter = array();        
        if ($this->request->citizen_price) {
            $arCitizenPrices = U::getCitizenPrices();
            $filter[1] = array("LOGIC" => "OR", array("UF_BR_PRICES" => false, "UF_RF_PRICES" => false, "UF_EU_PRICES" => false), array($arCitizenPrices["ITEMS"][$this->request->citizen_price] => 1));
        }
        $parameters["filter"] = array_merge($parameters["filter"], $filter);

        return $parameters;
    }

}
