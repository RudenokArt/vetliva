<?
$arResult["SERVICES"] = array();
$db_props = CIBlockElement::GetList(Array("sort" => "asc"), Array("IBLOCK_ID"=>10, "ACTIVE"=>"Y"), false, false, Array("ID", "NAME","PROPERTY_NAME".POSTFIX_PROPERTY));
while($ar_props = $db_props->Fetch()){
    $arResult["SERVICES"][$ar_props["ID"]] = POSTFIX_PROPERTY == "" ? $ar_props["NAME"] : $ar_props["PROPERTY_NAME".POSTFIX_PROPERTY."_VALUE"];
}
$arResult["MED_PROF"] = array();
$db_props = CIBlockElement::GetList(Array("sort" => "asc"), Array("IBLOCK_ID"=>17, "ACTIVE"=>"Y"), false, false, Array("ID", "NAME","PROPERTY_NAME".POSTFIX_PROPERTY));
while($ar_props = $db_props->Fetch()){
    $arResult["MED_PROF"][$ar_props["ID"]] = POSTFIX_PROPERTY == "" ? $ar_props["NAME"] : $ar_props["PROPERTY_NAME".POSTFIX_PROPERTY."_VALUE"];
}
$arResult["MED_SERVICES"] = array();
$db_props = CIBlockElement::GetList(Array("sort" => "asc"), Array("IBLOCK_ID"=>18, "ACTIVE"=>"Y"), false, false, Array("ID", "NAME","PROPERTY_NAME".POSTFIX_PROPERTY));
while($ar_props = $db_props->Fetch()){
    $arResult["MED_SERVICES"][$ar_props["ID"]] = POSTFIX_PROPERTY == "" ? $ar_props["NAME"] : $ar_props["PROPERTY_NAME".POSTFIX_PROPERTY."_VALUE"];
}

$arResult["PROPERTIES"] = [];
$properties = CIBlockProperty::GetList(Array("sort"=>"asc", "name"=>"asc"), Array("ACTIVE"=>"Y", "IBLOCK_ID"=>$arParams["IBLOCK_ID"]));
while ($prop_fields = $properties->GetNext())
{
    if(in_array($prop_fields["CODE"],$arParams["PROPERTY_CODE"]) && !in_array($prop_fields["CODE"],array("REGIONS","TOWN","REGION","COUNTRY"))){
        $arResult["PROPERTIES"][$prop_fields["CODE"]] = $prop_fields["NAME"];
    }
}

foreach ($arResult["ITEMS"] as $key=>$item) {

    if(isset($item["DISPLAY_PROPERTIES"]["REGIONS"])){
        if(!empty($item["DISPLAY_PROPERTIES"]["REGIONS"]["VALUE"])){
            $arResult["ITEMS"][$key]["PROPERTIES"]["REGIONS"]["DISPLAY_VALUE"] = $item["DISPLAY_PROPERTIES"]["REGIONS"]["DISPLAY_VALUE"];
        }
        unset($arResult["ITEMS"][$key]["DISPLAY_PROPERTIES"]["REGIONS"]);
    }
    if(isset($item["DISPLAY_PROPERTIES"]["TOWN"])){
        if(!empty($item["DISPLAY_PROPERTIES"]["TOWN"]["VALUE"])){
            $arResult["ITEMS"][$key]["PROPERTIES"]["TOWN"]["DISPLAY_VALUE"] = $item["DISPLAY_PROPERTIES"]["TOWN"]["DISPLAY_VALUE"];
        }
        unset($arResult["ITEMS"][$key]["DISPLAY_PROPERTIES"]["TOWN"]);
    }
    if(isset($item["DISPLAY_PROPERTIES"]["REGION"])){
        if(!empty($item["DISPLAY_PROPERTIES"]["REGION"]["VALUE"])){
            $arResult["ITEMS"][$key]["PROPERTIES"]["REGION"]["DISPLAY_VALUE"] = $item["DISPLAY_PROPERTIES"]["REGION"]["DISPLAY_VALUE"];
        }
        unset($arResult["ITEMS"][$key]["DISPLAY_PROPERTIES"]["REGION"]);
    }
    if(isset($item["DISPLAY_PROPERTIES"]["COUNTRY"])){
        if(!empty($item["DISPLAY_PROPERTIES"]["COUNTRY"]["VALUE"])){
            $arResult["ITEMS"][$key]["PROPERTIES"]["COUNTRY"]["DISPLAY_VALUE"] = $item["DISPLAY_PROPERTIES"]["COUNTRY"]["DISPLAY_VALUE"];
        }
        unset($arResult["ITEMS"][$key]["DISPLAY_PROPERTIES"]["COUNTRY"]);
    }

}