<? 
$this->addExternalCss($templateFolder . "/bootstrap-select.min.css");
$this->addExternalJs($templateFolder . "/bootstrap-select.min.js"); ?>
<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();
if (!$arParams["__BOOKING_REQUEST"]["id"][0]) {
    $custom = array_keys($arResult["CALCULATION"]);
    if ($custom[0])  $arParams["__BOOKING_REQUEST"]["id"][0] = $custom[0];
}        
$no_results = false;
if ($arParams["__BOOKING_REQUEST"]['date_from']) $seat_start_date = date('d.m.Y',$arParams["__BOOKING_REQUEST"]['date_from']);
else $seat_start_date = date('d.m.Y');
if(empty($arResult["CALCULATION"])) $no_results = true;
//if(!$no_results)
//include_once 'specifying_form.php';
if (!$no_results)
include_once 'citizen_price_select.php';
if ($no_results) {
    if (empty($arResult["CALCULATION"]) && empty($arResult["SETTLING_BY"]) && !$arResult["NEED_LAZY_LOAD"]) {

         include_once 'similar_offers.php';
    } elseif (!empty($arResult["SETTLING_BY"])) {

         include_once 'settling_by.php';
    } else {
        
         include_once "common.php";
    }
$APPLICATION->IncludeComponent("travelsoft:seat.availability", "", [
    "OBJECT_ID" => $arParams["__BOOKING_REQUEST"]["id"][0],
    "DATE_FROM" => $seat_start_date,
    "LINK" => $APPLICATION->GetCurDir(),
    "EMTY_RESULT"=>"Y",
    "__BOOKING_REQUEST" => $arResult["REQUEST"]->getPropertiesLikeArray()
]);?>
<span id="sp"></span>
<?
}
else {
$APPLICATION->IncludeComponent("travelsoft:seat.availability", "", [
    "OBJECT_ID" => $arParams["__BOOKING_REQUEST"]["id"][0],
    "DATE_FROM" => $seat_start_date,
    "LINK" => $APPLICATION->GetCurDir(),
    "__BOOKING_REQUEST" => $arResult["REQUEST"]->getPropertiesLikeArray()
]);
?>
<span id="sp"></span>

    <?
    if (empty($arResult["CALCULATION"]) && empty($arResult["SETTLING_BY"]) && !$arResult["NEED_LAZY_LOAD"]) {

        return include_once 'similar_offers.php';
    } elseif (!empty($arResult["SETTLING_BY"])) {

        return include_once 'settling_by.php';
    } else {
        
        return include_once "common.php";
    }
}?>