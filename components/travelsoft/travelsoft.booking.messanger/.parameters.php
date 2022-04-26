<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$rsGroups = CGroup::GetList(($by="c_sort"), ($order="desc"));
$arGroups[] = "Учитывать пользователей всех групп";
while($arGroup = $rsGroups->Fetch()) {
    $arGroups[$arGroup["ID"]] = $arGroup["NAME"];
}

$arComponentParameters["PARAMETERS"]['ORDER_ID'] = array(
            "PARENT" => "BASE",
            "NAME" => "Номер заказа",
            "TYPE" => "STRING",
            "DEFAULT" => '={$_REQUEST[\'order_id\']}'
        );

$arComponentParameters["PARAMETERS"]['TOKEN'] = array(
            "PARENT" => "BASE",
            "NAME" => "Токен пользователя для получения сообщений",
            "TYPE" => "STRING",
            "DEFAULT" => '={$_SESSION[\'__TRAVELSOFT\'][\'TOKEN\']}'
        );

$arComponentParameters["PARAMETERS"]['USE_AJAX'] = array(
            "PARENT" => "BASE",
            "NAME" => "Работа в ajax-режиме",
            "REFRESH" => "Y",
            "TYPE" => "CHECKBOX"
        );

$arComponentParameters["PARAMETERS"]['DATE_FROM'] = array(
            "PARENT" => "BASE",
            "NAME" => "Дата с которой показывать сообщения (DD.MM.YYYY HH:MM:SS)",
            "TYPE" => "STRING"
        );

if ($arCurrentValues["USE_AJAX"] == "Y" ) {
    $arComponentParameters["PARAMETERS"]['FREQREQUEST'] = array(
            "PARENT" => "BASE",
            "NAME" => "Частота опроса входящих сообщений (в секундах)",
            "TYPE" => "STRING"
        );
}