<?php

namespace travelsoft\booking\datastores;

use travelsoft\booking\abstractions\Adapter1DataStore;

/**
 * Класс данных из таблицы цен
 *
 * @author dimabresky
 */
class PricesDataStore extends Adapter1DataStore {

    /**
     * @var string
     */
    protected static $store = "prices";

    /**
     * Получение списка доступных периодов проживания
     * @param string $timestamp
     * @return array
     */
    public function getLifePeriods(string $timestamp) {

        $lp = array();
        
        foreach ($this->_data as $arData) {
            if ($timestamp == $arData['UF_DATE'] && !empty($arData['UF_LIFE_PERIOD'])) {
                foreach ($arData['UF_LIFE_PERIOD'] as $n) {
                    if (!in_array($n, $lp)) {
                        $lp[] = $n;
                    }
                }
            }
        }
        
        sort($lp);
        return $lp;
    }

    /**
     * Фильтрует данные по указанному количеству дней
     * @param int $n
     * @return \travelsoft\booking\datastores\PricesDataStore
     */
    public function filterByLifePeriod(int $n, string $timestamp) {

        $tmpdata = null;

        $data = $this->fetch(array('UF_SERVICE_ID', 'UF_PTPR_ID', 'UF_DATE'));

        foreach ($data as $servId => $arServData) {
            foreach ($arServData as $ptpr_id => $arDateData) {
                if (!empty($arDateData[$timestamp][0]["UF_LIFE_PERIOD"])) {
                    if (in_array($n, $arDateData[$timestamp][0]["UF_LIFE_PERIOD"])) {
                        foreach ($arDateData as $arData) {
                            $tmpdata[] = $arData[0];
                        }
                    }
                } else {
                    foreach ($arDateData as $arData) {
                        $tmpdata[] = $arData[0];
                    }
                }
            }
        }

        $this->_data = $tmpdata;

        return $this;
    }

    /**
     * Фильтрует данные по MinLos-MaxLos количество ночей, которое должен прожить гость, в случае, если дата с ограничением попадает в период проживания
     * @param int $n
     * @return void
     */
    public function filterByLifePeriodStay(int $n) {
        $tmpdata = null;

        $data = $this->fetch(array('UF_SERVICE_ID', 'UF_PTPR_ID', 'UF_DATE'));
        $lifePeriod = null;

        foreach ($data as $servId => $arServData) {
            foreach ($arServData as $ptpr_id => $arDateData) {
                foreach ($arDateData as $timestamp => $dateData) {
                    if (!empty($dateData[0]["UF_LIFE_PERIOD_STAY"])) {
                        $minEl = min($dateData[0]["UF_LIFE_PERIOD_STAY"]);
                        $maxEl = max($dateData[0]["UF_LIFE_PERIOD_STAY"]);
                        $sId = $dateData[0]["UF_SERVICE_ID"];

                        if(!isset($lifePeriod[$sId][$ptpr_id]['minLos']) || $lifePeriod[$sId][$ptpr_id]['minLos'] < $minEl) {
                            $lifePeriod[$sId][$ptpr_id]['minLos'] = $minEl;
                        }

                        if(!isset($lifePeriod[$sId][$ptpr_id]['maxLos']) || $lifePeriod[$sId][$ptpr_id]['maxLos'] > $maxEl) {
                            $lifePeriod[$sId][$ptpr_id]['maxLos'] = $maxEl;
                        }
                    }
                }
            }
        }

        if(empty($lifePeriod)) {
            return $this;
        }

        foreach ($data as $servId => $arServData) {
            foreach ($arServData as $ptpr_id => $arDateData) {
                $sId = $arDateData[array_key_first($arDateData)][0]["UF_SERVICE_ID"];
                if (!empty($lifePeriod[$sId][$ptpr_id])) {
                    if ($n >= $lifePeriod[$sId][$ptpr_id]['minLos'] && $n <= $lifePeriod[$sId][$ptpr_id]['maxLos']) {
                        foreach ($arDateData as $arData) {
                            $tmpdata[] = $arData[0];
                        }
                    }
                } else {
                    foreach ($arDateData as $arData) {
                        $tmpdata[] = $arData[0];
                    }
                }
            }
        }

        $this->_data = $tmpdata;

        return $this;
    }

    /**
     * Фильтрует данные от "пустых" цен по указанному периоду
     * @param int $unxIn
     * @param int $duration
     * @param array $servicesByDayDuration массив услуг с продолжительнстью по дням
     * @return \travelsoft\booking\datastores\PricesDataStore
     */
    public function filterByEmptyPricesPerRange(int $unxIn, int $stepRange, array $quotas = null, array $servicesByDayDuration = null) {

        $tmpdata = null;

        $data = $this->fetch(array("UF_SERVICE_ID", "UF_PTPR_ID", "UF_DATE"));
        $this->_data = null;
        
        foreach ($data as $serviceId => $arData) {

            $_stepRange = in_array($serviceId, $servicesByDayDuration) ? $stepRange : ($stepRange - 1 > 0 ? $stepRange - 1 : 0);

            foreach ($arData as $arrData) {
                
                $tmpdata = null;
                $date = $unxIn;
                $dateTo = $unxIn + $_stepRange * 86400;
                while ($date <= $dateTo) {
                    
                    if (!$arrData[$date][0]["UF_GROSS"] || !$quotas[$serviceId][$date]) {
                        $tmpdata = null;
                        break;
                    }

                    $tmpdata[] = $arrData[$date][0];

                    $date += 86400;
                }
                for ($i = 0, $cnt = count($tmpdata); $i < $cnt; $i++) {
                    $this->_data[] = $tmpdata[$i];
                }
            }
        }

        return $this;
    }
    
    /**
     * Фильтруем цены по "пустым значениям"
     * @param array $timestamps
     */
    public function filterByEmptyPricesPerDates (array $timestamps) {
        
        $tmp_data = null;
        $data = $this->fetch(array("UF_SERVICE_ID", "UF_PTPR_ID", "UF_DATE"));
        $this->_data = null;
        
        foreach ($data as $service_id => $arr_serivce_data) {
            foreach ($arr_serivce_data as $ptpr_id => $arr_ptpr_data) {
                $have_empty = false;
                foreach ($timestamps as $timestamp) {
                    
                    if (!isset($arr_ptpr_data[$timestamp]) || empty($arr_ptpr_data[$timestamp])) {
                        $have_empty = true;
                        break;
                    }
                }
                if (!$have_empty) {
                    foreach ($arr_ptpr_data as $arr_price_data) {
                        $tmp_data[] = $arr_price_data[0];
                    }
                }
            }
        }
        $this->_data = $tmp_data;
    }

    /**
     * Производит умножение цены брутто
     * @param int $factor
     * @return $this
     */
    public function multiplyGross(int $factor = 1) {

        if ($factor >= 1) {

            foreach ($this->_data as &$arData) {

                $arData["UF_GROSS"] = $arData["UF_GROSS"] * $factor;
            }
        }

        return $this;
    }

    /**
     * Фильтрация цен по параметру "Нет заездов"
     * @param string $timestamp_from
     * @return $this
     */
    public function filterByNoArrivals(string $timestamp_from) {

        $tmpdata = null;

        $data = $this->fetch(array("UF_SERVICE_ID", "UF_PTPR_ID", "UF_DATE"));

        $this->_data = null;

        foreach ($data as $serviceId => $arSubData) {
            foreach ($arSubData as $ptid => $arTimestampData) {
                if (!$arTimestampData[$timestamp_from][0]['UF_NO_ARRIVALS']) {
                    foreach ($arTimestampData as $arDataDate) {

                        foreach ($arDataDate as $arData) {
                            $tmpdata[] = $arData;
                        }
                    }
                }
            }
        }

        $this->_data = $tmpdata;

        return $this;
    }

    /**
     * @param array $parameters
     * @return $this
     */
    public function filterByCalcForPlacesRates(array $parameters) {

        $arPricesGroupedByServices = $parameters['prices']->fetch(array('UF_SERVICE_ID', 'UF_DATE', 'UF_PTPR_ID'));

        $srvsId = array_keys($arPricesGroupedByServices);

        $arRatesGroupedByServices = array();
        foreach ($parameters['rates']->fetch() as $arRate) {
            if ($arRate['UF_FOR_PLACE']) {
                
                foreach ($srvsId as $id) {
                    if (in_array($id, $arRate['UF_SERVICES_ID'])) {
                        $arRatesGroupedByServices[$id][] = $arRate['ID'];
                    }
                }
            }
        }

        if (empty($arRatesGroupedByServices)) {
            return $this;
        }

        $arToDelete = array();
        $arPTRates = $parameters['priceTypesRates']->fetch(array('UF_RATE_CATEGORY_ID'));
        
        $arServicesGroupedById = $parameters['services']->fetch(array('ID'));
        
        foreach ($parameters['quotas']->fetch(array('UF_SERVICE_ID')) as $srvId => $arQuotas) {
            $mc = $arServicesGroupedById[$srvId][0]['UF_PLACES_MAIN'];
            if ($parameters['request']->adults + $parameters['request']->children < $mc) {
                $mc = $parameters['request']->adults + $parameters['request']->children;
            }
            foreach ($arQuotas as $arQuota) {
                if ($arQuota['ON_SALE'] < $mc) {
                    foreach ($arRatesGroupedByServices[$srvId] as $rateId) {
                        foreach ($arPTRates[$rateId] as $arPTRate) {
                            $arToDelete[$srvId][$rateId][$arQuota['UF_DATE']][] = $arPTRate['ID'];
                        }
                    }
                }
            }
        }
        
        if (empty($arToDelete)) {
            return $this;
        }

        $tmp_data = array();
        foreach ($arToDelete as $srvId => $arRates) {
            foreach ($arRates as $rateId => $arTimestampPrices) {
                foreach ($arTimestampPrices as $timestamp => $arPTRRates) {
                    foreach ($arPTRRates as $ptrid) {
                        unset($arPricesGroupedByServices[$srvId][$timestamp][$ptrid]);
                    }
                }
            }
        }
        
        foreach ($arPricesGroupedByServices as $srvId => $arTimestampPrices) {
            foreach ($arTimestampPrices as $arPTRRates) {
                foreach ($arPTRRates as $arPrices) {
                    foreach ($arPrices as $arPrice) {
                        $tmp_data[] = $arPrice;
                    }
                }
            }
        }
        
        $this->_data = $tmp_data;

        return $this;
    }
    
    /**
     * @param array $parameters
     */
    public function filterByNumberOnSaleForExcursions (array $parameters) {
 
        $tmp_data = array();
        
        $pricesGroupedByServicesAndTimestamp = $parameters['prices']->fetch(array("UF_SERVICE_ID", "UF_DATE"));
        
        foreach ($parameters['quotas']->fetch(array("UF_SERVICE_ID", "UF_DATE")) as $serviceId => $arTimestampData) {
            
            foreach ($arTimestampData as $timestamp => $arQuota) {
            
                if ($arQuota[0]['ON_SALE'] >= $parameters['request']->adults + $parameters['request']->children) {
                    foreach ($pricesGroupedByServicesAndTimestamp[$serviceId][$timestamp] as $arPrice) {
                        $tmp_data[] = $arPrice;
                    }
                }
            }
        }
        
        $this->_data = $tmp_data;
        
        return $this;
    }
    
    
    /**
     * @param array $parameters
     */
    public function filterByAvailableRates (array $parameters) {
 
        $tmp_data = array();
        
        $grbyptrid = $this->fetch(array("UF_PTPR_ID", "UF_DATE"));
        
        $qgrbyrates = $parameters["quotas"]->fetch(array("UF_RATE_ID", "UF_DATE"));
        
        $prridgrbyid = $parameters["priceTypesRates"]->fetch(array("ID"));
        
        foreach ($grbyptrid as $ptrid => $arr_grbydate) {
            
            if (isset($qgrbyrates[$prridgrbyid[$ptrid][0]["UF_RATE_CATEGORY_ID"]])) {
                
                foreach ($qgrbyrates[$prridgrbyid[$ptrid][0]["UF_RATE_CATEGORY_ID"]] as $date => $arr_qitem) {
                    
                    if (isset($arr_grbydate[$date])) {
                        foreach ($arr_grbydate[$date] as $arr_pitem) {
                            $tmp_data[] = $arr_pitem;
                        }
                        
                    }
                }
            }
            
        }
        
        uasort($tmp_data, function ($a, $b) {
                    return $a['UF_DATE'] >= $b['UF_DATE'];
                });
        
        $this->_data = $tmp_data;

        
        return $this;
    }
}
