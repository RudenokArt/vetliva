<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

global $APPLICATION;

$APPLICATION->SetTitle($arResult["NAME"]);
$APPLICATION->AddChainItem($arResult["NAME"]);
