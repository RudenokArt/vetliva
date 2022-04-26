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

if($this->startResultCache()){
    $langPrefix = '_'.mb_strtoupper(LANGUAGE_ID);
    $arSelect = Array("ID", "IBLOCK_ID", 'DETAIL_PAGE_URL', 'PROPERTY_NAME'.$langPrefix, 'PROPERTY_SIGNATURE'.$langPrefix, 'PROPERTY_ERORR'.$langPrefix, 'PROPERTY_FILE'.$langPrefix);
    $arFilter = Array("IBLOCK_ID"=>$arParams["IBLOCK_ID"], "ACTIVE"=>"Y");
    $res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);

    while($ar = $res->GetNext()) {
        if(empty($ar['PROPERTY_FILE'.$langPrefix.'_VALUE'])){
            $linkType = 'PAGE';
            $link = $ar['DETAIL_PAGE_URL'];
        } else {
            $linkType = 'FILE';
            $link = CFile::GetPath($ar['PROPERTY_FILE'.$langPrefix.'_VALUE']);
        }

        $arResult['LIST'][] = [
            'ID' =>  $ar['ID'],
            'NAME' =>   $ar['PROPERTY_NAME'.$langPrefix.'_VALUE'],
            'SIGNATURE' => $ar['PROPERTY_SIGNATURE'.$langPrefix.'_VALUE'],
            'ERROR' => $ar['PROPERTY_ERORR'.$langPrefix.'_VALUE'],
            'LINK_TYPE' =>  $linkType,
            'LINK' =>  $link,
        ];
    }

    $this->IncludeComponentTemplate();
}

