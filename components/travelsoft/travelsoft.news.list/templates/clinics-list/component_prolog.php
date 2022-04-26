<?

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();

#######################
# СОРТИРОВКА
#######################

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
    if (LANGUAGE_ID == "ru") {
        $arSortBy = array(
			"sort" => array(
                "realfield" => "SORT",
                "order_def" => "asc"
            ),
            "name" => array(
                "realfield" => "NAME",
                "order_def" => "asc"
            ),
            "rating" => array(
                "realfield" => "PROPERTY_RATING",
                "order_def" => "asc"
            )
        );
    }
    else {
        $arSortBy = array(
			"sort" => array(
                "realfield" => "SORT",
                "order_def" => "asc"
            ),
            "name" => array(
                "realfield" => "PROPERTY_NAME_".strtoupper(LANGUAGE_ID),
                "order_def" => "asc"
            ),
            "rating" => array(
                "realfield" => "PROPERTY_RATING",
                "order_def" => "asc"
            )
        );
    }
    
}

$arParams["SORT_PARAMETERS"] = null;

if (isset($arSortBy[$_REQUEST["sort_by"]])) {

    if ($arSortBy[$_REQUEST["sort_by"]]["callback"]["name"]) {
        call_user_func_array($arSortBy[$_REQUEST["sort_by"]]["callback"]["name"], $arSortBy[$_REQUEST["sort_by"]]["callback"]["params"]);
    }

    $arParams["SORT_BY1"] = $arSortBy[$_REQUEST["sort_by"]]["realfield"];
    $arParams["SORT_ORDER1"] = $_REQUEST["order"] == "desc" ? "DESC" : "ASC";
    if ($arParams["SORT_BY2"]==$arParams["SORT_BY1"]) $arParams["SORT_ORDER2"] = $arParams["SORT_ORDER1"];
}

foreach ($arSortBy as $name => $arp) {
    if ($name == $_REQUEST["sort_by"]) {
        $arParams["SORT_PARAMETERS"][] = array("name" => $name, "order" => $_REQUEST["order"] == "asc" ? "desc" : "asc", "selected" => true);
    } else {
        $arParams["SORT_PARAMETERS"][] = array("name" => $name, "order" => $arp["order_def"], "selected" => false);
    }
}
##################################