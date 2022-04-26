<?php

namespace travelsoft\booking\abstractions\commons;

use travelsoft\booking\abstractions\DataProviderBuilder as AbstractDataProvider;
use travelsoft\booking\datastores\ServicesDataStore;
use travelsoft\booking\datastores\QuotasDataStore;
use travelsoft\booking\datastores\PTRatesDataStore;
use travelsoft\booking\datastores\RatesDataStore;
use travelsoft\booking\datastores\PriceTypesDataStore;
use travelsoft\booking\datastores\PricesDataStore;

/**
 * Расширение абстрактного класса строителя поставщика данных
 *
 * @author dimabresky
 */
abstract class DataProviderBuilder extends AbstractDataProvider {

    /**
     * @return array
     */
    protected function _getParameters() {

        $parameters = array();

        $parameters["services"] = new ServicesDataStore($this->_qfs());

        //$SID = array_keys($parameters["services"]->filterByAutostopsale($this->request->date_from)->fetch(array("ID")));
        $SID = array_keys($parameters["services"]->fetch(array("ID")));
        
        if ($SID) {

            //для экскурсий проверям на релиз период все даты а не дату начала
            $releasePeriodAllDays = false;
            if ($this->type == 'excursions') {
                $releasePeriodAllDays = true;
            }

            $parameters["quotas"] = new QuotasDataStore($this->_qfq($SID));
            $SID = array_keys($parameters["quotas"]->filterAvailableForSale($releasePeriodAllDays)->filterByAutostopsale()->fetch(array("UF_SERVICE_ID")));
           
            if ($SID) {
 
                $parameters["rates"] = new RatesDataStore($this->_qfr($SID));
                
                $dates = (array)array_keys($parameters["quotas"]->fetch(array("UF_DATE")));
                
                $RID = array_keys($parameters["rates"]->fetch(array("ID")));

                if ($RID) {

                    $parameters["priceTypes"] = new PriceTypesDataStore($this->_qfpt());
                    $PTID = array_keys($parameters["priceTypes"]->fetch(array("ID")));

                    $parameters["priceTypesRates"] = new PTRatesDataStore($this->_qfptr($RID, $PTID));
                    $PTRID = array_keys($parameters["priceTypesRates"]->fetch(array("ID")));
                    
                    if ($PTRID) {

                        $parameters["prices"] = new PricesDataStore($this->_qfp($SID, $PTRID, $dates));
                    }
                }
            }
        }

        $parameters['request'] = $this->request;
        return $parameters;
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
        }
        $filter[0] = array("LOGIC" => "OR", array("UF_MIN_PEOPLE" => false), array("<=UF_MIN_PEOPLE" => $people));
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

        $filter["!UF_QUOTE"] = false;
        $filter["UF_STOP"] = 0;
        $select = array("ID", "UF_SERVICE_ID", "UF_QUOTE", "UF_SOLD_NUMBER", "UF_STOP", "UF_DATE", "UF_RELEASE_PERIOD");
        $order = array("UF_DATE" => "ASC");

        return array("filter" => $filter, "select" => $select, "order" => $order);
    }

    /**
     * ФОРМИРОВАНИЕ ПАРАМЕТРОВ ДЛЯ ПОИСКА ПО ТАРИФАМ
     * @param array $SID
     * @return array
     */
    protected function _qfr(array $SID) {

        $filter = $select = null;
        $filter[0] = array("LOGIC" => "OR", array("UF_SERVICES_ID" => false), array("UF_SERVICES_ID" => $SID));

        if ($this->request->rate_id) {
            
            $filter["ID"] = $this->request->rate_id;
        }

        $select = array(
            "ID",
            "UF_NAME",
            "UF_FOR_PLACE",
            "UF_ADULTS",
            "UF_CHILDREN",
            "UF_PEOPLE",
            "UF_SERVICES_ID",
            "UF_CURRENCY_ID",
            "UF_BR_PRICES",
            "UF_RF_PRICES",
            "UF_EU_PRICES",
            "UF_AGE_CAT_1_MIN",
            "UF_AGE_CAT_1_MAX",
            "UF_AGE_CAT_2_MIN",
            "UF_AGE_CAT_2_MAX",
            "UF_AGE_CAT_3_MIN",
            "UF_AGE_CAT_3_MAX",
            "UF_DISCOUNT",
            "UF_DISCOUNT_BY_DAYS"
        );

        return array("filter" => $filter, "select" => $select);
    }

    /**
     * ФОРМИРОВАНИЕ ПАРАМЕТРОВ ДЛЯ ПОИСКА ПО ТИПАМ ЦЕН
     * @return array
     */
    protected function _qfpt() {

        $filter = $select = null;


        $filter["UF_ACTIVE"] = true;

        return array("filter" => $filter);
    }

    /**
     * ФОРМИРОВАНИЕ ПАРАМЕТРОВ ДЛЯ ПОИСКА ПО ТАРИФАМ + ТИПАМ ЦЕН
     * @param array $RID
     * @param array $PTID
     * @return array
     */
    protected function _qfptr(array $RID, array $PTID) {

        $filter = null;

        $filter['UF_RATE_ID'] = $PTID;

        $filter['UF_RATE_CATEGORY_ID'] = $RID;

        return array("filter" => $filter);
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

        $filter['UF_DATE'] = $dates;

        $select = array("ID", "UF_GROSS", "UF_DATE", "UF_SERVICE_ID", "UF_LIFE_PERIOD", "UF_PTPR_ID", "UF_NO_ARRIVALS", "UF_DISCOUNT_PERCENT", "UF_DISCOUNT_ABS", "UF_EXTENDED_GROSS", "UF_LIFE_PERIOD_STAY");

        $filter['!UF_GROSS'] = false;

        $order = array("UF_DATE" => "ASC");

        return array("filter" => $filter, "select" => $select, "order" => $order);
    }

}
