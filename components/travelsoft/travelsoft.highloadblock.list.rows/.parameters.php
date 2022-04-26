<?php

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();

CModule::IncludeModule('highloadblock');

$arHiloadblocks = Bitrix\Highloadblock\HighloadBlockTable::getList(array(
            "order" => array("ID" => "ASC")
        ))->fetchAll();

foreach ($arHiloadblocks as $arHL) {
    $arHLDef[$arHL['ID']] = $arHL['NAME'];
}

foreach ($arHiloadblocks as $arHL) {
    $arHLDef[$arHL['ID']] = $arHL['NAME'];
}

$arUsersGroups = Bitrix\Main\GroupTable::getList(array(
            "select" => array('ID', "NAME")
        ))->fetchAll();

foreach ($arUsersGroups as $arUserGroup) {
    $arUGDef[$arUserGroup['ID']] = $arUserGroup['NAME'];
}

$arComponentParameters = array(
    "GROUPS" => array(
    ),
    "PARAMETERS" => array(
        "BLOCK_ID" => array(
            "PARENT" => "BASE",
            "NAME" => "ID highload - сущности (ТАБЛИЦЫ)",
            "TYPE" => "LIST",
            "VALUES" => $arHLDef
        ),
        "USER_LINK_FIELD_NAME" => array(
            "PARENT" => "BASE",
            "NAME" => "Название поля сущности для привязки к пользователю",
            "TYPE" => "TEXT"
        ),
        "ALLOW_USER_GROUPS" => array(
            "PARENT" => "BASE",
            "NAME" => "Группы пользователей, которым разрешено добавлять/редактировать элементы",
            "TYPE" => "LIST",
            "MULTIPLE" => "Y",
            "VALUES" => $arUGDef
        ),
        "ADD_PRICES_LINK" => array(
            "PARENT" => "BASE",
            "NAME" => "Страница добавления цен"
        ),
        "ADDITIONAL_PARAM" => array(
            "PARENT" => "BASE",
            "NAME" => "Дополнительный параметр",
            "TYPE" => "TEXT"
        ),
        "SERVICE_TYPE_ID" => array(
            "PARENT" => "BASE",
            "NAME" => "ID типа услуги",
            "TYPE" => "TEXT"
        ),
        "EDIT_URL" => array(
            "PARENT" => "BASE",
            "NAME" => "Путь для добавления/редактирования",
            "TYPE" => "TEXT"
        ),
        "CNT_ELS" => array(
            "PARENT" => "BASE",
            "NAME" => "Количество элементов на странице списка",
            "TYPE" => "TEXT"
        ),
        "SHOW_FILTER_BY_OBJECTS" => array(
            "PARENT" => "BASE",
            "NAME" => "Показывать фильтр по объектам",
            "TYPE" => "CHECKBOX"
        ),
        "SHOW_FILTER_BY_SERVICES" => array(
            "PARENT" => "BASE",
            "NAME" => "Показывать фильтр по услугам",
            "TYPE" => "CHECKBOX"
        )
    )
);

if ($arCurrentValues['BLOCK_ID'] > 0) {

    $arFields = $GLOBALS['USER_FIELD_MANAGER']->GetUserFields('HLBLOCK_' . $arCurrentValues['BLOCK_ID'], 0, LANGUAGE_ID);
    foreach ($arFields as $arField) {

        $arUFFields[$arField['ID']] = $arField['FIELD_NAME'];
    }

    if ($arUFFields) {
        $arComponentParameters["PARAMETERS"]["SHOW_UF_FIELDS"] = array(
            "PARENT" => "BASE",
            "NAME" => "Поля для отображения (если не указаны, то выведутся все доступные)",
            "TYPE" => "LIST",
            "MULTIPLE" => "Y",
            "VALUES" => $arUFFields
        );
    }
}