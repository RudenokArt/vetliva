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
            "VALUES" => $arHLDef,
            "REFRESH" => "Y"
        ),
        "ROW_ID" => array(
            "PARENT" => "BASE",
            "NAME" => 'ID Записи',
            "TYPE" => "TEXT",
            "DEFAULT" => '={$_REQUEST[\'row_id\']}'
        ),
        "COPY_ID" => array(
            "PARENT" => "BASE",
            "NAME" => 'ID Записи для копирования',
            "TYPE" => "TEXT",
            "DEFAULT" => '={$_REQUEST[\'copy\']}'
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
        "LIST_URL" => array(
            "PARENT" => "BASE",
            "NAME" => 'Страница списка записей',
            "TYPE" => "TEXT"
        ),
        "SERVICE_TYPE_ID" => array(
            "PARENT" => "BASE",
            "NAME" => "ID типа услуги",
            "TYPE" => "TEXT"
        ),
        "URL_FOR_ADD" => array(
            "PARENT" => "BASE",
            "NAME" => "Путь для добавления санатория, отеля и т.д.",
            "TYPE" => "TEXT"
        ),
        "MESSOK" => array(
            "PARENT" => "BASE",
            "NAME" => "Сообщение об успешном сохранении",
            "TYPE" => "TEXT"
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