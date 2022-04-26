<?
$arResult["SERVICES_ICON"] = array();
$db_props = CIBlockElement::GetList(Array("sort" => "asc"), Array("IBLOCK_ID"=>35, "ACTIVE"=>"Y"), false, false, Array("ID", "NAME","PROPERTY_SERVICE_ICON","PROPERTY_NAME".POSTFIX_PROPERTY));
while($ar_props = $db_props->Fetch()){
    $arResult["SERVICES_ICON"][$ar_props["ID"]] = array(
        "ICON" => $ar_props["PROPERTY_SERVICE_ICON_VALUE"],
        "TITLE" => POSTFIX_PROPERTY == "" ? $ar_props["NAME"] : $ar_props["PROPERTY_NAME".POSTFIX_PROPERTY."_VALUE"]
    );
}
$hotels = $codes = [];
foreach ($arResult['ITEMS'] as $tmpitem) {
    $codes[] = $tmpitem['CODE'];
    foreach ($tmpitem["PROPERTIES"]["HOTEL"]["VALUE"] as $tmpval) $hotels[] = $tmpval;
}
$hotels = array_unique($hotels);
$custom_request = ['adults'=>1, 'date_from'=>strtotime("now"), 'date_to'=>strtotime("+60 days")];
$result_price = $APPLICATION->IncludeComponent(
        "travelsoft:travelsoft.service.price.result",
        "on.detail.page.render",
        Array(
            "CACHE_TYPE" => "A",
            "CACHE_TIME" => 3600,
            "COMPONENT_TEMPLATE" => "on.detail.page.render",
            "CODE" => $codes,
            "FILTER_BY_PRICES_FOR_CITIZEN" => $arParams["FILTER_BY_PRICES_FOR_CITIZEN"] == "Y" ? "Y" : "N",
            "INC_JQUERY" => "N",
            "INC_MAGNIFIC_POPUP" => "Y",
            "INC_OWL_CAROUSEL" => "N",
            "TYPE" => $arParams["TYPE"],
            "MAKE_ORDER_PAGE" => "/booking/",
            "POSTFIX_PROPERTY" => POSTFIX_PROPERTY,
            "__BOOKING_REQUEST" => $custom_request,
            "RETURN_RESULT" => "Y",
            "FOLDER_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["news"],
            "CURRENCY" => travelsoft\Currency::getInstance()->get("current_currency")["iso"]
        )
    );
$custom_request = ['adults'=>2, 'date_from'=>strtotime("now"), 'date_to'=>strtotime("+60 days")];
$result_price2 = $APPLICATION->IncludeComponent(
        "travelsoft:travelsoft.service.price.result",
        "on.detail.page.render",
        Array(
            "CACHE_TYPE" => "A",
            "CACHE_TIME" => 3600,
            "COMPONENT_TEMPLATE" => "on.detail.page.render",
            "CODE" => $codes,
            "FILTER_BY_PRICES_FOR_CITIZEN" => $arParams["FILTER_BY_PRICES_FOR_CITIZEN"] == "Y" ? "Y" : "N",
            "INC_JQUERY" => "N",
            "INC_MAGNIFIC_POPUP" => "Y",
            "INC_OWL_CAROUSEL" => "N",
            "TYPE" => $arParams["TYPE"],
            "MAKE_ORDER_PAGE" => "/booking/",
            "POSTFIX_PROPERTY" => POSTFIX_PROPERTY,
            "__BOOKING_REQUEST" => $custom_request,
            "RETURN_RESULT" => "Y",
            "FOLDER_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["news"],
            "CURRENCY" => travelsoft\Currency::getInstance()->get("current_currency")["iso"]
        )
    );
$dates  =[];
foreach ($result_price  as $id  =>$val) 
    foreach ($val as $valtmp) 
        foreach ($valtmp as $dataunix => $valtmp2) 
        if (!in_array($dataunix, $dates[$id])) $dates[$id][] = $dataunix;
foreach ($result_price2  as $id  =>$val) 
    foreach ($val as $valtmp) 
        foreach ($valtmp as $dataunix => $valtmp2) 
        if (!in_array($dataunix, $dates[$id])) $dates[$id][] = $dataunix;
$arResult['AVAIL_DATES'] = $dates;
if (count($hotels)>0) {
    $townsinfo = $catsinfo = $hotelsinfo = $towns = $cats = [];
    $db_res_hotels = CIBlockElement::GetList(false, array( "IBLOCK_ID" => 7, "ID" => $hotels), false, false, array("ID", "NAME", "PROPERTY_NAME" . POSTFIX_PROPERTY, "PROPERTY_TOWN", "PROPERTY_CAT_ID"));
    while ($res = $db_res_hotels->Fetch()) {
        $hotelsinfo[$res['ID']] = ['name'=>$res["PROPERTY_NAME" . POSTFIX_PROPERTY . "_VALUE"] ? $res["PROPERTY_NAME" . POSTFIX_PROPERTY . "_VALUE"] : $res["NAME"], 'cat'=>$res['PROPERTY_CAT_ID_VALUE'], 'town'=>$res['PROPERTY_TOWN_VALUE']];
        $towns[] = $res['PROPERTY_TOWN_VALUE'];
        $cats[] = $res['PROPERTY_CAT_ID_VALUE'];
    }
    $arResult['HOTELS'] = $hotelsinfo;
    $towns = array_unique($towns);
    $cats = array_unique($cats);
    if (count($cats)>0) {
        $db_res_cats = CIBlockElement::GetList(false, array("ACTIVE" => "Y", "IBLOCK_ID" => 15, "ID" => $cats), false, false, array("ID", "NAME", "PROPERTY_NAME" . POSTFIX_PROPERTY));
        while ($res = $db_res_cats->Fetch()) {
            if (strpos($res['NAME'],'*')===false) continue;
            $catsinfo[$res['ID']] = $res["PROPERTY_NAME" . POSTFIX_PROPERTY . "_VALUE"] ? $res["PROPERTY_NAME" . POSTFIX_PROPERTY . "_VALUE"] : $res["NAME"];
        }
        $arResult['CAT_HOTELS'] = $catsinfo;
    }
    if (count($towns)>0) {
        $db_res_towns = CIBlockElement::GetList(false, array("ACTIVE" => "Y", "IBLOCK_ID" => 5, "ID" => $towns), false, false, array("ID", "NAME", "PROPERTY_NAME" . POSTFIX_PROPERTY));
        while ($res = $db_res_towns->Fetch()) {
            $townsinfo[$res['ID']] = $res["PROPERTY_NAME" . POSTFIX_PROPERTY . "_VALUE"] ? $res["PROPERTY_NAME" . POSTFIX_PROPERTY . "_VALUE"] : $res["NAME"];
        }
        $arResult['TOWNS_HOTELS'] = $townsinfo;
    }
}