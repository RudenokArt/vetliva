<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$arComponentParameters["PARAMETERS"] = array (  
    
    "__BOOKING_REQUEST" => array(
        "NAME" => "Параметры запроса",
        "TYPE" => "STRING",
        "DEFAULT" => '={$_REQUEST["booking"]}'
    ),
    
    "TYPE" => array (
        "NAME" => "Тип услуг для поиска цен",
        "TYPE" => "LIST",
        "VALUES" => travelsoft\booking\Utils::stOnlyNames(),
        "DEFAULT" => "placements"
    ),
    
    "CODE" => array(
        "NAME" => "Параметры запроса",
        "TYPE" => "LIST"
    ),
    
    "MAKE_ORDER_PAGE" => array(
        "NAME" => "Страница оформления заказа",
        "TYPE" => "STRING",
        "DEFAULT" => "/booking/"
    ),
    
    "POSTFIX_PROPERTY" => array(
        "NAME" => "Языковой потсфикс для полей услуг",
        "TYPE" => "STRING",
        "DEFAULT" => "={POSTFIX_PROPERTY}"
    ),
    
    "FILTER_BY_PRICES_FOR_CITIZEN" => array(
        "NAME" => "Фильтровать по ценам для граждан",
        "TYPE" => "CHECKBOX"
    ),
    
    "RETURN_RESULT" => array (
        "NAME" => "Возвращает только результат запроса",
        "TYPE" => "CHECKBOX"
    ),
    
    "MP" => array (
        "NAME" => "Производить поиск минимальной цены",
        "TYPE" => "CHECKBOX"
    ),
    
    "CACHE_TIME" => array("DEFAULT" => 3600000)
    
);

// include javascript plugins
$arComponentParameters["PARAMETERS"]["INC_JQUERY"] = array(
    "NAME" => "Подключить jquery",
    "TYPE" => "CHECKBOX",
    "DEFAULT" => "Y"
);

$arComponentParameters["PARAMETERS"]["INC_MAGNIFIC_POPUP"] = array(
        "NAME" => "Подключить magnific.popup из CDN",
        "TYPE" => "CHECKBOX",
        "DEFAULT" => "Y"
    );

$arComponentParameters["PARAMETERS"]["INC_OWL_CAROUSEL"] = array(
        "NAME" => "Подключить owl.carousel из CDN",
        "TYPE" => "CHECKBOX",
        "DEFAULT" => "Y"
    );