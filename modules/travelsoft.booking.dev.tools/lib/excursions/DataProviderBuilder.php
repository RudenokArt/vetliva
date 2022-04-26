<?php

namespace travelsoft\booking\excursions;
use travelsoft\booking\abstractions\commons\DataProviderBuilder as CommonDataProviderBuilder;
use travelsoft\booking\excursions\Request;

/**
 * "Строитель" поставщика данных по экскурсиям
 *
 * @author dimabresky
 */
class DataProviderBuilder extends CommonDataProviderBuilder {

    protected $type= 'excursions';

    public function __construct (Request $request) {
        
        parent::__construct($request);
    }
    
    public function build () {
        
        $parameters = parent::_getParameters();
        if ($parameters['prices']) {
            $parameters['prices']->filterByNumberOnSaleForExcursions($parameters);
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
    protected function _qfr(array $SID) {

        $qfr = parent::_qfr($SID);
        // ДЛЯ МНОГОДНЕВНЫХ ТУРОВ
        $qfr['select'][] = "UF_MAIN_PLACES";
        $qfr['select'][] = "UF_ADD_PLACES";
        return $qfr;
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

        $filter[">=UF_QUOTE"] = $this->request->adults + $this->request->children;
        $filter["UF_STOP"] = 0;
        $select = array("ID", "UF_SERVICE_ID", "UF_QUOTE", "UF_SOLD_NUMBER", "UF_STOP", "UF_DATE", "UF_RELEASE_PERIOD");
        $order = array("UF_DATE" => "ASC");
        
        return array("filter" => $filter, "select" => $select, "order" => $order);
    }
}
