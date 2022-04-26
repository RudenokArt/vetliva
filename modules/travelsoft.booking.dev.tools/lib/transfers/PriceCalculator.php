<?php

namespace travelsoft\booking\transfers;
use travelsoft\booking\abstractions\PriceCalculator as AbstractPriceCalculator;
use travelsoft\booking\Utils as U;

/**
 * Класс расчёта цен по трансферам
 *
 * @author dima
 */
class PriceCalculator extends AbstractPriceCalculator {
    
    /**
     * Производит расчёт цен
     * @return array|null
     * [CLASS_AUTO_ID]->[SERVICE_ID]->[ [RATE_ID], [PRICE], [CURRENCY_ID] ]
     */
    public function calculate(array $id = null) {

        $arResult = null;
        if ($this->_dataProvider->prices && !empty($this->_dataProvider->prices->fetch())) {
            $arPricesData = $this->_dataProvider->prices->fetch(array("UF_SERVICE_ID", "UF_PTPR_ID", "UF_DATE"));

            $arTransfersData = $this->_dataProvider->transfers->fetch(array("ID"));

            $arServicesTransfersData = $this->_dataProvider->services->fetch(array("ID"));

            $arRatesData = $this->_dataProvider->transfersRates->fetch(array("ID"));
            
            $arPTRatesData = $this->_dataProvider->priceTypesRates->fetch(array("ID"));
            
            $arCurrency = U::getCurrentCurrency();
            
            $tmpRes = null;
            
            foreach ($arPricesData as $SID => $arServicePriceData) {
                $TID = $arServicesTransfersData[$SID][0]["UF_IBLOCK_ELEMENT_ID"];
                if ($arTransfersData[$TID][0]) {
                    $factor = $this->_dataProvider->transfer->distance / 1000;
                    if ($arTransfersData[$TID][0]["UF_POINT_A"] && $arTransfersData[$TID][0]["UF_POINT_B"]) {
                        $factor = 1;
                    }
                    foreach ($arServicePriceData as $PTRID => $arPTRatesPriceData) {
                        $RID = $arPTRatesData[$PTRID][0]["UF_RATE_CATEGORY_ID"];
                        foreach ($arPTRatesPriceData as $arDateData) {
                            if ($arDateData[0]["UF_GROSS"] <= 0.0001) {
                                if (isset($tmpRes[$RID])) {
                                    unset($tmpRes[$RID]);
                                }
                                break;
                            } else {
                                $tmpRes[$RID][$SID] += U::convertCurrency($arDateData[0]["UF_GROSS"], $arRatesData[$RID][0]["UF_CURRENCY_ID"], $arCurrency["id"], true) * $factor;
                            }
                        }
                    }
                }
            }
            
            if ($tmpRes) {
                foreach ($tmpRes as $RID => $arServicesPriceData) {
                    $min = exp(100);
                    $CAID = $arRatesData[$RID][0]["UF_CLASS_AUTO"];
                    $USER_ID = $arRatesData[$RID][0]["UF_USER_ID"];
                    foreach ($arServicesPriceData as $SID => $price) {
                        if ($min >= $price) {
                            $min = $price;
                            $CSID = $SID;
                            $CPRICE = $min;
                        }
                    }
                    $arResult[$CAID][$CSID] = array("USER_ID" => $USER_ID, "RATE_ID" => $RID, "PRICE" => $CPRICE, "CURRENCY_ID" => $arCurrency["id"]);
                }
            }
        }
        return $arResult;
    }

    public function minPrice() {/* TODO */}
}
