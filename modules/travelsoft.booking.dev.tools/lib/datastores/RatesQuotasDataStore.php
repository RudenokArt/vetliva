<?php

namespace travelsoft\booking\datastores;

use travelsoft\booking\abstractions\Adapter1DataStore;

/**
 * Класс данных из таблицы квот
 *
 * @author dimabresky
 */
class RatesQuotasDataStore extends Adapter1DataStore {

    /**
     * @var string
     */
    protected static $store = "ratesQuotas";

    /**
     * Фильтрует по доступным к продаже
     * @return $this
     */
    public function filterAvailableForSale($releasePeriodAllDays = false) {
        $_tmp_data = null;

        $TSFrom = $this->query['filter']['><UF_DATE'][0];
        foreach ($this->_data as $arData) {

            // проверка Релиз периода
            $checkReleasePeriod = true;
            if (!empty($arData["UF_RELEASE_PERIOD"]) && ($releasePeriodAllDays == true || $TSFrom == $arData['UF_DATE'])) {
                $releasePeriod = $arData["UF_RELEASE_PERIOD"];
                $TSReleasePeriod = strtotime("-$releasePeriod day", $arData['UF_DATE']);
                $TSDateNow = strtotime(date('Y-m-d'));
                if ($TSDateNow >= $TSReleasePeriod) {
                    $checkReleasePeriod = false;
                }
            }

            $onSale = $arData["UF_QUOTE"] - $arData["UF_SOLD_NUMBER"];
            if ($onSale > 0 && $checkReleasePeriod) {
                $arData["ON_SALE"] = $onSale;
                $_tmp_data[] = $arData;
            }
        }

        $this->_data = $_tmp_data;

        return $this;
    }
    
    /**
     * Фильтрует по доступным тарифам к продаже
     * @return $this
     */
    public function filterByAvailableRates() {
        $_tmp_data = null;
        
        $arr_data = $this->fetch(array("UF_RATE_ID"));
        
        foreach ($arr_data as $rate_id => $gbr) {
            foreach ($gbr as $arr_item) {
                if (!$arr_item["UF_STOP"]) {
                    
                    $arr_item["date"] = date("d.m.Y", $arr_item["UF_DATE"]);
                    $_tmp_data[] = $arr_item;
                }
            }
            
        }

        $this->_data = $_tmp_data;
         
        return $this;
    }
    

    /**
     * Возвращает (сгруппированный по ID услуг) массив
     * минимального количество услуг достурного для бронирования
     * @return array
     */
    public function getServiceCountOnSale() {
        # group count by services
        $arGroupCountByServices = null;
        foreach ($this->_data as $arData) {
            $arGroupCountByServices[$arData["UF_SERVICE_ID"]][] = $arData["ON_SALE"];
        }

        return array_map(function ($arVals) {

            return min($arVals);
        }, $arGroupCountByServices);
    }

    /**
     * @return $this
     */
    public function filterByAutostopsale() {

        $_tmp_data = null;

        $services_list_id = array_keys($this->fetch(array("UF_SERVICE_ID")));

        $arr_autostopsales_grouped_by_services = (new Autostopsale(array(
            "filter" => array("UF_SERVICE_ID" => $services_list_id),
                )))->fetch(array("UF_SERVICE_ID"));

        $arr_data = $this->fetch(array("UF_RATE_ID", "UF_SERVICE_ID"));

        foreach ($arr_data as $rate_id => $_arr_data1) {

            foreach ($_arr_data1 as $service_id => $_arr_data2) {

                foreach ($_arr_data2 as $_arr_data3) {
                    if (isset($arr_autostopsales_grouped_by_services[$_arr_data3["UF_SERVICE_ID"]])) {

                        $arr_autostopsale = $arr_autostopsales_grouped_by_services[$_arr_data3["UF_SERVICE_ID"]][0];

                        if (($arr_autostopsale["UF_HOURS"] * 3600) < ($_arr_data3["UF_DATE"] - time())) {

                            $_tmp_data[] = $_arr_data3;
                        }
                    } else {
                        $_tmp_data[] = $_arr_data3;
                    }
                }
            }
        }

        $this->_data = $_tmp_data;

        return $this;
    }

}
