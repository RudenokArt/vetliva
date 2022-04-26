<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();
$no_results = false;
include_once 'citizen_price_select.php';
if(empty($arResult["CALCULATION"])) $no_results = true;
if ($no_results) {
    if (empty($arResult["CALCULATION"]) && empty($arResult["SETTLING_BY"])  && !$arResult["NEED_LAZY_LOAD"]) {

         include_once 'similar_offers.php';
    } elseif (!empty($arResult["SETTLING_BY"])) {

         include_once 'settling_by_for_placements.php';
    } else {

         include_once "common.php";
    }
     $APPLICATION->IncludeComponent("travelsoft:seat.availability", "", [
        "OBJECT_ID" => $arParams["__BOOKING_REQUEST"]["id"][0],
        "DATE_FROM" => date('d.m.Y'),
        "EMTY_RESULT"=>"Y",
        "LINK" => $APPLICATION->GetCurDir(),
        "__BOOKING_REQUEST" => $arResult["REQUEST"]->getPropertiesLikeArray()
    ]);?>
    <span id="sp"></span>
    <?
}
else {
    $APPLICATION->IncludeComponent("travelsoft:seat.availability", "", [
        "OBJECT_ID" => $arParams["__BOOKING_REQUEST"]["id"][0],
        "DATE_FROM" => date('d.m.Y'),
        "LINK" => $APPLICATION->GetCurDir(),
        "__BOOKING_REQUEST" => $arResult["REQUEST"]->getPropertiesLikeArray()
    ]);?>
    <span id="sp"></span>
    <?
    if (empty($arResult["CALCULATION"]) && empty($arResult["SETTLING_BY"])  && !$arResult["NEED_LAZY_LOAD"]) {

        return include_once 'similar_offers.php';
    } elseif (!empty($arResult["SETTLING_BY"])) {

        return include_once 'settling_by_for_placements.php';
    } else {

        return include_once "common.php";
    }
    
}?>