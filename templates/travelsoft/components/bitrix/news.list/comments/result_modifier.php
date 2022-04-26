<?
foreach ($arResult["ITEMS"] as $key=>$item) {
    $arResult["ITEMS"][$key]["DISPLAY_DATE_CREATE"] = CIBlockFormatProperties::DateFormat($arParams["ACTIVE_DATE_FORMAT"], MakeTimeStamp($item["DATE_CREATE"], CSite::GetDateFormat()));
}