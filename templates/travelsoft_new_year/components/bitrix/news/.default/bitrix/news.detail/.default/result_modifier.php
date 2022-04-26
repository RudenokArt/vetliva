<?

use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

require_once 'functions.php';

//$arCode = array("SEARCH".POSTFIX_PROPERTY,"HD_DESCROOM".POSTFIX_PROPERTY,"HD_DESC".POSTFIX_PROPERTY,"MAP".POSTFIX_PROPERTY);

$arMenu = array();
$arMenu["HD_DESC"] = array("TITLE" => Loc::getMessage("HD_DESC"), "ANCHOR" => "block_" . mb_strtolower("HD_DESC"), "SHOW" => resultCode($arResult["PROPERTIES"], "HD_DESC" . POSTFIX_PROPERTY));
if (!empty($GLOBALS["PRICE_CALCULATION_RESULT_HTML"])) {
    $arMenu["PRICES"] = array("TITLE" => Loc::getMessage("PRICES"), "ANCHOR" => "block_" . mb_strtolower("PRICES"), "SHOW" => true);
}
$arMenu["SERVICES"] = array("TITLE" => Loc::getMessage("SERVICES"), "ANCHOR" => "block_" . mb_strtolower("SERVICES"), "SHOW" => resultCode($arResult["PROPERTIES"], Array("SERVICES" . POSTFIX_PROPERTY, "HD_DESCSERVICE" . POSTFIX_PROPERTY, "HD_DESCCHILD" . POSTFIX_PROPERTY)));
$arMenu["HD_DESCROOM"] = array("TITLE" => Loc::getMessage("HD_DESCROOM"), "ANCHOR" => "block_" . mb_strtolower("HD_DESCROOM"), "SHOW" => resultCode($arResult["PROPERTIES"], Array("HD_DESCROOM" . POSTFIX_PROPERTY, "HD_ADDINFORMATION" . POSTFIX_PROPERTY)));
$arMenu["HD_DESCMEAL"] = array("TITLE" => Loc::getMessage("HD_DESCMEAL"), "ANCHOR" => "block_" . mb_strtolower("HD_DESCMEAL"), "SHOW" => resultCode($arResult["PROPERTIES"], "HD_DESCMEAL" . POSTFIX_PROPERTY));
$arMenu["HD_DESCSPORT"] = array("TITLE" => Loc::getMessage("HD_DESCSPORT"), "ANCHOR" => "block_" . mb_strtolower("HD_DESCSPORT"), "SHOW" => resultCode($arResult["PROPERTIES"], "HD_DESCSPORT" . POSTFIX_PROPERTY));
$arMenu["YOUTUBE"] = array("TITLE" => Loc::getMessage("YOUTUBE"), "ANCHOR" => "block_" . mb_strtolower("YOUTUBE"), "SHOW" => resultCode($arResult["PROPERTIES"], Array("YOUTUBE" . POSTFIX_PROPERTY, "VIMEO" . POSTFIX_PROPERTY)));
$arMenu["MAP"] = array("TITLE" => Loc::getMessage("MAP"), "ANCHOR" => "block_" . mb_strtolower("MAP"), "SHOW" => resultCode($arResult["PROPERTIES"], "MAP"));
$arMenu["MED_SERVICES"] = array("TITLE" => Loc::getMessage("MED_SERVICES"), "ANCHOR" => "block_" . mb_strtolower("MED_SERVICES"), "SHOW" => resultCode($arResult["PROPERTIES"], "MED_SERVICES" . POSTFIX_PROPERTY));
$arMenu["TREATMENT"] = array("TITLE" => Loc::getMessage("TREATMENT"), "ANCHOR" => "block_" . mb_strtolower("TREATMENT"), "SHOW" => resultCode($arResult["PROPERTIES"], "TREATMENT" . POSTFIX_PROPERTY));
if($USER->GetID() == 1) $arMenu["REVIEWS"] = array("TITLE" => Loc::getMessage("REVIEWS"), "ANCHOR" => "block_" . mb_strtolower("REVIEWS"), "SHOW" => showReviews($arResult["ID"]));


$arResult["MENU_ITEM"] = $arMenu;

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

        $dbResElement = CIBlockElement::GetList(false, Array("IBLOCK_ID" => $iblockId, "ID" => $arServicesId, "ACTIVE" => "Y"), false, false, $arSelect);

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

            $dbResSection = CIBlockSection::GetList(array(), array("IBLOCK_ID" => $iblockId, "ID" => array_keys($arResult[$PROPERTY_CODE . "_GROUP"])), false, $arSelect);
            while ($arRes = $dbResSection->Fetch()) {
                $arResult[$PROPERTY_CODE . "_SECTIONS"][$arRes["ID"]]["TITLE"] = LANGUAGE_ID != "ru" ? $arRes["UF_NAME" . POSTFIX_PROPERTY] : $arRes["NAME"];
                if ($arRes["PICTURE"]) {
                    $arResult[$PROPERTY_CODE . "_SECTIONS"][$arRes["ID"]]["PICTURE"] = CFile::GetFileArray($arRes["PICTURE"]);
                }
            }
        }
    }
}
