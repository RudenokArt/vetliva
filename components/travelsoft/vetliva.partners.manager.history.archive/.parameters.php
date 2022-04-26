<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$rsGroups = CGroup::GetList(($by="c_sort"), ($order="desc"));
$arGroups[] = "Учитывать пользователей всех групп";
while($arGroup = $rsGroups->Fetch()) {
    $arGroups[$arGroup["ID"]] = $arGroup["NAME"];
}

$arComponentParameters["PARAMETERS"]['OBJECT'] = array(
            "PARENT" => "BASE",
            "NAME" => "Объект архива",
            "TYPE" => "LIST",
            "MULTIPLE" => "N",
            "VALUES" => [
                "prices" => "Цены",
                "quotas" => "Квоты",
                "stop_sale" => "Stop sale"
            ]
        );
    