<?

use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

require_once 'functions.php';

//$arCode = array("SEARCH".POSTFIX_PROPERTY,"HD_DESCROOM".POSTFIX_PROPERTY,"HD_DESC".POSTFIX_PROPERTY,"MAP".POSTFIX_PROPERTY);

$cost_services = array();
$arResult["COST_SERVICES"] = array();

if (!empty($arResult["DISPLAY_PROPERTIES"]["SERVICES"]["VALUE"]) || !empty($arResult["DISPLAY_PROPERTIES"]["SERVICES_PAID"]["VALUE"])) {
    $iblock = $arResult["DISPLAY_PROPERTIES"]["SERVICES"]["LINK_IBLOCK_ID"];
    $arResult["SERVICES_ICON"] = array();

    $services_all = $arResult["DISPLAY_PROPERTIES"]["SERVICES"]["VALUE"];
    if(!empty($arResult["PROPERTIES"]["SERVICES_PAID"]["VALUE"])) {
        $services_all = array_merge($services_all, $arResult["PROPERTIES"]["SERVICES_PAID"]["VALUE"]);
    }
    $db_props = CIBlockElement::GetList(Array("sort" => "asc"), Array("IBLOCK_ID" => $iblock, "ID" => $services_all, "ACTIVE" => "Y"), false, false, Array("ID", "NAME", "PROPERTY_SERVICE_ICON", "PROPERTY_NAME" . POSTFIX_PROPERTY));
    while ($ar_props = $db_props->Fetch()) {
        $arResult["SERVICES_ICON"][$ar_props["ID"]] = array(
            "ICON" => $ar_props["PROPERTY_SERVICE_ICON_VALUE"],
            "TITLE" => POSTFIX_PROPERTY == "" ? $ar_props["NAME"] : $ar_props["PROPERTY_NAME" . POSTFIX_PROPERTY . "_VALUE"]
        );
    }
}


servicesGroup("SERVICES", $arResult);
servicesGroup("MED_SERVICES", $arResult);
servicesGroup("MED_PROFILES", $arResult);

// ГРУППИРОВКА УСЛУГ В САНАТОРИЯХ И ОБЪЕКТАХ РАЗМЕЩЕНИЯ
function servicesGroup($PROPERTY_CODE, &$arResult) {

    $arServicesId = array();
    $iblockId = $arResult["PROPERTIES"][$PROPERTY_CODE]["LINK_IBLOCK_ID"];
    if (!empty($arResult["PROPERTIES"][$PROPERTY_CODE]["VALUE"])) {
        $arServicesId = $arResult["PROPERTIES"][$PROPERTY_CODE]["VALUE"];
    }

    if (!empty($arResult["PROPERTIES"][$PROPERTY_CODE . "_PAID"]["VALUE"])) {
        $arServicesId = array_merge($arServicesId, $arResult["PROPERTIES"][$PROPERTY_CODE . "_PAID"]["VALUE"]);
        $arResult["COST_SERVICES"] = $arResult["PROPERTIES"][$PROPERTY_CODE . "_PAID"]["VALUE"];
    }

    if (!empty($arServicesId)) {

        $arSelect = Array("ID", "PROPERTY_SERVICE_ICON", "IBLOCK_SECTION_ID");

        if (LANGUAGE_ID != "ru") {
            $arSelect[] = "PROPERTY_NAME" . POSTFIX_PROPERTY;
        } else {
            $arSelect[] = "NAME";
        }

        $dbResElement = CIBlockElement::GetList(array("sort" =>"asc"), Array("IBLOCK_ID" => $iblockId, "ID" => $arServicesId, "ACTIVE" => "Y"), false, false, $arSelect);

        $arResult[$PROPERTY_CODE . "_GROUP"] = NULL;
        while ($arRes = $dbResElement->Fetch()) {
            if ($arRes["IBLOCK_SECTION_ID"] > 0) {
                $arResult[$PROPERTY_CODE . "_GROUP"][$arRes["IBLOCK_SECTION_ID"]][$arRes["ID"]] = array(
                    "TITLE" => LANGUAGE_ID != "ru" ? $arRes["PROPERTY_NAME" . POSTFIX_PROPERTY . "_VALUE"] : $arRes["NAME"],
                    "PAID" => in_array($arRes["ID"], $arResult["PROPERTIES"][$PROPERTY_CODE . "_PAID"]["VALUE"])
                );
            }
        }

        if ($arResult[$PROPERTY_CODE . "_GROUP"]) {

            $arResult[$PROPERTY_CODE . "_SECTIONS"] = NULL;

            $arSelect = array("ID", "PICTURE");

            if (LANGUAGE_ID != "ru") {
                $arSelect[] = "UF_NAME" . POSTFIX_PROPERTY;
            } else {
                $arSelect[] = "NAME";
            }

            $dbResSection = CIBlockSection::GetList(array("sort" =>"asc"), array("IBLOCK_ID" => $iblockId, "ID" => array_keys($arResult[$PROPERTY_CODE . "_GROUP"])), false, $arSelect);
            while ($arRes = $dbResSection->Fetch()) {
                $arResult[$PROPERTY_CODE . "_SECTIONS"][$arRes["ID"]]["TITLE"] = LANGUAGE_ID != "ru" ? $arRes["UF_NAME" . POSTFIX_PROPERTY] : $arRes["NAME"];
                if ($arRes["PICTURE"]) {
                    $arResult[$PROPERTY_CODE . "_SECTIONS"][$arRes["ID"]]["PICTURE"] = CFile::GetFileArray($arRes["PICTURE"]);
                }
            }
        }
    }
}



if(!empty($arResult["COST_SERVICES"])){

    $arResult["COST_SERVICES"]["ITEMS"] = array();

    if($arParams["IBLOCK_ID"] == SANATORIUM_IBLOCK_ID || $arParams["IBLOCK_ID"] == PLACEMENTS_IBLOCK_ID){

        $iblock_costservices = COST_SERVICES_IBLOCK_ID;
        $db_services = CIBlockElement::GetList(Array("sort" => "asc"), Array("IBLOCK_ID" => $iblock_costservices, "PROPERTY_SERVICE" => $arResult["COST_SERVICES"], "PROPERTY_OBJECT" => $arResult["ID"], "ACTIVE" => "Y"), false, false, Array("ID", "NAME", "PROPERTY_DESCRIPTION" . POSTFIX_PROPERTY, "PROPERTY_PRICE", "PROPERTY_CURRENCY", "PROPERTY_SERVICE"));

        while ($ar_props_services = $db_services->Fetch()) {

            $arResult['COST_SERVICES']["ITEMS"][$ar_props_services["PROPERTY_SERVICE_VALUE"]] = array(
                "ID" => $ar_props_services["ID"],
                "NAME" => $arResult["SERVICES_ICON"][$ar_props_services["PROPERTY_SERVICE_VALUE"]]["TITLE"],
                "DESCRIPTION" => $ar_props_services["PROPERTY_DESCRIPTION" . POSTFIX_PROPERTY ."_VALUE"]["TYPE"] == "HTML" ? htmlspecialcharsEx($ar_props_services["PROPERTY_DESCRIPTION" . POSTFIX_PROPERTY ."_VALUE"]["TEXT"]) : $ar_props_services["PROPERTY_DESCRIPTION" . POSTFIX_PROPERTY ."_VALUE"]["TEXT"],
                "PRICE" => $ar_props_services["PROPERTY_PRICE_VALUE"],
                "CURRENCY" => $ar_props_services["PROPERTY_CURRENCY_VALUE"]

            );

        }

    }

}

if($arParams["IBLOCK_ID"] == SANATORIUM_IBLOCK_ID){

    if(!empty($arResult['DISPLAY_PROPERTIES']['MED_SERVICES']['VALUE'])){

        $arResult['DISPLAY_PROPERTIES']['MED_SERVICES']['DESC'] = array();

        $iblock_med = $arResult["DISPLAY_PROPERTIES"]["MED_SERVICES"]["LINK_IBLOCK_ID"];
        $arSelectMed = Array("ID", "PROPERTY_DESCRIPTION" . POSTFIX_PROPERTY, "IBLOCK_SECTION_ID");

        if (LANGUAGE_ID != "ru") {
            $arSelectMed[] = "PROPERTY_NAME" . POSTFIX_PROPERTY;
        } else {
            $arSelectMed[] = "NAME";
        }

        $iblock_video = VIDEO_IBLOCK_ID;
        $arResult["MED_SERVICES_VIDEO"] = array();
        $db_props_v = CIBlockElement::GetList(Array("sort" => "asc"), Array("IBLOCK_ID" => $iblock_video, "PROPERTY_MED_SERVICES" => $arResult["DISPLAY_PROPERTIES"]["MED_SERVICES"]["VALUE"], "PROPERTY_SANATORIUM" => $arResult["ID"], "ACTIVE" => "Y"), false, false, Array("ID","NAME","PROPERTY_YOUTUBE" . POSTFIX_PROPERTY,"PROPERTY_SANATORIUM","PROPERTY_MED_SERVICES"));
        while ($ar_props_v = $db_props_v->Fetch()) {

            $arResult["MED_SERVICES_VIDEO"][$ar_props_v["PROPERTY_MED_SERVICES_VALUE"]] = array(
                "ID" => $ar_props_v["PROPERTY_MED_SERVICES_VALUE"],
                "NAME" => $ar_props_v["NAME"],
                "YOUTUBE_CODE" => $ar_props_v["PROPERTY_YOUTUBE" . POSTFIX_PROPERTY ."_VALUE"],
            );

        }

        $db_props = CIBlockElement::GetList(Array("sort" => "asc"), Array("IBLOCK_ID" => $iblock_med, "ID" => $arResult["DISPLAY_PROPERTIES"]["MED_SERVICES"]["VALUE"], "ACTIVE" => "Y"), false, false, $arSelectMed);

            while ($ar_props = $db_props->Fetch()) {

                $arResult['DISPLAY_PROPERTIES']['MED_SERVICES']['DESC'][$ar_props["ID"]] = array(
                    "ID" => $ar_props["ID"],
                    "NAME" => isset($ar_props["NAME"]) && !empty($ar_props["NAME"]) ? $ar_props["NAME"] : $ar_props["PROPERTY_NAME" . POSTFIX_PROPERTY ."_VALUE"],
                    "DESCRIPTION" => $ar_props["PROPERTY_DESCRIPTION" . POSTFIX_PROPERTY ."_VALUE"]["TYPE"] == "HTML" ? htmlspecialcharsEx($ar_props["PROPERTY_DESCRIPTION" . POSTFIX_PROPERTY ."_VALUE"]["TEXT"]) : $ar_props["PROPERTY_DESCRIPTION" . POSTFIX_PROPERTY ."_VALUE"]["TEXT"],
                    "VIDEO" => isset($arResult["MED_SERVICES_VIDEO"][$ar_props["ID"]]) && !empty($arResult["MED_SERVICES_VIDEO"][$ar_props["ID"]]["YOUTUBE_CODE"]) ? "Y" : "N"

                );

            }

    }

}

$cp = $this->__component; // объект компонента

if (is_object($cp))
{
    $cp->arResult['PROPERTIES'] = $arResult["PROPERTIES"];
    $cp->SetResultCacheKeys(array('PROPERTIES'));
}
