<?php

$_SERVER["DOCUMENT_ROOT"] = realpath(dirname(__FILE__)."/../../..");
$DOCUMENT_ROOT = $_SERVER["DOCUMENT_ROOT"];



require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
@set_time_limit(0);
\Bitrix\Main\Loader::includeModule("iblock");
\Bitrix\Main\Loader::includeModule("travelsoft.booking.dev.tools");
$arFilter = array("IBLOCK_ID" => 33, "ACTIVE" => "Y", "PROPERTY_IS_EXCURSION_TOUR"=>false);
    
    $arElements = $APPLICATION->IncludeComponent("travelsoft:travelsoft.iblock.getlist.byfilter", "", Array(
        "CACHE_TIME" => 3600,
        "CACHE_TYPE" => "A",
        "CNT" => 999999,
        "FILTER" => $arFilter,
        "FILTER_NAME" => $arParams["FILTER_NAME"],
        "ORDER" => array($arParams["SORT_BY1"] => $arParams["ORDER_BY1"]),
        "RETURN_RESULT" => "Y",
        "SORT" => false,
        "SELECT" => array("ID"),
        "TITLE" => ""
            )
    );
    if ($arElements["ELEMENT_ID"]) {

        $parameters = $arParams["__BOOKING_REQUEST"];
        $parameters["id"] = $arElements["ELEMENT_ID"];

        $result = $APPLICATION->IncludeComponent(
                "travelsoft:travelsoft.service.price.result", "on.detail.page.render", Array(
            "RETURN_RESULT" => "Y",
                    "CACHE_TYPE" => "A",
            "CACHE_TIME" => 3600,
            "FILTER_BY_PRICES_FOR_CITIZEN" =>  "N",
            "TYPE" => "excursions",
            "MAKE_ORDER_PAGE" => "/booking/",
            "POSTFIX_PROPERTY" => POSTFIX_PROPERTY,
            "__BOOKING_REQUEST" => $parameters,
                    "MP" => "Y"
                )
        );
    }
    $factor = 100;
   
        // ÑÁÐÀÑÛÂÀÅÌ ÏÎËÅ SORY_BY_PRICE
        $dbRes = CIBlockElement::GetList(false, $arFilter, false, false, array("ID"));
        
        $sortIndexASC = (count($result) + 1) * $factor;
        $sortIndex = 0;
        while ($arRes = $dbRes->Fetch()) {
            CIBlockElement::SetPropertyValuesEx($arRes["ID"], 33, array("SORT_BY_PRICE" => $sortIndex));
            CIBlockElement::SetPropertyValuesEx($arRes["ID"], 33, array("SORT_BY_PRICE_ASC" => $sortIndexASC));
            
        }
    

        $calc = $result;

            uasort($calc, function ($a, $b) {
                return $a["PRICE"] - $b["PRICE"];
            });
            $i = 1;
            foreach ($calc as $id => $arr) {
                CIBlockElement::SetPropertyValuesEx($id, 33, array("SORT_BY_PRICE_ASC" => $i * $factor));
                \Bitrix\Iblock\PropertyIndex\Manager::updateElementIndex(33, $arRes["ID"]);
                $i++;
            }
   
            uasort($calc, function ($a, $b) {
                return $b["PRICE"] - $a["PRICE"];
            });
            $i = count($calc);
            foreach ($calc as $id => $arr) {
                CIBlockElement::SetPropertyValuesEx($id, 33, array("SORT_BY_PRICE" => $i * $factor));
                \Bitrix\Iblock\PropertyIndex\Manager::updateElementIndex(33, $arRes["ID"]);
                $i--;
            }
$arFilter = array("IBLOCK_ID" => 33, "ACTIVE" => "Y", "!PROPERTY_IS_EXCURSION_TOUR"=>false);
    
    $arElements = $APPLICATION->IncludeComponent("travelsoft:travelsoft.iblock.getlist.byfilter", "", Array(
        "CACHE_TIME" => 3600,
        "CACHE_TYPE" => "A",
        "CNT" => 999999,
        "FILTER" => $arFilter,
        "FILTER_NAME" => $arParams["FILTER_NAME"],
        "ORDER" => array($arParams["SORT_BY1"] => $arParams["ORDER_BY1"]),
        "RETURN_RESULT" => "Y",
        "SORT" => false,
        "SELECT" => array("ID"),
        "TITLE" => ""
            )
    );
    if ($arElements["ELEMENT_ID"]) {

        $parameters = $arParams["__BOOKING_REQUEST"];
        $parameters["id"] = $arElements["ELEMENT_ID"];

        $result = $APPLICATION->IncludeComponent(
                "travelsoft:travelsoft.service.price.result", "on.detail.page.render", Array(
            "RETURN_RESULT" => "Y",
                    "CACHE_TYPE" => "A",
            "CACHE_TIME" => 3600,
            "FILTER_BY_PRICES_FOR_CITIZEN" => "N",
            "TYPE" => "excursionstours",
            "MAKE_ORDER_PAGE" => "/booking/",
            "POSTFIX_PROPERTY" => POSTFIX_PROPERTY,
            "__BOOKING_REQUEST" => $parameters,
                    "MP" => "Y"
                )
        );
    }
    $factor = 100;
   
        // ÑÁÐÀÑÛÂÀÅÌ ÏÎËÅ SORY_BY_PRICE
        $dbRes = CIBlockElement::GetList(false, $arFilter, false, false, array("ID"));
        
        $sortIndexASC = (count($result) + 1) * $factor;
        $sortIndex = 0;
        while ($arRes = $dbRes->Fetch()) {
            CIBlockElement::SetPropertyValuesEx($arRes["ID"], 33, array("SORT_BY_PRICE" => $sortIndex));
            CIBlockElement::SetPropertyValuesEx($arRes["ID"], 33, array("SORT_BY_PRICE_ASC" => $sortIndexASC));
            
        }
    

        $calc = $result;

            uasort($calc, function ($a, $b) {
                return $a["PRICE"] - $b["PRICE"];
            });
            $i = 1;
            foreach ($calc as $id => $arr) {
                CIBlockElement::SetPropertyValuesEx($id, 33, array("SORT_BY_PRICE_ASC" => $i * $factor));
                \Bitrix\Iblock\PropertyIndex\Manager::updateElementIndex(33, $arRes["ID"]);
                $i++;
            }
   
            uasort($calc, function ($a, $b) {
                return $b["PRICE"] - $a["PRICE"];
            });
            $i = count($calc);
            foreach ($calc as $id => $arr) {
                CIBlockElement::SetPropertyValuesEx($id, 33, array("SORT_BY_PRICE" => $i * $factor));
                \Bitrix\Iblock\PropertyIndex\Manager::updateElementIndex(33, $arRes["ID"]);
                $i--;
            }

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");