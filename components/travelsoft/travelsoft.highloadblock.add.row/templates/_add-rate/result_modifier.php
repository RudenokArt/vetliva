<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();


        // ДОСТУПНЫЕ HL ЭЛЕМЕНТЫ
        $arResult['HL_ELEMENTS'] = $APPLICATION->IncludeComponent("travelsoft:travelsoft.highloadblock.getlist.byfilter", "", 
            Array(
                    "CACHE_TIME" => "0",
                    "CACHE_TYPE" => "N",
                    "CNT" => "",
                    "FILTER" => array("HLB_ID" => $arParams['BLOCK_ID']),
                    "FILTER_NAME" => "",
                    "ORDER" => "ASC",
                    "RETURN_RESULT" => "Y",
                    "SORT" => "UF_NAME",
                    "TITLE" => ""
            )
        );
        
        // ДОСТУПНЫЕ УСЛУГИ
        $arResult['SERVICES_HL_ELEMENTS'] = $APPLICATION->IncludeComponent("travelsoft:travelsoft.highloadblock.getlist.byfilter", "", 
            Array(
                    "CACHE_TIME" => "0",
                    "CACHE_TYPE" => "N",
                    "CNT" => "",
                    "FILTER" => array("HLB_ID" => SERVICES_BOOKING_HL_BLOCK, "UF_USER_ID" => $GLOBALS['USER']->GetID()),
                    "FILTER_NAME" => "",
                    "ORDER" => "ASC",
                    "RETURN_RESULT" => "Y",
                    "SORT" => "UF_NAME",
                    "TITLE" => ""
            )
        );
        
        // ТИПЫ ЦЕН
//        $arResult['PRICE_TYPES_HL_ELEMENTS'] = $APPLICATION->IncludeComponent("travelsoft:travelsoft.highloadblock.getlist.byfilter", "", 
//            Array(
//                    "CACHE_TIME" => "0",
//                    "CACHE_TYPE" => "N",
//                    "CNT" => "",
//                    "FILTER" => array("HLB_ID" => PRICE_TYPES_BOOKING_HL_BLOCK, array(array("LOGIC" => "OR", array("UF_USER_ID" => $GLOBALS['USER']->GetID()), array("UF_USER_ID" => FALSE)))),
//                    "FILTER_NAME" => "",
//                    "ORDER" => "ASC",
//                    "RETURN_RESULT" => "Y",
//                    "SORT" => "UF_NAME",
//                    "TITLE" => "",
//            )
//        );

        if ($arParams['ROW_ID']) {

            $result = $APPLICATION->IncludeComponent("travelsoft:travelsoft.highloadblock.getlist.byfilter", "", 
                Array(
                    
                        "CACHE_TIME" => "0",
                        "CACHE_TYPE" => "N",
                        "CNT" => "",
                        "FILTER" => array("HLB_ID" => PRICE_TYPES_PLUSE_RATES_BOOKING_HL_BLOCK, "UF_RATE_CATEGORY_ID" => $arParams['ROW_ID']),
                        "FILTER_NAME" => "",
                        "ORDER" => "ASC",
                        "RETURN_RESULT" => "Y",
                        "SORT" => "ID",
                        "TITLE" => "",
                )
            );
            
            //ТИПЫ ЦЕН + ТАРИФЫ ID
            foreach ($result['rows'] as $row) {
                $arResult['pt'][] = $row['UF_RATE_ID'];
            }
            
        }
        
        // FOOD
        $arResult['FOOD_HL_ELEMENTS'] = $APPLICATION->IncludeComponent("travelsoft:travelsoft.highloadblock.getlist.byfilter", "", 
            Array(
                    "CACHE_TIME" => "0",
                    "CACHE_TYPE" => "N",
                    "CNT" => "",
                    "FILTER" => array("HLB_ID" => FOOD_BOOKING_HL_BLOCK),
                    "FILTER_NAME" => "",
                    "ORDER" => "ASC",
                    "RETURN_RESULT" => "Y",
                    "SORT" => "UF_NAME_RU",
                    "TITLE" => "",
            )
        );
        
        // ВАЛЮТА
        Bitrix\Main\Loader::includeModule("travelsoft.currency");
        $arResult['CURRENCY'] = \travelsoft\Currency::getInstance()->get('currency');
