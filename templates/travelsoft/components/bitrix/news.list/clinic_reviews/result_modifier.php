<?

$RATING = array();
$PRICE_QUALITY = array();
$LOCATION = array();
$STAFF = array();
$PURITY = array();
$ROOMS = array();
$FOOD = array();
$RECOMMEND = '';

$prop_from_rating = array("PRICE_QUALITY", "LOCATION", "STAFF", "PURITY", "ROOMS", "FOOD");
foreach ($prop_from_rating as $prop)
{
    $arResult[$prop]["COUNT"] = 0;
    $arResult[$prop]["SUMM"] = 0;
}
$arResult["RECOMMEND"] = 0;
$arResult["RATING"] = 0;
foreach ($arResult["ITEMS"] as $key=>$item) {
    $count = 0;
    $summ = 0;
    $arResult["ITEMS"][$key]["DISPLAY_DATE_CREATE"] = CIBlockFormatProperties::DateFormat($arParams["ACTIVE_DATE_FORMAT"], MakeTimeStamp($item["DATE_CREATE"], CSite::GetDateFormat()));
    foreach ($prop_from_rating as $k=>$prop) {
        if(strlen($arResult[$prop]["NAME"]) <= 0)
            $arResult[$prop]["NAME"] = $item["PROPERTIES"][$prop]["NAME"];
        if (!empty($item["PROPERTIES"][$prop]["VALUE"]) && $item["PROPERTIES"][$prop]["VALUE"] != 0) {
            $count++;
            $summ += $item["PROPERTIES"][$prop]["VALUE"];
            $arResult[$prop]["COUNT"]++;
            $arResult[$prop]["SUMM"] += $item["PROPERTIES"][$prop]["VALUE"];
        }
    }
    $arResult["ITEMS"][$key]["ITEM_RATING"] = round($summ / $count, 1);
    if(!empty($item["PROPERTIES"]["RECOMMEND"]["VALUE"]))
        $arResult["RECOMMEND"]++;
}
$cnt = 0;
foreach ($prop_from_rating as $prop)
{
    if(!empty($arResult[$prop]["SUMM"]) && !empty($arResult[$prop]["COUNT"])){
        $arResult["RATING"] += $arResult[$prop]["SUMM"]/$arResult[$prop]["COUNT"];
        $cnt++;
    }
}

if($arResult["RATING"] > 0 && $cnt > 0) {
    $arResult["RATING"] = round($arResult["RATING"] / $cnt, 1);
}
$arResult["RECOMMEND"] = !empty($arResult["RECOMMEND"]) ? round(($arResult["RECOMMEND"]/count($arResult["ITEMS"]))*100) : 0;