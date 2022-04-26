<?php

$arResult['SEARCH_FILTER'] = [
    'SANATORIUM' => GetMessage("SANATORIUM"),
    'EXCURSION' => GetMessage("EXCURSION"),
    'PLACEMENTS' => GetMessage("PLACEMENTS"),
    'ABOUT_BELARUS' => GetMessage("ABOUT_BELARUS"),
];

//foreach ($arResult['SEARCH_FILTER'] as $key=>$value) {
//    $arResult["TYPE"][$key] = [];
//}

foreach ($arResult["SEARCH"] as $search) {
    if ($search["MODULE_ID"] == "iblock"){
        switch ($search["PARAM2"]) {
            case SANATORIUM_IBLOCK_ID:
                if (is_numeric($search['ITEM_ID'])) {
                    $arResult["TYPE"]['SANATORIUM'][] = $search['ITEM_ID'];
                }
                break;
            case EXCURSION_IBLOCK_ID:
                if (is_numeric($search['ITEM_ID'])) {
                    $arResult["TYPE"]['EXCURSION'][] = $search['ITEM_ID'];
                }
                break;
            case PLACEMENTS_IBLOCK_ID:
                if (is_numeric($search['ITEM_ID'])) {
                    $arResult["TYPE"]['PLACEMENTS'][] = $search['ITEM_ID'];
                }
                break;
            default:
                if (is_numeric($search['ITEM_ID'])) {
                    /**
                     * Для новостей добавляем отображение даты
                     */
                    if ($search["PARAM2"] == TOURISM_NEWS_IBLOCK_ID) {
                        $arResult["TYPE"]['ABOUT_BELARUS']['IBLOCK'][$search['PARAM2']]["DISPLAY_DATE"] = "Y";
                    }
                    $arResult["TYPE"]['ABOUT_BELARUS']['IBLOCK'][$search['PARAM2']]["ID"][] = $search['ITEM_ID'];
                }
        }
    } else {
        $arResult["TYPE"]['ABOUT_BELARUS']['TEXT'][] = $search;
    }
}

$arResult["COUNT"] = count($arResult["SEARCH"]);
unset($arResult["SEARCH"]);