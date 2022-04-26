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
        if (!empty($arParams['ADDITIOANL_FILTR_TYPE'])) $filtrtmp  = array("HLB_ID" => SERVICES_BOOKING_HL_BLOCK, "UF_USER_ID" => $arResult['FILTER_USER_ID'], 'UF_SERVICE_TYPE_NAME'=>$arParams['ADDITIOANL_FILTR_TYPE']);
        else $filtrtmp = array("HLB_ID" => SERVICES_BOOKING_HL_BLOCK, "UF_USER_ID" => $arResult['FILTER_USER_ID']);
        $arResult['SERVICES_HL_ELEMENTS'] = $APPLICATION->IncludeComponent("travelsoft:travelsoft.highloadblock.getlist.byfilter", "", 
            Array(
                    "CACHE_TIME" => "0",
                    "CACHE_TYPE" => "N",
                    "CNT" => "",
                    "FILTER" => $filtrtmp,
                    "FILTER_NAME" => "",
                    "ORDER" => "ASC",
                    "RETURN_RESULT" => "Y",
                    "SORT" => "UF_NAME",
                    "TITLE" => ""
            )
        );
        
        // ТИПЫ ЦЕН
        $arResult['PRICE_TYPES_HL_ELEMENTS'] = $APPLICATION->IncludeComponent("travelsoft:travelsoft.highloadblock.getlist.byfilter", "", 
            Array(
                    "CACHE_TIME" => "0",
                    "CACHE_TYPE" => "N",
                    "CNT" => "",
                    "FILTER" => array("HLB_ID" => \travelsoft\booking\Utils::getOpt('pricetypes'), array(array("LOGIC" => "OR", array("UF_USER_ID" => $arResult['FILTER_USER_ID']), array("UF_USER_ID" => FALSE)))),
                    "FILTER_NAME" => "",
                    "ORDER" => "ASC",
                    "RETURN_RESULT" => "Y",
                    "SORT" => "ID",
                    "TITLE" => "",
            )
        );

        if ($arParams['ROW_ID']) {

            $result = $APPLICATION->IncludeComponent("travelsoft:travelsoft.highloadblock.getlist.byfilter", "", 
                Array(
                    
                        "CACHE_TIME" => "0",
                        "CACHE_TYPE" => "N",
                        "CNT" => "",
                        "FILTER" => array("HLB_ID" => \travelsoft\booking\Utils::getOpt('ptrates'), "UF_RATE_CATEGORY_ID" => $arParams['ROW_ID']),
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
                    "FILTER" => array("HLB_ID" => \travelsoft\booking\Utils::getOpt('food')),
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
