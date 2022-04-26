<?php

namespace travelsoft\booking\excursionstours;

use travelsoft\booking\abstractions\commons\PriceCalculator as CommonPriceCalculator;
use travelsoft\booking\Utils as U;

/**
 * Класс расчёта цен по экскурсиям
 *
 * [EXCURSION_ID]->[SERVICE_ID]->[UNIX_DATE]->[RATE_ID]->[ [DURATION], [PRICE], [CURRENCY_ID] ]
 * 
 * @author dimabresky
 */
class PriceCalculator extends CommonPriceCalculator {

    /**
     * @param \travelsoft\booking\excursions\DataProvider $dataProvider
     */
    public function __construct(DataProvider $dataProvider) {
        parent::__construct($dataProvider);
    }

    protected function pricesByServices(array $id) {

        $arResult = null;
        $arPrices = $this->_dataProvider->prices->fetch(array("UF_SERVICE_ID", "UF_PTPR_ID", "UF_DATE"));

        $arPTRates = $this->_dataProvider->priceTypesRates->fetch(array("ID"));

        $arRates = $this->_dataProvider->rates->fetch(array("ID"));

        $arCurrency = U::getCurrentCurrency();

        for ($i = 0, $cnt = count($id); $i < $cnt; $i++) {

            $arGroupedData = $arGroupedDiscountData = array();

            // отмечаем на какие даты есть цены в выбранной валюте
            $isFindNowCurrency = [];
            foreach ($arPrices[$id[$i]] as $PTRID => $arPTRateDataPrice) {
                $RID = $arPTRates[$PTRID][0]["UF_RATE_CATEGORY_ID"];
                foreach ($arPTRateDataPrice as $unxdate => $arDateDataPrice) {
                    if (!$isFindNowCurrency[$unxdate] && $arCurrency["id"] ==  $arRates[$RID][0]["UF_CURRENCY_ID"]) {
                        $isFindNowCurrency[$unxdate] = true;
                    }
                }
            }

            foreach ($arPrices[$id[$i]] as $PTRID => $arPTRateDataPrice) {

                $RID = $arPTRates[$PTRID][0]["UF_RATE_CATEGORY_ID"];
                if (empty($arRates[$RID])) {
                    continue;
                }
                $PTID = $arPTRates[$PTRID][0]["UF_RATE_ID"];

                foreach ($arPTRateDataPrice as $unxdate => $arDateDataPrice) {

                    if (!$arResult[$id[$i]][$unxdate][$RID]) {
                        $arResult[$id[$i]][$unxdate][$RID] = array("DURATION" => $arDateDataPrice[0]["UF_LIFE_PERIOD"][0] > 0 ? $arDateDataPrice[0]["UF_LIFE_PERIOD"][0] : 1, "PRICE" => NULL, "CURRENCY_ID" => $arCurrency["id"]);
                    }

                    for ($j = 0, $cntt = count($arDateDataPrice); $j < $cntt; $j++) {

                        // Если на дату есть цены в выбранной валюте, не выводим прочие валюты
                        // Если на дату нет цен в выбранной валюте, ковертируем цены BYN в выбранную валюту
                        if ($arRates[$RID][0]["UF_CURRENCY_ID"] != $arCurrency["id"]
                            && ($isFindNowCurrency[$unxdate] == true || ($isFindNowCurrency[$unxdate] == false && $arRates[$RID][0]["UF_CURRENCY_ID"] != 1))) {
                            continue;
                        }

                        $arGroupedData[$unxdate][$RID][$PTID][] = U::convertCurrency($arDateDataPrice[$j]["UF_GROSS"], $arRates[$RID][0]["UF_CURRENCY_ID"], $arCurrency["id"], true);
                        if ($arDateDataPrice[$j]["UF_DISCOUNT_ABS"]) $absval =  U::convertCurrency($arDateDataPrice[$j]["UF_DISCOUNT_ABS"], $arRates[$RID][0]["UF_CURRENCY_ID"], $arCurrency["id"], true);
                        else $absval = 0;
                        $arGroupedDiscountData[$unxdate][$RID][$PTID][] = ['abs'=>$absval, 'percent'=>$arDateDataPrice[$j]["UF_DISCOUNT_PERCENT"]];
                    }
                }
            }

            if (!empty($arGroupedData)) {

                $arRatesFiltratedByAge = $this->_dataProvider->rates->filterByAge($this->_dataProvider->request->children_age)->fetch(array("ID"));

                $arPriceTypes = $this->_dataProvider->priceTypes->fetch(array('ID'));

                foreach ($arGroupedData as $unixdate => $arSubGroupedData) {

                    foreach ($arSubGroupedData as $rateId => $arPriceTypesData) {
                        $arPriceTypesDiscountData = $arGroupedDiscountData[$unixdate][$rateId];
                        if (isset($arPriceTypesData[self::MAIN_PTID_OLD_VERSION])) {

                            // РАСЧЕТ ЦЕНЫ ТАРИФА ПО ТИПУ "ЦЕНА"
                            // ИСПОЛЬЗУЕТСЯ ДЛЯ ОБРАТНОЙ СОВМЕСТИМОСТИ СО СТАРОЙ ВЕРСИЕЙ МОДУЛЯ
                            if (
                                    isset($arRatesFiltratedByAge[$rateId]) &&
                                    $arRates[$rateId][0]['UF_ADULTS'] == $this->_dataProvider->request->adults &&
                                    $arRates[$rateId][0]['UF_CHILDREN'] == $this->_dataProvider->request->children &&
                                    $arRates[$rateId][0]['UF_PEOPLE'] == $this->_dataProvider->request->children + $this->_dataProvider->request->adults
                            ) {

                                $arResult[$id[$i]][$unixdate][$rateId]['PRICE'] = array_sum($arPriceTypesData[4]);
                                $summwithdiscount = 0;
                                foreach ($arPriceTypesData[4] as $key=>$val) {
                                    if ($arPriceTypesDiscountData[4][$key]['abs']) 
                                        $summwithdiscount+=$val-$arPriceTypesDiscountData[4][$key]['abs'];
                                    elseif ($arPriceTypesDiscountData[$key]['percent']) 
                                        $summwithdiscount+=$val*(100-$arPriceTypesDiscountData[4][$key]['percent'])/100;
                                    else
                                        $summwithdiscount+=$val;
                                }
                                $arResult[$id[$i]][$unixdate][$rateId]['NEW_DISCOUNT_PRICE'] = $summwithdiscount;
                            } else {

                                unset($arResult[$id[$i]][$unixdate][$rateId]);
                            }
                        } else {

                            $arRates[$rateId][0]['UF_MAIN_PLACES'] = (int) $arRates[$rateId][0]['UF_MAIN_PLACES'];
                            $arRates[$rateId][0]['UF_ADD_PLACES'] = (int) $arRates[$rateId][0]['UF_ADD_PLACES'];

                            $arAllocate = $this->allocate($arRates[$rateId][0]['UF_MAIN_PLACES'], $arRates[$rateId][0]['UF_ADD_PLACES']);

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
                            }
                            
                            foreach ($arPriceTypesData as $ptid => $arrPrices) {

                                $arPriceType = $arPriceTypes[$ptid][0];

                                if ($arPriceType['UF_MAIN'] && $arPriceType['UF_CALC_WIDGET']) {
                                    $result = call_user_func_array(str_replace("\\\\", "\\", $arPriceType['UF_CALC_WIDGET']), array(array(
                                            'id' => $ptid,
                                            'prices' => $arPriceTypesData,
                                            'pricesdiscount' => $arPriceTypesDiscountData,
                                            'price_types' => $arPriceTypes,
                                            'sub_price_types' => $arPriceType['UF_SUB_PRICE_TYPES'],
                                            'allocate' => $arAllocate
                                    )));

                                    if (
                                            $result['allocate']['main']['adults'] == 0 &&
                                            $result['allocate']['additional']['adults'] == 0 &&
                                            $result['allocate']['main']['children'] == 0 &&
                                            $result['allocate']['additional']['children'] == 0 &&
                                            $result['allocate']['without_place']['children'] == 0 &&
                                            $result['price'] > 0
                                    ) {

                                        $arResult[$id[$i]][$unixdate][$rateId]['PRICE'] = $result['price'];
                                        $arResult[$id[$i]][$unixdate][$rateId]['NEW_DISCOUNT_PRICE'] = $result['pricesdiscount'];
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        return $this->applyDiscountNew($this->filterEmpty($arResult), $arRates);
    }

    /**
     * Фильтрация пустых результатов расчета
     * @param array $arResult
     * @return array|null
     */
    public static function filterEmpty($arResult = []) {

        foreach ($arResult as $servId => $arServData) {
            foreach ($arServData as $unixdate => $arUnixData) {
                foreach ($arUnixData as $rid => $arRateData) {
                    if ($arRateData['PRICE'] <= 0) {
                        unset($arResult[$servId][$unixdate][$rid]);
                    }
                }
                if (empty($arResult[$servId][$unixdate])) {
                    unset($arResult[$servId][$unixdate]);
                }
            }
            if (empty($arResult[$servId])) {
                unset($arResult[$servId]);
            }
        }

        return $arResult;
    }

    /**
     * Применениe скидок
     * @param array $arResult
     * @param array $arRates
     * @return array|null
     */
    public static function applyDiscountNew($arResult = [], array $arRates = []) {
       foreach ($arResult as $servId => $arServData) {
            foreach ($arServData as $unixdate => $arUnixData) {
                foreach ($arUnixData as $rid => $arRateData) {
    
                    if ( $arRateData["NEW_DISCOUNT_PRICE"]<$arRateData['PRICE'] && $arRateData["NEW_DISCOUNT_PRICE"] > 0) {
    
                        $arResult[$servId][$unixdate][$rid]["DISCOUNT"] = $arRateData['PRICE'] - $arRateData["NEW_DISCOUNT_PRICE"];
                        $arResult[$servId][$unixdate][$rid]["DISCOUNT_PRICE"] = $arRateData["NEW_DISCOUNT_PRICE"];
                        
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
                    foreach ($arServData as $unixdate => $arUnixData) {
                        $rates_ids = \array_merge($rates_ids, \array_values(\array_keys($arUnixData)));
                    }
                }

                $arRates = (new \travelsoft\booking\datastores\RatesDataStore([
                            "filter" => ["ID" => \array_unique($rates_ids), "!UF_DISCOUNT" => false],
                            "select" => ["ID", "UF_DISCOUNT"]
                                ]))->fetch(["ID"]);
            }

            if (!empty($arRates)) {

                foreach ($arResult as $servId => $arServData) {
                    foreach ($arServData as $unixdate => $arUnixData) {

                        foreach ($arUnixData as $rid => $arRateData) {

                            if (isset($arRates[$rid]) && $arRates[$rid][0]["UF_DISCOUNT"] > 0) {

                                $arResult[$servId][$unixdate][$rid]["DISCOUNT"] = $arRateData['PRICE'] * $arRates[$rid][0]["UF_DISCOUNT"] / 100;
                                $arResult[$servId][$unixdate][$rid]["DISCOUNT_PRICE"] = $arRateData['PRICE'] - $arResult[$servId][$unixdate][$rid]["DISCOUNT"];
                                if ($arResult[$servId][$unixdate][$rid]["DISCOUNT_PRICE"] <= 0) {
                                    unset($arResult[$servId][$unixdate][$rid]); // если скидка >= 100%, то удаляем из поиска.
                                }
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

        foreach ($arCalcPrices as $excursionID => $arServices) {
            $arPrices = null;
            $min_price = exp(20);
            foreach ($arServices as $arDatePriceData) {
                foreach ($arDatePriceData as $arRatePriceData) {
                    foreach ($arRatePriceData as $arPriceData) {

                        if (isset($arPriceData["DISCOUNT_PRICE"]) && $arPriceData["DISCOUNT_PRICE"] > 0) {

                            if ($min_price >= $arPriceData["DISCOUNT_PRICE"]) {
                                $arPrices = [
                                    "DISCOUNT_PRICE" => $arPriceData["DISCOUNT_PRICE"],
                                    "PRICE" => $arPriceData["PRICE"],
                                    "DISCOUNT" => $arPriceData["DISCOUNT"],
                                ];
                                $min_price = $arPriceData["DISCOUNT_PRICE"];
                            }
                        } else {
                            if ($min_price >= $arPriceData["PRICE"]) {

                                $arPrices = [
                                    "PRICE" => $arPriceData["PRICE"]
                                ];
                                $min_price = $arPriceData["PRICE"];
                            }
                        }
                    }
                }
            }
            if ($arPrices) {

                $arCurrency = U::getCurrentCurrency();
                $arResult[$excursionID] = array("PRICE" => $arPrices["PRICE"], "CURRENCY_ID" => $arCurrency["id"]);
                if (isset($arPrices["DISCOUNT_PRICE"]) && $arPrices["DISCOUNT_PRICE"] > 0) {
                    $arResult[$excursionID]["DISCOUNT_PRICE"] = $arPrices["DISCOUNT_PRICE"];
                    $arResult[$excursionID]["DISCOUNT"] = $arPrices["DISCOUNT"];
                }
            }
        }

        return $arResult;
    }

    public function allocate(int $main, int $add): array {

        $arAllocate = array(
            'main' => array(
                'adults' => 0,
                'children' => 0,
                'children_age' => array()
            ),
            'additional' => array(
                'adults' => 0,
                'children' => 0,
                'children_age' => array()
            ),
            "without_place" => array(
                "adults" => 0,
                "children" => 0,
                "children_age" => array()
            )
        );

        $adults = $this->_dataProvider->request->adults;
        $children = $this->_dataProvider->request->children;
        $arAge = $this->_dataProvider->request->children_age;

        rsort($arAge);
        $deltaMainPlaces = $main - $adults;
        if ($deltaMainPlaces >= 0) {

            $arAllocate['main']['adults'] = $adults;

            if ($children > 0) {

                if ($deltaMainPlaces == 0) {

                    $additionalChildren = $add - $children;

                    if ($additionalChildren >= 0) {
                        $arAllocate['additional']['children'] = $children;
                        $arAllocate['additional']['children_age'] = $arAge;
                    } else {

                        $arAllocate['additional']['children'] = $add;
                        for ($i = 0; $i < $add; $i++) {
                            $arAllocate['additional']['children_age'][] = $arAge[$i];
                            unset($arAge[$i]);
                        }
                        $arAllocate['without_place']['children'] = $children - $add;
                        $arAllocate['without_place']['children_age'] = array_values($arAge);
                    }
                } else {

                    $deltaAdditPlaces = $deltaMainPlaces - $children;

                    if ($deltaAdditPlaces >= 0) {

                        $arAllocate['main']['children'] = $children;
                        $arAllocate['main']['children_age'] = $arAge;
                    } else {

                        $delta = $children + $deltaAdditPlaces;
                        $arAllocate['main']['children'] = $delta;
                        for ($i = 0; $i < $delta; $i++) {
                            $arAllocate['main']['children_age'][] = $arAge[$i];
                            unset($arAge[$i]);
                        }

                        $deltaAddit = $add - abs($deltaAdditPlaces);
                        if ($deltaAddit >= 0) {
                            $arAllocate['additional']['children'] = abs($deltaAdditPlaces);
                            $arAllocate['additional']['children_age'] = array_values($arAge);
                        } else {
                            $delta = $children + $deltaAddit;
                            $arAllocate['additional']['children'] = $delta;
                            for ($i = 0; $i < $delta; $i++) {
                                $arAllocate['additional']['children_age'][] = $arAge[$i];
                                unset($arAge[$i]);
                            }

                            $arAllocate['without_place']['children'] = abs($deltaAddit);
                            $arAllocate['without_place']['children_age'] = array_values($arAge);
                        }
                    }
                }
            }
        } else {

            $arAllocate['main']['adults'] = $main;

            $deltaAdditPlaces = $add + $deltaMainPlaces;

            if ($deltaAdditPlaces >= 0) {

                $arAllocate['additional']['adults'] = abs($deltaMainPlaces);

                if ($children > 0) {

                    if ($deltaAdditPlaces == 0) {

                        $arAllocate['without_place']['children'] = $children;
                        $arAllocate['without_place']['children_age'] = array_values($arAge);
                    } else {

                        $deltaAddit = $deltaAdditPlaces > $children ? $children : $deltaAdditPlaces;
                        $arAllocate['additional']['children'] = $deltaAddit;
                        for ($i = 0; $i < $deltaAddit; $i++) {
                            $arAllocate['additional']['children_age'][] = $arAge[$i];
                            unset($arAge[$i]);
                        }
                        if ($children > $deltaAdditPlaces) {
                            $arAllocate['without_place']['children'] = $children - $deltaAdditPlaces;
                            $arAllocate['without_place']['children_age'] = array_values($arAge);
                        }
                    }
                }
            }
        }

        return $arAllocate;
    }

}
