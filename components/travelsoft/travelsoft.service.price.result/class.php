<?php

/**
 * travelsoft search service price result
 */
use \travelsoft\booking\datastores\ServicesDataStore;
use \travelsoft\booking\datastores\QuotasDataStore;
use \travelsoft\booking\datastores\RatesDataStore;
use \travelsoft\booking\datastores\ClassAutoDataStore;
use \travelsoft\booking\Encoder;
use \travelsoft\booking\Utils as U;
use \Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

class TravelsoftServicePriceResult extends CBitrixComponent {

    /**
     * @var \travalsoft\Currency
     */
    protected $_currency = null;

    /**
     * @var string
     */
    protected $_method_part = "";

    /**
     * @var string
     */
    protected $_request_class = "";

    /**
     * @var \Bitrix\Main\Data\Cache
     */
    protected $_cache = null;

    /**
     * @var array
     */
    public $request = null;

    protected function _getIdByCode(array $codes) {

        Bitrix\Main\Loader::includeModule("iblock");

        $ID = NULL;

        if ($this->_cache->initCache($this->arParams["CACHE_TIME"], "get_id_by_codes_" . serialize($codes), U::getCacheRootDirForSearchOffers() . "/function_getIdByCodes")) {

            $ID = $this->_cache->getVars();
        } elseif ($this->_cache->startDataCache()) {

            $dbRes = CIBlockElement::GetList(
                            false, array("CODE" => $codes), false, false, array("ID")
            );

            while ($arElement = $dbRes->Fetch()) {
                $ID[] = $arElement["ID"];
            }

            if ($ID) {
                $this->_cache->endDataCache($ID);
            } else {
                $this->_cache->abortDataCache();
            }
        }

        return $ID ? $ID : NULL;
    }

    /**
     * Общая логика для всех типов объектов подготовки результата расчета
     */
    protected function _setCommonResult() {

        $pipeMethod = "_pipe" . $this->_method_part . "CalculationData";
        $cancellationPolicy = "_getCancellationPolicyFor" . $this->_method_part;
        if (method_exists($this, $pipeMethod)) {
            $this->arResult["CALCULATION"] = $this->$pipeMethod($this->arResult["CALCULATION"]);
        }

        $sorting_method = '_' . $this->arParams["TYPE"] . 'Sorting';
        $method = "_get" . $this->_method_part . "Result";

        foreach ($this->arResult["CALCULATION"] as $object_id => $arData) {
            $this->arResult['CANCELLATION_POLICY'][$object_id] = $this->$cancellationPolicy((int) $object_id);
            $this->$sorting_method($arData);

            if (($HTML_DATA = $this->$method($arData))) {
                $this->arResult["HTML_DATA"][$object_id] = $HTML_DATA;
            }
        }
    }

    /**
     * @return boolean
     */
    protected function _setCommonResultIfCalculationsExists() {

        $this->arResult["LOCAL_REQUEST"] = $this->arResult["REQUEST"]->getPropertiesLikeArray();
        if ($this->arResult["CALCULATION"]) {

            $this->_setCommonResult();
            return true;
        }

        return false;
    }

    /**
     * результат поиска
     * @return array
     */
    protected function _setResult() {

        $this->_method_part = ucfirst($this->arParams["TYPE"]);
        $this->_request_class = "\\travelsoft\\booking\\" . $this->arParams['TYPE'] . "\\Request";
        $this->_currency = U::getCurrentCurrency();
        $setJsMethod = "_set" . $this->_method_part . "JsSettings";
        $this->$setJsMethod();

        switch ($this->arParams["TYPE"]) {

            case "placements":
                if (!$this->_setCommonResultIfCalculationsExists()) {
                    //if ($GLOBALS["USER"]->IsAdmin()) {
                    if (!$this->_settlingByRooms()) {
                        //$this->_setSimilarOffers();
                    }
                    /* } else {
                      $this->_setSimilarOffers();
                      } */
                }
                break;

            case "sanatorium":

                if (isset($this->arParams["__BOOKING_REQUEST"]["specifying"]) && !empty($this->arParams["__BOOKING_REQUEST"]["specifying"]["rates"])) {

                    $this->_setSpecifyingSanatoriumRequest();
                } elseif (!$this->_setCommonResultIfCalculationsExists()) {

                    //$this->_setSimilarOffers();
                }
                break;

            default :
                $this->_setCommonResultIfCalculationsExists();
        }
    }

    protected function _setSpecifyingSanatoriumRequest() {

        // reset common calculations
        $this->arResult["CALCULATION"] = array();

        $arr_source_request = $this->arResult["REQUEST"]->getPropertiesLikeArray();

        $one_man_in_source_request = $arr_source_request['adults'] == 1 && $arr_source_request["children"] == 0;

        unset($arr_source_request['citizen_price']);

        $arr_rates_group = array();

        foreach ($this->arParams["__BOOKING_REQUEST"]["specifying"]["rates"] as $people_type => $arr_rates_id) {

            switch ($people_type) {
                case "adults":
                    foreach ($arr_rates_id as $i => $rate_id) {

                        $arr_rates_group[$rate_id][$people_type] ++;
                    }
                    break;
                case "children":

                    foreach ($arr_rates_id as $i => $rate_id) {

                        $arr_rates_group[$rate_id][$people_type]["count"] ++;
                        $arr_rates_group[$rate_id][$people_type]["age"][] = $arr_source_request["children_age"][$i];
                    }
                    break;
            }
        }

        $arr_results_calculations = array();

        $arr_request = $arr_source_request;

        $sfilter["UF_IBLOCK_ELEMENT_ID"] = $arr_source_request['id'];

        $people = null;
        if ($arr_source_request['adults']) {
            $people = $arr_source_request['adults'];
            $sfilter[">=UF_ADULTS"] = $arr_source_request['adults'];
        }
        if ($arr_source_request['children']) {
            $people += $arr_source_request['children'];
            $sfilter[">=UF_CHILDREN"] = $arr_source_request['children'];
        }
        if ($people) {
            $sfilter[">=UF_PEOPLE"] = $people;
        }

        $sfilter[0] = array("LOGIC" => "OR", array("UF_MIN_PEOPLE" => false), array("<=UF_MIN_PEOPLE" => $people));

        $services = (new ServicesDataStore(array("filter" => $sfilter)))->fetch(['ID']);

        unset($arr_source_request['id']);

        foreach ($services as $id => $service) {

            $arSourceAllocate = \travelsoft\booking\abstractions\commons\PriceCalculator::_allocate($service[0], $arr_source_request['adults'], $arr_source_request['children'], $arr_source_request['children_age']);

            $stop = false;
            $arr_local_result_calculations = [];
            foreach ($arr_rates_group as $rate_id => $arr_data) {

                // проверка принадлежности номера тарифу
                $rate = RatesDataStore::get([
                            'filter' => [array("LOGIC" => "OR", array("UF_SERVICES_ID" => false), array("UF_SERVICES_ID" => $id)), 'ID' => $rate_id]
                ]);

                if (empty($rate)) {

                    $stop = true;
                    break;
                }

                $arr_request["adults"] = 0;
                if (isset($arr_data["adults"])) {
                    $arr_request["adults"] = $arr_data["adults"];
                }

                $arr_request["children"] = 0;
                $arr_request["children_age"] = array();
                if (isset($arr_data["children"])) {
                    $arr_request["children"] = $arr_data["children"]["count"];
                    $arr_request["children_age"] = $arr_data["children"]["age"];
                }

                $arr_request["rate_id"] = $rate_id;

                $arSourceAllocate = travelsoft\booking\Utils::getChangedSourceAllocate($arSourceAllocate, $arCurrentAllocate = travelsoft\booking\Utils::getCurrentAllocate($arSourceAllocate, $arr_request));

                $arr_request['service_id'] = $id;

                $arr_local_result_calculation = \travelsoft\booking\Utils::recalculateOnlyPriceType($arr_request, $arCurrentAllocate, $one_man_in_source_request, "\\travelsoft\\booking\\sanatorium\\PriceCalculator");

                if (empty($arr_local_result_calculation)) {
                    $stop = true;
                    break;
                }

                $arr_local_result_calculation[$id][$rate_id]['ALLOCATE'] = $arCurrentAllocate;
                $arr_local_result_calculation[$id][$rate_id]['REQUEST'] = $arr_request;

                $arr_local_result_calculations[$rate_id] = $arr_local_result_calculation[$id][$rate_id];
            }

            if ($stop) {
                continue;
            }

            $arr_results_calculations[$id] = $arr_local_result_calculations;
        }

        $this->_setSanatoriumSpecifyingOffers($arr_results_calculations);

        return !empty($this->arResult["SETTLING_BY"]);
    }

    protected function _setSanatoriumSpecifyingOffers($arr_results_calculations) {

        $sorting_method = '_' . $this->arParams["TYPE"] . 'Sorting';
        $method = '_get' . $this->_method_part . "Result";

        $tmp_html_data = array();

        $this->$sorting_method($arr_results_calculations);
        foreach ($arr_results_calculations as $service_id => $arr_calc_grouped_by_rates) {
            foreach ($arr_calc_grouped_by_rates as $rate_id => $arr_calc) {
                $this->arResult["LOCAL_REQUEST"] = $arr_calc['REQUEST'];
                if (($HTML_DATA = $this->$method([$service_id => [$rate_id => $arr_calc]]))) {
                    @$tmp_html_data[$service_id][] = $HTML_DATA;
                }
            }
        }

        foreach ($tmp_html_data as $service_id => $sub_tmp_html_data) {
            $this->arResult["SETTLING_BY"][$this->arResult["REQUEST"]->id[0]][] = $this->_prepareSettlingByHtmlData($sub_tmp_html_data);
        }
    }

    protected function _setSpecifyingFormData() {

        if ($this->_cache->initCache($this->arParams["CACHE_TIME"], serialize(array($this->arResult["REQUEST"]->getPropertiesLikeArray(), POSTFIX_PROPERTY, SITE_ID, U::getCurrentCurrency()['iso'], $this->arParams["__BOOKING_REQUEST"])), U::getCacheRootDirForSearchOffers() . "/specifying_form_data_for_" . $this->arParams["TYPE"])) {
            $this->arResult["SPECIFYING_DATA"] = $this->_cache->getVars();
        } elseif ($this->_cache->startDataCache()) {

            $services_id = \array_filter(array_keys((new ServicesDataStore(array(
                        "filter" => array(
                            "UF_IBLOCK_ELEMENT_ID" => $this->arResult["REQUEST"]->id
                        ),
                        "select" => array("ID")
                            )))->fetch(array("ID"))), function ($item) {
                return $item > 0;
            });


            $arr_rates = RatesDataStore::get(array(
                        "filter" => array("UF_SERVICES_ID" => is_array($services_id) && !empty($services_id) ? $services_id : -1)
            ));

            if (count($arr_rates) > 1) {

                $ids = array();
                foreach ($arr_rates as $arr_rate) {
                    $ids[] = $arr_rate["ID"];
                }

                $arr_ = (new travelsoft\booking\datastores\PTRatesDataStore(array(
                    "filter" => array("UF_RATE_CATEGORY_ID" => $ids), "select" => array("ID")
                        )))->fetch(array("ID"));

                $arr_ptrates_id = array_keys($arr_);

                if (!empty($arr_ptrates_id)) {

                    $oPrices = new travelsoft\booking\datastores\PricesDataStore(array(
                        "filter" => array("UF_PTPR_ID" => $arr_ptrates_id, ">UF_GROSS" => 0.0001, "><UF_DATE" => array($this->arResult["REQUEST"]->date_from, $this->arResult["REQUEST"]->date_to)
                    )));

                    if (!empty($oPrices->fetch(array("ID")))) {

                        $this->arResult["SPECIFYING_DATA"] = array(
                            "RATES" => array(),
                            "RATES_RENDER_DATA" => [],
                            "PEOPLE" => array(
                                "ADULTS" => $this->arResult["REQUEST"]->adults,
                                "CHILDREN" => $this->arResult["REQUEST"]->children_age
                            )
                        );

                        $arr_rates_id = array_keys((new travelsoft\booking\datastores\PTRatesDataStore(array(
                            "filter" => array(
                                "ID" => array_keys($oPrices->fetch(array("UF_PTPR_ID")))
                            ),
                            "select" => array("UF_RATE_CATEGORY_ID", "ID")
                                )))->fetch(array("UF_RATE_CATEGORY_ID", "ID")));

                        if (!empty($arr_rates_id)) {
                            $existsUsers = [];
                            $notExistsUsers = [];
                            foreach ($arr_rates as $arr_rate) {
                                if (in_array($arr_rate["ID"], $arr_rates_id)) {
                                    $user_id = $arr_rate["UF_USER_ID"][0];
                                    if ($user_id > 0) {

                                        if (in_array($user_id, $notExistsUsers)) {
                                            continue;
                                        }
                                        if (!in_array($user_id, $existsUsers)) {

                                            $user = $GLOBALS["USER"]->GetByID($user_id)->Fetch();
                                            if (@$user["ID"] > 0) {
                                                $existsUsers[] = $user["ID"];
                                            } else {
                                                $notExistsUsers[] = $user["ID"];
                                                continue;
                                            }
                                        }
                                    }

                                    $this->arResult["SPECIFYING_DATA"]["RATES"][$arr_rate["ID"]] = $arr_rate;
                                    if (
                                            ($arr_rate["UF_BR_PRICES"] == 0 && $arr_rate["UF_RF_PRICES"] == 0 && $arr_rate["UF_EU_PRICES"] == 0) ||
                                            ($arr_rate["UF_BR_PRICES"] == 1 && $arr_rate["UF_RF_PRICES"] == 1 && $arr_rate["UF_EU_PRICES"] == 1)
                                    ) {
                                        $this->arResult["SPECIFYING_DATA"]["RATES_RENDER_DATA"]["UF_RF_PRICES"][$arr_rate["ID"]] = GetMessage('RF').', '.$arr_rate["UF_NAME" . POSTFIX_PROPERTY];
                                        $this->arResult["SPECIFYING_DATA"]["RATES_RENDER_DATA"]["UF_BR_PRICES"][$arr_rate["ID"]] = GetMessage('BR').', '.$arr_rate["UF_NAME" . POSTFIX_PROPERTY];
                                        $this->arResult["SPECIFYING_DATA"]["RATES_RENDER_DATA"]["UF_EU_PRICES"][$arr_rate["ID"]] = GetMessage('EU').', '.$arr_rate["UF_NAME" . POSTFIX_PROPERTY];
                                    } elseif ($arr_rate["UF_BR_PRICES"] == 1) {
                                        $this->arResult["SPECIFYING_DATA"]["RATES_RENDER_DATA"]["UF_BR_PRICES"][$arr_rate["ID"]] = GetMessage('BR').', '.$arr_rate["UF_NAME" . POSTFIX_PROPERTY];
                                    } elseif ($arr_rate["UF_RF_PRICES"] == 1) {
                                        $this->arResult["SPECIFYING_DATA"]["RATES_RENDER_DATA"]["UF_RF_PRICES"][$arr_rate["ID"]] = GetMessage('RF').', '.$arr_rate["UF_NAME" . POSTFIX_PROPERTY];
                                    } elseif ($arr_rate["UF_EU_PRICES"] == 1) {
                                        $this->arResult["SPECIFYING_DATA"]["RATES_RENDER_DATA"]["UF_EU_PRICES"][$arr_rate["ID"]] = GetMessage('EU').', '.$arr_rate["UF_NAME" . POSTFIX_PROPERTY];
                                    }
                                }
                            }
                        }
                    }
                }
            }

            if (isset($this->arResult["SPECIFYING_DATA"]) && !empty($this->arResult["SPECIFYING_DATA"])) {
                $this->_cache->endDataCache($this->arResult["SPECIFYING_DATA"]);
            } else {
                $this->_cache->abortDataCache();
            }
        }
    }

    /**
     * Расположение в 2 или 3 номерах
     * @return boolean
     */
    protected function _settlingByRooms() {

        if (!$this->_settlingByTwoRooms()) {
            return $this->_settlingByThreeRooms();
        } else {

            return true;
        }
    }

    /**
     * Расположение в двух номерах
     * @return boolean
     */
    protected function _settlingByTwoRooms() {

        $this->arResult["SETTLING_BY"] = array();

        if ($this->arResult["REQUEST"]->adults >= 2) {

            $arr_people = $this->_makePeopleArray();

            $part_people_count = count($arr_people) / 2;

            $arr_first_room_count_people = floor($part_people_count);

            $arr_grouped_people_by_room = array(
                array_slice($arr_people, 0, $arr_first_room_count_people),
                array_slice($arr_people, $arr_first_room_count_people)
            );

            $arr_result_calculations = array();

            $arr_request = $this->arResult['REQUEST']->getPropertiesLikeArray();

            foreach ($arr_grouped_people_by_room as $arr_group_people) {

                $arr_request = $this->_setLocalRequest($arr_request, $arr_group_people);

                $local_result_calculation = U::getPriceCalculation(array(
                            "request" => new $this->_request_class($arr_request),
                            "type" => $this->arParams["TYPE"],
                            "mp" => false
                ));

                if (!empty($local_result_calculation)) {
                    $arr_result_calculations[] = array("local_request" => $arr_request, "data" => $local_result_calculation);
                } else {
                    return false;
                }
            }

            $tmp_html_data_first_group = $this->_getTmpHtmlDataForSettlingByRooms($arr_result_calculations[0]);
            $tmp_html_data_second_group = $this->_getTmpHtmlDataForSettlingByRooms($arr_result_calculations[1]);

            foreach ($tmp_html_data_first_group as $object_id => $html_data_first_group) {
                foreach ($html_data_first_group as $first_group_room_data_html) {
                    foreach ($tmp_html_data_second_group[$object_id] as $html_data_second_group) {
                        $this->arResult['SETTLING_BY'][$object_id][] = [current($first_group_room_data_html), current($html_data_second_group)];
                    }
                }
            }
        }

        $return = !empty($this->arResult["SETTLING_BY"]);

        if ($return) {
            $this->arResult["dynamic_calculation_placements_add2cart"] = [
                'initialization' => true
            ];
        }


        return $return;
    }

    protected function _getTmpHtmlDataForSettlingByRooms($data) {
        $cancellationPolicy = "_getCancellationPolicyFor" . $this->_method_part;

        $method = "_get" . $this->_method_part . "Result";
        $sorting_method = '_' . $this->arParams["TYPE"] . 'Sorting';
        $tmp_data = [];
        foreach ($data['data'] as $object_id => $arr_grouped_by_rooms_data) {
            if (!isset($this->arResult['CANCELLATION_POLICY'][$object_id])) {
                $this->arResult['CANCELLATION_POLICY'][$object_id] = $this->$cancellationPolicy((int) $object_id);
            }
            $tmp_data[$object_id] = [];
            foreach ($arr_grouped_by_rooms_data as $room_id => $rooms_data) {
                $tmp = [$room_id => $rooms_data];
                $this->$sorting_method($tmp);
                $this->arResult["LOCAL_REQUEST"] = $data['local_request'];
                if (($HTML_DATA = $this->$method($tmp, $data['local_request']))) {

                    $HTML_DATA['MAIN_BLOCK']['request'] = $this->arResult["LOCAL_REQUEST"];
                    $tmp_data[$object_id][] = $HTML_DATA;
                }
            }
        }
        return $tmp_data;
    }

    /**
     * Расположение в трех номерах
     * @return boolean
     */
    protected function _settlingByThreeRooms() {

        $this->arResult["SETTLING_BY"] = array();

        if ($this->arResult["REQUEST"]->adults >= 3) {

            $arr_people = $this->_makePeopleArray();

            $part_people_count = count($arr_people) / 3;

            $arr_first_room_count_people = floor($part_people_count);

            $arr_grouped_people_by_room = array(
                array_slice($arr_people, 0, $arr_first_room_count_people),
                array_slice($arr_people, $arr_first_room_count_people, $arr_first_room_count_people),
                array_slice($arr_people, 2 * $arr_first_room_count_people),
            );

            $arr_result_calculations = array();

            $arr_request = $this->arResult['REQUEST']->getPropertiesLikeArray();

            foreach ($arr_grouped_people_by_room as $arr_group_people) {

                $arr_request = $this->_setLocalRequest($arr_request, $arr_group_people);

                $local_result_calculation = U::getPriceCalculation(array(
                            "request" => new $this->_request_class($arr_request),
                            "type" => $this->arParams["TYPE"],
                            "mp" => false
                ));

                if (!empty($local_result_calculation)) {
                    $arr_result_calculations[] = array("local_request" => $arr_request, "data" => $local_result_calculation);
                } else {
                    return false;
                }
            }

            $tmp_html_data_first_group = $this->_getTmpHtmlDataForSettlingByRooms($arr_result_calculations[0]);
            $tmp_html_data_second_group = $this->_getTmpHtmlDataForSettlingByRooms($arr_result_calculations[1]);
            $tmp_html_data_third_group = $this->_getTmpHtmlDataForSettlingByRooms($arr_result_calculations[2]);

            foreach ($tmp_html_data_first_group as $object_id => $html_data_first_group) {
                foreach ($html_data_first_group as $first_group_room_data_html) {
                    foreach ($tmp_html_data_second_group[$object_id] as $html_data_second_group) {
                        foreach ($tmp_html_data_third_group[$object_id] as $html_data_third_group) {
                            $this->arResult['SETTLING_BY'][$object_id][] = [current($first_group_room_data_html), current($html_data_second_group), current($html_data_third_group)];
                        }
                    }
                }
            }
        }

        $return = !empty($this->arResult["SETTLING_BY"]);

        if ($return) {
            $this->arResult["dynamic_calculation_placements_add2cart"] = [
                'initialization' => true
            ];
        }


        return $return;
    }

    /**
     * @return $this
     */
    protected function _unsetSettlingByDoubles() {

        $arr_doubles = array();

        foreach ($this->arResult["SETTLING_BY"] as $object_id => $arr_data) {
            foreach ($arr_data as $k => $v) {

                $arr_data_for_check = array_merge(array_keys($v["DETAILS"]), array($v["TOTAL_PRICE"]));

                $double_exists = false;
                foreach ($arr_doubles as $arr_double) {

                    if (empty(array_diff($arr_double, $arr_data_for_check))) {
                        $double_exists = true;
                        break;
                    }
                }

                if ($double_exists) {

                    unset($this->arResult["SETTLING_BY"][$object_id][$k]);
                } else {

                    $arr_doubles[] = $arr_data_for_check;
                }
            }
        }

        return $this;
    }

    /**
     * @return $this
     */
    protected function _sortingSettlingBy() {
        foreach (array_keys($this->arResult["SETTLING_BY"]) as $object_id) {

            usort($this->arResult["SETTLING_BY"][$object_id], function ($a, $b) {
                if ($a["TOTAL_PRICE"] == $b["TOTAL_PRICE"]) {
                    return 0;
                }
                return ($a["TOTAL_PRICE"] < $b["TOTAL_PRICE"]) ? -1 : 1;
            });
        }
        return $this;
    }

    /**
     * @param int $count_variants
     * @return $this
     */
    protected function _setSettlingByCountVariants(int $count_variants = 3) {
        foreach (array_keys($this->arResult["SETTLING_BY"]) as $object_id) {
            $this->arResult["SETTLING_BY"][$object_id] = array_slice($this->arResult["SETTLING_BY"][$object_id], 0, $count_variants);
        }
        return $this;
    }

    /**
     * @param array $arr_data
     * @param int $variant_count
     * @return array
     */
    protected function _prepareSettlingByHtmlData(array $arr_data) {

        $arr_add2cart = array();

        $arr_result = array(
            "DETAILS" => array(),
            "ADD2CART" => "",
            "TOTAL_PRICE" => 0.00
        );

        foreach ($arr_data as $arr_sub_data) {

            foreach ($arr_sub_data as $arr_offer) {
                if (!isset($arr_result["DETAILS"][$arr_offer["MAIN_BLOCK"]["ID"]])) {
                    $arr_result["DETAILS"][$arr_offer["MAIN_BLOCK"]["ID"]] = array(
                        "MAIN_BLOCK" => array(
                            "ID" => $arr_offer["MAIN_BLOCK"]["ID"],
                            "FOR_SALE" => $arr_offer["MAIN_BLOCK"]["FOR_SALE"],
                            "IMAGE_ID" => $arr_offer["MAIN_BLOCK"]["IMAGE_ID"],
                            'PICTURES' => $arr_offer["MAIN_BLOCK"]["UF_PICTURES"],
                            "TITLE" => $arr_offer["MAIN_BLOCK"]["TITLE"],
                            "DESCRIPTION" => $arr_offer["MAIN_BLOCK"]["DESCRIPTION"],
                            "UF_PEOPLE" => $arr_offer["MAIN_BLOCK"]["UF_PEOPLE"],                            
                        ),
                        "ROWS" => array()
                    );
                }

                foreach ($arr_offer["ROWS"] as $rate_id => $arr_rate) {
                    if (!isset($arr_result["DETAILS"][$arr_offer["MAIN_BLOCK"]["ID"]]["ROWS"][$rate_id])) {
                        $arr_result["DETAILS"][$arr_offer["MAIN_BLOCK"]["ID"]]["ROWS"][$rate_id] = array(
                            "ID" => $arr_rate["ID"],
                            "TITLE" => $arr_rate["TITLE"],
                            "PRICE" => $arr_rate["~PRICE"],
                            "X" => 1,
                        );
                        if (isset($arr_rate["~DISCOUNT_PRICE"]) && $arr_rate["~DISCOUNT_PRICE"] > 0) {
                            $arr_result["DETAILS"][$arr_offer["MAIN_BLOCK"]["ID"]]["ROWS"][$rate_id]["DISCOUNT_PRICE"] = $arr_rate["~DISCOUNT_PRICE"];
                            $arr_result["TOTAL_PRICE"] += $arr_rate["~DISCOUNT_PRICE"];
                            
                        } else {
                            $arr_result["TOTAL_PRICE"] += $arr_rate["~PRICE"];
                        }
                    } else {
                        if (isset($arr_rate["~DISCOUNT_PRICE"]) && $arr_rate["~DISCOUNT_PRICE"] > 0) {
                            $arr_result["DETAILS"][$arr_offer["MAIN_BLOCK"]["ID"]]["ROWS"][$rate_id]["DISCOUNT_PRICE"] += $arr_rate["~DISCOUNT_PRICE"];
                            $arr_result["TOTAL_PRICE"] += $arr_rate["~DISCOUNT_PRICE"];
                        } else {
                            $arr_result["DETAILS"][$arr_offer["MAIN_BLOCK"]["ID"]]["ROWS"][$rate_id]["PRICE"] += $arr_rate["~PRICE"];
                            $arr_result["TOTAL_PRICE"] += $arr_rate["~PRICE"];
                        }
                        
                        $arr_result["DETAILS"][$arr_offer["MAIN_BLOCK"]["ID"]]["ROWS"][$rate_id]["X"] ++;
                    }
                    $arr_add2cart[] = $arr_rate["ADD2CART"];
                }
            }
        }

        $arr_result["ADD2CART"] = $this->_getMultipleAdd2CartParams($arr_add2cart);

        return $arr_result;
    }

    /**
     * @return array
     */
    protected function _makePeopleArray() {

        $arr_people = array();

        $adults = $this->arResult["REQUEST"]->adults;

        $children = $this->arResult["REQUEST"]->children;

        $children_age = $this->arResult["REQUEST"]->children_age;

        while ($adults > 0 || $children > 0) {

            if ($adults > 0) {

                $arr_people[] = array(
                    "type" => "adult"
                );

                $adults--;
            }

            if ($children > 0) {

                $arr_people[] = array(
                    "type" => "child",
                    "age" => array_pop($children_age)
                );

                $children--;
            }
        }

        return $arr_people;
    }

    /**
     * @param array $arr_request
     * @param array $arr_group_people
     * @return array
     */
    protected function _setLocalRequest($arr_request, $arr_group_people) {
        $arr_request["adults"] = 0;
        $arr_request["children"] = 0;
        $arr_request["children_age"] = array();

        foreach ($arr_group_people as $arr_man) {
            if ($arr_man["type"] === "adult") {
                $arr_request["adults"] ++;
            } else {
                $arr_request["children"] ++;
                $arr_request["children_age"][] = $arr_man["age"];
            }
        }
        return $arr_request;
    }

    /**
     * Поиск похожих предложений по датам
     * @return boolean
     */
    protected function _setSimilarOffers() {

        $duration = ($this->arResult['REQUEST']->date_to - $this->arResult['REQUEST']->date_from) / 86400;

        $arRequest = $this->arResult['REQUEST']->getPropertiesLikeArray();

        $lifePeriods = $this->_getDuration($arRequest);

        $this->arResult['SIMILAR_OFFERS'] = array();

        if (in_array($duration, $lifePeriods)) {
            unset($lifePeriods[array_search($duration, $lifePeriods)]);
        }

        foreach ($lifePeriods as $n) {
            $arSimilarOffer = $this->_getSimilarOffer($arRequest, 0, ($n));
            if ($arSimilarOffer['calculation']) {
                $this->arResult['SIMILAR_OFFERS'][] = $arSimilarOffer;
                break;
            }
        }

        $step_to = 7;
        $_step_to = -7;
        $lifePeriods = $this->_getDurations($arRequest, 1, $step_to);
        if (empty($lifePeriods)) {
            for ($i = 1; $i <= $step_to; $i++) {
                $arSimilarOffer = $this->_getSimilarOffer($arRequest, $i, $duration);


                if ($arSimilarOffer['calculation']) {

                    $this->arResult['SIMILAR_OFFERS'][] = $arSimilarOffer;
                    break;
                }
            }
        } else {
            for ($i = 1; $i <= $step_to; $i++) {
                $arSimilarOffer = array();
                foreach ($lifePeriods[$i] as $n) {

                    $arSimilarOffer = $this->_getSimilarOffer($arRequest, $i, ($n));

                    if ($arSimilarOffer['calculation']) {
                        break;
                    }
                }

                if ($arSimilarOffer['calculation']) {

                    $this->arResult['SIMILAR_OFFERS'][] = $arSimilarOffer;
                    break;
                }
            }
        }

        $lifePeriods = $this->_getDurations($arRequest, $_step_to, -1);
        if (empty($lifePeriods)) {
            for ($i = -1; $i >= $_step_to; $i--) {

                $arSimilarOffer = $this->_getSimilarOffer($arRequest, $i, $duration);


                if ($arSimilarOffer['calculation']) {

                    $this->arResult['SIMILAR_OFFERS'][] = $arSimilarOffer;
                    break;
                }
            }
        } else {
            for ($i = -1; $i >= $_step_to; $i--) {
                $arSimilarOffer = array();
                foreach ($lifePeriods[$i] as $n) {

                    $arSimilarOffer = $this->_getSimilarOffer($arRequest, $i, ($n));

                    if ($arSimilarOffer['calculation']) {
                        break;
                    }
                }

                if ($arSimilarOffer['calculation']) {

                    $this->arResult['SIMILAR_OFFERS'][] = $arSimilarOffer;
                    break;
                }
            }
        }

        return !empty($this->arResult['SIMILAR_OFFERS']);
    }

    /**
     * @param array $request
     * @return array
     */
    protected function _getDuration($request) {
        $arDurations = (new \travelsoft\booking\datastores\PricesDataStore(array(
            "filter" => array("UF_SERVICE_ID" => $this->_getServicesByParentId($request["id"][0]), "UF_DATE" => $request["date_from"])
                )))->getLifePeriods($request["date_from"]);

        return $arDurations;
    }

    /**
     * @param array $request
     * @param int $from
     * @param int $to
     * @return array
     */
    protected function _getDurations($request, $from, $to) {

        $pricesStore = (new \travelsoft\booking\datastores\PricesDataStore(array(
            "filter" => array("UF_SERVICE_ID" => $this->_getServicesByParentId($request["id"][0]), "><UF_DATE" => array($request["date_from"] + (86400 * $from), $request["date_from"] + (86400 * $to)))
        )));

        $lifePeriods = array();
        for ($i = $from; $i <= $to; $i++) {

            $lifePeriods[$i] = $pricesStore->getLifePeriods((string) ($request["date_from"] + (86400 * $i)));
            if (empty($lifePeriods[$i])) {
                unset($lifePeriods[$i]);
            }
        }

        return $lifePeriods;
    }

    protected function _getServicesByParentId($id) {

        static $result = array();

        if (!isset($result[$id])) {
            $result[$id] = array_keys((new \travelsoft\booking\datastores\ServicesDataStore(array(
                "filter" => array("UF_IBLOCK_ELEMENT_ID" => $id), "select" => array("ID")
                    )))->fetch(array("ID")));
        }

        return $result[$id];
    }

    /**
     * @param array $arRequest
     * @param int $nf
     * @param int $nt
     * @return array
     */
    protected function _getSimilarOffer(array $arRequest, int $nf, int $nt) {

        $arRequest['date_from'] += 86400 * $nf;
        $arRequest['date_to'] = $arRequest['date_from'] + (86400 * $nt);

        $reqclass = "\\travelsoft\\booking\\" . $this->arParams['TYPE'] . "\\Request";

        return array(
            'calculation' => current(U::getPriceCalculation(array(
                        "request" => new $reqclass($arRequest),
                        "type" => $this->arParams["TYPE"],
                        "mp" => true
            ))),
            'request_params' => $arRequest,
            'date_from' => date('d.m.Y', $arRequest['date_from']),
            'date_to' => date('d.m.Y', $arRequest['date_to'])
        );
    }

    /**
     * @param array $arData
     */
    protected function _commonSorting(array &$arData) {

        foreach ($arData as &$arSubData) {
            uasort($arSubData, function ($a, $b) {
				if ($a['PRICE'] == $b['PRICE']) {
					return 0;
				}
				return $a['PRICE'] < $b['PRICE'] ? 1 : -1;
            });
        }
    }

    /**
     * @param array $arData
     */
    protected function _excursionsSorting(array &$arData) {
		
		foreach ($arData as &$arSubData) {
			ksort($arSubData);
            foreach ($arSubData as &$arSub2Data) {
                uasort($arSub2Data, function ($a, $b) {
                    if ($a['PRICE'] == $b['PRICE']) {
						return 0;
					}
					return $a['PRICE'] < $b['PRICE'] ? 1 : -1;
                });
            }
		}
    }

    /**
     * @param array $arData
     */
    protected function _excursionstoursSorting(array &$arData) {

        $this->_excursionsSorting($arData);
    }

    /**
     * @param array $arData
     */
    protected function _placementsSorting(array &$arData) {

        $this->_commonSorting($arData);
    }

    /**
     * @param array $arData
     */
    protected function _sanatoriumSorting(array &$arData) {

        $this->_commonSorting($arData);
    }

    /**
     * @param array $arData
     */
    protected function _transfersSorting(array &$arData) {

        $this->_commonSorting($arData);
    }

    /**
     * @param int $id
     * @return string|null
     */
    protected function _getCommonCancellationPolicy(int $id) {

        if (($dbElement = \CIBlockElement::GetByID($id)->GetNextElement())) {
            $arProperties = $dbElement->GetProperties();
            if ($arProperties["CANCELLATION_POLICY" . POSTFIX_PROPERTY]["~VALUE"]["TEXT"]) {
                return preg_replace('#\r\n#', '', $arProperties["CANCELLATION_POLICY" . POSTFIX_PROPERTY]["~VALUE"]["TEXT"]);
            }
        }
        return null;
    }

    /**
     * @param int $id
     * @return string|null
     */
    protected function _getCancellationPolicyForPlacements(int $id) {
        return $this->_getCommonCancellationPolicy($id);
    }

    /**
     * @param int $id
     * @return string|null
     */
    protected function _getCancellationPolicyForSanatorium(int $id) {
        return $this->_getCommonCancellationPolicy($id);
    }

    /**
     * @param int $id
     * @return string|null
     */
    protected function _getCancellationPolicyForExcursions(int $id) {
        return $this->_getCommonCancellationPolicy($id);
    }

    /**
     * @param int $id
     * @return string|null
     */
    protected function _getCancellationPolicyForExcursionstours(int $id) {
        return $this->_getCancellationPolicyForExcursions($id);
    }

    /**
     * @param int $id
     * @return string|null
     */
    protected function _getCancellationPolicyForTransfers(int $id) {
        return null;
    }

    /**
     * @param bool $mp
     * @return array
     */
    protected function _getPriceCalculation(bool $mp = false) {

        return U::getPriceCalculation(array(
                    "request" => $this->arResult["REQUEST"],
                    "type" => $this->arParams["TYPE"],
                    "mp" => $mp
        ));
    }
    
    protected function _getPriceCalculationCustom(bool $mp = false) {

        return U::getPriceCalculation(array(
                    "request" => $this->arResult["REQUEST_CUSTOM"],
                    "type" => $this->arParams["TYPE"],
                    "mp" => $mp
        ));
    }

    protected function _setPlacementsRequest() {

        $reqparams = array_merge($this->_getC1RP(), $this->_getC2RP(), $this->_getC3RP());

        U::preparePlacementsBookingRequest($reqparams);
        $this->arResult["REQUEST"] = new \travelsoft\booking\placements\Request($reqparams);
    }
    
    protected function _setSanatoriumRequestCustom() {

        $reqparams = array_merge($this->_getC1RP(), $this->_getC2RP());

        U::prepareSanatoriumBookingRequest($reqparams);
        $this->arResult["REQUEST_CUSTOM"] = new \travelsoft\booking\placements\Request($reqparams);
    }

    protected function _setSanatoriumRequest() {
        //$this->_setPlacementsRequest();
        $reqparams = array_merge($this->_getC1RP(), $this->_getC2RP(), $this->_getC3RP());

        U::prepareSanatoriumBookingRequest($reqparams);
        $this->arResult["REQUEST"] = new \travelsoft\booking\placements\Request($reqparams);
        
    }

    protected function _setExcursionstoursRequest() {
        $reqparams = array_merge($this->_getC1RP(), $this->_getC2RP());
        if (empty($reqparams['date_from']) && empty($reqparams['date_to'])) {
            $reqparams['date_from'] = strtotime(date('d.m.Y'));
            $reqparams['date_to'] = strtotime("+1 day");
        }
        U::prepareExcursionsBookingRequest($reqparams);
        $this->arResult["REQUEST"] = new \travelsoft\booking\excursions\Request($reqparams);
    }

    protected function _setExcursionsRequest() {
        $reqparams = array_merge($this->_getC1RP(), $this->_getC2RP());
        U::prepareExcursionsBookingRequest($reqparams);
        $this->arResult["REQUEST"] = new \travelsoft\booking\excursions\Request($reqparams);
    }

    protected function _setTransfersRequest() {

        $this->arResult["REQUEST"] = new \travelsoft\booking\transfers\Request(array_merge($this->_getC1RP(), array(
                    "point_A" => $this->arParams["__BOOKING_REQUEST"]["point_A"],
                    "point_B" => $this->arParams["__BOOKING_REQUEST"]["point_B"],
                    "roundtrip" => $this->arParams["__BOOKING_REQUEST"]["roundtrip"],
        )));
    }

    /**
     * @return array
     */
    protected function _getC1RP() {

        return array(
            "date_from" => $this->arParams["__BOOKING_REQUEST"]["date_from"],
            "date_to" => $this->arParams["__BOOKING_REQUEST"]["date_to"],
            "adults" => $this->arParams["__BOOKING_REQUEST"]["adults"],
            "service_id" => $this->arParams["__BOOKING_REQUEST"]["service_id"],
            "rate_id" => $this->arParams["__BOOKING_REQUEST"]["rate_id"]
        );
    }

    /**
     * @return array
     */
    protected function _getC2RP() {

        $reqparams = array(
            "id" => array_filter($this->arParams["__BOOKING_REQUEST"]["id"], function ($id) {
                        return $id > 0;
                    }),
            "children" => $this->arParams["__BOOKING_REQUEST"]["children"],
            "children_age" => $this->arParams["__BOOKING_REQUEST"]["children_age"],
        );

        if (empty($reqparams["id"]) && !empty($this->arParams["CODE"]) && ($ID = $this->_getIdByCode($this->arParams["CODE"]))) {
            $reqparams["id"] = $ID;
        }

        if (empty($reqparams['id'])) {
            throw new Exception();
        }

        return $reqparams;
    }

    /**
     * @return array
     */
    protected function _getC3RP() {
        $reqparams = array();
        $arCitizenPrices = U::getCitizenPrices();
        if ($_SESSION['current_currency']) {
            $convert_currency = [1=>333, 2=>332, 3=>356];
            if ($convert_currency[$_SESSION['current_currency']]) $arCitizenPrices['CURRENT'] = $convert_currency[$_SESSION['current_currency']];
        }
        # фильтруем по ценам для граждан
        if ($this->arParams["FILTER_BY_PRICES_FOR_CITIZEN"] == "Y") {

            $reqparams = array("citizen_price" => $this->arParams["__BOOKING_REQUEST"]["citizen_price"] > 0 ? $this->arParams["__BOOKING_REQUEST"]["citizen_price"] : $arCitizenPrices["CURRENT"]);
        }
        return $reqparams;
    }

    /**
     * @param array $arData
     * @return array
     */
    protected function _pipeTransfersCalculationData(array $arData) {
        return array($arData);
    }

    protected function _setPlacementsJsSettings() {
        $this->arResult["SERVICE_POPUP_JS"] = true;
        $this->arResult["ADD2CART_POPUP_JS"] = true;
        $this->arResult["RATE_POPUP_JS"] = true;
        $this->arResult["CANCELLATION_POLICY_POPUP_JS"] = true;
    }

    protected function _setSanatoriumJsSettings() {
        $this->_setPlacementsJsSettings();
    }

    protected function _setExcursionsJsSettings() {
        $this->arResult["SERVICE_POPUP_JS"] = false;
        $this->arResult["ADD2CART_POPUP_JS"] = true;
        $this->arResult["RATE_POPUP_JS"] = true;
        $this->arResult["CANCELLATION_POLICY_POPUP_JS"] = true;
    }

    protected function _setExcursionstoursJsSettings() {
        $this->_setExcursionsJsSettings();
    }

    protected function _setTransfersJsSettings() {
        $this->arResult["SERVICE_POPUP_JS"] = false;
        $this->arResult["ADD2CART_POPUP_JS"] = true;
        $this->arResult["RATE_POPUP_JS"] = false;
    }

    /**
     * @param int $service_id
     * @param int $rate_id
     * @return array
     */
    protected function _getCommonBasketFields(int $service_id, int $rate_id) {
        return array(
            "service_id" => $service_id,
            "rate_id" => $rate_id,
            "adults" => $this->arResult["LOCAL_REQUEST"]["adults"],
            "currency" => $this->_currency["id"],
            "can_buy" => true
        );
    }

    /**
     * @param array $arData
     * @return array|null
     */
    protected function _getPlacementsResult(array $arData) {

        $servicesId = array_keys($arData);

        $arServices = ( new ServicesDataStore(array(
            "filter" => array("ID" => $servicesId),
            "select" => array("ID", "UF_IBLOCK_ELEMENT_ID", "UF_PICTURES", "UF_NAME" . POSTFIX_PROPERTY, "UF_SERVICE_DESC" . POSTFIX_PROPERTY)
                )))->fetch(array("ID"));


        $arForSale = (new QuotasDataStore(array(
            "filter" => array("UF_SERVICE_ID" => $servicesId, "><UF_DATE" => array($this->arResult["BOOKING_REQUEST"]->date_from, $this->arResult["BOOKING_REQUEST"]->date_to)),
            "select" => array("ID", "UF_SERVICE_ID", "UF_QUOTE", "UF_SOLD_NUMBER")
                )))->filterAvailableForSale()->getServiceCountOnSale();

        $HTML_DATA = null;

        foreach ($arData as $SID => $arRatesData) {
            if ($arServices[$SID]) {
                $ratesId = array_keys($arRatesData);
                $arRates = ( new RatesDataStore(array(
                    "filter" => array("ID" => $ratesId),
                    "select" => array("ID", "UF_NAME" . POSTFIX_PROPERTY)
                        )))->fetch(array("ID"));


                $arService = $arServices[$SID][0];
                $HTML_DATA[$SID]["MAIN_BLOCK"]["ID"] = $SID;
                $HTML_DATA[$SID]["MAIN_BLOCK"]["REQUEST"] = $this->arResult["LOCAL_REQUEST"];
                $HTML_DATA[$SID]["MAIN_BLOCK"]["FOR_SALE"] = $arForSale[$SID];
                if ($arService["UF_PICTURES"][0] > 0) {
                    $HTML_DATA[$SID]["MAIN_BLOCK"]["IMAGE_ID"] = $arService["UF_PICTURES"][0];
                }
                $HTML_DATA[$SID]["MAIN_BLOCK"]['PICTURES'] = $arService["UF_PICTURES"];
                $HTML_DATA[$SID]["MAIN_BLOCK"]["TITLE"] = $arService["UF_NAME" . POSTFIX_PROPERTY];
                $HTML_DATA[$SID]["MAIN_BLOCK"]["DESCRIPTION"] = substr2($arService["UF_SERVICE_DESC" . POSTFIX_PROPERTY], 450);
                
                if (isset($arPriceData["DISCOUNT_PRICE"]) && $arPriceData["DISCOUNT_PRICE"] > 0) {
                    $HTML_DATA[$SID]["ROWS"][$unixdate . $RID]["DISCOUNT_PRICE"] = U::convertCurrency($arPriceData["DISCOUNT_PRICE"], $arPriceData["CURRENCY_ID"], $this->_currency["id"]);
                }
                
                $arPrices = null;
                foreach ($arRatesData as $RID => $arPriceData) {
                    if ($arRates[$RID]) {
                        $arRate = $arRates[$RID][0];
                        $HTML_DATA[$SID]["ROWS"][$RID]["ID"] = $RID;
                        $HTML_DATA[$SID]["ROWS"][$RID]["PRICE"] = U::convertCurrency($arPriceData["PRICE"], $arPriceData["CURRENCY_ID"], $this->_currency["id"]);
                        $HTML_DATA[$SID]["ROWS"][$RID]["~PRICE"] = U::convertCurrency($arPriceData["PRICE"], $arPriceData["CURRENCY_ID"], $this->_currency["id"], true);
                        $arPrices[] = $HTML_DATA[$SID]["ROWS"][$RID]["~PRICE"];
                        $HTML_DATA[$SID]["ROWS"][$RID]["TITLE"] = $arRate["UF_NAME" . POSTFIX_PROPERTY];
                        
                        if (isset($arPriceData["DISCOUNT_PRICE"]) && $arPriceData["DISCOUNT_PRICE"] > 0) {
                            $HTML_DATA[$SID]["ROWS"][$RID]["DISCOUNT_PRICE"] = U::convertCurrency($arPriceData["DISCOUNT_PRICE"], $arPriceData["CURRENCY_ID"], $this->_currency["id"]);
                            $HTML_DATA[$SID]["ROWS"][$RID]["~DISCOUNT_PRICE"] = U::convertCurrency($arPriceData["DISCOUNT_PRICE"], $arPriceData["CURRENCY_ID"], $this->_currency["id"], true);
                        }
                        
                        $add2cart = $this->_getCommonBasketFields($SID, $RID);
                        $add2cart["duration"] = $arPriceData["DURATION"] + 1;
                        $add2cart["date_from"] = $this->arResult["LOCAL_REQUEST"]["date_from"];
                        $add2cart["date_to"] = $this->arResult["LOCAL_REQUEST"]["date_to"];
                        $add2cart["children"] = $this->arResult["LOCAL_REQUEST"]["children"];
                        $add2cart["children_age"] = $this->arResult["LOCAL_REQUEST"]["children_age"];

                        if (isset($arPriceData["DISCOUNT"]) && $arPriceData["DISCOUNT"] > 0) {
                            $add2cart["discount"] = [(float) $arPriceData["DISCOUNT"]];
                        }

                        $add2cart["type"] = "placements";
                        $add2cart["price"] = U::convertCurrency($arPriceData["PRICE"], $arPriceData["CURRENCY_ID"], $this->_currency["id"], true);
                        $HTML_DATA[$SID]["ROWS"][$RID]["ADD2CART"] = $this->_getAdd2CartParams(new \travelsoft\booking\placements\BasketItem($add2cart));
                    }
                }
                if ($arPrices) {
                    $HTML_DATA[$SID]["MAIN_BLOCK"]["PRICE"] = U::convertCurrency(min($arPrices), $this->_currency["id"]);
                }
            }
        }

        if ($HTML_DATA) {
            $parentId = current($arServices)[0]['UF_IBLOCK_ELEMENT_ID'];

            $this->arResult['FORSPOTPAYMENT'] = false;
            if ($parentId) {
                $dbParent = CIBlockElement::GetByID($parentId)->GetNextElement();

                $arProperties = $dbParent->GetProperties();
                if ($arProperties['FOR_SPOT_PAYMENT']['VALUE']) {
                    $this->arResult['FORSPOTPAYMENT'] = true;
                }
            }
        }

        return $HTML_DATA;
    }

    /**
     * @param array $arData
     * @return array|null
     */
    protected function _getSanatoriumResult(array $arData) {

        $servicesId = array_keys($arData);

        $arServices = ( new ServicesDataStore(array(
            "filter" => array("ID" => $servicesId),
            "select" => array("ID", "UF_IBLOCK_ELEMENT_ID", "UF_PICTURES", "UF_NAME" . POSTFIX_PROPERTY, "UF_SERVICE_DESC" . POSTFIX_PROPERTY, "UF_PEOPLE")
                )))->fetch(array("ID"));

        $arForSale = (new QuotasDataStore(array(
            "filter" => array("UF_SERVICE_ID" => $servicesId, "><UF_DATE" => array($this->arResult["BOOKING_REQUEST"]->date_from, $this->arResult["BOOKING_REQUEST"]->date_to)),
            "select" => array("ID", "UF_SERVICE_ID", "UF_QUOTE", "UF_SOLD_NUMBER")
                )))->filterAvailableForSale()->getServiceCountOnSale();

        $HTML_DATA = null;

        foreach ($arData as $SID => $arRatesData) {
            if ($arServices[$SID]) {
                $ratesId = array_keys($arRatesData);
                $arRates = ( new RatesDataStore(array(
                    "filter" => array("ID" => $ratesId),
                    "select" => array("ID", "UF_NAME" . POSTFIX_PROPERTY)
                        )))->fetch(array("ID"));


                $arService = $arServices[$SID][0];
                $HTML_DATA[$SID]["MAIN_BLOCK"]["ID"] = $SID;
                $HTML_DATA[$SID]["MAIN_BLOCK"]["FOR_SALE"] = $arForSale[$SID];
                if ($arService["UF_PICTURES"][0] > 0) {
                    $HTML_DATA[$SID]["MAIN_BLOCK"]["IMAGE_ID"] = $arService["UF_PICTURES"][0];
                }
                $HTML_DATA[$SID]["MAIN_BLOCK"]['PICTURES'] = $arService["UF_PICTURES"];
                $HTML_DATA[$SID]["MAIN_BLOCK"]["TITLE"] = $arService["UF_NAME" . POSTFIX_PROPERTY];
                $HTML_DATA[$SID]["MAIN_BLOCK"]["UF_PEOPLE"] = $arService["UF_PEOPLE"];                
                $HTML_DATA[$SID]["MAIN_BLOCK"]["DESCRIPTION"] = substr2($arService["UF_SERVICE_DESC" . POSTFIX_PROPERTY], 450);
                $arPrices = null;
                foreach ($arRatesData as $RID => $arPriceData) {
                    if ($arRates[$RID]) {
                        $arRate = $arRates[$RID][0];
                        $HTML_DATA[$SID]["ROWS"][$RID]["ID"] = $RID;
                        $HTML_DATA[$SID]["ROWS"][$RID]["PRICE"] = U::convertCurrency($arPriceData["PRICE"], $arPriceData["CURRENCY_ID"], $this->_currency["id"]);
                        $HTML_DATA[$SID]["ROWS"][$RID]["~PRICE"] = U::convertCurrency($arPriceData["PRICE"], $arPriceData["CURRENCY_ID"], $this->_currency["id"], true);
                        $arPrices[] = $HTML_DATA[$SID]["ROWS"][$RID]["~PRICE"];
                        $HTML_DATA[$SID]["ROWS"][$RID]["TITLE"] = $arRate["UF_NAME" . POSTFIX_PROPERTY];
                        
                        if (isset($arPriceData["DISCOUNT_PRICE"]) && $arPriceData["DISCOUNT_PRICE"] > 0) {
                            $HTML_DATA[$SID]["ROWS"][$RID]["DISCOUNT_PRICE"] = U::convertCurrency($arPriceData["DISCOUNT_PRICE"], $arPriceData["CURRENCY_ID"], $this->_currency["id"]);
                            $HTML_DATA[$SID]["ROWS"][$RID]["~DISCOUNT_PRICE"] = U::convertCurrency($arPriceData["DISCOUNT_PRICE"], $arPriceData["CURRENCY_ID"], $this->_currency["id"], true);
                        }
                        
                        $add2cart = $this->_getCommonBasketFields($SID, $RID);
                        $add2cart["duration"] = $arPriceData["DURATION"] + 1;
                        $add2cart["date_from"] = $this->arResult["LOCAL_REQUEST"]["date_from"];
                        $add2cart["date_to"] = $this->arResult["LOCAL_REQUEST"]["date_to"];
                        $add2cart["children"] = $this->arResult["LOCAL_REQUEST"]["children"];
                        $add2cart["children_age"] = $this->arResult["LOCAL_REQUEST"]["children_age"];
                        $add2cart["type"] = "sanatorium";
                        if (isset($arPriceData["DISCOUNT"]) && $arPriceData["DISCOUNT"] > 0) {
                            $add2cart["discount"] = [(float) $arPriceData["DISCOUNT"]];
                        }
                        $add2cart["allocate"] = isset($arPriceData['ALLOCATE']) ? $arPriceData['ALLOCATE'] : [];
                        $add2cart["price"] = U::convertCurrency($arPriceData["PRICE"], $arPriceData["CURRENCY_ID"], $this->_currency["id"], true);
                        $HTML_DATA[$SID]["ROWS"][$RID]["ADD2CART"] = $this->_getAdd2CartParams(new \travelsoft\booking\sanatorium\BasketItem($add2cart));
                    }
                }
                if ($arPrices) {
                    $HTML_DATA[$SID]["MAIN_BLOCK"]["PRICE"] = U::convertCurrency(min($arPrices), $this->_currency["id"]);
                }
            }
        }

        if ($HTML_DATA) {
            $parentId = current($arServices)[0]['UF_IBLOCK_ELEMENT_ID'];

            $this->arResult['FORSPOTPAYMENT'] = false;
            if ($parentId) {
                $dbParent = CIBlockElement::GetByID($parentId)->GetNextElement();

                $arProperties = $dbParent->GetProperties();

                if ($arProperties['FOR_SPOT_PAYMENT']['VALUE'] && (

                        (!$arProperties["TO_PAY_FROM1"]["VALUE"] &&
                        !$arProperties["TO_PAY_TO1"]["VALUE"] &&
                        !$arProperties["TO_PAY_FROM2"]["VALUE"] &&
                        !$arProperties["TO_PAY_TO2"]["VALUE"] &&
                        !$arProperties["TO_PAY_FROM3"]["VALUE"] &&
                        !$arProperties["TO_PAY_TO3"]["VALUE"]
                        ) ||
                        (
                        strtotime($arProperties["TO_PAY_FROM1"]["VALUE"]) <= $this->arResult["LOCAL_REQUEST"]["date_from"] &&
                        strtotime($arProperties["TO_PAY_TO1"]["VALUE"]) >= $this->arResult["LOCAL_REQUEST"]["date_to"]
                        ) ||
                        (
                        strtotime($arProperties["TO_PAY_FROM2"]["VALUE"]) <= $this->arResult["LOCAL_REQUEST"]["date_from"] &&
                        strtotime($arProperties["TO_PAY_TO2"]["VALUE"]) >= $this->arResult["LOCAL_REQUEST"]["date_to"]
                        ) ||
                        (
                        strtotime($arProperties["TO_PAY_FROM3"]["VALUE"]) <= $this->arResult["LOCAL_REQUEST"]["date_from"] &&
                        strtotime($arProperties["TO_PAY_TO3"]["VALUE"]) >= $this->arResult["LOCAL_REQUEST"]["date_to"]
                        )
                        )) {
                    $this->arResult['FORSPOTPAYMENT'] = true;
                }
            }
        }

        return $HTML_DATA;
    }

    /**
     * @param array $arData
     * @return array|null
     */
    protected function _getTransfersResult(array $arData) {

        $HTML_DATA = null;

        $classAutoId = array_keys($arData);

        $arClassesAuto = ( new ClassAutoDataStore(array(
            "filter" => array("ID" => $classAutoId),
            "select" => array("ID", "UF_NAME" . POSTFIX_PROPERTY, "UF_AUTO" . POSTFIX_PROPERTY, "UF_PICTURES", "UF_BAGGAGE", "UF_CAPACITY")
                )))->fetch(array("ID"));
        $arr_users = array();
        foreach ($arData as $CAID => $arServicesData) {

            if ($arClassesAuto[$CAID]) {

                $arClassAuto = $arClassesAuto[$CAID][0];

                $HTML_DATA[$CAID]["MAIN_BLOCK"]["ID"] = $CAID;
                if ($arClassAuto["UF_PICTURES"][0] > 0) {
                    $HTML_DATA[$CAID]["MAIN_BLOCK"]["IMAGE_ID"] = $arClassAuto["UF_PICTURES"][0];
                }
                $HTML_DATA[$CAID]["MAIN_BLOCK"]["TITLE"] = $arClassAuto["UF_NAME" . POSTFIX_PROPERTY];
                $HTML_DATA[$CAID]["MAIN_BLOCK"]["DESCRIPTION"] = Loc::getMessage("CLASS_AUTO_DESCRIPTION", array(
                            "#AUTO#" => $arClassAuto["UF_AUTO" . POSTFIX_PROPERTY],
                            "#CAPACITY#" => $arClassAuto["UF_CAPACITY"],
                            "#BAGAGGE#" => $arClassAuto["UF_BAGGAGE"]
                ));
                $arPrices = null;

                foreach ($arServicesData as $SID => $arPriceData) {
                    $HTML_DATA[$CAID]["ROWS"][$SID]["ID"] = $SID;
                    $HTML_DATA[$CAID]["ROWS"][$SID]["PRICE"] = U::convertCurrency($arPriceData["PRICE"], $arPriceData["CURRENCY_ID"], $this->_currency["id"]);
                    $arPrices[] = U::convertCurrency($arPriceData["PRICE"], $arPriceData["CURRENCY_ID"], $this->_currency["id"], true);
//                    $arUser = CUser::GetByID($arPriceData["USER_ID"])->Fetch();
                    if ($arPriceData["USER_ID"] > 0 && !isset($arr_users[$arPriceData["USER_ID"]])) {
                        $arr_users[$arPriceData["USER_ID"]] = CUser::GetList(($by = "personal_country"), ($order = "desc"), array(
                                    "ID" => $arPriceData["USER_ID"]
                                        ), array(
                                    "SELECT" => array("UF_FOR_SPOT_PAYMENT", "UF_LEGAL_NAME", "UF_LEGAL_NAME" . POSTFIX_PROPERTY),
                                    "FIELDS" => array()
                                ))->Fetch();
                    }


                    $HTML_DATA[$CAID]["ROWS"][$SID]["TITLE"] = Loc::getMessage("PROVIDER_DESC", array(
                                "#NAME#" => $arr_users[$arPriceData["USER_ID"]]["UF_LEGAL_NAME" . POSTFIX_PROPERTY] ? $arr_users[$arPriceData["USER_ID"]]["UF_LEGAL_NAME" . POSTFIX_PROPERTY]: $arr_users[$arPriceData["USER_ID"]]["UF_LEGAL_NAME"]
                    ));
                    $HTML_DATA[$CAID]["ROWS"][$SID]["FOR_SPOT_PAYMENT"] = (int) $arr_users[$arPriceData["USER_ID"]]["UF_FOR_SPOT_PAYMENT"] === 1;
                    $add2cart = $this->_getCommonBasketFields($SID, $arPriceData["RATE_ID"]);
                    $add2cart["point_A"] = $this->arResult["LOCAL_REQUEST"]["point_A"];
                    $add2cart["point_B"] = $this->arResult["LOCAL_REQUEST"]["point_B"];
                    $add2cart["for_spot_payment"] = $HTML_DATA[$CAID]["ROWS"][$SID]["FOR_SPOT_PAYMENT"];
                    $add2cart["roundtrip"] = $this->arResult["LOCAL_REQUEST"]["roundtrip"];
                    $add2cart["date_from"] = $this->arResult["LOCAL_REQUEST"]["date_from"];
                    $add2cart["date_to"] = $this->arResult["LOCAL_REQUEST"]["date_to"];
                    $add2cart["type"] = "transfers";
                    $add2cart["price"] = U::convertCurrency($arPriceData["PRICE"], $arPriceData["CURRENCY_ID"], $this->_currency["id"], true);
                    $HTML_DATA[$CAID]["ROWS"][$SID]["ADD2CART"] = $this->_getAdd2CartParams(new \travelsoft\booking\transfers\BasketItem($add2cart));
                }
                if ($arPrices) {
                    $min_price = min($arPrices);
                    $HTML_DATA[$CAID]["MAIN_BLOCK"]["PRICE"] = U::convertCurrency($min_price, $this->_currency["id"]);
                    $HTML_DATA[$CAID]["MAIN_BLOCK"]["COMPARE_PRICE"] = $min_price;
                }
            }
        }

        uasort($HTML_DATA, function ($a, $b) {
            if ($a["MAIN_BLOCK"]["COMPARE_PRICE"] == $b["MAIN_BLOCK"]["COMPARE_PRICE"]) {
                return 0;
            }
            return ($a["MAIN_BLOCK"]["COMPARE_PRICE"] < $b["MAIN_BLOCK"]["COMPARE_PRICE"]) ? -1 : 1;
        });

        return $HTML_DATA;
    }

    /**
     * @param array $arData
     * @return array|null
     */
    protected function _getExcursionstoursResult(array $arData) {
        $servicesId = array_keys($arData);

        $arServices = ( new ServicesDataStore(array(
            "filter" => array("ID" => $servicesId),
            "select" => array("ID", "UF_IBLOCK_ELEMENT_ID")
                )))->fetch(array("ID"));

        $HTML_DATA = null;

        foreach ($arData as $SID => $arDateData) {
            if ($arServices[$SID]) {
                foreach ($arDateData as $unixdate => $arRatesData) {
                    $ratesId = array_keys($arRatesData);
                    $arRates = ( new RatesDataStore(array(
                        "filter" => array("ID" => $ratesId),
                        "select" => array("ID", "UF_NAME" . POSTFIX_PROPERTY)
                            )))->fetch(array("ID"));

                    foreach ($arRatesData as $RID => $arPriceData) {
                        if ($arRates[$RID]) {
                            $arRate = $arRates[$RID][0];
                            $HTML_DATA[$SID]["ROWS"][$unixdate . $RID]["ID"] = $RID;
                            $HTML_DATA[$SID]["ROWS"][$unixdate . $RID]["UNIX_DATE"] = $unixdate;
                            $HTML_DATA[$SID]["ROWS"][$unixdate . $RID]["DATE"] = date("d.m.Y", $unixdate);
                            $HTML_DATA[$SID]["ROWS"][$unixdate . $RID]["PRICE"] = U::convertCurrency($arPriceData["PRICE"], $arPriceData["CURRENCY_ID"], $this->_currency["id"]);
                            $HTML_DATA[$SID]["ROWS"][$unixdate . $RID]["TITLE"] = $arRate["UF_NAME" . POSTFIX_PROPERTY];
                            
                            if (isset($arPriceData["DISCOUNT_PRICE"]) && $arPriceData["DISCOUNT_PRICE"] > 0) {
                                $HTML_DATA[$SID]["ROWS"][$unixdate . $RID]["DISCOUNT_PRICE"] = U::convertCurrency($arPriceData["DISCOUNT_PRICE"], $arPriceData["CURRENCY_ID"], $this->_currency["id"]);
                            }
                            
                            $add2cart = $this->_getCommonBasketFields($SID, $RID);
                            $add2cart["date_from"] = $unixdate;
                            $add2cart["date_to"] = $unixdate + (($arPriceData["DURATION"] - 1) * 86400);
                            $add2cart["duration"] = $arPriceData["DURATION"];
                            $add2cart["children"] = $this->arResult["LOCAL_REQUEST"]["children"];
                            $add2cart["children_age"] = $this->arResult["LOCAL_REQUEST"]["children_age"];
                            $add2cart["type"] = "excursionstours";
                            if (isset($arPriceData["DISCOUNT"]) && $arPriceData["DISCOUNT"] > 0) {
                                $add2cart["discount"] = [(float) $arPriceData["DISCOUNT"]];
                            }
                            $add2cart["price"] = U::convertCurrency($arPriceData["PRICE"], $arPriceData["CURRENCY_ID"], $this->_currency["id"], true);
                            $HTML_DATA[$SID]["ROWS"][$unixdate . $RID]["ADD2CART"] = $this->_getAdd2CartParams(new travelsoft\booking\excursions\BasketItem($add2cart));
                        }
                    }
                }
            }
        }

        if ($HTML_DATA) {
            $parentId = current($arServices)[0]['UF_IBLOCK_ELEMENT_ID'];

            $this->arResult['FORSPOTPAYMENT'] = false;
            if ($parentId) {
                $dbParent = CIBlockElement::GetByID($parentId)->GetNextElement();

                $arProperties = $dbParent->GetProperties();
                if ($arProperties['FOR_SPOT_PAYMENT']['VALUE'] && (

                        (!$arProperties["PROPERTIES"]["TO_PAY_FROM1"]["VALUE"] &&
                        !$arProperties["PROPERTIES"]["TO_PAY_TO1"]["VALUE"] &&
                        !$arProperties["PROPERTIES"]["TO_PAY_FROM2"]["VALUE"] &&
                        !$arProperties["PROPERTIES"]["TO_PAY_TO2"]["VALUE"] &&
                        !$arProperties["PROPERTIES"]["TO_PAY_FROM3"]["VALUE"] &&
                        !$arProperties["PROPERTIES"]["TO_PAY_TO3"]["VALUE"]
                        ) ||
                        (
                        strtotime($arProperties["PROPERTIES"]["TO_PAY_FROM1"]["VALUE"]) <= time() &&
                        strtotime($arProperties["PROPERTIES"]["TO_PAY_TO1"]["VALUE"]) >= time()
                        ) ||
                        (
                        strtotime($arProperties["PROPERTIES"]["TO_PAY_FROM2"]["VALUE"]) <= time() &&
                        strtotime($arProperties["PROPERTIES"]["TO_PAY_TO2"]["VALUE"]) >= time()
                        ) ||
                        (
                        strtotime($arProperties["PROPERTIES"]["TO_PAY_FROM3"]["VALUE"]) <= time() &&
                        strtotime($arProperties["PROPERTIES"]["TO_PAY_TO3"]["VALUE"]) >= time()
                        )
                        )) {
                    $this->arResult['FORSPOTPAYMENT'] = true;
                }
            }
        }

        return $HTML_DATA;
    }

    /**
     * @param array $arData
     * @return array|null
     */
    protected function _getExcursionsResult(array $arData) {

        $servicesId = array_keys($arData);

        $arServices = ( new ServicesDataStore(array(
            "filter" => array("ID" => $servicesId),
            "select" => array("ID", "UF_IBLOCK_ELEMENT_ID")
                )))->fetch(array("ID"));

        $HTML_DATA = null;

        foreach ($arData as $SID => $arDateData) {
            if ($arServices[$SID]) {
                foreach ($arDateData as $unixdate => $arRatesData) {
                    $ratesId = array_keys($arRatesData);
                    $arRates = ( new RatesDataStore(array(
                        "filter" => array("ID" => $ratesId),
                        "select" => array("ID", "UF_NAME" . POSTFIX_PROPERTY)
                            )))->fetch(array("ID"));

                    foreach ($arRatesData as $RID => $arPriceData) {
                        if ($arRates[$RID]) {
                            $arRate = $arRates[$RID][0];
                            $HTML_DATA[$SID]["ROWS"][$unixdate . $RID]["ID"] = $RID;
                            $HTML_DATA[$SID]["ROWS"][$unixdate . $RID]["UNIX_DATE"] = $unixdate;
                            $HTML_DATA[$SID]["ROWS"][$unixdate . $RID]["DATE"] = date("d.m.Y", $unixdate);
                            $HTML_DATA[$SID]["ROWS"][$unixdate . $RID]["PRICE"] = U::convertCurrency($arPriceData["PRICE"], $arPriceData["CURRENCY_ID"], $this->_currency["id"]);
                            $HTML_DATA[$SID]["ROWS"][$unixdate . $RID]["TITLE"] = $arRate["UF_NAME" . POSTFIX_PROPERTY];
                            
                            if (isset($arPriceData["DISCOUNT_PRICE"]) && $arPriceData["DISCOUNT_PRICE"] > 0) {
                                $HTML_DATA[$SID]["ROWS"][$unixdate . $RID]["DISCOUNT_PRICE"] = U::convertCurrency($arPriceData["DISCOUNT_PRICE"], $arPriceData["CURRENCY_ID"], $this->_currency["id"]);
                            }
                            
                            $add2cart = $this->_getCommonBasketFields($SID, $RID);
                            $add2cart["date_from"] = $unixdate;
                            $add2cart["date_to"] = $unixdate + (($arPriceData["DURATION"] - 1) * 86400);
                            $add2cart["duration"] = $arPriceData["DURATION"];
                            $add2cart["children"] = $this->arResult["LOCAL_REQUEST"]["children"];
                            $add2cart["children_age"] = $this->arResult["LOCAL_REQUEST"]["children_age"];
                            $add2cart["type"] = "excursions";
                            if (isset($arPriceData["DISCOUNT"]) && $arPriceData["DISCOUNT"] > 0) {
                                $add2cart["discount"] = [(float) $arPriceData["DISCOUNT"]];
                            }
                            $add2cart["price"] = U::convertCurrency($arPriceData["PRICE"], $arPriceData["CURRENCY_ID"], $this->_currency["id"], true);

                            $HTML_DATA[$SID]["ROWS"][$unixdate . $RID]["ADD2CART"] = $this->_getAdd2CartParams(new travelsoft\booking\excursions\BasketItem($add2cart));
                        }
                    }
                }
            }
        }

        if ($HTML_DATA) {
            $parentId = current($arServices)[0]['UF_IBLOCK_ELEMENT_ID'];

            $this->arResult['FORSPOTPAYMENT'] = false;
            if ($parentId) {
                $dbParent = CIBlockElement::GetByID($parentId)->GetNextElement();

                $arProperties = $dbParent->GetProperties();
                if ($arProperties['FOR_SPOT_PAYMENT']['VALUE'] && (

                        (!$arProperties["PROPERTIES"]["TO_PAY_FROM1"]["VALUE"] &&
                        !$arProperties["PROPERTIES"]["TO_PAY_TO1"]["VALUE"] &&
                        !$arProperties["PROPERTIES"]["TO_PAY_FROM2"]["VALUE"] &&
                        !$arProperties["PROPERTIES"]["TO_PAY_TO2"]["VALUE"] &&
                        !$arProperties["PROPERTIES"]["TO_PAY_FROM3"]["VALUE"] &&
                        !$arProperties["PROPERTIES"]["TO_PAY_TO3"]["VALUE"]
                        ) ||
                        (
                        strtotime($arProperties["PROPERTIES"]["TO_PAY_FROM1"]["VALUE"]) <= time() &&
                        strtotime($arProperties["PROPERTIES"]["TO_PAY_TO1"]["VALUE"]) >= time()
                        ) ||
                        (
                        strtotime($arProperties["PROPERTIES"]["TO_PAY_FROM2"]["VALUE"]) <= time() &&
                        strtotime($arProperties["PROPERTIES"]["TO_PAY_TO2"]["VALUE"]) >= time()
                        ) ||
                        (
                        strtotime($arProperties["PROPERTIES"]["TO_PAY_FROM3"]["VALUE"]) <= time() &&
                        strtotime($arProperties["PROPERTIES"]["TO_PAY_TO3"]["VALUE"]) >= time()
                        )
                        )) {
                    $this->arResult['FORSPOTPAYMENT'] = true;
                }
            }
        }

        return $HTML_DATA;
    }

    /**
     * @param travelsoft\booking\abstractions\BasketItem $basketItem
     * @return string
     */
    protected function _getAdd2CartParams(travelsoft\booking\abstractions\BasketItem $basketItem) {
        $sbi = serialize($basketItem);
        return Encoder::encode(array(
                    "add2cart" => $sbi,
                    "hash" => Encoder::hash(
                            $sbi, U::getOpt("salt")
        )));
    }

    /**
     * @param array $arr
     * @return string
     */
    protected function _getMultipleAdd2CartParams(array $arr) {
        $sbi = serialize($arr);
        return Encoder::encode(array(
                    "add2cart" => $sbi,
                    "hash" => Encoder::hash(
                            $sbi, U::getOpt("salt")
        )));
    }

    /**
     * Подключает модули
     * @throws Exception
     */
    public function includeModules() {

        if (!\Bitrix\Main\Loader::includeModule("highloadblock")) {
            throw new Exception("модуль highloadblock не найден");
        }

        if (!\Bitrix\Main\Loader::includeModule("travelsoft.booking.dev.tools")) {
            throw new Exception("модуль travelsoft.booking.dev.tools не найден");
        }

        if (!\Bitrix\Main\Loader::includeModule("travelsoft.currency")) {
            throw new Exception("модуль travelsoft.currency module не найден");
        }
    }

    protected function _setCitizenPriceRenderData() {
        if ($this->arParams["FILTER_BY_PRICES_FOR_CITIZEN"] == "Y") {
            $this->arResult["CITIZEN_PRICES"] = U::getCitizenPrices();
            if ($_SESSION['current_currency']) {
                $convert_currency = [1=>333, 2=>332, 3=>356];
                if ($convert_currency[$_SESSION['current_currency']]) $this->arResult["CITIZEN_PRICES"]['CURRENT'] = $convert_currency[$_SESSION['current_currency']];
            }
        }
    }

    function _setRequest() {
        $method_name = "_set" . ucfirst($this->arParams["TYPE"]) . "Request";

        $this->$method_name();
    }

    public function executeComponent() {

        try {

            $this->includeModules();

            travelsoft\booking\Validation::checkType($this->arParams["TYPE"]);

            $this->_cache = \Bitrix\Main\Data\Cache::createInstance();

            $this->arParams["__BOOKING_REQUEST"] = (array) $this->arParams["__BOOKING_REQUEST"];

            $this->_setRequest();

            if ($this->arParams["RETURN_RESULT"] == "Y") {

                return $this->_getPriceCalculation($this->arParams["MP"] == "Y");
            } else {

                $cache_id = U::getCacheIdForSearchOffers($this->arResult["REQUEST"], $this->arParams["TYPE"], boolval($this->arParams["MP"]), $this->arParams["__BOOKING_REQUEST"]);

                $cache = new travelsoft\booking\Cache($cache_id, U::getCacheRootDirForSearchOffers() . "/" . $cache_id, $this->arParams["CACHE_TIME"]);

                if (empty($this->arResult = $cache->get())) {

                    $this->_setCitizenPriceRenderData();

                    $this->_setRequest();

                    switch ($this->arParams["TYPE"]) {

                        case "sanatorium":
                            if (($this->arResult["REQUEST"]->adults + $this->arResult["REQUEST"]->children)>1)
                            $this->_setSpecifyingFormData();
                            break;
                    }

                    if ($this->arParams["IS_AJAX"] === "Y") {
                        $cache->caching(function () use ($cache) {

                            // список цен без ограничения по курсу
                            if ($this->arParams["TYPE"]=='sanatorium' || $this->arParams["TYPE"]=='placements') {
                                $this->_setSanatoriumRequestCustom();
                                $this->arResult["CALCULATION_ALL"] = $this->_getPriceCalculationCustom($this->arParams["MP"] == "Y");
                            }
                            
                            $this->arResult["CALCULATION"] = $this->_getPriceCalculation($this->arParams["MP"] == "Y");

                            $this->_setResult();

                            U::setTagCacheForSearchOffers($cache, $this->arResult["REQUEST"]);

                            return $this->arResult;
                        });

                        $this->arResult["NEED_LAZY_LOAD"] = false;
                    } else {
                        $this->arResult["CALCULATION"] = $this->_getPriceCalculation($this->arParams["MP"] == "Y");
                        $this->arResult["NEED_LAZY_LOAD"] = true;
                    }
                } else {

                    $this->arResult["NEED_LAZY_LOAD"] = false;
                }

                $this->IncludeComponentTemplate();

                $this->_includeJs();

                return $this->arResult;
            }
        } catch (\Exception $e) {

            if ($GLOBALS['USER']->IsAdmin()) {
                ShowError($e->getMessage());
            } else {
				//ShowError('Unknown error');
            }
        }
    }

    /** объект запроса приложения */
    public function setRequest() {

        $this->request = \Bitrix\Main\Application::getInstance()
                        ->getContext()->getRequest();

        $this->request->addFilter(new \Bitrix\Security\Filter\Request);
    }

    /**
     * попытка добавления в корзину
     * @return boolean
     */
    public function attemptAddToCart() {

        if (
                $this->request->get("add2cart") &&
                ($params = Encoder::decode($this->request->get("add2cart"))) &&
                Encoder::checkhash($params['add2cart'], $params["hash"], U::getOpt("salt"))
        ) {
            $unserialize_params = unserialize($params['add2cart']);

            if (is_array($unserialize_params)) {
                foreach ($unserialize_params as $enc_add2cart) {

                    $dec_add2cart = Encoder::decode($enc_add2cart);
                    if (Encoder::checkhash($dec_add2cart['add2cart'], $dec_add2cart["hash"], U::getOpt("salt"))) {

                        (new travelsoft\booking\Basket)->add(unserialize($dec_add2cart['add2cart']));
                    }
                }
            } else {
                (new travelsoft\booking\Basket)->add($unserialize_params);
            }

            return true;
        }

        return false;
    }

    protected function _includeJs() {

        $oAsset = \Bitrix\Main\Page\Asset::getInstance();

        if ((string) $this->arParams["INC_JQUERY"] === "Y") {
            $oAsset->addJs("https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js");
        }

        if ((string) $this->arParams["INC_MAGNIFIC_POPUP"] === "Y") {
            $oAsset->addCss("https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/magnific-popup.min.css");
            $oAsset->addJs("https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.0.0/jquery.magnific-popup.js", true);
        }

        if ((string) $this->arParams["INC_OWL_CAROUSEL"] === "Y") {
            $oAsset->addCss("https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.2.0/assets/owl.carousel.min.css");
            $oAsset->addJs("https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.2.0/owl.carousel.min.js", true);
        }

        $oAsset->addCss($this->__path . "/public/_styles.min.css");
        $oAsset->addJs($this->__path . "/public/_script.js");
    }

}

if (!function_exists("substr2")) {

    function substr2($str, $nos = null) {

        $str = strip_tags($str);

        if ($nos === null || strlen($str) <= $nos)
            return $str;

        return substr($str, 0, $nos) . "...";
    }

}
