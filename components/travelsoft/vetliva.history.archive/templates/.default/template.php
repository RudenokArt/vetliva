<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();
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
$this->setFrameMode(true);

if (!file_exists(Bitrix\Main\Application::getDocumentRoot() . $templateFolder . "/objects/{$arParams["OBJECT"]}.php")) {
    throw new Exception("vetliva history archive: объект архива \"{$arParams["OBJECT"]}\" не найден");
}

include_once "objects/{$arParams["OBJECT"]}.php";
?>