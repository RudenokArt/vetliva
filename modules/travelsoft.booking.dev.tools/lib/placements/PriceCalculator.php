<?php

namespace travelsoft\booking\placements;

use travelsoft\booking\abstractions\commons\PriceCalculator as CommonPriceCalculator;
use travelsoft\booking\Utils as U;

/**
 * Класс расчёта цен по размещениям (отели)
 *
 * [PLACEMENT_ID]->[SERVICE_ID]->[RATE_ID]->[ [PRICE], [CURRENCY_ID] ]
 *
 * @author dimabresky
 */
class PriceCalculator extends CommonPriceCalculator {

    /**
     * @param \travelsoft\booking\placements\DataProvider $dataProvider
     */
    public function __construct(DataProvider $dataProvider) {
        parent::__construct($dataProvider);
    }

    /**
     * Рассчет цен по услуге
     * @param array $id
     * @return array
     */
    protected function pricesByServices(array $id) {

        $arResult = null;

        $arPrices = $this->_dataProvider->prices->fetch(array("UF_SERVICE_ID", "UF_PTPR_ID", "UF_DATE"));

        $arPTRates = $this->_dataProvider->priceTypesRates->fetch(array("ID"));

        $arRates = $this->_dataProvider->rates->fetch(array("ID"));

        $arCalcByArrival = U::filterServicesByParameter($id, "CALC_BY_ARRIVAL");

        $arCurrency = U::getCurrentCurrency();

        $people = $this->_dataProvider->request->children + $this->_dataProvider->request->adults;

        for ($i = 0, $cnt = count($id); $i < $cnt; $i++) {

            $arGroupedData = $arGroupedDiscountData= array();

            foreach ($arPrices[$id[$i]] as $PTRID => $arPTRateDataPrice) {

                $RID = $arPTRates[$PTRID][0]["UF_RATE_CATEGORY_ID"];

                if (empty($arRates[$RID])) {
                    continue;
                }
                $PTID = $arPTRates[$PTRID][0]["UF_RATE_ID"];

                if (!$arResult[$id[$i]][$RID]) {

                    $arResult[$id[$i]][$RID] = array("DURATION" => $this->_dataProvider->duration, "PRICE" => null, "CURRENCY_ID" => $arCurrency["id"]);
                }
                if (!$arGroupedData[$RID]) {

                    $arGroupedData[$RID] = array();
                }
                if (!$arGroupedDiscountData[$RID]) {

                    $arGroupedDiscountData[$RID] = array();
                }

                // ГРУППИРУЕМ ДАННЫЕ К ВИДУ ТАРИФ->ТИП ЦЕНЫ->ЦЕНА
                // ДЛЯ ДАЛЬНЕЙШЕГО РАСЧЕТА ЦЕНЫ ПО ТАРИФУ

                foreach ($arPTRateDataPrice as $dd => $arDateDataPrice) {

                    for ($j = 0, $cntt = count($arDateDataPrice); $j < $cntt; $j++) {

                        $gross = $arDateDataPrice[$j]["UF_GROSS"];

                        //новая цена если попадутся условия
                        $newGross = 0;

                        //меняем цену в зависимости от количества человек
                        if(!empty($arDateDataPrice[$j]["UF_EXTENDED_GROSS"])){
                            $extendedPriceArr = json_decode($arDateDataPrice[$j]["UF_EXTENDED_GROSS"], true); 
                                            
                            //сначала ищем цену, которая подходит по количеству человек под условие =                             
                            $key = array_search($people, array_column($extendedPriceArr['='], 'persons'));                             
                            if($key !== false){
                                $newGross = $extendedPriceArr['='][$key]['price'];
                            }
                            //если нет, то ещем подходящую под условие <
                            if(!$newGross && $people < $extendedPriceArr['<'][0]['persons']){
                                $newGross = $extendedPriceArr['<'][0]['price'];
                            }     

                            //если нет, то ищем наиболее подходящее под условие >
                            if(!$newGross){
                                foreach($extendedPriceArr['>'] as $keyGross => $gr){
                                    if($people > $gr['persons']){
                                        $newGross = $gr['price'];
                                        break;
                                    }
                                }
                            }                            

                            if($newGross){
                                $gross = $newGross;
                            }

                        }

                        $arGroupedData[$RID][$PTID][] = U::convertCurrency($gross, $arRates[$RID][0]["UF_CURRENCY_ID"], $arCurrency["id"], true);
                        if ($arDateDataPrice[$j]["UF_DISCOUNT_ABS"]) $absval =  U::convertCurrency($arDateDataPrice[$j]["UF_DISCOUNT_ABS"], $arRates[$RID][0]["UF_CURRENCY_ID"], $arCurrency["id"], true);
                        else $absval = 0;
                        $arGroupedDiscountData[$RID][$PTID][] = ['abs'=>$absval, 'percent'=>$arDateDataPrice[$j]["UF_DISCOUNT_PERCENT"]];
                    }
                }
            }

            if (!empty($arGroupedData)) {
                
                

                $arAllocate = $this->allocate($id[$i]);

                $arRatesFiltratedByAge = $this->_dataProvider->rates->filterByAge($this->_dataProvider->request->children_age)->fetch(array("ID"));

                $arPriceTypes = $this->_dataProvider->priceTypes->fetch(array('ID'));

                foreach ($arGroupedData as $rateId => $arPriceTypesData) {
                    $arPriceTypesDiscountData = $arGroupedDiscountData[$rateId];
                    if (isset($arPriceTypesData[self::MAIN_PTID_OLD_VERSION])) {
                        
                        // РАСЧЕТ ЦЕНЫ ТАРИФА ПО ТИПУ "ЦЕНА"
                        // ИСПОЛЬЗУЕТСЯ ДЛЯ ОБРАТНОЙ СОВМЕСТИМОСТИ СО СТАРОЙ ВЕРСИЕЙ МОДУЛЯ
                        if (
                                isset($arRatesFiltratedByAge[$rateId]) &&
                                // ПРОВЕРКА ПОДХОДИТ ЛИ ТАРИФ ПО КОЛИЧЕСТВУ ЛЮДЕЙ
                                $arRates[$rateId][0]['UF_ADULTS'] == $this->_dataProvider->request->adults &&
                                $arRates[$rateId][0]['UF_CHILDREN'] == $this->_dataProvider->request->children &&
                                $arRates[$rateId][0]['UF_PEOPLE'] == $this->_dataProvider->request->children + $this->_dataProvider->request->adults
                        ) {

                            $arResult[$id[$i]][$rateId]['PRICE'] = array_sum($arPriceTypesData[4]);
                            $summwithdiscount = 0;
                            foreach ($arPriceTypesData[4] as $key=>$val) {
                                if ($arPriceTypesDiscountData[4][$key]['abs']) 
                                    $summwithdiscount+=$val-$arPriceTypesDiscountData[4][$key]['abs'];
                                elseif ($arPriceTypesDiscountData[$key]['percent']) 
                                    $summwithdiscount+=$val*(100-$arPriceTypesDiscountData[4][$key]['percent'])/100;
                                else
                                    $summwithdiscount+=$val;
                            }
                            $arResult[$id[$i]][$rateId]['NEW_DISCOUNT_PRICE'] = $summwithdiscount;
                        } else {

                            unset($arResult[$id[$i]][$rateId]);
                        }
                    } else {

                        if (
                                $arRates[$rateId][0]['UF_FOR_PLACE'] &&
                                $arAllocate['main']['adults'] == 1 &&
                                $arAllocate['main']['children'] == 0 &&
                                $arAllocate['additional']['adults'] == 0 &&
                                $arAllocate['additional']['children'] == 0 &&
                                isset($arPriceTypesData[self::PRICE_FOR_ONE_ADULT_BY_ROOM])
                        ) {
                            // ИСКЛЮЧАЕМ ВСЕ ТИПЫ ЦЕН КРОМЕ ЦЕНЫ ЗА ЧЕЛОВЕКА ЗА НОМЕР
                            // ПРИ РАСЧЕТЕ ЦЕНЫ ЗА МЕСТО
                            $arPriceTypesData = array(self::PRICE_FOR_ONE_ADULT_BY_ROOM => $arPriceTypesData[self::PRICE_FOR_ONE_ADULT_BY_ROOM]);
                            $arPriceTypesDiscountData  = array(self::PRICE_FOR_ONE_ADULT_BY_ROOM => $arPriceTypesDiscountData[self::PRICE_FOR_ONE_ADULT_BY_ROOM]);
                            
                        }

                        $arLocalPriceResult = $arLocalPriceDiscountResult =  array();

                        foreach ($arPriceTypesData as $ptid => $arrPrices) {

                            $arPriceType = $arPriceTypes[$ptid][0];

                            if ($arPriceType['UF_MAIN'] && $arPriceType['UF_CALC_WIDGET']) {
                               // file_put_contents('/home/bitrix/www/local/modules/travelsoft.booking.dev.tools/lib/test123.txt', print_r($arPriceTypesData,1), FILE_APPEND);
                               // file_put_contents('/home/bitrix/www/local/modules/travelsoft.booking.dev.tools/lib/test122.txt', print_r($arPriceTypesDiscountData,1), FILE_APPEND);
                                $result = call_user_func_array(str_replace("\\\\", "\\", $arPriceType['UF_CALC_WIDGET']), array(array(
                                        'id' => $ptid,
                                        'prices' => $arPriceTypesData,
                                        'pricesdiscount' => $arPriceTypesDiscountData,
                                        'price_types' => $arPriceTypes,
                                        'sub_price_types' => $arPriceType['UF_SUB_PRICE_TYPES'],
                                        'allocate' => $arAllocate,
                                        'calc_by_arrival' => in_array($id[$i], $arCalcByArrival)
                                )));

                                if (
                                        $result['allocate']['main']['adults'] == 0 &&
                                        $result['allocate']['additional']['adults'] == 0 &&
                                        $result['allocate']['main']['children'] == 0 &&
                                        $result['allocate']['additional']['children'] == 0 &&
                                        $result['price'] > 0
                                ) {

                                    $arLocalPriceResult[] = $result['price'];
                                    $arLocalPriceDiscountResult[] = $result['pricesdiscount'];
                                }
                                //file_put_contents('/home/bitrix/www/local/modules/travelsoft.booking.dev.tools/lib/test12.txt', $arPriceType['UF_CALC_WIDGET'].'<br>', FILE_APPEND);
                                //file_put_contents('/home/bitrix/www/local/modules/travelsoft.booking.dev.tools/lib/test22.txt', $result['price'].'<br>', FILE_APPEND);
                                //file_put_contents('/home/bitrix/www/local/modules/travelsoft.booking.dev.tools/lib/test32.txt', $result['pricesdiscount'].'<br>', FILE_APPEND);                                                                                                
                            }
                        }

                        if (!empty($arLocalPriceResult)) {

                            $arResult[$id[$i]][$rateId]['PRICE'] = min($arLocalPriceResult);
                            $arResult[$id[$i]][$rateId]['NEW_DISCOUNT_PRICE'] = min($arLocalPriceDiscountResult);
                        } else {

                            unset($arResult[$id[$i]][$rateId]);
                        }
                    }
                }
            }

            if (empty($arResult[$id[$i]])) {
                unset($arResult[$id[$i]]);
            }
        }

        return self::applyDiscountNew($arResult, $arRates);
    }

    /**
     * Применениe скидок
     * @param array|null $arResult
     * @param array $arRates
     * @return array
     */
    public static function applyDiscountNew($arResult = [], array $arRates = []) { 
       foreach ($arResult as $servId => $arServData) {

            foreach ($arServData as $rid => $arRateData) {

                if ( round($arRateData["NEW_DISCOUNT_PRICE"],2)<round($arRateData['PRICE'],2) && $arRateData["NEW_DISCOUNT_PRICE"] > 0) {

                    $arResult[$servId][$rid]["DISCOUNT"] = $arRateData['PRICE'] - $arRateData["NEW_DISCOUNT_PRICE"];
                    $arResult[$servId][$rid]["DISCOUNT_PRICE"] = $arRateData["NEW_DISCOUNT_PRICE"];
                    
                }

                //добавляем к скидкам скидку, зависимую от количества суток
                if (isset($arRates[$rid]) && $arRates[$rid][0]["UF_DISCOUNT_BY_DAYS"]) {
                    $arrOfDiscounts = json_decode($arRates[$rid][0]["UF_DISCOUNT_BY_DAYS"],true);
                    $arrDays = array_column($arrOfDiscounts, 'days');
                    $arrDiscountvalues = array_column($arrOfDiscounts, 'discount');

                    $duration = $arResult[$servId][$rid]["DURATION"];

                    $arrDaysFiltered = array_filter($arrDays, function ($v) use ($duration) {
                        return $v <= $duration; 
                    });

                    //ищем ключ в массиве дней, чтобы по нему получить значение скидки
                    $keyOfDiscount = array_search(max($arrDaysFiltered), $arrDaysFiltered);                    
                    if($keyOfDiscount !== false){
                        //значение скидки
                        $discountValue = $arrDiscountvalues[$keyOfDiscount];
                    }
                   
                    if($discountValue){  
                        //значение скидки, зависимая от количества суток
                        $discountSumValue = $arRateData['PRICE'] * $discountValue / 100;
                        //прибавляем скидку от исходной цены к уже существующей
                        $arResult[$servId][$rid]["DISCOUNT"] += $discountSumValue;
                        
                        if($arResult[$servId][$rid]["DISCOUNT_PRICE"]){
                            $arResult[$servId][$rid]["DISCOUNT_PRICE"] -= $discountSumValue;
                        } else {
                            $arResult[$servId][$rid]["DISCOUNT_PRICE"] = $arRateData['PRICE'] - $discountSumValue;
                        }
                        
                    }
                }

            }
        }
        return $arResult;
    }
    
    public static function applyDiscount($arResult = [], array $arRates = []) {
                            
        if (!empty($arResult) && is_array($arResult)) {

            if (empty($arRates)) {

                $rates_ids = [];

                // собираем id тарифов для 
                foreach ($arResult as $servId => $arServData) {
                    $rates_ids = \array_merge($rates_ids, \array_values(\array_keys($arServData)));
                }

                $arRates = (new \travelsoft\booking\datastores\RatesDataStore([
                            "filter" => ["ID" => \array_unique($rates_ids), "!UF_DISCOUNT" => false],
                            "select" => ["ID", "UF_DISCOUNT"]
                        ]))->fetch(["ID"]);
            }

            if (!empty($arRates)) {

                foreach ($arResult as $servId => $arServData) {

                    foreach ($arServData as $rid => $arRateData) {

                        if (isset($arRates[$rid]) && $arRates[$rid][0]["UF_DISCOUNT"] > 0) {

                            $arResult[$servId][$rid]["DISCOUNT"] = $arRateData['PRICE'] * $arRates[$rid][0]["UF_DISCOUNT"] / 100;
                            $arResult[$servId][$rid]["DISCOUNT_PRICE"] = $arRateData['PRICE'] - $arResult[$servId][$rid]["DISCOUNT"];
                            if ($arResult[$servId][$rid]["DISCOUNT_PRICE"] <= 0) {
                                unset($arResult[$servId][$rid]); // если скидка >= 100%, то удаляем из поиска.
                            }
                        }
                    }
                }
            }
        }

        return $arResult;
    }

    public function minPrice(array $id = null) {

        $arResult = null;

        $arCalcPrices = $this->calculate($id);
        $cnt = 0;
        foreach ($arCalcPrices as $placementID => $arServices) {
            
            $arPrices = NULL;
            $min_price = exp(100);
            foreach ($arServices as $arRates) {
                foreach ($arRates as $arPriceData) {
                    if (isset($arPriceData["DISCOUNT_PRICE"]) && $arPriceData["DISCOUNT_PRICE"] > 0) {

                        if ($min_price >= $arPriceData["DISCOUNT_PRICE"]) {
                            $arPrices = [
                                "DISCOUNT_PRICE" => $arPriceData["DISCOUNT_PRICE"],
                                "PRICE" => $arPriceData["PRICE"],
                                "DISCOUNT" => $arPriceData["DISCOUNT"],
                                "DURATION" => $arPriceData["DURATION"]
                            ];
                            $min_price = $arPriceData["DISCOUNT_PRICE"];
                        }
                    } else {
                        if ($min_price >= $arPriceData["PRICE"]) {
                            $arPrices = [
                                "PRICE" => $arPriceData["PRICE"],
                                "DURATION" => $arPriceData["DURATION"]
                            ];
                            $min_price = $arPriceData["PRICE"];
                        }
                    }
                }
            }
            
            if ($arPrices) {$cnt++;
                $arCurrency = U::getCurrentCurrency();
                $arResult[$placementID] = array("PRICE" => $arPrices["PRICE"], "CURRENCY_ID" => $arCurrency["id"], "DURATION" => $arPrices["DURATION"]);
                if (isset($arPrices["DISCOUNT_PRICE"]) && $arPrices["DISCOUNT_PRICE"] > 0) {
                    $arResult[$placementID]["DISCOUNT_PRICE"] = $arPrices["DISCOUNT_PRICE"];
                    $arResult[$placementID]["DISCOUNT"] = $arPrices["DISCOUNT"];
                }
            }
        }
        
        return $arResult;
    }

}
