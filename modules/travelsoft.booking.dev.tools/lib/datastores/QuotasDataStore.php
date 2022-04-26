<?php

namespace travelsoft\booking\datastores;
use travelsoft\booking\abstractions\Adapter1DataStore;

/**
 * Класс данных из таблицы квот
 *
 * @author dimabresky
 */
class QuotasDataStore extends Adapter1DataStore {
    
    /**
     * @var string
     */
    protected static $store = "quotas";
    
    /**
     * Фильтрует по доступным к продаже
     * @var bool $releasePeriodAllDays релиз период применяется: на все дни/на первый день; true/false
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
        
        foreach ($this->_data as $arData) {

            if (isset($arr_autostopsales_grouped_by_services[$arData["UF_SERVICE_ID"]])) {

                $arr_autostopsale = $arr_autostopsales_grouped_by_services[$arData["UF_SERVICE_ID"]][0];

                    if (($arr_autostopsale["UF_HOURS"] * 3600) < ($arData["UF_DATE"] - time())) {
                    
                        $_tmp_data[] = $arData;
                    }
                
            } else {
                $_tmp_data[] = $arData;
            }
        }

        $this->_data = $_tmp_data;

        return $this;
    }
}
