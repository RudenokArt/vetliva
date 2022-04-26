<?php

/**
 * travelsoft.booking.messanger
 * @author dimabresky
 * @copyright (c) 2017, travelsoft
 */

class TravelsoftBookingMessanger extends CBitrixComponent {
    
    public function prepareInputParameters () {
        
        if (!Bitrix\Main\Loader::includeModule("travelsoft.booking.dev.tools")) {
            throw new Exception("travelsoft.booking.messager: Модуль travelsoft.booking.dev.tools не найден");
        }
        
        if (strlen($this->arParams["ORDER_ID"]) <= 0) {
            throw new Exception("travelsoft.booking.messager: Укажите номер заказа");
        }
        
        if (preg_match("#^\d{2}\.\d{2}\.\d{4} \d{2}\:\d{2}\:\d{2}$#", $this->arParams["DATE_FROM"]) !== 1) {
            throw new Exception("travelsoft.booking.messager: Укажите дату с которой показывать сообщения в формате DD.MM.YYYY HH:MM:SS");
        }
        
        if (strlen($this->arParams["TOKEN"]) <= 0) {
            throw new Exception("travelsoft.booking.messager: Укажите token пользователя");
        }
        
        $this->arParams["MAX_LENGTH"] = 254;
        
        if ($this->arParams["USE_AJAX"] == "Y") {
            $this->arParams["FREQREQUEST"] = intVal($this->arParams["FREQREQUEST"]);
            if ($this->arParams["FREQREQUEST"] <= 0) {
                $this->arParams["FREQREQUEST"] = 30;
            }
            $_SESSION["__TRAVELSOFT"]["CTBM"] = $this->arParams;
        }
        
    }
    
    public function executeComponent() {
        
        try {
            
            unset($_SESSION["__TRAVELSOFT"]["CTBM"]);
            
            $this->prepareInputParameters();
            
            if ($_SERVER["REQUEST_METHOD"] === "POST" && check_bitrix_sessid() && strlen($_POST['send_message'])> 0) {
                if ($this->sendMessage(strip_tags($_POST['message']))) {
                    LocalRedirect($GLOBALS["APPLICATION"]->GetCurPageParam("", array(), false));
                }                
            }
            
            $this->arResult = $this->getMessages($this->arParams["DATE_FROM"]);
            $this->IncludeComponentTemplate();
            
        } catch (\Exception $e) {
            ShowError($e->getMessage());
        }
    }
    
    /**
     * Запрос на получения списка сообщений переписки
     * @param string $date_from
     * @return array|boolean
     */
    public function getMessages (string $date_from) {
        
        $arResponse = \Bitrix\Main\Web\Json::decode(\travelsoft\booking\Gateway::getBookingMessages(array(
            "url" => Bitrix\Main\Config\Option::get("travelsoft.booking.dev.tools", "tsmo_url"),
            "params" => array(
                "token" => $this->arParams["TOKEN"],
                "dogovor_code" => $this->arParams["ORDER_ID"],
                "min_date_time" => $date_from
            )
        )));
        
        $arResult = array("DATE_FROM" => $date_from);
        
        if ($arResponse["result"]) {
                        
            foreach ($arResponse["result"] as $arItem) {
                
                $arResult["MESSAGES"][] = array(
                    "IS_MANAGER" => boolval($arItem["in_out"]),
                    "DATE" => $arItem["date"],
                    "UNIX" => MakeTimeStamp($arItem["date"]),
                    "MESSAGE" => $arItem["text"]
                );
                
                usort($arResult["MESSAGES"], function ($a, $b) {
                    return $a["UNIX"] < $b["UNIX"];
                });
                
            }
        }
        
        if ($arResponse["error"]) {
            return false;
        }
        
        return $arResult;
    }
    
    /**
     * Отправка сообщения в ПК-МастерТур
     * @param string $message
     * @return boolean|array
     */
    public function sendMessage (string $message) {
        
        if (strlen($message) > 0) {
            $dateFrom = date("d.m.Y H:i:s");
            $message = substr($message, 0, $this->arParams["MAX_LENGTH"]);
            $arResponse = \Bitrix\Main\Web\Json::encode(\travelsoft\booking\Gateway::sendBookingMessage(array(
                "url" => Bitrix\Main\Config\Option::get("travelsoft.booking.dev.tools", "tsmo_url"),
                "params" => array(
                    "token" => $this->arParams["TOKEN"],
                    "dogovor_code" => $this->arParams["ORDER_ID"],
                    "text" => $message
                )
            )));

            if ($arResponse["result"]["result"]) {
                return array(
                        "DATE_FROM" => $dateFrom,
                        "MESSAGES" => array(
                            array(
                                "IS_MANAGER" => false,
                                "DATE" => date("d.m.Y H:i:s"),
                                "MESSAGE" => $message
                            )
                        )
                    );
            }
        }
        
        return false;
    }
    
}
