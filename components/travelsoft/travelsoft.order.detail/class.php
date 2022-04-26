<?php

/*
 * Компонент детальной информации по заказу
 */

class TravelsoftOrderDetail extends CBitrixComponent {

    /** детальная информация по заказу */
    protected function __setOrderDetail() {

        if ($this->arParams["ORDER_ID"] <> '') {

            $response = \Bitrix\Main\Web\Json::decode(\travelsoft\booking\Gateway::getOrderDetail(array(
                                "url" => \Bitrix\Main\Config\Option::get("travelsoft.booking.dev.tools", "tsmo_url"),
                                "params" => array("token" => $_SESSION["__TRAVELSOFT"]["TOKEN"], "dogovor_code" => $this->arParams["ORDER_ID"])
            )));

            if ($response["result"]) {
                $this->arResult["ORDER"] = $response["result"];
                if (!empty($this->arResult["ORDER"]["cities"])) {

                    Bitrix\Main\Loader::includeModule("iblock");

                    $dbList = CIBlockElement::GetList(false, array("IBLOCK_ID" => TOWN_IBLOCK_ID, "ACTIVE" => "Y", "ID" => $this->arResult["ORDER"]["cities"]), false, false, array("ID", "NAME", "PROPERTY_NAME" . POSTFIX_PROPERTY, "DETAIL_PAGE_URL"));
                    $this->arResult["ORDER"]["guides"] = array();
                    while ($arCity = $dbList->GetNext()) {
                        $this->arResult["ORDER"]["guides"][] = array(
                            "ID" => $arCity["ID"],
                            "NAME" => strlen($arCity["PROPERTY_NAME" . POSTFIX_PROPERTY . "_VALUE"]) > 0 ? $arCity["PROPERTY_NAME" . POSTFIX_PROPERTY . "_VALUE"] : $arCity["NAME"],
                            "DETAIL_PAGE_URL" => $arCity["DETAIL_PAGE_URL"]
                        );
                    }
                }
                if (!$this->arResult["ORDER"]["isPaid"] && !in_array($this->arResult["ORDER"]["dogovor_status"]["key"], array(19, 2, 23))) {
                    \Bitrix\Main\Loader::includeModule("iblock");
                    $this->arResult["cross_sale_parameters"] = [];
                    $placements_iblock_id = travelsoft\booking\Utils::getOpt("placements");
                    $sanatorium_iblock_id = travelsoft\booking\Utils::getOpt("sanatorium");
                    foreach ($this->arResult["ORDER"]["services"] as $service) {
                        
                        if (isset($service["bitrix_id"]) && $service["bitrix_id"] > 0) {
                            
                            $element = CIBlockElement::GetByID(current(\travelsoft\booking\datastores\ServicesDataStore::get(["filter" => ["ID" => $service["bitrix_id"]], "select" => ["ID", "UF_IBLOCK_ELEMENT_ID"]]))["UF_IBLOCK_ELEMENT_ID"])->Fetch();
                            if (in_array($element["IBLOCK_ID"], [$placements_iblock_id, $sanatorium_iblock_id])) {
                                if ($element["IBLOCK_ID"] == $placements_iblock_id) {
                                    $this->arResult["cross_sale_parameters"]["type"] = "placements";
                                } else {
                                    $this->arResult["cross_sale_parameters"]["type"] = "sanatorium";
                                }
                                $timestamp = strtotime($service["date_begin"]);
                                $this->arResult["cross_sale_parameters"]["date_from"] = $timestamp;
                                $this->arResult["cross_sale_parameters"]["date_to"] = $timestamp;
                                $this->arResult["cross_sale_parameters"]["adults"] = count($this->arResult["ORDER"]["turists"]);
                                $this->arResult["cross_sale_parameters"]["children"] = 0;
                                $this->arResult["cross_sale_parameters"]["service_id"] = $service["bitrix_id"];
                                break;
                            }
                        }
                    }
                }
                
            } else {
                $this->arResult["ERRORS"][] = "UNKNOWN_ERROR";
            }
        } else {
            $this->arResult["ERRORS"][] = "UNKNOWN_ORDER_ID";
        }
    }

    /** подключение модулей */
    protected function __include_modules() {
        if (!\Bitrix\Main\Loader::includeModule("travelsoft.booking.dev.tools")) {
            throw new Exception("Не найден модуль \"инструменты бронирования\" ");
        }
    }

    /** проверка, что пользователь пришёл со страницы */
    protected function __check_just_booked() {
        if ($_SESSION["__TRAVELSOFT"]["JUST_BOOKED"] == "Y") {
            $this->arResult["JUST_BOOKED"] = true;
            unset($_SESSION["__TRAVELSOFT"]["JUST_BOOKED"]);
        }
    }

    protected function _procActionRequest() {

        if (check_bitrix_sessid() && $this->arParams["ORDER_ID"]) {

            if ($_REQUEST['action'] == "cancel") {

                $response = \Bitrix\Main\Web\Json::decode(\travelsoft\booking\Gateway::orderToCancel(array(
                                    "url" => \Bitrix\Main\Config\Option::get("travelsoft.booking.dev.tools", "tsmo_url"),
                                    "params" => array("token" => $_SESSION["__TRAVELSOFT"]["TOKEN"], "dogovor_code" => $this->arParams["ORDER_ID"], "service_keys" => array())
                )));

                if ($response["result"]["result"]) {
                    $_SESSION['__TRAVELSOFT']['JUST_NOW_CANCELLATION'] = true;
                    LocalRedirect($GLOBALS["APPLICATION"]->GetCurPageParam("order_id=" . $this->arParams["ORDER_ID"], array("order_id", "action", "sessid")));
                } else {
                    $this->arResult["ERRORS"][] = "CANCEL_FAIL";
                }
            } elseif ($_REQUEST['action'] == "fsp") {

                \travelsoft\booking\Gateway::orderToPaidOnTheSpot(array(
                    "url" => \Bitrix\Main\Config\Option::get("travelsoft.booking.dev.tools", "tsmo_url"),
                    "params" => array("token" => $_SESSION["__TRAVELSOFT"]["TOKEN"], "dogovor_code" => $this->arParams["ORDER_ID"])
                ));
                LocalRedirect($GLOBALS["APPLICATION"]->GetCurPageParam("order_id=" . $this->arParams["ORDER_ID"], array("order_id", "action", "sessid")));
            }
        }
    }

    public function executeComponent() {

        $this->__include_modules();

        $this->__check_just_booked();

        $this->__setOrderDetail();

        $this->_procActionRequest();

        $this->IncludeComponentTemplate();
    }

}
