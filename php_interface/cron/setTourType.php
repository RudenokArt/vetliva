<?php

$_SERVER["DOCUMENT_ROOT"] = realpath(dirname(__FILE__)."/../../..");
$DOCUMENT_ROOT = $_SERVER["DOCUMENT_ROOT"];



require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
@set_time_limit(0);
\Bitrix\Main\Loader::includeModule("iblock");
\Bitrix\Main\Loader::includeModule("travelsoft.booking.dev.tools");
// set default
$arFilter = array("IBLOCK_ID" => 8, "ACTIVE" => "Y", "!PROPERTY_TYPE_TARIF"=>false);
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
if ($arElements["ELEMENT_ID"]) 
    foreach ($arElements["ELEMENT_ID"] as $id) 
        CIBlockElement::SetPropertyValuesEx($id, 8, array("TYPE_TARIF" => false));

$rates = (new \travelsoft\booking\datastores\RatesDataStore(array("filter" => [0=>['!UF_TYPE_TARIF'=>false]])))->fetch(['ID']);
    if (!empty($rates)) {
        $ratetypes = [];
        foreach ($rates as $ratetmp) 
            foreach ($ratetmp[0]['UF_SERVICES_ID'] as $service_id)
               $ratetypes[$service_id][] = $ratetmp[0]['UF_TYPE_TARIF']; 
    
    
        $arFilter = array("IBLOCK_ID" => 8, "ACTIVE" => "Y");
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
            $parameters["adults"] = 2;
            $parameters["date_from"] = strtotime(date('d.m.Y'));
            $parameters["date_to"] = strtotime(date('d.m.Y',strtotime("+1 day")));
            
            $result = $APPLICATION->IncludeComponent(
                    "travelsoft:travelsoft.service.price.result", "on.detail.page.render", Array(
                "RETURN_RESULT" => "Y",
                        "CACHE_TYPE" => "A",
                "CACHE_TIME" => 3600,
                "FILTER_BY_PRICES_FOR_CITIZEN" =>  "N",
                "TYPE" => "sanatorium",
                "MAKE_ORDER_PAGE" => "/booking/",
                "POSTFIX_PROPERTY" => POSTFIX_PROPERTY,
                "__BOOKING_REQUEST" => $parameters,
                        "MP" => "N"
                    )
            );
            $elements_type = [];
            $exist_services = array_keys($ratetypes);
            foreach ($result as $element_id =>$tmpdata) {
                $need_serviceid = array_keys($tmpdata);
                $elements_type[$element_id] = [];
                foreach ($need_serviceid as $tmpid) 
                    if (in_array($tmpid, $exist_services))
                        $elements_type[$element_id] = array_merge($ratetypes[$tmpid],$elements_type[$element_id]);
                
            }
            // set true types
            $el = new CIBlockElement;
            foreach ($elements_type as $element_id=>$typedata)
                if (count($typedata)) {
                    CIBlockElement::SetPropertyValues($element_id, 8, array_unique($typedata), "TYPE_TARIF");
                    $res = $el->Update($element_id, ['ACTIVE'=>'Y']);
                }
            $index = \Bitrix\Iblock\PropertyIndex\Manager::createIndexer(8);
                    
        }
    
    }
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");