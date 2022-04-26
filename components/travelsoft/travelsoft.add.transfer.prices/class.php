<?php

Bitrix\Main\Loader::includeModule("travelsoft.booking.dev.tools");

use travelsoft\booking\Utils as U;

class TravelsoftAddEditTransfersPrices extends CBitrixComponent {

    /**
     * @var null 
     */
    protected $_procres = null;
    
    protected $_user_id = null;
    
    public function checks() {
        U::incHL();
        U::incCurrency();
        U::isProvider();
        
        $this->_user_id = $this->arParams['SUPER_USER_EDIT'] === 'Y' && $this->arParams['PROVIDER_ID'] > 0 ? $this->arParams['PROVIDER_ID'] :  $GLOBALS['USER']->GetID();
    }

    public function executeComponent() {
        try {

            $this->checks();

            $sdc = U::dc("service_hl_id");

            $dbServices = $sdc::getList(array("filter" => array("UF_USER_ID" => $this->_user_id, "UF_SERVICE_TYPE_NAME" => $this->arParams["SERVICE_TYPE"])));

            while ($arService = $dbServices->fetch()) {
                $this->arResult["SERVICES"][$arService["ID"]] = $arService;
            }

            $this->arResult["CURRENCY"] = \travelsoft\Currency::getInstance()->get("currency");

            $_SESSION["__TRAVELSOFT"]["CP"]["TAP"] = NULL;
            if ($this->arResult["SERVICES"] && isset($this->arResult["SERVICES"][$this->arParams["ROW_ID"]])) {
                // массив дат
                $this->arResult['dateArray'] = U::getDateArray();
                $this->arResult['dateArray']['dateRangeSettings'] = U::getDateRangeSettingsArray();

                $cadc = U::dc("class_auto_hl_id");

                $dbAutoClasses = $cadc::getList();

                //классы авто
                while ($arAutoClasses = $dbAutoClasses->fetch()) {
                    $this->arResult["AUTO_CLASSES"][$arAutoClasses["ID"]] = $arAutoClasses;
                }

                if ($this->arResult["AUTO_CLASSES"]) {

                    $trdc = U::dc("transfer_rates_hl_id");

                    $dbRates = $trdc::getList(array(
                                "filter" => array(
                                    "UF_CLASS_AUTO" => array_keys($this->arResult["AUTO_CLASSES"]),
                                    "UF_USER_ID" => $this->_user_id,
                                    "UF_TRANSFER" => $this->arParams["ROW_ID"]
                                )
                                    )
                    );

                    // тариф
                    while ($arRate = $dbRates->fetch()) {
                        $this->arResult["RATES"][$arRate["ID"]] = $arRate;
                        $this->arResult["RATES_LINK_CLASS_AUTO"][$arRate["UF_CLASS_AUTO"]] = $arRate;
                    }

                    $ptdc = U::dc("price_type_hl_id");

                    $dbPriceTypes = $ptdc::getList(array("filter" => array("UF_ACTIVE" => true, "ID" => $this->arParams['MAIN_PRICE_TYPE'])));

                    while ($arPriceTypes = $dbPriceTypes->fetch()) {
                        $this->arResult["PRICE_TYPES"][$arPriceTypes["ID"]] = $arPriceTypes;
                    }

                    if ($this->arResult["RATES"]) {

                        $ptrdc = U::dc("ptr_hl_id");

                        $dbPTRates = $ptrdc::getList(array("filter" => array("UF_RATE_CATEGORY_ID" => array_keys($this->arResult["RATES"]))));

                        // тариф + тип цены
                        while ($arPTRate = $dbPTRates->fetch()) {
                            $this->arResult["PTRATES"][$arPTRate["ID"]] = $arPTRate;
                            $ptrates[] = $arPTRate["ID"];
                        }

                        if ($ptrates) {

                            $pdc = U::dc("price_hl_id");

                            $dbPrices = $pdc::getList(array("filter" => array(
                                            "UF_DATE" => $this->arResult['dateArray']['unixDaysArray'],
                                            "UF_SERVICE_ID" => $this->arParams['ROW_ID'],
                                            "UF_PTPR_ID" => $ptrates
                            )));

                            // цены
                            while ($arPrices = $dbPrices->fetch()) {
                                $this->arResult["PRICES"][$this->arResult["RATES"][$this->arResult["PTRATES"][$arPrices["UF_PTPR_ID"]]["UF_RATE_CATEGORY_ID"]]["UF_CLASS_AUTO"]][$this->arResult["PTRATES"][$arPrices["UF_PTPR_ID"]]["UF_RATE_ID"]][$arPrices["UF_DATE"]] = $arPrices;
                            }
                        }
                    }
                    # SAVE FOR AJAX
                    $_SESSION["__TRAVELSOFT"]["CP"]["TAP"]["ROW_ID"] = $this->arParams["ROW_ID"];
                    $_SESSION["__TRAVELSOFT"]["CP"]["TAP"]["ST"] = $this->arParams["SERVICE_TYPE"];
                    if ($this->arParams['SUPER_USER_EDIT'] === 'Y' && $this->arParams['PROVIDER_ID'] > 0) {
                        $_SESSION["__TRAVELSOFT"]["CP"]["TAP"]["PROVIDER_ID"] = $this->arParams["PROVIDER_ID"];
                    }
                }
            }

            $this->IncludeComponentTemplate();
        } catch (\Exception $ex) {
            ShowError($ex->getMessage());
        }
    }

    public function processingRequest() {

        if (check_bitrix_sessid() && $_SERVER["REQUEST_METHOD"] == "POST") {

            if ($_POST["MAINFORM"]) {
                $this->_processingMainForm($_POST["MAINFORM"]);
            } elseif ($_POST["MASSEDIT"]) {
                
                $dateRangeSetting = U::getDateRangeSettingsArray();
                $arDate = explode($dateRangeSetting['separator'], $_POST["MASSEDIT"]['dateRange']);
                $unixStartDate = MakeTimeStamp($arDate[0], $dateRangeSetting['format']);
                $unixEndDate = MakeTimeStamp($arDate[1], $dateRangeSetting['format']);
                
                if (!($dateRangeSetting['minUnixDate'] <= $unixStartDate &&
                            $dateRangeSetting['maxUnixDate'] >=  $unixEndDate &&
                                $unixEndDate >= $unixStartDate)) { throw new Exception("Указан неправильный период дат для массового редактирования"); }
                
                $arDays = U::filterDays(
                        $_POST["MASSEDIT"]['dayNumber'] ? (array)$_POST["MASSEDIT"]['dayNumber'] : array(),
                        U::getFormattedIntervalDate($unixStartDate, $unixEndDate, "day", "getDaysFormat")
                );
                
                $arMainData = Bitrix\Main\Web\Json::decode($_POST["MASSEDIT"]['PRICE'][0]);
                
                $arData[$arMainData["CLASS_AUTO"]]["CURRENCY"] = $arMainData["CURRENCY_ID"];
                
                foreach ($arDays as $arDay) {
                    $arData[$arMainData["CLASS_AUTO"]]["PRICE"][$arMainData["PT_ID"]][$arDay['unixDate']] = $_POST["MASSEDIT"]["PRICE"][1];
                } 
                
                $this->_processingMainForm($arData);
                
            }
        }
    }

    /**
     * @param array $data
     */
    protected function _processingMainForm(array $data) {

        $tdc = U::dc("transfer_rates_hl_id");
        $ptrdc = U::dc("ptr_hl_id");
        $ptdc = U::dc("price_type_hl_id");
        $pdc = U::dc("price_hl_id");
        $arCurrency = \travelsoft\Currency::getInstance()->get("currency");
        if (!empty($data)) {
            $arRates = [];
            $dbRates = $tdc::getList(array("filter" => array("UF_CLASS_AUTO" => array_keys($data), "UF_TRANSFER" => $this->arParams["ROW_ID"], "UF_USER_ID" => $this->_user_id)));   
            while ($arRate = $dbRates->fetch()) {
                $arRates[$arRate["UF_CLASS_AUTO"]] = $arRate;
            }
            
        }
        
        foreach ($data as $ID => $arData) {
            $CURRENCY_ID = $arCurrency[$arData["CURRENCY"]]["id"];
            # ОБРАБОТКА ЦЕН
            if ($CURRENCY_ID) {
                # ПРОВЕРКА СУЩЕСТВОВАНИЯ ТАРИФА
                if (isset($arRates[$ID])) {
                    $RATE_ID = $arRates[$ID]["ID"];
                    
                    $tdc::update($RATE_ID, array("UF_CURRENCY_ID" => $CURRENCY_ID));
                } else {
                    $RATE_ID = $tdc::add(array(
                                "UF_CURRENCY_ID" => $CURRENCY_ID,
                                "UF_CLASS_AUTO" => $ID,
                                "UF_TRANSFER" => $this->arParams["ROW_ID"],
                                "UF_USER_ID" => $this->_user_id
                            ))->getId();
                }

                if ($RATE_ID) {
                    if ($arData["PRICE"]) {
                        $arPtrs = [];
                        $dbPtrs = $ptdc::getList(array("filter" => array("ID" => array_keys($arData["PRICE"]))));
                        while ($arPtr = $dbPtrs->fetch()) {
                            $arPtrs[$arPtr["ID"]] = $arPtr;
                        }
                        foreach ($arData["PRICE"] as $PT_ID => $arPriceData) {

                            $arPT = @$arPtrs[$PT_ID];

                            if (@$arPT["ID"] > 0) {
                                $arPTRate = $ptrdc::getList(array("filter" => array("UF_RATE_ID" => $PT_ID, "UF_RATE_CATEGORY_ID" => $RATE_ID)))->fetch();
                                if ($arPTRate["ID"] > 0) {
                                    $PTR_ID = $arPTRate["ID"];
                                } else {
                                    $PTR_ID = $ptrdc::add(array("UF_RATE_ID" => $PT_ID, "UF_RATE_CATEGORY_ID" => $RATE_ID))->getId();
                                }

                                if ($PTR_ID) {
                                    
                                    $arPrices = [];
                                    $db_prices = $pdc::getList(array("filter" => array("UF_DATE" => array_keys($arPriceData), "UF_SERVICE_ID" => $this->arParams["ROW_ID"], "UF_PTPR_ID" => $PTR_ID)));
                                    while ($arr_price = $db_prices->fetch()) {
                                        $arPrices[$arr_price["UF_DATE"]] = $arr_price;
                                    }
                                    
                                    foreach ($arPriceData as $date => $price) {

                                        $arPrice = @$arPrices[$date];
                                          
                                        if (@$arPrice["ID"] > 0) {
                                            if ($price <= 0) {
                                                $pdc::delete($arPrice["ID"]);
                                                $this->_procres[$ID]["PRICE"][$PT_ID][$date] = null;
                                            } else {
                                                $pdc::update($arPrice["ID"], array("UF_GROSS" => $price));
                                                $this->_procres[$ID]["PRICE"][$PT_ID][$date] = $price;
                                            }
                                        } elseif ($price > 0) {
                                            
                                            $pdc::add(array("UF_DATE" => $date, "UF_SERVICE_ID" => $this->arParams["ROW_ID"], "UF_PTPR_ID" => $PTR_ID, "UF_GROSS" => $price));
                                            $this->_procres[$ID]["PRICE"][$PT_ID][$date] = $price;
                                        }
                                    }
                                }
                            }
                        }
                    } else {
                        $this->_procres[$ID]["UPDATE_RATE_CURRENCY"] = $arCurrency[$CURRENCY_ID]["iso"];
                    }
                } 
            }
        }
    }

    public function sendResponse() {
        if ($this->_procres) {
            echo \Bitrix\Main\Web\Json::encode($this->_procres);
        }
        die;
    }

}
