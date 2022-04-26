<?
use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

require_once 'functions.php';

//$arCode = array("SEARCH".POSTFIX_PROPERTY,"HD_DESCROOM".POSTFIX_PROPERTY,"HD_DESC".POSTFIX_PROPERTY,"MAP".POSTFIX_PROPERTY);

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
$db_cities = CIBlockElement::GetList(Array("SORT"=>"ASC"), Array("IBLOCK_ID" => $arResult['PROPERTIES'][$TOWN_CODE]["LINK_IBLOCK_ID"], "ACTIVE" => "Y"), false, false, Array("ID","NAME", "PROPERTY_NAME" . POSTFIX_PROPERTY, "PROPERTY_MAP"));
while($ar_fields = $db_cities->GetNext())
{
    $arCities[$ar_fields["ID"]] = $ar_fields;
}

$cnt = 0;
$cnt = count($arResult['PROPERTIES'][$TOWN_CODE]["VALUE"]);
/*foreach ($arResult['PROPERTIES'][$TOWN_CODE]["VALUE"] as $maps) {

    if ($arCities[$maps][$MAP_CODE] != "") {
        $LATLNG = explode(",", $arCities[$maps][$MAP_CODE]);
        $arResult['ROUTE_INFO'][] = array(
            "lat" => $LATLNG[0],
            "lng" => $LATLNG[1],
            "title" => $arCities[$maps]['NAME'],
			"content" => "<div style='color:red'><b>" . ( LANGUAGE_ID !== "ru" ? $arCities[$maps]['PROPERTY_NAME' . POSTFIX_PROPERTY . "_VALUE"] : $arCities[$maps]['NAME'] ) . "</b></div>"
        );
    }

}*/

$cp = $this->__component; // объект компонента

if (is_object($cp))
{
    $cp->arResult['PROPERTIES'] = $arResult["PROPERTIES"];
    $cp->SetResultCacheKeys(array('PROPERTIES'));
}