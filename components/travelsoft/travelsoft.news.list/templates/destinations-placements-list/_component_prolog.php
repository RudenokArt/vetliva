<?

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();

#######################
# СОРТИРОВКА
#######################

function prepareSortByPrice(array $arParams = null) {

    \Bitrix\Main\Loader::includeModule("iblock");
    \Bitrix\Main\Loader::includeModule("travelsoft.booking.dev.tools");
    $factor = 100;
    
    if (!$arParams["__BOOKING_REQUEST"]) {
        // СБРАСЫВАЕМ ПОЛЕ SORY_BY_PRICE
        $dbRes = CIBlockElement::GetList(false, array("IBLOCK_ID" => $arParams['IBLOCK_ID'], "ACTIVE" => "Y"), false, false, array("ID"));
        if ($_REQUEST["order"] == "asc" && $arParams["CALCULATION_PRICE_RESULT"]) {
            $sortIndex = (count($arParams["CALCULATION_PRICE_RESULT"]) + 1) * $factor;
        } else {
            $sortIndex = 0;
        }
        
        while ($arRes = $dbRes->Fetch()) {
            CIBlockElement::SetPropertyValuesEx($arRes["ID"], $arParams['IBLOCK_ID'], array("SORT_BY_PRICE" => $sortIndex));
        }
    }
    if ($arParams["CALCULATION_PRICE_RESULT"]) {
        $calc = $arParams["CALCULATION_PRICE_RESULT"];
        if ($_REQUEST["order"] == "asc") {
            uasort($calc, function ($a, $b) {
                return $a["PRICE"] - $b["PRICE"];
            });
            $i = 1;
            foreach ($calc as $id => $arr) {
                CIBlockElement::SetPropertyValuesEx($id, $arParams['IBLOCK_ID'], array("SORT_BY_PRICE" => $i * $factor));
                $i++;
            }
        } else {
            uasort($calc, function ($a, $b) {
                return $b["PRICE"] - $a["PRICE"];
            });
            $i = count($calc);
            foreach ($calc as $id => $arr) {
                CIBlockElement::SetPropertyValuesEx($id, $arParams['IBLOCK_ID'], array("SORT_BY_PRICE" => $i * $factor));
                $i--;
            }
        }
    }
}

if($arParams["IBLOCK_ID"] == PLATFORM_IBLOCK_ID) {
    $arSortBy = array(
        "name" => array(
            "realfield" => "NAME",
            "order_def" => "asc"
        ),
        "sort" => array(
            "realfield" => "SORT",
            "order_def" => "asc"
        ),
    );
} else {
    if (LANGUAGE_ID == "ru") {
        $arSortBy = array(
		    "sort" => array(
                "realfield" => "SORT",
                "order_def" => "asc"
            ),
            "name" => array(
                "realfield" => "NAME",
                "order_def" => "asc"
            ),
            "price" => array(
                "realfield" => "PROPERTY_SORT_BY_PRICE",
                "order_def" => "asc",
                "callback" => array(
                    "name" => "prepareSortByPrice",
                    "params" => array($arParams)
                )
            ),
            "rating" => array(
                "realfield" => "PROPERTY_RATING",
                "order_def" => "asc"
            )
        );
    }
    else {
        $arSortBy = array(
			"sort" => array(
                "realfield" => "SORT",
                "order_def" => "asc"
            ),
            "name" => array(
                "realfield" => "PROPERTY_NAME_".strtoupper(LANGUAGE_ID),
                "order_def" => "asc"
            ),
            "price" => array(
                "realfield" => "PROPERTY_SORT_BY_PRICE",
                "order_def" => "asc",
                "callback" => array(
                    "name" => "prepareSortByPrice",
                    "params" => array($arParams)
                )
            ), 
            "rating" => array(
                "realfield" => "PROPERTY_RATING",
                "order_def" => "asc"
            )
        );
    }
    
}

$arParams["SORT_PARAMETERS"] = null;

if (isset($arSortBy[$_REQUEST["sort_by"]])) {

    if ($arSortBy[$_REQUEST["sort_by"]]["callback"]["name"]) {
        if ($arSortBy[$_REQUEST["sort_by"]]["callback"]["name"]=='prepareSortByPrice') {
            if (empty($arParams["CALCULATION_PRICE_RESULT"]) && empty($_SESSION[$_SERVER['REMOTE_ADDR']][$arParams['IBLOCK_ID']]['CALCULATION_PRICE_RESULT'])) {
                global $APPLICATION;
                $arFilter = array("IBLOCK_ID" => $arParams["IBLOCK_ID"], "ACTIVE" => "Y");
                $complex_logic = array();
            
                $_is_booking_request = !empty($arParams["__BOOKING_REQUEST"]);
            
                if ($_is_booking_request) {
            
                    $complex_logic = array(array(
                            "LOGIC" => "OR",
                            // по городам
                            array("PROPERTY_TOWN" => $arParams["__BOOKING_REQUEST"]['id']),
                            // по областям
                            array("PROPERTY_REGION" => $arParams["__BOOKING_REQUEST"]['id']),
                            // по достопримечательностям
                            array("PROPERTY_SIGHT" => $arParams["__BOOKING_REQUEST"]['id']),
                            // по мед. профилям
                            array("PROPERTY_TYPE" => $arParams["__BOOKING_REQUEST"]['id'])
                    ));
                }
            
                $arElements = $APPLICATION->IncludeComponent("travelsoft:travelsoft.iblock.getlist.byfilter", "", Array(
                    "CACHE_TIME" => "3600",
                    "CACHE_TYPE" => "A",
                    "CNT" => "",
                    "FILTER" => array_merge($arFilter, $complex_logic),
                    "FILTER_NAME" => "",
                    "ORDER" => false,
                    "RETURN_RESULT" => "Y",
                    "SORT" => false,
                    "SELECT" => array("ID"),
                    "TITLE" => ""
                        )
                );
                if ($arElements["ELEMENT_ID"]) {
            
                    $parameters = $arParams["__BOOKING_REQUEST"];
                    $parameters["id"] = $arElements["ELEMENT_ID"];
            
                    $arParams["CALCULATION_PRICE_RESULT"] = $APPLICATION->IncludeComponent(
                            "travelsoft:travelsoft.service.price.result", "on.detail.page.render", Array(
                        "RETURN_RESULT" => "Y",
                        "CACHE_TIME" => 3600,
                        "CACHE_TYPE" => "A",
                        "FILTER_BY_PRICES_FOR_CITIZEN" => $arParams["FILTER_BY_PRICES_FOR_CITIZEN"] == "Y" ? "Y" : "N",
                        "TYPE" => $arParams["TYPE"],
                        "MAKE_ORDER_PAGE" => "/booking/",
                        "POSTFIX_PROPERTY" => POSTFIX_PROPERTY,
                        "__BOOKING_REQUEST" => $parameters,
                        "MP" => "Y",
                            )
                    );
                }
                $_SESSION[$_SERVER['REMOTE_ADDR']][$arParams['IBLOCK_ID']]['CALCULATION_PRICE_RESULT'] = $arParams["CALCULATION_PRICE_RESULT"];    
            $arSortBy[$_REQUEST["sort_by"]]["callback"]["params"] = array($arParams);
            }
            elseif (empty($arParams["CALCULATION_PRICE_RESULT"])) {
                $arParams["CALCULATION_PRICE_RESULT"]=$_SESSION[$_SERVER['REMOTE_ADDR']][$arParams['IBLOCK_ID']]['CALCULATION_PRICE_RESULT'];
                $arSortBy[$_REQUEST["sort_by"]]["callback"]["params"] = array($arParams);
            }
            
        }
        call_user_func_array($arSortBy[$_REQUEST["sort_by"]]["callback"]["name"], $arSortBy[$_REQUEST["sort_by"]]["callback"]["params"]);
    }

    $arParams["SORT_BY1"] = $arSortBy[$_REQUEST["sort_by"]]["realfield"];
    $arParams["SORT_ORDER1"] = $_REQUEST["order"] == "desc" ? "DESC" : "ASC";
    if ($arParams["SORT_BY2"]==$arParams["SORT_BY1"]) $arParams["SORT_ORDER2"] = $arParams["SORT_ORDER1"];
}

foreach ($arSortBy as $name => $arp) {
    if ($name == $_REQUEST["sort_by"] || (empty($_REQUEST["sort_by"]) && $name=='sort')) {
        if ( empty($_REQUEST["order"])) if ($arParams["SORT_ORDER1"]=='asc') $_REQUEST["order"] = 'desc'; else $_REQUEST["order"] = 'asc';	 
        $arParams["SORT_PARAMETERS"][] = array("name" => $name, "order" => $_REQUEST["order"] == "asc" ? "desc" : "asc", "selected" => true);
    } else {
        $arParams["SORT_PARAMETERS"][] = array("name" => $name, "order" => $arp["order_def"], "selected" => false);
    }
}
##################################