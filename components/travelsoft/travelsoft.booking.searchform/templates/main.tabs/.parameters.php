<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

/**
 * что использовать для поиска по вкладкам
 */

\Bitrix\Main\Loader::includeModule("iblock");

// ДОПОЛНИТЕЛЬНО ПОЛУЧАТЬ ИЗ ОБЪЕКТОВ ПОИСКА
$arAdditional = array(
    "TOWN" => "Выводить как объекты города и подклеивать их название в название основных объектов",
    "TYPE" => "Выводить Медицинские профили как объекты поиска",
    "SIGHTS" => "Выводить Достопримечательности как объекты поиска",
    "ADD_TYPE_TO_NAME" => "Подклеивать тип к имени объекта поиска",
    "NOT_SHOW_REGIONS_LIKE_OBJECT" => "Не показывать области и регионы как основные объекты поиска"
);

$ibTable = new CIBlock;

$dbRes = $ibTable->GetList(array("NAME" => "ASC"), array("ACTIVE" => "Y"));

while ($arRes = $dbRes->Fetch()) {
    
    $arIblocks[$arRes['ID']] = $arRes['NAME'];
    
}
/** Вкладка размещений */
$arTemplateParameters['show_placement_tab'] = array(
            "PARENT" => "BASE",
            "NAME" => "Показывать вкладку размещений",
            "TYPE" => "CHECKBOX",
            "REFRESH" => "Y"
        );

if ($arCurrentValues["show_placement_tab"] == "Y") {
    $arTabs["placement_tab"] = "Вкладка объекты размещения";
    $arTemplateParameters['placement_tab'] = array(
                "PARENT" => "BASE",
                "NAME" => "Производить поиск во вкладке размещений по",
                "TYPE" => "LIST",
                "MULTIPLE" => "Y",
                "REFRESH" => "Y",
                "VALUES" => $arIblocks
            );
    $arTemplateParameters['placement_result_page'] = array(
                "PARENT" => "BASE",
                "NAME" => "Страница результатов поиска(Вкладка размещений)",
                "TYPE" => "STRING"
        );
    
    if ($arCurrentValues["placement_tab"]) {
        for ($i = 0, $cnt = count($arCurrentValues["placement_tab"]); $i < $cnt; $i++) {
            $arTemplateParameters["placement_additional_show_" . $arCurrentValues["placement_tab"][$i]] = array(
                "PARENT" => "BASE",
                    "NAME" => "Дополнительные действия над объектами поиска " . $arIblocks[$arCurrentValues["placement_tab"][$i]],
                    "TYPE" => "LIST",
                    "MULTIPLE" => "Y",
                    "VALUES" => $arAdditional
            );
        }
    }
       
}

/** */

/** Вкладка санатории */
$arTemplateParameters['show_sanatorium_tab'] = array(
            "PARENT" => "BASE",
            "NAME" => "Показывать вкладку санатории",
            "TYPE" => "CHECKBOX",
            "REFRESH" => "Y"
        );

if ($arCurrentValues['show_sanatorium_tab'] == "Y") {
    $arTabs["sanatorium_tab"] = "Вкладка санатории";
    $arTemplateParameters['sanatorium_tab'] = array(
            "PARENT" => "BASE",
            "NAME" => "Производить поиск во вкладке санатории по",
            "TYPE" => "LIST",
            "REFRESH" => "Y",
            "MULTIPLE" => "Y",
            "VALUES" => $arIblocks
        );
    
    $arTemplateParameters['sanatorium_result_page'] = array(
                "PARENT" => "BASE",
                "NAME" => "Страница результатов поиска(Вкладка санатории)",
                "TYPE" => "STRING"
            );
    
    if ($arCurrentValues["sanatorium_tab"]) {
        for ($i = 0, $cnt = count($arCurrentValues["sanatorium_tab"]); $i < $cnt; $i++) {
            $arTemplateParameters["sanatorium_additional_show_" . $arCurrentValues["sanatorium_tab"][$i]] = array(
                "PARENT" => "BASE",
                    "NAME" => "Дополнительные действия над объектами поиска " . $arIblocks[$arCurrentValues["sanatorium_tab"][$i]],
                    "TYPE" => "LIST",
                    "MULTIPLE" => "Y",
                    "VALUES" => $arAdditional
            );
        }
    }

}

/** */

/** Вкладка экскурсии*/
$arTemplateParameters['show_tours_tab'] = array(
            "PARENT" => "BASE",
            "NAME" => "Показывать вкладку экскурсии",
            "TYPE" => "CHECKBOX",
            "REFRESH" => "Y"
        );

if ($arCurrentValues["show_tours_tab"] == "Y") {
    $arTabs["tours_tab"] = "Вкладка экскурсии";
    $arTemplateParameters['tours_tab'] = array(
            "PARENT" => "BASE",
            "NAME" => "Производить поиск во вкладке экскурсии по",
            "TYPE" => "LIST",
            "MULTIPLE" => "Y",
            "REFRESH" => "Y",
            "VALUES" => $arIblocks
        );
    
    $arTemplateParameters['tours_result_page'] = array(
                "PARENT" => "BASE",
                "NAME" => "Страница результатов поиска(Вкладка экскурсии)",
                "TYPE" => "STRING"
            );
    
     if ($arCurrentValues["tours_tab"]) {
        for ($i = 0, $cnt = count($arCurrentValues["tours_tab"]); $i < $cnt; $i++) {
            $arTemplateParameters["tours_additional_show_" . $arCurrentValues["tours_tab"][$i]] = array(
                "PARENT" => "BASE",
                    "NAME" => "Дополнительные действия над объектами поиска " . $arIblocks[$arCurrentValues["tours_tab"][$i]],
                    "TYPE" => "LIST",
                    "MULTIPLE" => "Y",
                    "VALUES" => $arAdditional
            );
        }
     }

}
/** */

/** Вкладка туры*/
$arTemplateParameters['show_excursionstours_tab'] = array(
            "PARENT" => "BASE",
            "NAME" => "Показывать вкладку туры",
            "TYPE" => "CHECKBOX",
            "REFRESH" => "Y"
        );

if ($arCurrentValues["show_excursionstours_tab"] == "Y") {
    $arTabs["show_excursionstours_tab"] = "Вкладка туры";
    $arTemplateParameters['excursionstours_tab'] = array(
            "PARENT" => "BASE",
            "NAME" => "Производить поиск во вкладке туры по",
            "TYPE" => "LIST",
            "MULTIPLE" => "Y",
            "REFRESH" => "Y",
            "VALUES" => $arIblocks
        );
    
    $arTemplateParameters['excursionstours_result_page'] = array(
                "PARENT" => "BASE",
                "NAME" => "Страница результатов поиска(Вкладка туры)",
                "TYPE" => "STRING"
            );
    
     if ($arCurrentValues["excursionstours_tab"]) {
        for ($i = 0, $cnt = count($arCurrentValues["excursionstours_tab"]); $i < $cnt; $i++) {
            $arTemplateParameters["excursionstours_additional_show_" . $arCurrentValues["tours_tab"][$i]] = array(
                "PARENT" => "BASE",
                    "NAME" => "Дополнительные действия над объектами поиска " . $arIblocks[$arCurrentValues["tours_tab"][$i]],
                    "TYPE" => "LIST",
                    "MULTIPLE" => "Y",
                    "VALUES" => $arAdditional
            );
        }
     }

}
/** */

/** Вкладка трансферы */

$arTemplateParameters['show_transfer_tab'] = array(
            "PARENT" => "BASE",
            "NAME" => "Показывать вкладку трансферы",
            "TYPE" => "CHECKBOX",
            "REFRESH" => "Y"
        );

if ($arCurrentValues["show_transfer_tab"] == "Y") {
    $arTabs["transfer_tab"] = "Вкладка  трансферы";
    
    CModule::IncludeModule('highloadblock');

    $arHLS = Bitrix\Highloadblock\HighloadBlockTable::getList(array(
                "order" => array("ID" => "ASC")
            ))->fetchAll();

    foreach ($arHLS as $arHL) {
        $arHiloadblocks[$arHL['ID']] = $arHL['NAME'];
    }
    
    $arTemplateParameters['transfer_tab'] = array(
            "PARENT" => "BASE",
            "NAME" => "Производить поиск во вкладке трансферы по",
            "TYPE" => "LIST",
            "MULTIPLE" => "Y",
            "REFRESH" => "Y",
            "VALUES" => $arHiloadblocks
        );
    
    $arTemplateParameters['transfer_result_page'] = array(
            "PARENT" => "BASE",
            "NAME" => "Страница результатов поиска(Вкладка трансферы)",
            "TYPE" => "STRING"
        );
    
    if ($arCurrentValues["transfer_tab"]) {
        for ($i = 0, $cnt = count($arCurrentValues["transfer_tab"]); $i < $cnt; $i++) {
            $arTemplateParameters["transfer_additional_show_" . $arCurrentValues["transfer_tab"][$i]] = array(
                "PARENT" => "BASE",
                    "NAME" => "Дополнительные действия над объектами поиска " . $arIblocks[$arCurrentValues["transfer_tab"][$i]],
                    "TYPE" => "LIST",
                    "MULTIPLE" => "Y",
                    "VALUES" => $arAdditional
            );
        }
    }

}

if ($arTabs) {
    $arTemplateParameters['active_tab'] = array(
            "PARENT" => "BASE",
            "NAME" => "Активная вкладка по-умалчанию",
            "TYPE" => "LIST",
            "ADDITIONAL_VALUES" => "Y",
            "VALUES" => $arTabs
        );
}

/** */

/** Вкладка авиа */
$arTemplateParameters['show_avia_tab'] = array(
            "PARENT" => "BASE",
            "NAME" => "Показывать вкладку авиа",
            "TYPE" => "CHECKBOX",
            "REFRESH" => "Y"
        );

if ($arCurrentValues['show_avia_tab'] == "Y") {
    $arTabs["avia_tab"] = "Вкладка авиа";
    $arTemplateParameters['avia_tab'] = array(
            "PARENT" => "BASE",
            "NAME" => "Производить поиск во вкладке авиа по",
            "TYPE" => "LIST",
            "REFRESH" => "Y",
            "MULTIPLE" => "Y",
            "VALUES" => $arIblocks
        );
    
    $arTemplateParameters['avia_result_page'] = array(
                "PARENT" => "BASE",
                "NAME" => "Страница результатов поиска(Вкладка авиа)",
                "TYPE" => "STRING"
            );
    
    if ($arCurrentValues["sanatorium_tab"]) {
        for ($i = 0, $cnt = count($arCurrentValues["sanatorium_tab"]); $i < $cnt; $i++) {
            $arTemplateParameters["sanatorium_additional_show_" . $arCurrentValues["sanatorium_tab"][$i]] = array(
                "PARENT" => "BASE",
                    "NAME" => "Дополнительные действия над объектами поиска " . $arIblocks[$arCurrentValues["avia_tab"][$i]],
                    "TYPE" => "LIST",
                    "MULTIPLE" => "Y",
                    "VALUES" => $arAdditional
            );
        }
    }

}

/** */
