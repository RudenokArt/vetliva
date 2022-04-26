<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

global $APPLICATION;

$curpage = str_replace("index.php", "", $APPLICATION->GetCurPage());

if (LANGUAGE_ID == "ru") {
    $APPLICATION->AddChainItem($arResult["NAME"]);
} else {
    $APPLICATION->SetTitle($arResult["PROPERTIES"]["NAME".POSTFIX_PROPERTY]["VALUE"]);
    $APPLICATION->AddChainItem($arResult["PROPERTIES"]["NAME".POSTFIX_PROPERTY]["VALUE"]);
}