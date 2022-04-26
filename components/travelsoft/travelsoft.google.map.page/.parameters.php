<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();


\Bitrix\Main\Loader::includeModule("iblock");


$ibTable = new CIBlock;

$dbRes = $ibTable->GetList(array("NAME" => "ASC"), array("ACTIVE" => "Y"));

while ($arRes = $dbRes->Fetch()) {
    
    $arIblocks[$arRes['ID']] = $arRes['NAME'];
    
}

$arComponentParameters = array(
    
    "GROUPS" => array(
        "LOCATIONS" => array(
            "SORT" => 100,
            "NAME" => "Месторасположение"
        ),
         "POINTS" => array(
            "SORT" => 110,
            "NAME" => "Отображение точек на карте"
        ),
    ),
    
    "PARAMETERS" => array(
        
        "region" => array(
            "PARENT" => "LOCATIONS",
            "NAME" => "Инфоблок регионов",
            "TYPE" => "LIST",
            "VALUES" => $arIblocks
        ),
        
        "city" => array(
            "PARENT" => "LOCATIONS",
            "NAME" => "Инфоблок городов",
            "TYPE" => "LIST",
            "VALUES" => $arIblocks
        ),
        
        "points" => array(
            "PARENT" => "POINTS",
            "NAME" => "Отображать на карте",
            "TYPE" => "LIST",
            "MULTIPLE" => "Y",
            "VALUES" => $arIblocks
        ),
        
        "defPoints" => array(
            "PARENT" => "POINTS",
            "NAME" => "Отображать на карте по-умолчанию",
            "TYPE" => "LIST",
            "MULTIPLE" => "Y",
            "VALUES" => $arIblocks
        ),
        "CACHE_TIME" => array()
    )
);
            
