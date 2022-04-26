<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

Bitrix\Main\Loader::includeModule("travelsoft.favorites");

$arComponentParameters["PARAMETERS"]['OBJECT_ID'] = array(
            "PARENT" => "BASE",
            "NAME" => "ID объекта",
            "TYPE" => "STRING"
        );
 
$arComponentParameters["PARAMETERS"]['OBJECT_TYPE'] = array(
            "PARENT" => "BASE",
            "NAME" => "Тип объекта",
            "TYPE" => "LIST",
            "VALUES" => travelsoft\favorites\Utils::getObjectTypes()
        );
$arComponentParameters["PARAMETERS"]['STORE_ID'] = array(
            "PARENT" => "BASE",
            "NAME" => "ID хранилища элемента",
            "TYPE" => "STRING"
        );