<?

use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

require_once 'functions.php';

use Bitrix\Main\Loader; 

Loader::includeModule("highloadblock"); 

use Bitrix\Highloadblock as HL; 
use Bitrix\Main\Entity;

$hlbl = 61;
$hlblock = HL\HighloadBlockTable::getById($hlbl)->fetch(); 

$entity = HL\HighloadBlockTable::compileEntity($hlblock); 
$entity_data_class = $entity->getDataClass(); 

$rsData = $entity_data_class::getList(array(
   "select" => array("UF_NAME" . POSTFIX_PROPERTY),
   "order" => array("UF_NAME" . POSTFIX_PROPERTY => "ASC"),
   "filter" => array()
));

$arResult["CITIZENSHIP"] = array();

while($arData = $rsData->Fetch()){
   $arResult["CITIZENSHIP"][] = $arData;
}



$arResult['TODAY'] = date('D');

    if(!empty($arResult['DISPLAY_PROPERTIES']['MED_SERVICES']['VALUE'])){

        $arResult['DISPLAY_PROPERTIES']['MED_SERVICES']['DESC'] = array();

        $iblock_med = $arResult["DISPLAY_PROPERTIES"]["MED_SERVICES"]["LINK_IBLOCK_ID"];
        $arSelectMed = Array("ID", "PROPERTY_DESCRIPTION" . POSTFIX_PROPERTY, "IBLOCK_SECTION_ID");

        if (LANGUAGE_ID != "ru") {
            $arSelectMed[] = "PROPERTY_NAME" . POSTFIX_PROPERTY;
        } else {
            $arSelectMed[] = "NAME";
        }

        $db_props = CIBlockElement::GetList(Array("sort" => "asc"), Array("IBLOCK_ID" => $iblock_med, "ID" => $arResult["DISPLAY_PROPERTIES"]["MED_SERVICES"]["VALUE"], "ACTIVE" => "Y"), false, false, $arSelectMed);

            while ($ar_props = $db_props->Fetch()) {

                $arResult['DISPLAY_PROPERTIES']['MED_SERVICES']['DESC'][$ar_props["ID"]] = array(
                    "ID" => $ar_props["ID"],
                    "NAME" => isset($ar_props["NAME"]) && !empty($ar_props["NAME"]) ? $ar_props["NAME"] : $ar_props["PROPERTY_NAME" . POSTFIX_PROPERTY ."_VALUE"],
                    "DESCRIPTION" => $ar_props["PROPERTY_DESCRIPTION" . POSTFIX_PROPERTY ."_VALUE"]["TYPE"] == "HTML" ? htmlspecialcharsEx($ar_props["PROPERTY_DESCRIPTION" . POSTFIX_PROPERTY ."_VALUE"]["TEXT"]) : $ar_props["PROPERTY_DESCRIPTION" . POSTFIX_PROPERTY ."_VALUE"]["TEXT"],
                );

            }
    }

if(!empty($arResult['DISPLAY_PROPERTIES']['TYPE']['VALUE'])){

        $arResult['DISPLAY_PROPERTIES']['TYPE']['DESC'] = array();

        $iblock_type = $arResult["DISPLAY_PROPERTIES"]["TYPE"]["LINK_IBLOCK_ID"];
        $arSelecttype = Array("ID", "PROPERTY_DESCRIPTION" . POSTFIX_PROPERTY, "IBLOCK_SECTION_ID");

        if (LANGUAGE_ID != "ru") {
            $arSelecttype[] = "PROPERTY_NAME" . POSTFIX_PROPERTY;
        } else {
            $arSelecttype[] = "NAME";
        }

        $db_props_type = CIBlockElement::GetList(Array("sort" => "asc"), Array("IBLOCK_ID" => $iblock_type, "ID" => $arResult["DISPLAY_PROPERTIES"]["TYPE"]["VALUE"], "ACTIVE" => "Y"), false, false, $arSelecttype);

            while ($ar_props_type = $db_props_type->Fetch()) {

                $arResult['DISPLAY_PROPERTIES']['TYPE']['DESC'][$ar_props_type["ID"]] = array(
                    "ID" => $ar_props_type["ID"],
                    "NAME" => isset($ar_props_type["NAME"]) && !empty($ar_props_type["NAME"]) ? $ar_props_type["NAME"] : $ar_props_type["PROPERTY_NAME" . POSTFIX_PROPERTY ."_VALUE"],
                    "DESCRIPTION" => $ar_props_type["PROPERTY_DESCRIPTION" . POSTFIX_PROPERTY ."_VALUE"]["TYPE"] == "HTML" ? htmlspecialcharsEx($ar_props_type["PROPERTY_DESCRIPTION" . POSTFIX_PROPERTY ."_VALUE"]["TEXT"]) : $ar_props_type["PROPERTY_DESCRIPTION" . POSTFIX_PROPERTY ."_VALUE"]["TEXT"],
                );

            }
}


$cp = $this->__component; // объект компонента

if (is_object($cp))
{
    $cp->arResult['PROPERTIES'] = $arResult["PROPERTIES"];
    $cp->SetResultCacheKeys(array('PROPERTIES'));
}
