<?php

require_once "cronHeader.php";

Bitrix\Main\Loader::includeModule("travelsoft.currency");
Bitrix\Main\Loader::includeModule("iblock");
$CURDATE = date("d.m.Y");
$IBLOCK_ID = Bitrix\Main\Config\Option::get("travelsoft.currency", "courses_iblock_id");

# попытка получения валюты на текущий день из базы данных
$arCourse = CIBlockElement::GetList(false, array("ACTIVE" => "Y", "IBLOCK_ID" => $IBLOCK_ID, "PROPERTY_DATE" => $CURDATE))->Fetch();

if ($arCourse["ID"] > 0) {
    exit;
}

# получение валюты на текущий день из нац.банка
$arImportCurrencyCourses = Bitrix\Main\Web\Json::decode(file_get_contents("https://www.nbrb.by/API/ExRates/Rates?onDate=".date('Y-m-d')."&Periodicity=0"));

$arAllCurrency = travelsoft\Currency::getInstance()->get("currency");

# получение iso кодов валюты, которую нужно выгрузить
$arIsoCurrency = null;
foreach ($arAllCurrency as $arItem) {
    $arIsoCurrency[] = $arItem["iso"];
}

# фильтрация "нужных" курсов валют
$arCoursesNeeded = array_filter($arImportCurrencyCourses, function ($arItem) use ($arIsoCurrency) {
    return in_array($arItem["Cur_Abbreviation"], $arIsoCurrency);
});

$arBase = travelsoft\Currency::getInstance()->get("base_currency");
$arSave = array(
    "IBLOCK_ID" =>$IBLOCK_ID, 
    "NAME" => $CURDATE,
    "CODE" => date('d-m-Y'),
    "ACTIVE" => "Y",
    "PROPERTY_VALUES" => array(
        "DATE" => $CURDATE,
        $arBase["iso"] => 1
    )
);

foreach ($arCoursesNeeded as $arValue) {
    $arSave["PROPERTY_VALUES"][$arValue["Cur_Abbreviation"]] = $arValue["Cur_OfficialRate"]/$arValue["Cur_Scale"];
}

$ibel = new CIBlockElement;
if (!empty($arCoursesNeeded)) {
    # добавляем
    $ibel->Add($arSave);
}