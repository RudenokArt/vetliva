<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();

include_once 'citizen_price_select.php';
?>
<span id="sp"></span>

<?
if (empty($arResult["CALCULATION"]) && !$arResult["NEED_LAZY_LOAD"]) {
    
    include_once 'similar_offers.php';
} else {
    include_once "common.php";
}?>


