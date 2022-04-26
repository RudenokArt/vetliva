<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();


// формирование ссылки на добавление по экскурсионным турам
if ($arParams["IS_EXC_TOUR"] == "Y") {

    foreach ($arResult["ELEMENTS"] as &$arElement) {
        $id[] = $arElement["ID"];        
    }
    
    if ($id) {
        
        Bitrix\Main\Loader::includeModule("highloadblock");
        $data_class = \Bitrix\Highloadblock\HighloadBlockTable::compileEntity(
                    \Bitrix\Highloadblock\HighloadBlockTable::getById(SERVICES_BOOKING_HL_BLOCK)->fetch())->getDataClass();
                
        $db_res = $data_class::getList(array(
            "filter" => array("UF_IBLOCK_ELEMENT_ID" => $id),
            "select" => array("ID", "UF_IBLOCK_ELEMENT_ID")
        ));
        
        while ($res = $db_res->fetch()) {
            $arResult["add_price"][$res["UF_IBLOCK_ELEMENT_ID"]] = "<li ><a href=\"/partners/tseny-i-nalichie-mest/dobavlenie-redaktirovanie-tsen/?row_id=".$res["ID"]."\"><i class=\"icon-coins\"></i></a></li>";
        }
        
        
    } 
    
}