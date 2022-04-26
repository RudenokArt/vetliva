<?php

use Bitrix\Main\Config\Option;

use travelsoft\vetliva\DBHistory;

/**
 * Класс HistoryEeventsHandlers
 * 
 * Класс для обработки событий bitrix для ведения истории
 * @author dimabresky
 * @copyright (c) 2017, travelsoft
 */
class TravelsoftVetlivaHistoryEventsHandlers {
    
    public static function onAfterIBlockElementDelete (array $arFields) {
        
        $arIblocks = explode(";", Option::get("travelsoft.vetliva.history", "follow_by_iblocks"));
        
        if ( in_array($arFields["IBLOCK_ID"], $arIblocks) && $arFields['ID'] > 0) {
            
            $parameters = array (
                "UF_STORE_ID" => $arFields['IBLOCK_ID'],
                "UF_ELEMENT_ID" => $arFields['ID'],
                "UF_OBJECT" => "IBLOCK_ELEMENT",
                "UF_ACTION" => "DELETE",
                "UF_DETAIL_INFO" => travelsoft\vetliva\ats(array("FIELDS" => $arFields))
            );

            DBHistory::getInstance()->save($parameters);
        }
        
    }
    
    public static function onAfterIBlockElementAdd (array $arFields) {
        
        $arIblocks = explode(";", Option::get("travelsoft.vetliva.history", "follow_by_iblocks"));
        
        if ( in_array($arFields["IBLOCK_ID"], $arIblocks) && $arFields['ID'] > 0) {
            
            $dbElement = \CIBlockElement::GetByID($arFields['ID'])->GetNextElement();
            
            if ($dbElement) {
                
                $parameters = array (
                    "UF_STORE_ID" => $arFields['IBLOCK_ID'],
                    "UF_ELEMENT_ID" => $arFields['ID'],
                    "UF_OBJECT" => "IBLOCK_ELEMENT",
                    "UF_ACTION" => "ADD",
                    "UF_DETAIL_INFO" => travelsoft\vetliva\ats(array(
                        "FIELDS" => $dbElement->GetFields(),
                        "PROPERTIES" => $dbElement->GetProperties()
                    ))
                );

                DBHistory::getInstance()->save($parameters);
            }
        }
        
    }
    
    public static function onAfterIBlockElementUpdate (array $arFields) {
        
        $arIblocks = explode(";", Option::get("travelsoft.vetliva.history", "follow_by_iblocks"));
        
        if ( in_array($arFields["IBLOCK_ID"], $arIblocks) &&  $arFields['ID'] > 0) {
            
            $dbElement = \CIBlockElement::GetByID($arFields['ID'])->GetNextElement();
            
            if ($dbElement) {
                
                $parameters = array (
                    "UF_STORE_ID" => $arFields['IBLOCK_ID'],
                    "UF_ELEMENT_ID" => $arFields['ID'],
                    "UF_OBJECT" => "IBLOCK_ELEMENT",
                    "UF_ACTION" => "UPDATE",
                    "UF_DETAIL_INFO" => travelsoft\vetliva\ats(array(
                        "FIELDS" => $dbElement->GetFields(),
                        "PROPERTIES" => $dbElement->GetProperties()
                    ))
                );

                DBHistory::getInstance()->save($parameters);
            }
        }
        
    }
    
    public static function onBeforeHighloadElementDelete ($storeId, $arElement) {
        
        $arHighloadblocks = explode(";", Option::get("travelsoft.vetliva.history", "follow_by_highloadblocks"));
        
        if ( in_array($storeId, $arHighloadblocks) ) {
            Option::delete("travelsoft.vetliva.history",  array('name' => "tmp_highloadblock_element_f_before_delete_{$arElement["ID"]}"));
            $dataClass = \travelsoft\vetliva\getHLDataClass($storeId);
            $arFields = $dataClass::getList(array(
                "filter" => array("ID" => $arElement["ID"])
            ))->fetchAll();
            Option::set("travelsoft.vetliva.history", "tmp_highloadblock_element_f_before_delete_{$arElement["ID"]}", travelsoft\vetliva\ats($arFields[0]));
        }
    }
    
    public static function onAfterHighloadElementDelete ($storeId, $arElement) {
        
        $arHighloadblocks = explode(";", Option::get("travelsoft.vetliva.history", "follow_by_highloadblocks"));
        if ( in_array($storeId, $arHighloadblocks) ) {
            $arFieldsStr = Option::get("travelsoft.vetliva.history", "tmp_highloadblock_element_f_before_delete_{$arElement["ID"]}");
            $arFields = travelsoft\vetliva\sta($arFieldsStr);
            if (is_array($arFields["UF_SERVICE_ID"])) $service_id = $arFields["UF_SERVICE_ID"]['VALUE'];
            elseif ($arFields["UF_SERVICE_ID"]) $service_id = $arFields["UF_SERVICE_ID"];            
            Option::delete("travelsoft.vetliva.history",  array('name' => "tmp_highloadblock_element_f_before_delete_{$arElement["ID"]}"));
            $parameters = array (
                "UF_STORE_ID" => $storeId,
                "UF_ELEMENT_ID" => $arElement["ID"],
                "UF_SERVICE_ID" => $service_id ?: travelsoft\vetliva\tryGetServiceId($arElement["ID"], $storeId),
                "UF_OBJECT" => "HIGHLOADBLOCK_ELEMENT",
                "UF_ACTION" => "DELETE",
                "UF_DETAIL_INFO" => travelsoft\vetliva\ats(["BEFORE_CHANGE" => [], "CHANGE" => $arFields])
            );

            DBHistory::getInstance()->save($parameters);
        }
        
    }
    
    public static function onBeforeHighloadElementUpdate ($storeId, $arElement) {
        
        $arHighloadblocks = explode(";", Option::get("travelsoft.vetliva.history", "follow_by_highloadblocks"));
        
        if ( in_array($storeId, $arHighloadblocks) ) {
            Option::delete("travelsoft.vetliva.history",  array('name' => "tmp_highloadblock_element_f_before_update_{$arElement["ID"]}"));
            $dataClass = \travelsoft\vetliva\getHLDataClass($storeId);
            $arFields = $dataClass::getList(array(
                "filter" => array("ID" => $arElement["ID"])
            ))->fetchAll();
            Option::set("travelsoft.vetliva.history", "tmp_highloadblock_element_f_before_update_{$arElement["ID"]}", travelsoft\vetliva\ats($arFields[0]));
        }
        
    }
    
    public static function onAfterHighloadElementUpdate ($storeId, $arElement, $arFields) {
        
        $arHighloadblocks = explode(";", Option::get("travelsoft.vetliva.history", "follow_by_highloadblocks"));
        $dataClass = \travelsoft\vetliva\getHLDataClass($storeId);
        $arFieldsNew = $dataClass::getList(array(
            "filter" => array("ID" => $arElement["ID"])
        ))->fetchAll();
        
        if ( in_array($storeId, $arHighloadblocks) ) {
            if (is_array($arFields["UF_SERVICE_ID"])) $service_id = $arFields["UF_SERVICE_ID"]['VALUE'];
            elseif ($arFields["UF_SERVICE_ID"]) $service_id = $arFields["UF_SERVICE_ID"];
            $arFieldsStr = Option::get("travelsoft.vetliva.history", "tmp_highloadblock_element_f_before_update_{$arElement["ID"]}");
            Option::delete("travelsoft.vetliva.history",  array('name' => "tmp_highloadblock_element_f_before_update_{$arElement["ID"]}"));
            $parameters = array (
                "UF_STORE_ID" => $storeId,
                "UF_ELEMENT_ID" => $arElement["ID"],
                "UF_SERVICE_ID" => $service_id ?: travelsoft\vetliva\tryGetServiceId($arElement["ID"], $storeId),
                "UF_OBJECT" => "HIGHLOADBLOCK_ELEMENT",
                "UF_ACTION" => "UPDATE",
                "UF_DETAIL_INFO" => travelsoft\vetliva\ats(["BEFORE_CHANGE" => travelsoft\vetliva\sta($arFieldsStr), "CHANGE" => $arFieldsNew[0]])
            );
            DBHistory::getInstance()->save($parameters);
        }
        
    }
    
   
    
    public static function onAfterHighloadElementAdd ($storeId, $elementId, $arFields) {
        
        $arHighloadblocks = explode(";", Option::get("travelsoft.vetliva.history", "follow_by_highloadblocks"));

        if ( in_array($storeId, $arHighloadblocks) ) {
            if (is_array($arFields["UF_SERVICE_ID"])) $service_id = $arFields["UF_SERVICE_ID"]['VALUE'];
            elseif ($arFields["UF_SERVICE_ID"]) $service_id = $arFields["UF_SERVICE_ID"];
            $parameters = array (
                "UF_STORE_ID" => $storeId,
                "UF_ELEMENT_ID" => $elementId,
                "UF_SERVICE_ID" => $service_id ?: travelsoft\vetliva\tryGetServiceId($elementId, $storeId),
                "UF_OBJECT" => "HIGHLOADBLOCK_ELEMENT",
                "UF_ACTION" => "ADD",
                "UF_DETAIL_INFO" => travelsoft\vetliva\ats(["BEFORE_CHANGE" => [], "CHANGE" => $arFields])
            );

            DBHistory::getInstance()->save($parameters);
        }
    }
    
    public static function onBeforeUserDelete (int $ID) {
        Option::delete("travelsoft.vetliva.history",  array('name' => 'tmp_users_fields_before_delete'));
        Option::set("travelsoft.vetliva.history", "tmp_users_fields_before_delete", travelsoft\vetliva\ats($GLOBALS["USER"]->GetByID($ID)->Fetch()));
    }
    
    public static function onAfterUserDelete (int $ID) {
        
        if ($ID > 0) {
            $arFieldsStr = Option::get("travelsoft.vetliva.history", "tmp_users_fields_before_delete");
            Option::delete("travelsoft.vetliva.history",  array('name' => 'tmp_users_fields_before_delete'));
            $parameters = array (
                "UF_ELEMENT_ID" => $ID,
                "UF_OBJECT" => "USER",
                "UF_ACTION" => "DELETE",
                "UF_DETAIL_INFO" => $arFieldsStr ? $arFieldsStr : ""
            );

            DBHistory::getInstance()->save($parameters);
        }
    }
    
    public static function onAfterUserUpdate (array $arFields) {
        
        if ($arFields["ID"] > 0) {
            
            $parameters = array (
                "UF_ELEMENT_ID" => $arFields['ID'],
                "UF_OBJECT" => "USER",
                "UF_ACTION" => "UPDATE",
                "UF_DETAIL_INFO" => travelsoft\vetliva\ats($arFields)
            );

            DBHistory::getInstance()->save($parameters);
        }
        
    }
    
    public static function onAfterUserAdd (array $arFields) {
        
        if ($arFields["ID"] > 0) {
            
            $parameters = array (
                "UF_ELEMENT_ID" => $arFields['ID'],
                "UF_OBJECT" => "USER",
                "UF_ACTION" => "ADD",
                "UF_DETAIL_INFO" => travelsoft\vetliva\ats($arFields)
            );
            DBHistory::getInstance()->save($parameters);
        }    
    }
    
}

