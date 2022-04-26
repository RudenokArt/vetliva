<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if(
    $arParams['FILTER_NAME'] &&
    isset($GLOBALS[$arParams['FILTER_NAME']]['ID']) &&
    is_array($GLOBALS[$arParams['FILTER_NAME']]['ID']) &&
    (count($GLOBALS[$arParams['FILTER_NAME']]['ID']) > 1)
){
    $sort = array_flip($GLOBALS[$arParams['FILTER_NAME']]['ID']);

    usort($arResult['ITEMS'], function($a, $b) use($sort){
        return $sort[$a['ID']] - $sort[$b['ID']];
    });
}