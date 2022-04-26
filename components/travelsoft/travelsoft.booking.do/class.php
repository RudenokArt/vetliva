<?php

use travelsoft\booking\Utils as U;
use Bitrix\Main\Config\Option as O;
use \Bitrix\Main\Localization\Loc;
use Bitrix\Highloadblock\HighloadBlockTable as HLBT;
CModule::IncludeModule('highloadblock');
Loc::loadMessages(__FILE__);

/*  Компонент бронирования  */

class TravelsoftBookingDo extends CBitrixComponent {

    /**
     *
     * @var travelsoft\booking\Basket
     */
    protected $_basket = null;

    public function getTotalBasketCost() {

        return $this->arResult["BASKET"]->formattedCost();
    }

    public function getDiscountBasket() {

        return $this->arResult["BASKET"]->formattedDiscount();
    }

    public function _verificationCitizenshipInServices() {


        $arPeopleCountByCitizenships = array();
        foreach ($this->arParams["_POST"]["tourist"] as $arr_tourist) {
            $arPeopleCountByCitizenships[$arr_tourist["citizenship"]] ++;
        }

        if (!empty($arPeopleCountByCitizenships)) {

            $arr_citizenships = (new \travelsoft\booking\datastores\CitizenshipDataStore(array()))->fetch(array("ID"));

            $arr_rates = $arr_citzenprice_by_services = array();

            while ($basket_oitem = $this->arResult["BASKET"]->fetch()) {

                $arr_basket_item = $basket_oitem["item"]->getPropertiesLikeArray();

                if (!in_array($arr_basket_item["type"], array("placements", "sanatorium"))) {
                    continue;
                }

                if (isset($arr_basket_item["rate_id"]) && !isset($arr_rates[$arr_basket_item["rate_id"]])) {
                    $arr_rates[$arr_basket_item["rate_id"]] = current(travelsoft\booking\datastores\RatesDataStore::get(array(
                                "filter" => array("ID" => $arr_basket_item["rate_id"]),
                                "select" => array("ID", "UF_BR_PRICES", "UF_RF_PRICES", "UF_EU_PRICES")
                    )));
                }

                if (isset($arr_rates[$arr_basket_item["rate_id"]]["UF_BR_PRICES"]) && 1 === (int) $arr_rates[$arr_basket_item["rate_id"]]["UF_BR_PRICES"]) {
                    $arr_citzenprice_by_services[333] += $arr_basket_item["adults"] + $arr_basket_item["children"];
                }

                if (isset($arr_rates[$arr_basket_item["rate_id"]]["UF_RF_PRICES"]) && 1 === (int) $arr_rates[$arr_basket_item["rate_id"]]["UF_RF_PRICES"]) {
                    $arr_citzenprice_by_services[332] += $arr_basket_item["adults"] + $arr_basket_item["children"];
                }

                if (isset($arr_rates[$arr_basket_item["rate_id"]]["UF_EU_PRICES"]) && 1 === (int) $arr_rates[$arr_basket_item["rate_id"]]["UF_EU_PRICES"]) {
                    $arr_citzenprice_by_services[356] += $arr_basket_item["adults"] + $arr_basket_item["children"];
                }
            }

            if (!empty($arr_citzenprice_by_services)) {

                foreach ($arPeopleCountByCitizenships as $citizenship_id => $p_count) {

                    $citizen_price_id = $arr_citizenships[$citizenship_id][0]["UF_CITIZEN_PRICE"];

                    if (!isset($arr_citzenprice_by_services[$citizen_price_id]) || $arr_citzenprice_by_services[$citizen_price_id] !== $p_count) {

                        $this->arResult["ERRORS"]["WRONG_VERIFICATION_BY_CITIZENSHIP"] = true;

                        return false;
                    }
                }
            }
        }
        return true;
    }
    
    public function url_encoded_data (array $data) {
        foreach ($data as $key => $value) {
            
            if (!empty($value)) {
                if (is_array($value)) {
                    $data[urlencode($key)] = $this->url_encoded_data($value);
                } else {
                    $data[urlencode($key)] = urlencode($value);
                }
            }
        }
        return $data;
    }

    /** обработка входящего запроса * */
    private function __processing_request() {

        if (
                $GLOBALS["USER"]->IsAuthorized() &&
                check_bitrix_sessid() &&
                strlen($this->arParams["_POST"]["submit"]) > 0 &&
                $this->__check_post()
        ) {
            
            if (!$this->arResult["BASKET"]->_change_blocked) {

                if (!$this->_verificationCitizenshipInServices()) {

                    return false;
                }
            }

            if (($_arr_booking_data = $this->__get_prepared_booking_data())) {

                $_arr_booking_data["token"] = $_SESSION["__TRAVELSOFT"]["TOKEN"];

                if (U::getCurrentCurrency()["iso"] !== "BYN") {
                    if ($this->arParams["_POST"]["tourist"][0]["citizenship"] == 1 /* Беларусь */) {
                        $_arr_booking_data["services"] = \array_values(\array_map(function ($service) {
                                    $service["brutto"] = round(U::convertCurrency($service["brutto"], $service["currency"], "BYN", TRUE), 2);
                                    $service["currency"] = "BYN";
                                    return $service;
                                }, $_arr_booking_data["services"]));
                    }
                }

                //добавляем заказ в highloadblock
                $hlblock = HLBT::getById(BOOKING_HL_BLOCK)->fetch();  
                $entity = HLBT::compileEntity($hlblock);
                $entity_data_class = $entity->getDataClass();

                $service_data = $_arr_booking_data; 
                
                $_arr_booking_data = $this->url_encoded_data($_arr_booking_data);
                
                $strResponse = \travelsoft\booking\Gateway::makeBooking(array(
                            "url" => U::getOpt("tsmo_url"),
                            "booking_data" => $_arr_booking_data
                ));
                
                $response = json_decode($strResponse, true);

                if ($response["result"]["dogovor_code"]) {

                    //region vd создание лида из заказа в Мастер-Тур
                    $arData = \travelsoft\Bx24::getLeadFieldsFromBooking($_arr_booking_data, $response);
                    \travelsoft\Bx24::createLeadFromBooking($arData);
                    //endregion vd

                    $data_class = \Bitrix\Highloadblock\HighloadBlockTable::compileEntity(\Bitrix\Highloadblock\HighloadBlockTable::getById(
                                                    \Bitrix\Main\Config\Option::get("travelsoft.booking.dev.tools", "quotas_hl_id"))
                                            ->fetch())->getDataClass();
                    while ($basketItem = $this->arResult["BASKET"]->fetch()) {
                        $arItem = $basketItem["item"]->getPropertiesLikeArray();
                        $method_name = "_increase" . ucfirst($arItem["type"]) . "NumberOfSold";
                        $this->$method_name($data_class, $arItem);
                        U::clearTagCacheForSearchOffers([$arItem["service_id"]]);
                    }
                    $this->arResult["BASKET"]->clear();
                    \travelsoft\booking\Promo::increasePromoQuota();
                    \travelsoft\booking\Promo::clear();
                    $_SESSION["__TRAVELSOFT"]['BOOKING_IS_DONE'] = true;

                    foreach($service_data['services'] as $service){
                        $result = $entity_data_class::add(array(
                            'UF_EMAIL'  => $service_data['buyer_info']['email'],
                            'UF_DATE_BEGIN'  => $service['dateBegin'],
                            'UF_DATE_END'  => $service['dateEnd'],
                            'UF_IBLOCK_ELEMENT_ID'  => $service['parts']['item_id'],
                            'UF_SERVICE_NAME'  => $service['parts']['clear_name'],
                            'UF_TO_BE_SEND'  => '1',
                            'UF_SENDED'  => '0',
                            'UF_SERVICE_JSON' => json_encode($service),
                            'UF_BUYER_INFO_JSON' => json_encode($service_data['buyer_info']),
                            'UF_TOURISTS_JSON' => json_encode($service_data['turists']),
                            'UF_DOGOVOR_CODE' => $response["result"]["dogovor_code"],
                            'UF_DATE_CREATE' => date("d.m.Y"),
                            'UF_CONVERTED_PRICE' => round(U::convertCurrency($service["brutto"]-$service["discount"], $service["currency"], "BYN", TRUE), 2)
                        ));     
                    }

                    LocalRedirect($this->arParams["PAYMENT_PAGE"] . "/detail.php?order_id=" . $response["result"]["dogovor_code"]);
                }
            }

            $this->arResult["ERRORS"]["BOOKING"] = true;
        }
    }

    /** увеличение количества проданных */
    protected function _increasePlacementsNumberOfSold($data_class, $arFields) {
        $start = $arFields["date_from"];
        $cbd = $this->arResult["PARENT_ELEMENTS"][$arFields["type"]][$this->arResult["SERVICES"][$arFields["type"]][$arFields["service_id"]]["UF_IBLOCK_ELEMENT_ID"]]['CALC_BY_DAY'];
        $end = $cbd ? $arFields["date_to"] : (($arFields["date_to"] - 86400) <= $arFields["date_from"] ? $arFields["date_from"] : ($arFields["date_to"] - 86400));
        $arFields["date_from"] = $start;
        $arFields["date_to"] = $end;

        $this->__increaseCommonNumberOfSold($data_class, $arFields, $this->__getIncreasePlacementsNumberOfSold($arFields));
    }

    /** увеличение количества проданных */
    protected function _increaseSanatoriumNumberOfSold($data_class, $arFields) {
        $this->_increasePlacementsNumberOfSold($data_class, $arFields);
    }

    /** увеличение количества проданных */
    protected function _increaseExcursionsNumberOfSold($data_class, $arFields) {

        $fname = "UF_DATE";
        $fvalue = $arFields['date_from'];
        # фильтр для списания квот по многодневгым эксурсиям
        if (!$this->arResult["RATES"][$arFields["type"]][$arFields["rate_id"]]['UF_FOR_PLACE']) {
            $fname = "><UF_DATE";
            $fvalue = array($arFields['date_from'], $arFields['date_from'] + (86400 * $arFields['duration']));
        }

        $arr_mail_fields = array(
            "OBJECT" => "",
            "SERVICE" => "",
            "DATES" => array(),
            "EMAIL_TO" => ""
        );

        $db_res = $data_class::getList(
                        array(
                            "filter" => array(
                                "UF_SERVICE_ID" => $arFields["service_id"],
                                $fname => $fvalue
                            ),
                            "select" => array("ID", "UF_SOLD_NUMBER", "UF_QUOTE", "UF_DATE")
                        )
        );
        while ($res = $db_res->fetch()) {
            $sold = $res["UF_SOLD_NUMBER"] + $arFields['adults'] + $arFields['children'];
            $data_class::update($res["ID"], array("UF_SOLD_NUMBER" => $sold));
            if ($sold >= $res["UF_QUOTE"]) {
                $arr_mail_fields["DATES"][] = date("d.m.Y", $res["UF_DATE"]);
            }
        }
        if (!empty($arr_mail_fields["DATES"])) {
            $arr_mail_fields["DATES"] = implode(", ", $arr_mail_fields["DATES"]);
            $arr_mail_fields["OBJECT"] = $this->arResult["PARENT_ELEMENTS"][$arFields["type"]][$this->arResult["SERVICES"][$arFields["type"]][$arFields["service_id"]]["UF_IBLOCK_ELEMENT_ID"]]['NAME'];
            $arr_user = $GLOBALS["USER"]->GetByID($this->arResult["PARENT_ELEMENTS"][$arFields["type"]][$this->arResult["SERVICES"][$arFields["type"]][$arFields["service_id"]]["UF_IBLOCK_ELEMENT_ID"]]['PARENT_USER'])->Fetch();
            if (strlen($arr_user["EMAIL"]) > 0) {
                $arr_mail_fields["EMAIL_TO"] = $arr_user["EMAIL"];
            }
            U::quotaExpired($arr_mail_fields);
        }
    }

    /** увеличение количества проданных */
    protected function _increaseExcursionstoursNumberOfSold($data_class, $arFields) {

        $data_class = \Bitrix\Highloadblock\HighloadBlockTable::compileEntity(\Bitrix\Highloadblock\HighloadBlockTable::getById(
                                        \Bitrix\Main\Config\Option::get("travelsoft.booking.dev.tools", "rates_quotas_hl_id"))
                                ->fetch())->getDataClass();

        $fname = "UF_DATE";
        $fvalue = $arFields['date_from'];

        $arr_mail_fields = array(
            "OBJECT" => "",
            "SERVICE" => "",
            "DATES" => array(),
            "EMAIL_TO" => ""
        );

        $db_res = $data_class::getList(
                        array(
                            "filter" => array(
                                "UF_SERVICE_ID" => $arFields["service_id"],
                                "UF_RATE_ID" => $arFields["rate_id"],
                                $fname => $fvalue
                            ),
                            "select" => array("ID", "UF_SOLD_NUMBER", "UF_QUOTE", "UF_DATE")
                        )
        );
        while ($res = $db_res->fetch()) {
            $sold = $res["UF_SOLD_NUMBER"] + 1;
            $data_class::update($res["ID"], array("UF_SOLD_NUMBER" => $sold));
            if ($sold >= $res["UF_QUOTE"]) {
                $arr_mail_fields["DATES"][] = date("d.m.Y", $res["UF_DATE"]);
            }
        }

        if (!empty($arr_mail_fields["DATES"])) {

            $arRate = current(\travelsoft\booking\datastores\RatesDataStore::get(array("filter" => array("ID" => $arFields["rate_id"]), "select" => array("ID", "UF_NAME"))));

            $arr_mail_fields["DATES"] = implode(", ", $arr_mail_fields["DATES"]);
            $arr_mail_fields["OBJECT"] = $this->arResult["PARENT_ELEMENTS"][$arFields["type"]][$this->arResult["SERVICES"][$arFields["type"]][$arFields["service_id"]]["UF_IBLOCK_ELEMENT_ID"]]['NAME'] . "[" . $arRate["UF_NAME"] . "]";
            $arr_user = $GLOBALS["USER"]->GetByID($this->arResult["PARENT_ELEMENTS"][$arFields["type"]][$this->arResult["SERVICES"][$arFields["type"]][$arFields["service_id"]]["UF_IBLOCK_ELEMENT_ID"]]['PARENT_USER'])->Fetch();
            if (strlen($arr_user["EMAIL"]) > 0) {
                $arr_mail_fields["EMAIL_TO"] = $arr_user["EMAIL"];
            }
            U::quotaExpired($arr_mail_fields);
        }
    }

    /** увеличение количества проданных */
    protected function _increaseTransfersNumberOfSold($data_class, $arFields) {
        return;
    }

    /** увеличение количества проданных */
    private function __increaseCommonNumberOfSold($data_class, $arFields, $toSale) {

        $db_res = $data_class::getList(
                        array(
                            "filter" => array(
                                "UF_SERVICE_ID" => $arFields["service_id"],
                                "><UF_DATE" => array($arFields["date_from"], $arFields["date_to"])
                            ),
                            "select" => array("ID", "UF_SOLD_NUMBER", "UF_QUOTE", "UF_DATE")
                        )
        );

        $arr_mail_fields = array(
            "OBJECT" => "",
            "SERVICE" => "",
            "DATES" => array(),
            "EMAIL_TO" => ""
        );

        while ($res = $db_res->fetch()) {
            $sold = $res["UF_SOLD_NUMBER"] + $toSale;
            $data_class::update($res["ID"], array("UF_SOLD_NUMBER" => $sold));
            if ($sold >= $res["UF_QUOTE"]) {
                $arr_mail_fields["DATES"][] = date("d.m.Y", $res["UF_DATE"]);
            }
        }


        if (!empty($arr_mail_fields["DATES"])) {
            $arr_mail_fields["DATES"] = implode(", ", $arr_mail_fields["DATES"]);
            $arr_mail_fields["OBJECT"] = $this->arResult["PARENT_ELEMENTS"][$arFields["type"]][$this->arResult["SERVICES"][$arFields["type"]][$arFields["service_id"]]["UF_IBLOCK_ELEMENT_ID"]]['NAME'];
            $arr_mail_fields["SERVICE"] = "[" . $this->arResult["SERVICES"][$arFields["type"]][$arFields["service_id"]]["UF_NAME"] . "]";
            $arr_user = $GLOBALS["USER"]->GetByID($this->arResult["PARENT_ELEMENTS"][$arFields["type"]][$this->arResult["SERVICES"][$arFields["type"]][$arFields["service_id"]]["UF_IBLOCK_ELEMENT_ID"]]['PARENT_USER'])->Fetch();
            if (strlen($arr_user["EMAIL"]) > 0) {
                $arr_mail_fields["EMAIL_TO"] = $arr_user["EMAIL"];
            }
            U::quotaExpired($arr_mail_fields);
        }
    }

    /** проверка формы * */
    private function __check_post() {

        $_post = $this->arParams["_POST"];

        $errors = null;

        /*$email = filter_var($_post["email"], FILTER_VALIDATE_EMAIL);

        if (!$email) {
            $errors[] = "WRONG_EMAIL";
        }*/

        if (!strlen($_post["phone"])) {
            $errors[] = "WRONG_PHONE";
        }

        if (empty($_post["tourist"])) {
            $errors[] = "WRONG_TOURIST";
        }

        foreach ($_post["tourist"] as $k => $val) {

            if (!strlen($val["name"])) {
                $errors["TOURIST"][$k][] = "WRONG_NAME";
            }
            if (!strlen($val["last_name"])) {
                $errors["TOURIST"][$k][] = "WRONG_LAST_NAME";
            }
            if (!isset($this->arResult["MALE"][$val["male"]])) {
                $errors["TOURIST"][$k][] = "WRONG_MALE";
            }
            if (preg_match("#" . $this->arResult["PATTERNS"]["birthdate"] . "#", $val['birthdate']) !== 1) {
                $errors["TOURIST"][$k][] = "WRONG_BIRTHDATE";
            }
            if (!strlen($val["passport"])) {
                $errors["TOURIST"][$k][] = "WRONG_PASSPORT";
            }
            if (!strlen($val["citizenship"])) {
                $errors["TOURIST"][$k][] = "WRONG_CITIZENSHIP";
            }
        }

        if ($errors) {
            $this->arResult["ERRORS"]["FORM"] = $errors;
            return false;
        }

        return true;
    }

    /**
     * Возвращает общие данные для бронирования
     * @param array $arItem
     * @return array
     */
    protected function _getBookingDataCommon(array $arItem) {

        $arResult["partnerId"] = $this->arResult["SERVICES"][$arItem["type"]][$arItem["service_id"]]["UF_USER_ID"];
        $arResult["dateBegin"] = date("d.m.Y", $arItem["date_from"]);
        $arResult["dateEnd"] = $arItem["date_to"] ? date("d.m.Y", $arItem["date_to"]) : date("d.m.Y", $arItem["date_from"]);

        $arResult["nmen"] = $arItem["adults"] + $arItem["children"];

        $arResult["brutto"] = number_format(
                $arItem["price"], O::get("travelsoft.currency", 'currency_format_decimals'), O::get("travelsoft.currency", 'currency_format_dec_point'), ""
        );

        foreach ($arItem['discount'] as $discount) {
            $arResult["discount"] += number_format(
                    $discount, O::get("travelsoft.currency", 'currency_format_decimals'), O::get("travelsoft.currency", 'currency_format_dec_point'), ""
            );
        }

        $arRate = current(\travelsoft\booking\datastores\RatesDataStore::get(array("filter" => array("ID" => $arItem["rate_id"]), "select" => array("ID", "UF_CURRENCY_ID"))));

        $arCurrency = U::getAllCurrency();

        $arResult["currency"] = $arCurrency[$arItem["currency"]]["iso"];

        $arResult["parameters"]["originalCurrency"] = $arCurrency[$arRate["UF_CURRENCY_ID"]]["iso"];
        $arResult["parameters"]["originalAmount"] = number_format(
                U::convertCurrency($arItem["price"], $arItem["currency"], $arCurrency[$arRate["UF_CURRENCY_ID"]]["iso"], true), O::get("travelsoft.currency", 'currency_format_decimals'), O::get("travelsoft.currency", 'currency_format_dec_point'), ""
        );

        return $arResult;
    }

    /**
     * Возвращает данные для бронирования для размещений
     * @param array $arItem
     * @return array
     */
    protected function _getBookingDataForPlacements(array $arItem) {

        $arResult = $this->_getBookingDataCommon($arItem);
        $arResult["countryId"] = $this->arResult["PARENT_ELEMENTS"][$arItem["type"]][$this->arResult["SERVICES"][$arItem["type"]][$arItem["service_id"]]["UF_IBLOCK_ELEMENT_ID"]]["PROPERTY_COUNTRY_ID"];
        $arResult["cityId"] = $this->arResult["PARENT_ELEMENTS"][$arItem["type"]][$this->arResult["SERVICES"][$arItem["type"]][$arItem["service_id"]]["UF_IBLOCK_ELEMENT_ID"]]["PROPERTY_TOWN_ID"];
        $arResult["type"] = 3;

        $str = array(
            "HOTEL::" . $this->arResult["PARENT_ELEMENTS"][$arItem["type"]][$this->arResult["SERVICES"][$arItem["type"]][$arItem["service_id"]]["UF_IBLOCK_ELEMENT_ID"]]["PROPERTY_TOWN_NAME"],
            $this->arResult["PARENT_ELEMENTS"][$arItem["type"]][$this->arResult["SERVICES"][$arItem["type"]][$arItem["service_id"]]["UF_IBLOCK_ELEMENT_ID"]]["NAME"] . "-" . $this->arResult["PARENT_ELEMENTS"][$arItem["type"]][$this->arResult["SERVICES"][$arItem["type"]][$arItem["service_id"]]["UF_IBLOCK_ELEMENT_ID"]]["PROPERTY_CAT_ID_NAME"],
            $this->arResult["SERVICES"][$arItem["type"]][$arItem["service_id"]]["UF_NAME" . POSTFIX_PROPERTY] . "(" . $this->arResult["RATES"][$arItem["type"]][$arItem["rate_id"]]["UF_NAME" . POSTFIX_PROPERTY] . ")," . $arItem["adults"] . "Ad" . ($arItem["children"] > 0 ? "+" . $arItem["children"] . "ch" : "")
        );

        //название услуги для письма пользователю
        $clearStr = array(
            $this->arResult["PARENT_ELEMENTS"][$arItem["type"]][$this->arResult["SERVICES"][$arItem["type"]][$arItem["service_id"]]["UF_IBLOCK_ELEMENT_ID"]]["PROPERTY_TOWN_NAME"],
            $this->arResult["PARENT_ELEMENTS"][$arItem["type"]][$this->arResult["SERVICES"][$arItem["type"]][$arItem["service_id"]]["UF_IBLOCK_ELEMENT_ID"]]["NAME"] . "-" . $this->arResult["PARENT_ELEMENTS"][$arItem["type"]][$this->arResult["SERVICES"][$arItem["type"]][$arItem["service_id"]]["UF_IBLOCK_ELEMENT_ID"]]["PROPERTY_CAT_ID_NAME"],
            $this->arResult["SERVICES"][$arItem["type"]][$arItem["service_id"]]["UF_NAME" . POSTFIX_PROPERTY] . "(" . $this->arResult["RATES"][$arItem["type"]][$arItem["rate_id"]]["UF_NAME" . POSTFIX_PROPERTY] . ")"
        );

        for ($i = 0, $cnt = count($this->arResult["RATES"][$arItem["type"]][$arItem["rate_id"]]["UF_FOOD_ID"]); $i < $cnt; $i++) {
            $food_arr[] = $this->arResult["FOOD"][$this->arResult["RATES"][$arItem["type"]][$arItem["rate_id"]]["UF_FOOD_ID"][$i]];
        }

        $str[] = implode(", ", $food_arr);
        $clearStr[] = implode(", ", $food_arr);

        $arResult["parts"]["name"] = implode("/", $str);
		file_put_contents("booking_log.txt", "str: " . $arResult["parts"]["name"] . "\n", FILE_APPEND | LOCK_EX);
        $arResult["parts"]["hotelId"] = $this->arResult["SERVICES"][$arItem["type"]][$arItem["service_id"]]["UF_IBLOCK_ELEMENT_ID"];
        $arResult["parts"]["roomId"] = $arItem["service_id"];
        $arResult["parts"]["categoryId"] = $this->arResult["RATES"][$arItem["type"]][$arItem["rate_id"]]["ID"];
        $arResult["parts"]["accmd"] = $str[3];

        $avail_for_spot_payment = ($this->arResult["PARENT_ELEMENTS"][$arItem["type"]][$this->arResult["SERVICES"][$arItem["type"]][$arItem["service_id"]]["UF_IBLOCK_ELEMENT_ID"]]['PROPERTY_FOR_SPOT_PAYMENT_VALUE'] && (

                (!$this->arResult["PARENT_ELEMENTS"][$arItem["type"]][$this->arResult["SERVICES"][$arItem["type"]][$arItem["service_id"]]["UF_IBLOCK_ELEMENT_ID"]]['PROPERTY_TO_PAY_FROM1_VALUE'] &&
                !$this->arResult["PARENT_ELEMENTS"][$arItem["type"]][$this->arResult["SERVICES"][$arItem["type"]][$arItem["service_id"]]["UF_IBLOCK_ELEMENT_ID"]]['PROPERTY_TO_PAY_TO1_VALUE'] &&
                !$this->arResult["PARENT_ELEMENTS"][$arItem["type"]][$this->arResult["SERVICES"][$arItem["type"]][$arItem["service_id"]]["UF_IBLOCK_ELEMENT_ID"]]['PROPERTY_TO_PAY_FROM2_VALUE'] &&
                !$this->arResult["PARENT_ELEMENTS"][$arItem["type"]][$this->arResult["SERVICES"][$arItem["type"]][$arItem["service_id"]]["UF_IBLOCK_ELEMENT_ID"]]['PROPERTY_TO_PAY_TO2_VALUE'] &&
                !$this->arResult["PARENT_ELEMENTS"][$arItem["type"]][$this->arResult["SERVICES"][$arItem["type"]][$arItem["service_id"]]["UF_IBLOCK_ELEMENT_ID"]]['PROPERTY_TO_PAY_FROM3_VALUE'] &&
                !$this->arResult["PARENT_ELEMENTS"][$arItem["type"]][$this->arResult["SERVICES"][$arItem["type"]][$arItem["service_id"]]["UF_IBLOCK_ELEMENT_ID"]]['PROPERTY_TO_PAY_TO3_VALUE']
                ) ||
                (
                strtotime($this->arResult["PARENT_ELEMENTS"][$arItem["type"]][$this->arResult["SERVICES"][$arItem["type"]][$arItem["service_id"]]["UF_IBLOCK_ELEMENT_ID"]]['PROPERTY_TO_PAY_FROM1_VALUE']) <= $arItem["date_from"] &&
                strtotime($this->arResult["PARENT_ELEMENTS"][$arItem["type"]][$this->arResult["SERVICES"][$arItem["type"]][$arItem["service_id"]]["UF_IBLOCK_ELEMENT_ID"]]['PROPERTY_TO_PAY_TO1_VALUE']) >= $arItem["date_to"]
                ) ||
                (
                strtotime($this->arResult["PARENT_ELEMENTS"][$arItem["type"]][$this->arResult["SERVICES"][$arItem["type"]][$arItem["service_id"]]["UF_IBLOCK_ELEMENT_ID"]]['PROPERTY_TO_PAY_FROM2_VALUE']) <= $arItem["date_from"] &&
                strtotime($this->arResult["PARENT_ELEMENTS"][$arItem["type"]][$this->arResult["SERVICES"][$arItem["type"]][$arItem["service_id"]]["UF_IBLOCK_ELEMENT_ID"]]['PROPERTY_TO_PAY_TO2_VALUE']) >= $arItem["date_to"]
                ) ||
                (
                strtotime($this->arResult["PARENT_ELEMENTS"][$arItem["type"]][$this->arResult["SERVICES"][$arItem["type"]][$arItem["service_id"]]["UF_IBLOCK_ELEMENT_ID"]]['PROPERTY_TO_PAY_FROM3_VALUE']) <= $arItem["date_from"] &&
                strtotime($this->arResult["PARENT_ELEMENTS"][$arItem["type"]][$this->arResult["SERVICES"][$arItem["type"]][$arItem["service_id"]]["UF_IBLOCK_ELEMENT_ID"]]['PROPERTY_TO_PAY_TO3_VALUE']) >= $arItem["date_to"]
                )
                )) ? true : false;

        $arResult["parts"]["for_spot_payment"] = ($avail_for_spot_payment && !in_array(U::getOpt('agents_group_id'), $GLOBALS['USER']->GetUserGroupArray())) ? 1 : 0;

        $arResult["parts"]["quantity"] = $this->__getIncreasePlacementsNumberOfSold($arItem);

//        $arResult["parts"]["quantity"] = $this->arResult["RATES"][$arItem["type"]][$arItem["rate_id"]]['UF_FOR_PLACE'] ? ($arItem["adults"] + $arItem["children"]) : 1;

        $arResult["parts"]['calc_by_day'] = $this->arResult["PARENT_ELEMENTS"][$arItem["type"]][$this->arResult["SERVICES"][$arItem["type"]][$arItem["service_id"]]["UF_IBLOCK_ELEMENT_ID"]]['CALC_BY_DAY'];
        $arResult["parts"]['is_travelline'] = $this->arResult["PARENT_ELEMENTS"][$arItem["type"]][$this->arResult["SERVICES"][$arItem["type"]][$arItem["service_id"]]["UF_IBLOCK_ELEMENT_ID"]]['IS_TRAVELLINE'];

        $arResult["parts"]['agent_id'] = (int) $arItem["agent"];

        $arResult["parameters"]["hotels"] = $this->_getHotelsInfo((array) $this->arResult["SERVICES"][$arItem["type"]][$arItem["service_id"]]["UF_IBLOCK_ELEMENT_ID"]);

        $arResult["parts"]["item_id"] = $this->arResult["SERVICES"][$arItem["type"]][$arItem["service_id"]]["UF_IBLOCK_ELEMENT_ID"];

        $arResult["parts"]["clear_name"] = implode("/", $clearStr);

		$dump_res = print_r($arResult, true);
		file_put_contents("booking_log_arr.txt", $dump_res, FILE_APPEND | LOCK_EX);
        return $arResult;
    }

    /**
     * @param type $arFields
     * @return int
     */
    protected function __getIncreasePlacementsNumberOfSold($arFields) {

        if ($this->arResult["RATES"][$arFields["type"]][$arFields["rate_id"]]['UF_FOR_PLACE']) {

            if ($arFields["adults"] == 1 && $arFields['children'] == 0 && U::priceForOneAdultByRoomIsExists($arFields['rate_id'])) {
                // ЕСЛИ НОМЕР РАССЧИТЫВАЕТСЯ ПО МЕСТАМ И ТИП ЦЕНЫ "Стоимость за номер для одного человека"
                // СНИМАЕМ КВОТУ РАВНУЮ КОЛИЧЕСТВУ ОСНОВНЫХ МЕСТ В НОМЕРЕ (ПРОДАЖА НОМЕРА ЦЕЛИКОМ)
                return $this->arResult["SERVICES"][$arFields["type"]][$arFields["service_id"]]["UF_PLACES_MAIN"];
            } else {
                // ЕСЛИ НОМЕР РАССЧИТЫВАЕТСЯ ПО МЕСТАМ
                // СНИМАЕМ КВОТУ РАВНУЮ КОЛИЧЕСТВУ ОСНОВНЫХ МЕСТ В НОМЕРЕ ИЛИ КОЛИЧЕСТВУ ЧЕЛОВЕК,
                // ЕСЛИ КОЛИЧЕСТВО ОСНОВНЫХ МЕСТ БОЛЬШЕ ЧЕМ КОЛИЧЕСТВО ЧЕЛОВЕК
                if ($this->arResult["SERVICES"][$arFields["type"]][$arFields["service_id"]]["UF_PLACES_MAIN"] > $arFields["adults"] + $arFields["children"]) {
                    return $arFields["adults"] + $arFields["children"];
                } else {
                    return $this->arResult["SERVICES"][$arFields["type"]][$arFields["service_id"]]["UF_PLACES_MAIN"];
                }
            }
        } else {
            return 1;
        }
    }

    /**
     * Возвращает данные для бронирования для санаториев
     * @param array $arItem
     * @return array
     */
    protected function _getBookingDataForSanatorium(array $arItem) {
        $arResult = $this->_getBookingDataForPlacements($arItem);
        $arResult['type'] = 319;
        return $arResult;
    }

    /**
     * Возвращает данные для бронирования для туров
     * @param array $arItem
     * @return array
     */
    protected function _getBookingDataForExcursionstours(array $arItem) {
        $result = $this->_getBookingDataForExcursions($arItem);
        $result["parts"]["quantity"] = 1;
        $result["parts"]["is_multipleday_tour"] = 1;
        $result["parts"]["rate_id"] = $arItem["rate_id"];
        return $result;
    }

    /**
     * Возвращает данные для бронирования для экскурсий
     * @param array $arItem
     * @return array
     */
    protected function _getBookingDataForExcursions(array $arItem) {
        $arResult = $this->_getBookingDataCommon($arItem);
        $arResult["type"] = 4;
        $arResult["days"] = $arItem["duration"];
        $arResult["countryId"] = $this->arResult["PARENT_ELEMENTS"][$arItem["type"]][$this->arResult["SERVICES"][$arItem["type"]][$arItem["service_id"]]["UF_IBLOCK_ELEMENT_ID"]]["PROPERTY_COUNTRY_ID"];
        $arResult["cityId"] = $this->arResult["PARENT_ELEMENTS"][$arItem["type"]][$this->arResult["SERVICES"][$arItem["type"]][$arItem["service_id"]]["UF_IBLOCK_ELEMENT_ID"]]["PROPERTY_TOWN_ID"];
        $str = array(
            "EXCURSION::" . $this->arResult["PARENT_ELEMENTS"][$arItem["type"]][$this->arResult["SERVICES"][$arItem["service_id"]]["UF_IBLOCK_ELEMENT_ID"]]["PROPERTY_TOWN_NAME"],
            $this->arResult["PARENT_ELEMENTS"][$arItem["type"]][$this->arResult["SERVICES"][$arItem["type"]][$arItem["service_id"]]["UF_IBLOCK_ELEMENT_ID"]]["NAME"],
            $this->arResult["RATES"][$arItem["type"]][$arItem["rate_id"]]["UF_NAME" . POSTFIX_PROPERTY] . "," . $arItem["adults"] . "Ad" . ($arItem["children"] > 0 ? "+" . $arItem["children"] . "ch" : "")
        );

        $clearStr = array(
            $this->arResult["PARENT_ELEMENTS"][$arItem["type"]][$this->arResult["SERVICES"][$arItem["service_id"]]["UF_IBLOCK_ELEMENT_ID"]]["PROPERTY_TOWN_NAME"],
            $this->arResult["PARENT_ELEMENTS"][$arItem["type"]][$this->arResult["SERVICES"][$arItem["type"]][$arItem["service_id"]]["UF_IBLOCK_ELEMENT_ID"]]["NAME"],
            $this->arResult["RATES"][$arItem["type"]][$arItem["rate_id"]]["UF_NAME" . POSTFIX_PROPERTY]
        );

        $arResult["parameters"]["route"] = $this->arResult["PARENT_ELEMENTS"][$arItem["type"]][$this->arResult["SERVICES"][$arItem["type"]][$arItem["service_id"]]["UF_IBLOCK_ELEMENT_ID"]]["PROPERTY_ROUTE" . POSTFIX_PROPERTY . "_VALUE"];

        $arResult["parameters"]["dep_point"] = $this->arResult["PARENT_ELEMENTS"][$arItem["type"]][$this->arResult["SERVICES"][$arItem["type"]][$arItem["service_id"]]["UF_IBLOCK_ELEMENT_ID"]]["PROPERTY_DEPARTURE_EXC_TEXT" . POSTFIX_PROPERTY . "_VALUE"];

        $arResult["parameters"]["hotels"] = $this->_getHotelsInfo((array) $this->arResult["PARENT_ELEMENTS"][$arItem["type"]][$this->arResult["SERVICES"][$arItem["type"]][$arItem["service_id"]]["UF_IBLOCK_ELEMENT_ID"]]["PROPERTY_HOTEL_VALUE"]);

        $arResult["parts"]["quantity"] = $arItem["children"] + $arItem["adults"];

        $avail_for_spot_payment = ($this->arResult["PARENT_ELEMENTS"][$arItem["type"]][$this->arResult["SERVICES"][$arItem["type"]][$arItem["service_id"]]["UF_IBLOCK_ELEMENT_ID"]]['PROPERTY_FOR_SPOT_PAYMENT_VALUE'] && (

                (!$this->arResult["PARENT_ELEMENTS"][$arItem["type"]][$this->arResult["SERVICES"][$arItem["type"]][$arItem["service_id"]]["UF_IBLOCK_ELEMENT_ID"]]['PROPERTY_TO_PAY_FROM1_VALUE'] &&
                !$this->arResult["PARENT_ELEMENTS"][$arItem["type"]][$this->arResult["SERVICES"][$arItem["type"]][$arItem["service_id"]]["UF_IBLOCK_ELEMENT_ID"]]['PROPERTY_TO_PAY_TO1_VALUE'] &&
                !$this->arResult["PARENT_ELEMENTS"][$arItem["type"]][$this->arResult["SERVICES"][$arItem["type"]][$arItem["service_id"]]["UF_IBLOCK_ELEMENT_ID"]]['PROPERTY_TO_PAY_FROM2_VALUE'] &&
                !$this->arResult["PARENT_ELEMENTS"][$arItem["type"]][$this->arResult["SERVICES"][$arItem["type"]][$arItem["service_id"]]["UF_IBLOCK_ELEMENT_ID"]]['PROPERTY_TO_PAY_TO2_VALUE'] &&
                !$this->arResult["PARENT_ELEMENTS"][$arItem["type"]][$this->arResult["SERVICES"][$arItem["type"]][$arItem["service_id"]]["UF_IBLOCK_ELEMENT_ID"]]['PROPERTY_TO_PAY_FROM3_VALUE'] &&
                !$this->arResult["PARENT_ELEMENTS"][$arItem["type"]][$this->arResult["SERVICES"][$arItem["type"]][$arItem["service_id"]]["UF_IBLOCK_ELEMENT_ID"]]['PROPERTY_TO_PAY_TO3_VALUE']
                ) ||
                (
                strtotime($this->arResult["PARENT_ELEMENTS"][$arItem["type"]][$this->arResult["SERVICES"][$arItem["type"]][$arItem["service_id"]]["UF_IBLOCK_ELEMENT_ID"]]['PROPERTY_TO_PAY_FROM1_VALUE']) <= $arItem["date_from"] &&
                strtotime($this->arResult["PARENT_ELEMENTS"][$arItem["type"]][$this->arResult["SERVICES"][$arItem["type"]][$arItem["service_id"]]["UF_IBLOCK_ELEMENT_ID"]]['PROPERTY_TO_PAY_TO1_VALUE']) >= $arItem["date_to"]
                ) ||
                (
                strtotime($this->arResult["PARENT_ELEMENTS"][$arItem["type"]][$this->arResult["SERVICES"][$arItem["type"]][$arItem["service_id"]]["UF_IBLOCK_ELEMENT_ID"]]['PROPERTY_TO_PAY_FROM2_VALUE']) <= $arItem["date_from"] &&
                strtotime($this->arResult["PARENT_ELEMENTS"][$arItem["type"]][$this->arResult["SERVICES"][$arItem["type"]][$arItem["service_id"]]["UF_IBLOCK_ELEMENT_ID"]]['PROPERTY_TO_PAY_TO2_VALUE']) >= $arItem["date_to"]
                ) ||
                (
                strtotime($this->arResult["PARENT_ELEMENTS"][$arItem["type"]][$this->arResult["SERVICES"][$arItem["type"]][$arItem["service_id"]]["UF_IBLOCK_ELEMENT_ID"]]['PROPERTY_TO_PAY_FROM3_VALUE']) <= $arItem["date_from"] &&
                strtotime($this->arResult["PARENT_ELEMENTS"][$arItem["type"]][$this->arResult["SERVICES"][$arItem["type"]][$arItem["service_id"]]["UF_IBLOCK_ELEMENT_ID"]]['PROPERTY_TO_PAY_TO3_VALUE']) >= $arItem["date_to"]
                )
                )) ? true : false;

        $arResult["parts"]["for_spot_payment"] = ($avail_for_spot_payment &&
                !in_array(U::getOpt('agents_group_id'), $GLOBALS['USER']->GetUserGroupArray())) ? 1 : 0;
        $arResult["parts"]["name"] = implode("/", $str);
        $arResult["parts"]["excursion"] = $this->arResult["SERVICES"][$arItem["type"]][$arItem["service_id"]]["ID"];
        $arResult["parts"]["serviceId"] = $arItem["service_id"];
        $arResult["parts"]['calc_by_day'] = !$this->arResult["RATES"][$arItem["type"]][$arItem["rate_id"]]['UF_FOR_PLACE'] ? 1 : 0;
        $arResult["parts"]['agent_id'] = (int) $arItem["agent"];
        $arResult["parts"]["item_id"] = $this->arResult["SERVICES"][$arItem["type"]][$arItem["service_id"]]["UF_IBLOCK_ELEMENT_ID"];
        $arResult["parts"]["clear_name"] = implode("/", $clearStr);
        return $arResult;
    }

    /**
     * Возвращает данные для бронирования для трансферов
     * @param array $arItem
     * @return array
     */
    protected function _getBookingDataForTransfers(array $arItem) {

        $arResult = $this->_getBookingDataCommon($arItem);
        $arResult["days"] = 1;
        $arResult["countryId"] = $this->arResult["SERVICES"][$arItem["type"]][$arItem["service_id"]]["POINT_A_COUNTRY_ID"];
        $arResult["cityId"] = $this->arResult["SERVICES"][$arItem["type"]][$arItem["service_id"]]["POINT_A_ID"];
        $arResult["type"] = 2;
        $arPoints = explode(" - ", $this->arResult["SERVICES"][$arItem["type"]][$arItem["service_id"]]["UF_NAME"]);

        if (@$arItem["reverse_name"]) {
            
            $str = array(
//                "TRANSFER::" . $this->arResult["SERVICES"][$arItem["type"]][$arItem["service_id"]]["UF_NAME"] . " — " .
                "TRANSFER::" . implode(" - ", [$arPoints[1], $arPoints[0]]),
                $this->arResult["CLASS_AUTO"][$this->arResult["RATES"][$arItem["type"]][$arItem["rate_id"]]["UF_CLASS_AUTO"]]["UF_NAME" . POSTFIX_PROPERTY]
            );

            $clearStr = array(
                implode(" - ", [$arPoints[1], $arPoints[0]]),
                $this->arResult["CLASS_AUTO"][$this->arResult["RATES"][$arItem["type"]][$arItem["rate_id"]]["UF_CLASS_AUTO"]]["UF_NAME" . POSTFIX_PROPERTY]
            );
        } else {
            $str = array(
//                "TRANSFER::" . $this->arResult["SERVICES"][$arItem["type"]][$arItem["service_id"]]["POINT_A_NAME"] . " — " .
                "TRANSFER::" . implode(" - ", [$arPoints[0], $arPoints[1]]),
                $this->arResult["CLASS_AUTO"][$this->arResult["RATES"][$arItem["type"]][$arItem["rate_id"]]["UF_CLASS_AUTO"]]["UF_NAME" . POSTFIX_PROPERTY]
            );

            $clearStr = array(
                 implode(" - ", [$arPoints[0], $arPoints[1]]),
                $this->arResult["CLASS_AUTO"][$this->arResult["RATES"][$arItem["type"]][$arItem["rate_id"]]["UF_CLASS_AUTO"]]["UF_NAME" . POSTFIX_PROPERTY]
            ); 
        }


        $arResult["parts"]["name"] = implode("/", $str);
        $arResult["parts"]["for_spot_payment"] = (int) $arItem["for_spot_payment"];
        $arResult["parts"]["transfer"] = $this->arResult["SERVICES"][$arItem["type"]][$arItem["service_id"]]["UF_IBLOCK_ELEMENT_ID"];
        $arResult["parts"]["transport"] = $this->arResult["RATES"][$arItem["type"]][$arItem["rate_id"]]["ID"];
        $arResult["parts"]["item_id"] = $this->arResult["SERVICES"][$arItem["type"]][$arItem["service_id"]]["UF_IBLOCK_ELEMENT_ID"];
        $arResult["parts"]["clear_name"] = implode("/", $clearStr);
        
        return $arResult;
    }

    /**
     * Возвращает информацию по отелям в виде массива
     * @param array $hotels
     * @return string
     */
    protected function _getHotelsInfo(array $hotels) {

        $result = array();
        if (!empty($hotels)) {

            foreach ($hotels as $hotel) {

                $dbElement = \CIBlockElement::GetByID($hotel)->GetNextElement();
                if ($dbElement) {

                    $arFields = $dbElement->getFields();

                    $HOTELNAME = NULL;

                    if ($arFields["ACTIVE"] == "Y") {

                        $arProperties = $dbElement->getProperties();

                        $HOTELNAME = LANGUAGE_ID === 'ru' ? $arFields["NAME"] : $arProperties['NAME' . POSTFIX_PROPERTY]['VALUE'];
                        $CITY = null;
                        if ($arProperties["TOWN"]["VALUE"]) {
                            $cityElement = \CIBlockElement::GetByID($arProperties["TOWN"]["VALUE"])->GetNextElement();
                            $cityFields = $cityElement->GetFields();
                            $cityProperties = $cityElement->GetProperties();
                            if ($cityFields['ID'] > 0) {
                                $CITY = LANGUAGE_ID === 'ru' ? $cityFields["NAME"] : $cityProperties['NAME' . POSTFIX_PROPERTY]['VALUE'];
                            }
                        }

                        $result[] = array(
                            "name" => $HOTELNAME,
                            "address" => implode(", ", array_filter(array($CITY, $arProperties["ADDRESS" . POSTFIX_PROPERTY]["VALUE"]), function ($el) {
                                                return strlen($el) > 0;
                                            })),
                            "phone" => ""
                        );
                    }
                }
            }
        }
        return $result;
    }

    /**
     * подготовка параметров для бронирования
     * @return null | array
     */
    private function __get_prepared_booking_data() {
        $key = 0;
        $data = null;
        while ($basketItem = $this->arResult["BASKET"]->fetch()) {
            $item = $basketItem["item"];
            if (!$item->can_buy) {
                continue;
            }

            $method_name = "_getBookingDataFor" . ucfirst($item->type);
            if ($item->type === "transfers" && 1 === (int) $item->roundtrip) {
                $arItem = $item->getPropertiesLikeArray();
                $tmp_arr_item = $arItem;
                
                $tmp_arr_item["date_to"] = $arItem["date_from"];
                if (is_array($tmp_arr_item["discount"])) {
                    foreach ($tmp_arr_item["discount"] as &$d) {
                        $dd = $d;
                        $d = round($dd / 2, 2);
                    }
                }elseif ($tmp_arr_item["discount"] > 0) {
                    $tmp_arr_item["discount"] = round($tmp_arr_item["discount"] / 2, 2);
                }
                $tmp_arr_item["price"] = round($tmp_arr_item["price"] / 2, 2);
                $tmp_arr_item["reverse_name"] = false;
                $data["services"][$key] = $this->$method_name($tmp_arr_item);
                $key++;
                $tmp_arr_item["date_from"] = $arItem["date_to"];
                $tmp_arr_item["date_to"] = $arItem["date_to"];
                $tmp_arr_item["point_A"] = $arItem["point_B"];
                $tmp_arr_item["reverse_name"] = true;
                $data["services"][$key] = $this->$method_name($tmp_arr_item);
            } else {
                $data["services"][$key] = $this->$method_name($item->getPropertiesLikeArray());
            }
            $key++;
        }

        if (!$data) {
            return null;
        }

        $data["buyer_info"]["email"] = $this->arParams["_POST"]["email"];
        $data["buyer_info"]["phone"] = $this->arParams["_POST"]["phone"];
        $data["buyer_info"]["language"] = LANGUAGE_ID;
        $data["comment"] = !$this->arParams["_POST"]["comment"] ? "" : $this->arParams["_POST"]["comment"];

        if(!empty($this->arParams["_POST"]['resp_adult'])){
            $data["comment"] .= '; Ответственный взрослый: ';
            
            $data["comment"] .= 'Имя: ';
            $data["comment"] .= !empty($this->arParams["_POST"]['resp_adult']['name']) ? $this->arParams["_POST"]['resp_adult']['name'].'; ' : '; ';

            $data["comment"] .= 'Фамилия: ';
            $data["comment"] .= !empty($this->arParams["_POST"]['resp_adult']['last_name']) ? $this->arParams["_POST"]['resp_adult']['last_name'].'; ' : '; ';

            $data["comment"] .= 'Пол: ';
            $data["comment"] .= !empty($this->arParams["_POST"]['resp_adult']['male']) ? $this->arParams["_POST"]['resp_adult']['male'].'; ' : '; ';

            $data["comment"] .= 'Дата рождения: ';
            $data["comment"] .= !empty($this->arParams["_POST"]['resp_adult']['birthdate']) ? $this->arParams["_POST"]['resp_adult']['birthdate'].'; ' : '; ';

            $data["comment"] .= 'Серия и номер ';
            $data["comment"] .= !empty($this->arParams["_POST"]['resp_adult']['passport']) ? $this->arParams["_POST"]['resp_adult']['passport'].'; ' : '; ';

            $data["comment"] .= 'Гражданство: ';
            $data["comment"] .= !empty($this->arParams["_POST"]['resp_adult']['citizenship']) ? $this->arResult["CITIZENSHIPS"][$this->arParams["_POST"]['resp_adult']['citizenship']].'; ' : '; ';
        }

        $countOfPeople = $this->arResult["BASKET"]->countOfPeople();
        foreach ($this->arParams["_POST"]["tourist"] as $key => $tourist) {
            if (($key + 1) <= $countOfPeople) {
                $data["turists"][] = array(
                    "first_name" => $tourist["name"],
                    "last_name" => $tourist["last_name"],
                    "sex" => $tourist["male"],
                    "citizenship" => $this->arResult["CITIZENSHIPS"][$tourist["citizenship"]],
                    "passport_num" => $tourist["passport"],
                    "birth_date" => $tourist["birthdate"]
                );
            }
        }
        return $data;
    }

    /** установка полей пользователя * */
    private function __set_user_info() {

        $this->arResult["USER"] = array(
            "is_authorized" => $GLOBALS["USER"]->IsAuthorized(),
            "email" => $GLOBALS["USER"]->GetEmail()
        );
    }

    /** подключение модулей * */
    private function __include_modules() {
        if (!Bitrix\Main\Loader::includeModule("travelsoft.booking.dev.tools")) {
            throw new Exception("It is impossible to make a booking");
        }
        if (!Bitrix\Main\Loader::includeModule("highloadblock")) {
            throw new Exception("Highloadblock not found");
        }
        if (!Bitrix\Main\Loader::includeModule("travelsoft.currency")) {
            throw new Exception("currency module not found");
        }
        if (!Bitrix\Main\Loader::includeModule("iblock")) {
            throw new Exception("iblock module not found");
        }
    }

    /**
     * подключение js
     */
    private function __include_js() {

        $asset = Bitrix\Main\Page\Asset::getInstance();

        CJSCore::Init(["popup"]);

        if ((string) $this->arParams["INC_JQUERY_MASKEDINPUT"] === "Y") {
            $asset->addJs("https://cdnjs.cloudflare.com/ajax/libs/jquery.maskedinput/1.4.1/jquery.maskedinput.min.js", true);
        }

        if ($this->__template && $this->__template->__folder) {
            $asset->addJs($this->__template->__folder . "/_script.js", true);
        }
    }

    /**
     * регулярные выражение для html аттрибута pattern
     */
    private function __set_input_check_patterns() {

        $this->arResult["PATTERNS"] = array(
            "birthdate" => "^\d{2}\.\d{2}\.\d{4}$",
            "phone" => "^\+?[0-9,\s]{0,}$"
        );
    }

    /**
     * Данные по полу
     */
    private function __set_male_values() {
        $this->arResult["MALE"] = array("м" => "MAN", "ж" => "WOMAN");
    }

    /**
     * возвращает ресайз-массив изображения для услуги 
     * @param mixed $imgId
     * @return array|null
     */
    protected function _getResizeImage($imgId) {
        $imgId = (array) $imgId;
        return CFile::ResizeImageGet(
                        $imgId[0], array('width' => 200, 'height' => 150), BX_RESIZE_IMAGE_PROPORTIONAL, true
        );
    }

    /**
     * Устанавливает массив $arResult
     * @param $without_recalculation_basket
     * @return $this
     */
    public function _setArResult($without_recalculation_basket = false) {

        if (!$without_recalculation_basket) {
            $this->arResult["BASKET"] = U::getRecalculationBasket();
        } elseif (!isset($this->arResult["BASKET"])) {
            $this->arResult["BASKET"] = new \travelsoft\booking\Basket;
        }

        $this->arResult["CITIZENSHIPS"] = \travelsoft\booking\Utils::getCitizenship();

        while ($basketItem = $this->arResult["BASKET"]->fetch()) {

            $item = $basketItem["item"];
            $method_name = "_setArResultFor" . ucfirst($item->type);
            $this->$method_name($item->getPropertiesLikeArray());
        }
        return $this;
    }

    /**
     * Возвращает данные по услугам в корзине определенного формата
     * @return array
     */
    public function getJsCartItems() {
        $this->_setArResult();
        $arJsCartItems = array();
        while ($arFetch = $this->arResult["BASKET"]->fetch()) {
            $item = $arFetch["item"];
            $arService = $this->arResult["SERVICES"][$item->type][$item->service_id];
            $arJsCartItems[] = [
                "recalculated" => false,
                "can_buy" => $item->can_buy,
                "position" => $arFetch["position"],
                "name" => $arService["UF_NAME" . POSTFIX_PROPERTY],
                "image_src" => $arService["PICTURE"]["src"],
                "rate_name" => $this->arResult["RATES"][$item->type][$item->rate_id]["UF_NAME" . POSTFIX_PROPERTY],
                "date_from" => $item->date_from,
                "date_from_formatted" => date("d.m.Y", $item->date_from),
                "date_to" => $item->date_to,
                "date_to_formatted" => strlen($item->date_to) > 0 ? date("d.m.Y", $item->date_to) : "",
                "adults" => $item->adults,
                "children" => $item->children,
                "price" => U::convertCurrency($item->price, $item->currency)
            ];
        }

        return $arJsCartItems;
    }

    /**
     * Возвращает данные по услугам в корзине определенного формата
     * @return array
     */
    public function getJsCartItemsFromCartElementFields(array $basketFields) {
        $this->_setArResult(true);

        foreach ($basketFields as $position => &$arFields) {
            $arService = $this->arResult["SERVICES"][$arFields["type"]][$arFields["service_id"]];

            $arFields["position"] = $position;
            $arFields["recalculated"] = true;
            $arFields["name"] = $arService["UF_NAME" . POSTFIX_PROPERTY];
            $arFields["image_src"] = $arService["PICTURE"]["src"];
            $arFields["rate_name"] = $this->arResult["RATES"][$arFields["type"]][$arFields["rate_id"]]["UF_NAME" . POSTFIX_PROPERTY];
            $arFields["date_from_formatted"] = date("d.m.Y", $arFields["date_from"]);
            $arFields["date_to_formatted"] = strlen($arFields["date_to"]) > 0 ? date("d.m.Y", $arFields["date_to"]) : "";
        }

        return $basketFields;
    }

    /**
     * Устанваливает общие параметры $arResult для услуг и тарифов
     * @param array $fields
     * @param string $type
     */
    protected function _setArResultFor(array $fields) {
        if (!$this->arResult["RATES"][$fields["type"]][$fields["rate_id"]]) {
            $arRate = \travelsoft\booking\datastores\RatesDataStore::get(array(
                        "filter" => array("ID" => $fields["rate_id"]),
                        "select" => array("ID", "UF_NAME" . POSTFIX_PROPERTY, "UF_FOOD_ID", 'UF_FOR_PLACE')
            ));
            if ($arRate[0]["ID"] > 0) {
                $this->arResult["RATES"][$fields["type"]][$arRate[0]["ID"]] = $arRate[0];
                for ($i = 0, $cnt = count($arRate[0]["UF_FOOD_ID"]); $i < $cnt; $i++) {
                    if (!$this->arResult["FOOD"][$arRate[0]["UF_FOOD_ID"][$i]]) {
                        $arFood = U::getFromStore("food", array(
                                    "filter" => array("ID" => $arRate[0]["UF_FOOD_ID"][$i]),
                                    "select" => array("ID", "UF_NAME_" . strtoupper(LANGUAGE_ID))
                        ));
                        $this->arResult["FOOD"][$arRate[0]["UF_FOOD_ID"][$i]] = $arFood["UF_NAME" . POSTFIX_PROPERTY];
                    }
                }
            }
        }
        if (!$this->arResult["SERVICES"][$fields["type"]][$fields["service_id"]]) {

            $arService = U::getServiceById($fields["service_id"]);
            if ($arService[0]["ID"] > 0) {
                $arService[0]["PICTURE"] = $this->_getResizeImage($arService[0]["UF_PICTURES"]);
                if (!$this->arResult["PARENT_ELEMENTS"][$fields["service_id"]][$arService[0]["UF_IBLOCK_ELEMENT_ID"]] &&
                        $dbElement = \CIBlockElement::GetByID($arService[0]["UF_IBLOCK_ELEMENT_ID"])->GetNextElement()) {
                    $arFields = $dbElement->GetFields();
                    $arProperties = $dbElement->GetProperties();

                    if ($arProperties['COUNTRY']['VALUE'] > 0) {
                        $arFields['PROPERTY_COUNTRY_ID'] = $arProperties['COUNTRY']['VALUE'];
                    }

                    if ($arProperties['TOWN']['VALUE']) {
                        $towns = (array) $arProperties['TOWN']['VALUE'];
                        $arFields['PROPERTY_TOWN_ID'] = $towns[0];
                        if (($dbTown = \CIBlockElement::GetByID($arFields['PROPERTY_TOWN_ID'])->GetNextElement())) {

                            if (LANGUAGE_ID !== 'ru') {
                                $prop = $dbTown->GetProperties();
                                $arFields['PROPERTY_TOWN_NAME'] = $prop['NAME' . POSTFIX_PROPERTY]['VALUE'];
                            } else {
                                $ff = $dbTown->GetFields();
                                $arFields['PROPERTY_TOWN_NAME'] = $ff['NAME'];
                            }
                        }
                    }

                    if ($arProperties['CAT_ID']['VALUE'] > 0 && ( $dbCat = \CIBlockElement::GetByID($arProperties['CAT_ID']['VALUE'])->GetNextElement() )) {

                        if (LANGUAGE_ID !== 'ru') {

                            $prop = $dbCat->GetProperties();
                            $arFields['PROPERTY_CAT_ID_NAME'] = $prop['NAME' . POSTFIX_PROPERTY]['VALUE'];
                        } else {

                            $ff = $dbCat->GetFields();
                            $arFields['PROPERTY_CAT_ID_NAME'] = $ff['NAME'];
                        }
                    }

                    if (strlen($arProperties['ROUTE' . POSTFIX_PROPERTY]['VALUE']) > 0) {
                        $arFields['PROPERTY_ROUTE' . POSTFIX_PROPERTY . '_VALUE'] = $arProperties['ROUTE' . POSTFIX_PROPERTY]['VALUE'];
                    }

                    if (strlen($arProperties['FOR_SPOT_PAYMENT']['VALUE']) > 0) {
                        $arFields['PROPERTY_FOR_SPOT_PAYMENT_VALUE'] = $arProperties['FOR_SPOT_PAYMENT']['VALUE'];
                    }
                    $arFields['PROPERTY_TO_PAY_FROM1_VALUE'] = $arProperties['TO_PAY_FROM1']['VALUE'];
                    $arFields['PROPERTY_TO_PAY_TO1_VALUE'] = $arProperties['TO_PAY_TO1']['VALUE'];
                    $arFields['PROPERTY_TO_PAY_FROM2_VALUE'] = $arProperties['TO_PAY_FROM2']['VALUE'];
                    $arFields['PROPERTY_TO_PAY_TO2_VALUE'] = $arProperties['TO_PAY_TO2']['VALUE'];
                    $arFields['PROPERTY_TO_PAY_FROM3_VALUE'] = $arProperties['TO_PAY_FROM3']['VALUE'];
                    $arFields['PROPERTY_TO_PAY_TO3_VALUE'] = $arProperties['TO_PAY_TO3']['VALUE'];
                    if (strlen($arProperties['DEPARTURE_EXC_TEXT' . POSTFIX_PROPERTY]['VALUE']) > 0) {
                        $arFields['PROPERTY_DEPARTURE_EXC_TEXT' . POSTFIX_PROPERTY . '_VALUE'] = $arProperties['DEPARTURE_EXC_TEXT' . POSTFIX_PROPERTY]['VALUE'];
                    }

                    $arFields['CALC_BY_DAY'] = 0;
                    if ($arProperties['CALC_BY_DAY']['VALUE'] == 'Y') {
                        $arFields['CALC_BY_DAY'] = 1;
                    }

                    $arFields['IS_TRAVELLINE'] = 0;
                    if ($arProperties['IS_TRAVELLINE']['VALUE'] == 'Y') {
                        $arFields['IS_TRAVELLINE'] = 1;
                    }

                    if ($arProperties['USER']['VALUE'] > 0) {
                        $arFields['PARENT_USER'] = $arProperties['USER']['VALUE'];
                    } elseif ($arProperties['USER_ID']['VALUE'] > 0) {
                        $arFields['PARENT_USER'] = $arProperties['USER_ID']['VALUE'];
                    }

                    if (LANGUAGE_ID != "ru") {

                        $arFields["NAME"] = $arProperties["NAME" . POSTFIX_PROPERTY]['VALUE'];
                    }

                    $this->arResult["PARENT_ELEMENTS"][$fields["type"]][$arFields["ID"]] = $arFields;
                }
                $this->arResult["SERVICES"][$fields["type"]][$fields["service_id"]] = $arService[0];
            }
        }
    }

    /**
     * Устанваливает $arResult для размещений
     * @param array $fields
     */
    protected function _setArResultForPlacements(array $fields) {
        $this->_setArResultFor($fields);
    }

    /**
     * Устанваливает $arResult для санаториев
     * @param array $fields
     */
    protected function _setArResultForSanatorium(array $fields) {
        $this->_setArResultForPlacements($fields);
    }

    /**
     * Устанваливает $arResult для экскурсии
     * @param array $fields
     */
    protected function _setArResultForExcursions(array $fields) {
        $this->_setArResultFor($fields);
    }

    /**
     * Устанваливает $arResult для туров
     * @param array $fields
     */
    protected function _setArResultForExcursionstours(array $fields) {
        $this->_setArResultFor($fields);
    }

    /**
     * Устанваливает $arResult для трансферов
     * @param array $fields
     */
    protected function _setArResultForTransfers(array $fields) {
        $this->arParams["NEED_TRANSFER_COMMENT"] = true;
        if (!$this->arResult["RATES"]["transfers"][$fields["rate_id"]]) {
            $arRate = \travelsoft\booking\datastores\TransfersRatesDataStore::get(array(
                        "filter" => array("ID" => $fields["rate_id"]),
                        "select" => array("ID", "UF_CLASS_AUTO")
            ));
            if ($arRate[0]["ID"] > 0) {
                if (!$this->arResult["CLASS_AUTO"][$arRate[0]["UF_CLASS_AUTO"]]) {
                    $arClassAuto = \travelsoft\booking\datastores\ClassAutoDataStore::get(array(
                                "filter" => array("ID" => $arRate[0]["UF_CLASS_AUTO"]),
                                "select" => array("ID", "UF_NAME" . POSTFIX_PROPERTY, "UF_AUTO" . POSTFIX_PROPERTY, "UF_PICTURES", "UF_BAGGAGE", "UF_CAPACITY")
                    ));
                    if ($arClassAuto[0]["ID"] > 0) {
                        $this->arResult["CLASS_AUTO"][$arClassAuto[0]["ID"]] = $arClassAuto[0];
                    }
                }
                $this->arResult["RATES"][$fields["type"]][$fields["rate_id"]] = $arRate[0];
                $this->arResult["RATES"][$fields["type"]][$fields["rate_id"]]["UF_NAME"] = $this->arResult["CLASS_AUTO"][$arRate[0]["UF_CLASS_AUTO"]]["UF_NAME" . POSTFIX_PROPERTY];
                $this->arResult["RATES"][$fields["type"]][$fields["rate_id"]]["DESCRIPTION"] = Loc::getMessage("CLASS_AUTO_DESCRIPTION", array(
                            "#AUTO#" => $this->arResult["CLASS_AUTO"][$arRate[0]["UF_CLASS_AUTO"]]["UF_AUTO" . POSTFIX_PROPERTY],
                            "#CAPACITY#" => $this->arResult["CLASS_AUTO"][$arRate[0]["UF_CLASS_AUTO"]]["UF_CAPACITY"],
                            "#BAGAGGE#" => $this->arResult["CLASS_AUTO"][$arRate[0]["UF_CLASS_AUTO"]]["UF_BAGGAGE"]
                ));
            }
        }
        if (!$this->arResult["SERVICES"][$fields["type"]][$fields["service_id"]]) {
            $arService = U::getServiceById($fields["service_id"]);
            if ($arService[0]["ID"] > 0) {
                $arService[0]["PICTURE"] = $this->_getResizeImage($this->arResult["CLASS_AUTO"][$this->arResult["RATES"][$fields["type"]][$fields["rate_id"]]["UF_CLASS_AUTO"]]["UF_PICTURES"]);
                if (!$this->arResult["PARENT_ELEMENTS"][$fields["type"]][$arService[0]["UF_IBLOCK_ELEMENT_ID"]]) {
                    $arTransfer = \travelsoft\booking\datastores\TransfersDataStore::get(array(
                                "filter" => array("ID" => $arService[0]["UF_IBLOCK_ELEMENT_ID"])
                    ));
                    $pointA = \CIBlockElement::GetByID($fields["point_A"])->GetNextElement();
                    $pointB = \CIBlockElement::GetByID($fields["point_B"])->GetNextElement();
                    $arFieldsPointA = $pointA->GetFields();
                    $arPropertiesPointA = $pointA->GetProperties();
                    $arFieldsPointB = $pointB->GetFields();
                    $arPropertiesPointB = $pointB->GetProperties();
                    $pointAName = $arFieldsPointA["NAME"];
                    $pointBName = $arFieldsPointB["NAME"];
                    if (LANGUAGE_ID != "ru") {
                        $pointAName = $arPropertiesPointA["NAME" . POSTFIX_PROPERTY]["VALUE"];
                        $pointBName = $arPropertiesPointB["NAME" . POSTFIX_PROPERTY]["VALUE"];
                    }
                    $serviceName = $pointAName . " - " . $pointBName;
                    if ($fields["date_to"] && $fields['roundtrip']) {
                        $serviceName .= " - " . $pointAName;
                    }
                    if ($arTransfer[0]["ID"]) {
                        $this->arResult["PARENT_ELEMENTS"][$fields["type"]][$arService[0]["UF_IBLOCK_ELEMENT_ID"]] = $arTransfer[0];
                    }
                }

                $this->arResult["SERVICES"][$fields["type"]][$fields["service_id"]] = $arService[0];
                $this->arResult["SERVICES"][$fields["type"]][$fields["service_id"]]["UF_NAME"] = $serviceName;
                $this->arResult["SERVICES"][$fields["type"]][$fields["service_id"]]["POINT_A_NAME"] = $pointAName;
                $this->arResult["SERVICES"][$fields["type"]][$fields["service_id"]]["POINT_A_COUNTRY_ID"] = $arPropertiesPointA["COUNTRY"]["VALUE"];
                $this->arResult["SERVICES"][$fields["type"]][$fields["service_id"]]["POINT_A_ID"] = $arFieldsPointA["ID"];
            }
        }
    }
    
    private function __check_basket_by_quotas() {
        
        $arBasket = [];
        while ($basketItem = $this->arResult["BASKET"]->fetch()) {
            $arItem = $basketItem["item"]->getPropertiesLikeArray();
            $method_name = "__check_basket_by_quotas_for_" . $arItem["type"];
            $arBasket[] = method_exists($this, $method_name) ? $this->$method_name($arItem) : $arItem;
        }
        $change_blocked = $this->arResult["BASKET"]->_change_blocked;
        $this->arResult["BASKET"]->reset($arBasket);
        $this->arResult["BASKET"]->_change_blocked = $change_blocked;
    }
    
    private function __check_basket_by_quotas_for_placements ($arFields) {
        
        static $need_count_of_services = [];
        
        if (!isset($need_count_of_services[$arFields["service_id"]])) {
            $need_count_of_services[$arFields["service_id"]] = 0;
        } 
        
        $need_count_of_services[$arFields["service_id"]] += $this->__getIncreasePlacementsNumberOfSold($arFields);
        $arr_quotas = \travelsoft\booking\datastores\QuotasDataStore::get([
            "filter" => [
                "><UF_DATE" => [$arFields["date_from"], $arFields["date_to"] - 86400],
                "UF_SERVICE_ID" => $arFields["service_id"]
            ],
            "order" => ["UF_DATE" => "ASC"]
        ]);
        
        if (!empty($arr_quotas)) {
            
           foreach ($arr_quotas as $arr_quota) {
               $on_sale = $arr_quota["UF_QUOTE"] - $arr_quota["UF_SOLD_NUMBER"];
               if ($on_sale <= 0) {
                   $on_sale = 0;
               }
               
               if ($need_count_of_services[$arFields["service_id"]] > $on_sale) {
                   $arFields["can_buy"] = false;
               }
           } 
            
        } else {
            $arFields["can_buy"] = false;
        }
        
        return $arFields;
    }
    
    private function __check_basket_by_quotas_for_sanatorium ($arFields) {
        
        return $this->__check_basket_by_quotas_for_placements($arFields);
    }
    
    private function __check_basket_by_quotas_for_excursions ($arFields) {
        static $need_count_of_services = [];
        
        if (!isset($need_count_of_services[$arFields["service_id"]])) {
            $need_count_of_services[$arFields["service_id"]] = 0;
        } 
        
        $need_count_of_services[$arFields["service_id"]] += $arFields["adults"] + $arFields["children"];
        $arr_quotas = \travelsoft\booking\datastores\QuotasDataStore::get([
            "filter" => [
                "UF_DATE" => $arFields["date_from"],
                "UF_SERVICE_ID" => $arFields["service_id"]
            ],
            "order" => ["UF_DATE" => "ASC"]
        ]);
        
        if (!empty($arr_quotas)) {
            
           foreach ($arr_quotas as $arr_quota) {
               $on_sale = $arr_quota["UF_QUOTE"] - $arr_quota["UF_SOLD_NUMBER"];
               if ($on_sale <= 0) {
                   $on_sale = 0;
               }
               
               if ($need_count_of_services[$arFields["service_id"]] > $on_sale) {
                   $arFields["can_buy"] = false;
               }
           } 
            
        } else {
            $arFields["can_buy"] = false;
        }
        
        return $arFields;
    }
    
    private function __check_basket_by_quotas_for_excursionstour ($arFields) {
        static $need_count_of_services = [];
        
        if (!isset($need_count_of_services[$arFields["service_id"]])) {
            $need_count_of_services[$arFields["service_id"]] = 0;
        } 
        
        $need_count_of_services[$arFields["service_id"]]++;
        $arr_quotas = \travelsoft\booking\datastores\QuotasDataStore::get([
            "filter" => [
                "UF_DATE" => $arFields["date_from"],
                "UF_SERVICE_ID" => $arFields["service_id"]
            ],
            "order" => ["UF_DATE" => "ASC"]
        ]);
        
        if (!empty($arr_quotas)) {
            
           foreach ($arr_quotas as $arr_quota) {
               $on_sale = $arr_quota["UF_QUOTE"] - $arr_quota["UF_SOLD_NUMBER"];
               if ($on_sale <= 0) {
                   $on_sale = 0;
               }
               
               if ($need_count_of_services[$arFields["service_id"]] > $on_sale) {
                   $arFields["can_buy"] = false;
               }
           } 
            
        } else {
            $arFields["can_buy"] = false;
        }
        
        return $arFields;
    }
    
    public function executeComponent() {

        try {
            $this->__include_modules();

            $this->_setArResult((new \travelsoft\booking\Basket)->_change_blocked);

            $this->__set_input_check_patterns();

            $this->__set_male_values();
            
            $this->__check_basket_by_quotas();
            
            $this->__processing_request();

            $this->__set_user_info();

            $this->IncludeComponentTemplate();

            $this->__include_js();
        } catch (\Exception $e) {

            ShowError($e->getMessage());
        }
    }

}
