<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
/** @var CBitrixComponent $this */
/** @var array $arParams */
/** @var array $arResult */
/** @var string $componentPath */
/** @var string $componentName */
/** @var string $componentTemplate */
/** @global CMain $APPLICATION */

$arParams["IBLOCK_ID"] = trim($arParams["IBLOCK_ID"]);
$arParams["ELEMENT_ID"] = intval($arParams["~ELEMENT_ID"]);
$APPLICATION->RestartBuffer();
if($this->startResultCache()) {
    $langPrefix = '_'.mb_strtoupper(LANGUAGE_ID);
    $db_props = CIBlockElement::GetProperty($arParams["IBLOCK_ID"], $arParams["ELEMENT_ID"], array("sort" => "asc"), Array("CODE" => "TEXT".$langPrefix));
    if($ar_props = $db_props->Fetch()) {
        $arResult['TEXT'] = $ar_props['VALUE']['TEXT'];
    }
    else {
        $this->abortResultCache();
        Iblock\Component\Tools::process404(
            trim($arParams["MESSAGE_404"]) ?: GetMessage("T_NEWS_DETAIL_NF")
            ,true
            ,$arParams["SET_STATUS_404"] === "Y"
            ,$arParams["SHOW_404"] === "Y"
            ,$arParams["FILE_404"]
        );
        return 0;
    }


//    echo $arResult['TEXT'];

    $this->IncludeComponentTemplate();
}
exit();

