<?php

namespace travelsoft\booking\excursionstours;
use travelsoft\booking\abstractions\commons\DataProviderBuilder as CommonDataProviderBuilder;

/**
 * "Строитель" поставщика данных по турам
 *
 * @author dimabresky
 */
class DataProviderBuilder extends CommonDataProviderBuilder {

    public function __construct (\travelsoft\booking\abstractions\commons\Request $request) {
        
        parent::__construct($request);
    }
    
    public function build () {
        
        $parameters = array("request" => $this->request);

        $parameters["services"] = new \travelsoft\booking\datastores\ServicesDataStore($this->_qfs());

        $SID = array_keys($parameters["services"]->fetch(array("ID")));

        if ($SID) {

            $parameters["quotas"] = new \travelsoft\booking\datastores\RatesQuotasDataStore($this->_qfq($SID));
            
            $SID = array_keys($parameters["quotas"]->filterAvailableForSale(true)->filterByAutostopsale()->fetch(array("UF_SERVICE_ID")));
   
            $this->request->rate_id = array_keys($parameters["quotas"]->fetch(array("UF_RATE_ID")));
            
            $dates = array_keys($parameters["quotas"]->fetch(array("UF_DATE")));
            
            if ($SID) {
                
                $parameters["rates"] = new \travelsoft\booking\datastores\RatesDataStore($this->_qfr($SID, $this->request->rate_id));

                $RID = array_keys($parameters["rates"]->filterByAge($this->request->children_age)->fetch(array("ID")));

                if ($RID) {
                    $parameters["priceTypes"] = new \travelsoft\booking\datastores\PriceTypesDataStore($this->_qfpt());
                    $PTID = array_keys($parameters["priceTypes"]->fetch(array("ID")));
                    $parameters["priceTypesRates"] = new \travelsoft\booking\datastores\PTRatesDataStore($this->_qfptr($RID, $PTID));
                    $PTRID = array_keys($parameters["priceTypesRates"]->fetch(array("ID")));

                    if ($PTRID) {
                        
                        $parameters["prices"] = new \travelsoft\booking\datastores\PricesDataStore($this->_qfp($SID, $PTRID, $dates));
                        $parameters["prices"]->filterByAvailableRates($parameters);
//                        if ($GLOBALS["USER"]->IsAdmin()) {dm($parameters["prices"]->fetch());die;}
                    }
                }
            }
        }
        
        return new DataProvider($parameters);
    }
    
    /**
     * ФОРМИРОВАНИЕ ПАРАМЕТРОВ ДЛЯ ПОИСКА ПО ЦЕНАМ
     * @param array $SID
     * @param array $PTRID
     * @return array
     */
    protected function _qfp(array $SID, array $PTRID, array $dates) {

        $filter = $select = $order = null;

        $filter['UF_SERVICE_ID'] = $SID;

        $filter["UF_PTPR_ID"] = $PTRID;

        $filter["UF_NO_ARRIVALS"] = false;
        
        $filter['UF_DATE'] = $dates;

        $select = array("ID", "UF_GROSS", "UF_DATE", "UF_SERVICE_ID", "UF_LIFE_PERIOD", "UF_PTPR_ID", "UF_DISCOUNT_PERCENT", "UF_DISCOUNT_ABS");

        $filter['!UF_GROSS'] = false;

        $order = array("UF_DATE" => "ASC");
        
        return array("filter" => $filter, "select" => $select, "order" => $order);
    }
    
    /**
     * ФОРМИРОВАНИЕ ПАРАМЕТРОВ ДЛЯ ПОИСКА ПО ТАРИФАМ
     * @param array $SID
     * @return array
     */
    protected function _qfr(array $SID, array $rates = null) {

       $filter = $select = null;
       if ($rates) {
           $filter["ID"] = $rates;
       }
        $filter[0] = array("LOGIC" => "OR", array("UF_SERVICES_ID" => false), array("UF_SERVICES_ID" => $SID));
        if ($this->request->rate_id) {
            $filter["ID"] = $this->request->rate_id;
        }
        $people = null;
        if ($this->request->adults) {
            $people = $this->request->adults;
            $filter[">=UF_ADULTS"] = $this->request->adults;
        }
        if ($this->request->children) {
            $people += $this->request->children;
            $filter[">=UF_CHILDREN"] = $this->request->children;
        }
        if ($people) {
            $filter[">=UF_PEOPLE"] = $people;
            $filter["<=UF_MIN_PEOPLE"] = $people;
        }
        $select = array("ID", "UF_MAIN_PLACES", "UF_ADD_PLACES", "UF_NAME", "UF_CURRENCY_ID", "UF_AGE_CAT_1_MIN", "UF_AGE_CAT_1_MAX", "UF_AGE_CAT_2_MIN", "UF_AGE_CAT_2_MAX",
            "UF_AGE_CAT_3_MIN", "UF_AGE_CAT_3_MAX");
        
        // ДЛЯ МНОГОДНЕВНЫХ ТУРОВ
        $filter['UF_FOR_PLACE'] = 0;
        $select[] = "UF_MAIN_PLACES";
        $select[] = "UF_ADD_PLACES";
        
        return array("filter" => $filter, "select" => $select);
    }
    
    /**
     * ФОРМИРОВАНИЕ ПАРАМЕТРОВ ДЛЯ ПОИСКА УСЛУГ
     * @return array
     */
    protected function _qfs() {

        $filter = $select = null;

        if ($this->request->id) {
            $filter["UF_IBLOCK_ELEMENT_ID"] = $this->request->id;
        }

        if ($this->request->service_id) {
            $filter["ID"] = $this->request->service_id;
        }
        
        $select = array("ID", "UF_IBLOCK_ELEMENT_ID", 'UF_PLACES_MAIN', 'UF_PLACES_ADD', 'UF_PEOPLE');

        return array("filter" => $filter, "select" => $select);
    }
    
    /**
     * ФОРМИРОВАНИЕ ПАРАМЕТРОВ ДЛЯ ПОИСКА ПО КВОТАМ
     * @param array $SID
     * @return array
     */
    protected function _qfq(array $SID) {

        $filter = $select = $order = null;
        $filter["UF_SERVICE_ID"] = $SID;
        
        if ($this->request->date_from && $this->request->date_to) {
            $filter["><UF_DATE"] = array($this->request->date_from, $this->request->date_to);
        } elseif ($this->request->date_from) {
            $filter[">=UF_DATE"] = $this->request->date_from;
        } elseif ($this->request->date_to) {
            $filter["<=UF_DATE"] = $this->request->date_to;
        }
       
        if ($this->request->rate_id) {
            $filter['UF_RATE_ID'] = $this->request->rate_id;
        }
        
        
        $filter["UF_STOP"] = 0;
        $select = array("ID", "UF_SERVICE_ID", "UF_QUOTE", "UF_SOLD_NUMBER", "UF_STOP", "UF_DATE", "UF_RATE_ID", "UF_RELEASE_PERIOD");
        $order = array("UF_DATE" => "ASC");
        
        return array("filter" => $filter, "select" => $select, "order" => $order);
    }
}
