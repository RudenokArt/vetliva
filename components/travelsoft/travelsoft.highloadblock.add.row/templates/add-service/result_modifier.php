<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

switch ($arParams['SERVICE_TYPE_ID']) {
    
    case SANATORIUM_SERVICE_TYPE_ID:
        
        $iblock_id = SANATORIUM_IBLOCK_ID;
        
        break;
    
    case HOTEL_SERVICE_TYPE_ID:
        
        $iblock_id = PLACEMENTS_IBLOCK_ID;
        
        break;
    
    case TOUR_SERVICE_TYPE_ID:
        break;
}

if ($iblock_id) {
        
        // ДОСТУПНЫЕ ЕЛЕМЕНТЫ ТИПОВ УСЛУГ
        $rs = $APPLICATION->IncludeComponent("travelsoft:travelsoft.iblock.getlist.byfilter", "", 
                Array(
                        "CACHE_TIME" => "0",
                        "CACHE_TYPE" => "N",
                        "CNT" => "",
                        "FILTER" => array("IBLOCK_ID" => $iblock_id, "PROPERTY_USER" => $arParams['SUPER_USER_EDIT'] === 'Y' && $_REQUEST['provider_id'] > 0 ? $_REQUEST['provider_id'] : $USER->GetID()),
                        "FILTER_NAME" => "",
                        "ORDER" => "DESC",
                        "RETURN_RESULT" => "Y",
                        "SORT" => "ID",
                        "TITLE" => ""
                )
        );
        
        $arResult['SERVICE_TYPE_ELEMENTS'] = $rs["ITEMS"];
}

if ($arParams['SERVICE_TYPE_ID']) {
        // ДОСТУПНЫЕ HL ЭЛЕМЕНТЫ
        $arResult['HL_ELEMENTS'] = $APPLICATION->IncludeComponent("travelsoft:travelsoft.highloadblock.getlist.byfilter", "", 
            Array(
                    "CACHE_TIME" => "0",
                    "CACHE_TYPE" => "N",
                    "CNT" => "",
                    "FILTER" => array("HLB_ID" => $arParams['BLOCK_ID'], "UF_SERVICE_TYPE_NAME" => $arParams['SERVICE_TYPE_ID']),
                    "FILTER_NAME" => "",
                    "ORDER" => "ASC",
                    "RETURN_RESULT" => "Y",
                    "SORT" => "UF_NAME",
                    "TITLE" => ""
            )
        );
}

// УДОБСТВА В НОМЕРЕ
$rs = $APPLICATION->IncludeComponent("travelsoft:travelsoft.iblock.getlist.byfilter", "", 
                Array(
                        "CACHE_TIME" => "0",
                        "CACHE_TYPE" => "N",
                        "CNT" => "",
                        "FILTER" => array("IBLOCK_ID" => SERVICES_IBLOCK_ID),
                        "FILTER_NAME" => "",
                        "ORDER" => "DESC",
                        "RETURN_RESULT" => "Y",
                        "SORT" => "ID",
                        "TITLE" => ""
                )
        );

$arResult['ROOM_SERVICES'] = $rs["ITEMS"];