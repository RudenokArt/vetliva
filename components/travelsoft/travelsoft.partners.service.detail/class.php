<?php

/* 
 * Компонент детальной информации по заказу
 */

class TravelsoftOrderDetail extends CBitrixComponent {
    
    /** детальная информация по заказу */
    protected function __setOrderDetail () {
        
        if ($this->arParams["KEY"] <> '') {
            
            $response = \Bitrix\Main\Web\Json::decode(\travelsoft\booking\Gateway::getPartnersServiceDetail(array(
                    "url" => \Bitrix\Main\Config\Option::get("travelsoft.booking.dev.tools", "tsmo_url"),
                    "params" => array("token" => $_SESSION["__TRAVELSOFT"]["TOKEN"], "serviceId" => $this->arParams["KEY"])
                )));
            
            if ($response["result"]) {
                $this->arResult["SERVICE"] = $response["result"];
                if ('yes' === $_REQUEST['fsp']) {
                    $response = \Bitrix\Main\Web\Json::decode(\travelsoft\booking\Gateway::serviceToPaidOnTheSpot(array(
                        "url" => \Bitrix\Main\Config\Option::get("travelsoft.booking.dev.tools", "tsmo_url"),
                        "params" => array("token" => $_SESSION["__TRAVELSOFT"]["TOKEN"], "serviceId" => $this->arParams["KEY"])
                    )));
                    LocalRedirect($GLOBALS['APPLICATION']->GetCurPageParam('', array('fsp'), false));
                } elseif ('no' === $_REQUEST['fsp']) {
                    $response = \Bitrix\Main\Web\Json::decode(\travelsoft\booking\Gateway::serviceNotToPaidOnTheSpot(array(
                        "url" => \Bitrix\Main\Config\Option::get("travelsoft.booking.dev.tools", "tsmo_url"),
                        "params" => array("token" => $_SESSION["__TRAVELSOFT"]["TOKEN"], "serviceId" => $this->arParams["KEY"])
                    )));
                    LocalRedirect($GLOBALS['APPLICATION']->GetCurPageParam('', array('fsp'), false));
                }
            } else {
                $this->arResult["ERRORS"][] = "UNKNOWN_ERROR";
            }

        } else {
            $this->arResult["ERRORS"][] = "UNKNOWN_ORDER_ID";
        }
        
    }
    
    /** подключение модулей */
    protected function __include_modules () {
        if (!\Bitrix\Main\Loader::includeModule("travelsoft.booking.dev.tools")) {
            throw new Exception("Не найден модуль \"инструменты бронирования\" ");
        }
    }

    /** проверка, что пользователь пришёл со страницы */
    /*protected function __check_just_booked () {
        if ($_SESSION["__TRAVELSOFT"]["JUST_BOOKED"] == "Y") {
            $this->arResult["JUST_BOOKED"] = true;
            unset($_SESSION["__TRAVELSOFT"]["JUST_BOOKED"]);
        }
    }
    
    protected function _procActionRequest () {
        
        if (check_bitrix_sessid() && $this->arParams["ORDER_ID"]) {
            
            if ($_REQUEST['action'] == "cancel") {

                $response = \Bitrix\Main\Web\Json::decode(\travelsoft\booking\Gateway::orderToCancel(array(
                    "url" => \Bitrix\Main\Config\Option::get("travelsoft.booking.dev.tools", "tsmo_url"),
                    "params" => array("token" => $_SESSION["__TRAVELSOFT"]["TOKEN"], "dogovor_code" => $this->arParams["ORDER_ID"], "service_keys" => array()) 
                )));
                
                if ($response["result"]["result"]) {
                    LocalRedirect($GLOBALS["APPLICATION"]->GetCurPageParam("order_id=" . $this->arParams["ORDER_ID"], array("order_id", "action", "sessid")));
                } else {
                    $this->arResult["ERRORS"][] = "CANCEL_FAIL";
                }
                
            }
            
        }

    }*/
    
    public function executeComponent() {
        
        $this->__include_modules();
        
        //$this->__check_just_booked();
        
        $this->__setOrderDetail();
        
        //$this->_procActionRequest();
        
        $this->IncludeComponentTemplate();
        
    }
    
}

