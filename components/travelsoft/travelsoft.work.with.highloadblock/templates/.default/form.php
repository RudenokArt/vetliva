<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(false);

$template = "add-service";
if ($arParams['ADD_ROW_COMPONENT_TEMPLATE'] != "") {
    $template = $arParams["ADD_ROW_COMPONENT_TEMPLATE"];
}

$APPLICATION->IncludeComponent("travelsoft:travelsoft.highloadblock.add.row", $template, $arParams, false);
?>
