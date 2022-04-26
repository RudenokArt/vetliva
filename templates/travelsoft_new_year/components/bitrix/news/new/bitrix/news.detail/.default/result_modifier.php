<?

use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

require_once 'functions.php';

//$arCode = array("SEARCH".POSTFIX_PROPERTY,"HD_DESCROOM".POSTFIX_PROPERTY,"HD_DESC".POSTFIX_PROPERTY,"MAP".POSTFIX_PROPERTY);

if (!empty($arResult["DISPLAY_PROPERTIES"]["SERVICES"]["VALUE"])) {
    $iblock = $arResult["DISPLAY_PROPERTIES"]["SERVICES"]["LINK_IBLOCK_ID"];
    $arResult["SERVICES_ICON"] = array();
    $db_props = CIBlockElement::GetList(Array("sort" => "asc"), Array("IBLOCK_ID" => $iblock, "ID" => $arResult["DISPLAY_PROPERTIES"]["SERVICES"]["VALUE"], "ACTIVE" => "Y"), false, false, Array("ID", "NAME", "PROPERTY_SERVICE_ICON", "PROPERTY_NAME" . POSTFIX_PROPERTY));
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
