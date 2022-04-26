<?

$arResult["SERVICES_ICON"] = array();
$db_props = CIBlockElement::GetList(Array("sort" => "asc"), Array("IBLOCK_ID"=>10, "ACTIVE"=>"Y"), false, false, Array("ID", "NAME","PROPERTY_SERVICE_ICON","PROPERTY_NAME".POSTFIX_PROPERTY));
while($ar_props = $db_props->Fetch()){
    $arResult["SERVICES_ICON"][$ar_props["ID"]] = array(
        "ICON" => $ar_props["PROPERTY_SERVICE_ICON_VALUE"],
        "TITLE" => POSTFIX_PROPERTY == "" ? $ar_props["NAME"] : $ar_props["PROPERTY_NAME".POSTFIX_PROPERTY."_VALUE"]
    );
}
