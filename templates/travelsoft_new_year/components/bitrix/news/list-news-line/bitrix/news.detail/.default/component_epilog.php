<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

global $APPLICATION;

if (LANGUAGE_ID == "ru") {
    $APPLICATION->SetTitle($arResult["NAME"]);
    $APPLICATION->AddChainItem($arResult["NAME"]);
} else {

    $APPLICATION->SetTitle($arResult["PROPERTIES"]["NAME".POSTFIX_PROPERTY]["VALUE"]);
    $APPLICATION->AddChainItem($arResult["PROPERTIES"]["NAME".POSTFIX_PROPERTY]["VALUE"]);
}