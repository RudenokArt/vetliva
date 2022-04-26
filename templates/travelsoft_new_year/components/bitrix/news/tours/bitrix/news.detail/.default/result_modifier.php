<?
use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

require_once 'functions.php';

//$arCode = array("SEARCH".POSTFIX_PROPERTY,"HD_DESCROOM".POSTFIX_PROPERTY,"HD_DESC".POSTFIX_PROPERTY,"MAP".POSTFIX_PROPERTY);

$arMenu = array();
$arMenu["HD_DESC"] = array("TITLE" => Loc::getMessage("HD_DESC"), "ANCHOR" => "block_".mb_strtolower("HD_DESC"), "SHOW" => resultCode($arResult["DISPLAY_PROPERTIES"],"HD_DESC".POSTFIX_PROPERTY));
if (!empty($GLOBALS["PRICE_CALCULATION_RESULT_HTML"])) {
    $arMenu["PRICES"] = array("TITLE" => Loc::getMessage("PRICES"), "ANCHOR" => "block_" . mb_strtolower("PRICES"), "SHOW" => true);
}
$arMenu["NDAYS"] = array("TITLE" => Loc::getMessage("NDAYS"), "ANCHOR" => "block_".mb_strtolower("NDAYS"), "SHOW" => resultCode($arResult["DISPLAY_PROPERTIES"], Array("NDAYS".POSTFIX_PROPERTY, "ROUTE".POSTFIX_PROPERTY)));
$arMenu["DOCUMENT"] = array("TITLE" => Loc::getMessage("DOCUMENT"), "ANCHOR" => "block_".mb_strtolower("DOCUMENT"), "SHOW" => resultCode($arResult["DISPLAY_PROPERTIES"], "DOCUMENT".POSTFIX_PROPERTY));
$arMenu["MAP"] = array("TITLE" => Loc::getMessage("MAP"), "ANCHOR" => "block_".mb_strtolower("MAP"), "SHOW" => resultCode($arResult["DISPLAY_PROPERTIES"], "TOWN".POSTFIX_PROPERTY));
$arMenu["YOUTUBE"] = array("TITLE" => Loc::getMessage("YOUTUBE"), "ANCHOR" => "block_".mb_strtolower("YOUTUBE"), "SHOW" => resultCode($arResult["DISPLAY_PROPERTIES"], Array("YOUTUBE".POSTFIX_PROPERTY,"VIMEO".POSTFIX_PROPERTY)));
$arMenu["SIGHTS"] = array("TITLE" => Loc::getMessage("SIGHTS"), "ANCHOR" => "block_".mb_strtolower("SIGHTS"), "SHOW" => resultCode($arResult["DISPLAY_PROPERTIES"],"SIGHTS".POSTFIX_PROPERTY));
$arMenu["HOTEL"] = array("TITLE" => Loc::getMessage("HOTEL"), "ANCHOR" => "block_".mb_strtolower("HOTEL"), "SHOW" => resultCode($arResult["DISPLAY_PROPERTIES"],"HOTEL".POSTFIX_PROPERTY));
if($USER->GetID() == 43) $arMenu["REVIEWS"] = array("TITLE" => Loc::getMessage("REVIEWS"), "ANCHOR" => "block_".mb_strtolower("REVIEWS"), "SHOW" => showReviews($arResult["ID"]));


$arResult["MENU_ITEM"] = $arMenu;

if(!empty($arResult["DISPLAY_PROPERTIES"]["SERVICES"]["VALUE"])){
    $iblock = $arResult["DISPLAY_PROPERTIES"]["SERVICES"]["LINK_IBLOCK_ID"];
    $arResult["SERVICES_ICON"] = array();
    $db_props = CIBlockElement::GetList(Array("sort" => "asc"), Array("IBLOCK_ID"=>$iblock, "ID"=>$arResult["DISPLAY_PROPERTIES"]["SERVICES"]["VALUE"], "ACTIVE"=>"Y"), false, false, Array("ID", "NAME","PROPERTY_SERVICE_ICON","PROPERTY_NAME".POSTFIX_PROPERTY));
    while($ar_props = $db_props->Fetch()){
        $arResult["SERVICES_ICON"][$ar_props["ID"]] = array(
            "ICON" => $ar_props["PROPERTY_SERVICE_ICON_VALUE"],
            "TITLE" => POSTFIX_PROPERTY == "" ? $ar_props["NAME"] : $ar_props["PROPERTY_NAME".POSTFIX_PROPERTY."_VALUE"]
        );
    }
}

$TOWN_CODE = "TOWN";
$MAP_CODE = "PROPERTY_MAP_VALUE";
$EXURSIONS_CODE = "EXURSIONS";

$arCities = array();
$db_cities = CIBlockElement::GetList(Array("SORT"=>"ASC"), Array("IBLOCK_ID" => $arResult['PROPERTIES'][$TOWN_CODE]["LINK_IBLOCK_ID"], "ACTIVE" => "Y"), false, false, Array("ID", "NAME", "PROPERTY_MAP"));
while($ar_fields = $db_cities->GetNext())
{
    $arCities[$ar_fields["ID"]] = $ar_fields;
}

$cnt = 0;
$cnt = count($arResult['PROPERTIES'][$TOWN_CODE]["VALUE"]);
foreach ($arResult['PROPERTIES'][$TOWN_CODE]["VALUE"] as $maps) {

    if ($arCities[$maps][$MAP_CODE] != "") {
        $LATLNG = explode(",", $arCities[$maps][$MAP_CODE]);
        $arResult['ROUTE_INFO'][] = array(
            "lat" => $LATLNG[0],
            "lng" => $LATLNG[1],
            "title" => $arCities[$maps]['NAME'],
            "infoWindow" => "<div style='color:red'><b>" . $arCities[$maps]['NAME'] . "</b></div>"
        );
    }

}