<?php

namespace travelsoft\booking\datastores;
use travelsoft\booking\abstractions\Adapter1DataStore;

/**
 * Класс данных из таблицы тарифов
 *
 * @author dima
 */
class RatesDataStore extends Adapter1DataStore {

    protected static $store = "rates";
    
    public function filterByAge(array $age = null) {

        $tmpdata = null;
        if ($age) {
            foreach ($this->_data as $arData) {
                
                for ($i = 0, $cnt = count($age); $i < $cnt; $i++) {

                    $_age = $age[$i];
                    $IN = TRUE;

                    if (($arData["UF_AGE_CAT_1_MIN"] || $arData["UF_AGE_CAT_1_MAX"]) && !( $_age >= $arData["UF_AGE_CAT_1_MIN"] && $_age <= $arData["UF_AGE_CAT_1_MAX"])) {
                        $IN = FALSE;
                    }
                    if (($arData["UF_AGE_CAT_2_MIN"] && $arData["UF_AGE_CAT_2_MAX"]) && !( $_age >= $arData["UF_AGE_CAT_2_MIN"] && $_age <= $arData["UF_AGE_CAT_2_MAX"])) {
                        $IN = FALSE;
                    }
                    if (($arData["UF_AGE_CAT_3_MIN"] && $arData["UF_AGE_CAT_3_MAX"]) && !( $_age >= $arData["UF_AGE_CAT_3_MIN"] && $_age <= $arData["UF_AGE_CAT_3_MAX"])) {
                        $IN = FALSE;
                    }
                }

                if ($IN) {
                    $tmpdata[] = $arData;
                }
            }

            $this->_data = $tmpdata;
        }

        return $this;
    }

}
