<?

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();

#######################
# СОРТИРОВКА
#######################

function prepareSortByPrice(array $arParams = null) {

    \Bitrix\Main\Loader::includeModule("iblock");
    \Bitrix\Main\Loader::includeModule("travelsoft.booking.dev.tools");
    $factor = 100;

    if (!$arParams["__BOOKING_REQUEST"]) {
        // СБРАСЫВАЕМ ПОЛЕ SORY_BY_PRICE
        $dbRes = CIBlockElement::GetList(false, array("IBLOCK_ID" => $arParams['IBLOCK_ID'], "ACTIVE" => "Y"), false, false, array("ID"));
        if ($_REQUEST["order"] == "asc" && $arParams["CALCULATION_PRICE_RESULT"]) {
            $sortIndex = (count($arParams["CALCULATION_PRICE_RESULT"]) + 1) * $factor;
        } else {
            $sortIndex = 0;
        }
        
        while ($arRes = $dbRes->Fetch()) {
            CIBlockElement::SetPropertyValuesEx($arRes["ID"], $arParams['IBLOCK_ID'], array("SORT_BY_PRICE" => $sortIndex));
        }
    }

    if ($arParams["CALCULATION_PRICE_RESULT"]) {
        $calc = $arParams["CALCULATION_PRICE_RESULT"];
        if ($_REQUEST["order"] == "asc") {
            uasort($calc, function ($a, $b) {
                return $a["PRICE"] - $b["PRICE"];
            });
            $i = 1;
            foreach ($calc as $id => $arr) {
                CIBlockElement::SetPropertyValuesEx($id, $arParams['IBLOCK_ID'], array("SORT_BY_PRICE" => $i * $factor));
                $i++;
            }
        } else {
            uasort($calc, function ($a, $b) {
                return $b["PRICE"] - $a["PRICE"];
            });
            $i = count($calc);
            foreach ($calc as $id => $arr) {
                CIBlockElement::SetPropertyValuesEx($id, $arParams['IBLOCK_ID'], array("SORT_BY_PRICE" => $i * $factor));
                $i--;
            }
        }
    }
}

if($arParams["IBLOCK_ID"] == PLATFORM_IBLOCK_ID) {
    $arSortBy = array(
        "name" => array(
            "realfield" => "NAME",
            "order_def" => "asc"
        ),
        "sort" => array(
            "realfield" => "SORT",
            "order_def" => "asc"
        ),
    );
} else {
    $arSortBy = array(
        "name" => array(
            "realfield" => "NAME",
            "order_def" => "asc"
        ),
        "price" => array(
            "realfield" => "PROPERTY_SORT_BY_PRICE",
            "order_def" => "asc",
            "callback" => array(
                "name" => "prepareSortByPrice",
                "params" => array($arParams)
            )
        ),
        "sort" => array(
            "realfield" => "SORT",
            "order_def" => "asc"
        ),
        "rating" => array(
            "realfield" => "PROPERTY_RATING",
            "order_def" => "asc"
        )
    );
}

$arParams["SORT_PARAMETERS"] = null;

if (isset($arSortBy[$_REQUEST["sort_by"]])) {

    if ($arSortBy[$_REQUEST["sort_by"]]["callback"]["name"]) {
        call_user_func_array($arSortBy[$_REQUEST["sort_by"]]["callback"]["name"], $arSortBy[$_REQUEST["sort_by"]]["callback"]["params"]);
    }

    $arParams["SORT_BY1"] = $arSortBy[$_REQUEST["sort_by"]]["realfield"];
    $arParams["SORT_ORDER1"] = $_REQUEST["order"] == "desc" ? "DESC" : "ASC";
}

foreach ($arSortBy as $name => $arp) {
    if ($name == $_REQUEST["sort_by"]) {
        $arParams["SORT_PARAMETERS"][] = array("name" => $name, "order" => $_REQUEST["order"] == "asc" ? "desc" : "asc", "selected" => true);
    } else {
        $arParams["SORT_PARAMETERS"][] = array("name" => $name, "order" => $arp["order_def"], "selected" => false);
    }
}
##################################