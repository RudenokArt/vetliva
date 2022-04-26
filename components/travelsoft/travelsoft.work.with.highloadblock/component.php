<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
/** @var CBitrixComponent $this */
/** @var array $arParams */
/** @var array $arResult */
/** @var string $componentPath */
/** @var string $componentName */
/** @var string $componentTemplate */
/** @global CDatabase $DB */
/** @global CUser $USER */
/** @global CMain $APPLICATION */
$this->setFrameMode(false);

if (!empty($_REQUEST["edit"])) {
	$componentPage = "form";
} else {
	$componentPage = "list";
}
$arParams["LIST_URL"] = $APPLICATION->GetCurPageParam("", array("edit", "copy", "delete", "row_id", "sessid"), false);
$arParams['EDIT_URL'] = $APPLICATION->GetCurPageParam("edit=Y", array("edit", "copy", "delete", "row_id", "sessid"), false);

$this->IncludeComponentTemplate($componentPage);
?>
