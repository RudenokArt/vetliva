<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
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
$template = strlen($arParams["TEMPLATE_DETAIL"]) ? $arParams["TEMPLATE_DETAIL"] : "";
?>

<?
ob_start();
$element_id = $arResult["VARIABLES"]["ELEMENT_ID"];
if (!$element_id) {
    $element_id = (CIBlockElement::GetList(false, ["IBLOCK_ID" => $arParams["IBLOCK_ID"], "CODE" => $arResult["VARIABLES"]["ELEMENT_CODE"]], false, false, ["ID"])->Fetch())["ID"];
}
$APPLICATION->IncludeComponent(
    "travelsoft:favorites.add",
    "",
    Array(
        "OBJECT_ID" => $element_id,
        "OBJECT_TYPE" => "IBLOCK_ELEMENT",
        "STORE_ID" => $arParams["IBLOCK_ID"]
    )
);
$GLOBALS["favorites_html"] = ob_get_clean();?>

<?$APPLICATION->ShowViewContent('head-detail-tours');?>
<div class="detail-cn myclass">
    <div class="row check-rates">
        <div class="col-sm-3 hidden-sm hidden-xs col-lg-3 detail-sidebar">
            <div class="scrolly scrollspy-sidebar sidebar-detail scroll-heading" role="complementary" data-offset="20">
                <ul class="nav">
                    <?$APPLICATION->ShowViewContent('menu-item-detail-tours');?>
                    <?$APPLICATION->ShowViewContent('menu-item-iblock-excursions');?>
                    <?$APPLICATION->ShowViewContent('menu-item-iblock-tours');?>
                    <?$APPLICATION->ShowViewContent('menu-item-iblock-hotels');?>
                </ul>
                <div style="width: 100%; height: 20px;"></div>
            </div>
        </div>
        <div class="col-lg-9 check-rates-cn section-list">
            <?$ElementID = $APPLICATION->IncludeComponent(
                "bitrix:news.detail",
                $template,
                Array(
                    "DISPLAY_DATE" => $arParams["DISPLAY_DATE"],
                    "DISPLAY_NAME" => $arParams["DISPLAY_NAME"],
                    "DISPLAY_PICTURE" => $arParams["DISPLAY_PICTURE"],
                    "NO_SHOW_WATERMARK" => $arParams["NO_SHOW_WATERMARK"],
                    "VIEW_DETAIL_ACCOMODATION" => $arParams["VIEW_DETAIL_ACCOMODATION"],
                    "VIEW_DETAIL_SANATORIUM" => $arParams["VIEW_DETAIL_SANATORIUM"],
                    "VIEW_DETAIL_TOUR" => $arParams["VIEW_DETAIL_TOUR"],
                    "HIDE_DETAIL_PICTURE" => $arParams["HIDE_DETAIL_PICTURE"],
                    "DISPLAY_PREVIEW_TEXT" => $arParams["DISPLAY_PREVIEW_TEXT"],
                    "IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
                    "IBLOCK_ID" => $arParams["IBLOCK_ID"],
                    "FIELD_CODE" => $arParams["DETAIL_FIELD_CODE"],
                    "PROPERTY_CODE" => $arParams["DETAIL_PROPERTY_CODE"],
                    "DETAIL_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["detail"],
                    "SECTION_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["section"],
                    "META_KEYWORDS" => $arParams["META_KEYWORDS"],
                    "META_DESCRIPTION" => $arParams["META_DESCRIPTION"],
                    "BROWSER_TITLE" => $arParams["BROWSER_TITLE"],
                    "SET_BROWSER_TITLE" => "Y",
                    "SET_CANONICAL_URL" => $arParams["DETAIL_SET_CANONICAL_URL"],
                    "DISPLAY_PANEL" => $arParams["DISPLAY_PANEL"],
                    "SET_LAST_MODIFIED" => $arParams["SET_LAST_MODIFIED"],
                    "SET_TITLE" => $arParams["SET_TITLE"],
                    "MESSAGE_404" => $arParams["MESSAGE_404"],
                    "SET_STATUS_404" => $arParams["SET_STATUS_404"],
                    "SHOW_404" => $arParams["SHOW_404"],
                    "FILE_404" => $arParams["FILE_404"],
                    "INCLUDE_IBLOCK_INTO_CHAIN" => $arParams["INCLUDE_IBLOCK_INTO_CHAIN"],
                    "ADD_SECTIONS_CHAIN" => $arParams["ADD_SECTIONS_CHAIN"],
                    "ACTIVE_DATE_FORMAT" => $arParams["DETAIL_ACTIVE_DATE_FORMAT"],
                    "CACHE_TYPE" => "N",
                    "CACHE_TIME" => $arParams["CACHE_TIME"],
                    "CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
                    "USE_PERMISSIONS" => $arParams["USE_PERMISSIONS"],
                    "GROUP_PERMISSIONS" => $arParams["GROUP_PERMISSIONS"],
                    "DISPLAY_TOP_PAGER" => $arParams["DETAIL_DISPLAY_TOP_PAGER"],
                    "DISPLAY_BOTTOM_PAGER" => $arParams["DETAIL_DISPLAY_BOTTOM_PAGER"],
                    "PAGER_TITLE" => $arParams["DETAIL_PAGER_TITLE"],
                    "PAGER_SHOW_ALWAYS" => "N",
                    "PAGER_TEMPLATE" => $arParams["DETAIL_PAGER_TEMPLATE"],
                    "PAGER_SHOW_ALL" => $arParams["DETAIL_PAGER_SHOW_ALL"],
                    "CHECK_DATES" => $arParams["CHECK_DATES"],
                    "ELEMENT_ID" => $arResult["VARIABLES"]["ELEMENT_ID"],
                    "ELEMENT_CODE" => $arResult["VARIABLES"]["ELEMENT_CODE"],
                    "IBLOCK_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["news"],
                    "USE_SHARE" => $arParams["USE_SHARE"],
                    "SHARE_HIDE" => $arParams["SHARE_HIDE"],
                    "SHARE_TEMPLATE" => $arParams["SHARE_TEMPLATE"],
                    "SHARE_HANDLERS" => $arParams["SHARE_HANDLERS"],
                    "SHARE_SHORTEN_URL_LOGIN" => $arParams["SHARE_SHORTEN_URL_LOGIN"],
                    "SHARE_SHORTEN_URL_KEY" => $arParams["SHARE_SHORTEN_URL_KEY"],
                    "ADD_ELEMENT_CHAIN" => (isset($arParams["ADD_ELEMENT_CHAIN"]) ? $arParams["ADD_ELEMENT_CHAIN"] : '')
                ),
                $component
            );?>

            <?
            if($ElementID){
                $keyCrc = abs(crc32($ElementID));
            }
            ?>

            <?if($arParams["VIEW_DETAIL_TOUR_ON_ELEMENT"]=="Y" && $ElementID):?>
                <?
                $GLOBALS['arrFilterExcursionShort']["PROPERTY_SIGHTS"] = $ElementID;
                $GLOBALS['arrFilterExcursionShort']["PROPERTY_IS_EXCURSION_TOUR"] = false;

                switch (SITE_ID){
                    case 's1':
                        $GLOBALS['arrFilterExcursionShort']['PROPERTY_SHOWONPAGE'] = 323;
                        break;
                    case 'en':
                        $GLOBALS['arrFilterExcursionShort']['PROPERTY_SHOWONPAGE'] = 325;
                        break;
                    case 'by':
                        $GLOBALS['arrFilterExcursionShort']['PROPERTY_SHOWONPAGE'] = 324;
                        break;
                }

                ?>
                <?$APPLICATION->IncludeComponent(
                    "bitrix:news.list",
                    "tours-short",
                    array(
                        "TITLE_LIST" => GetMessage("OTHER_TOURS"),
                        "IBLOCK_TYPE" => "Tourproduct",
                        "IBLOCK_ID" => "33",
                        "NEWS_COUNT" => "3",
                        "SORT_BY1" => "RAND",
                        "SORT_ORDER1" => "DESC",
                        "SORT_BY2" => "SORT",
                        "SORT_ORDER2" => "ASC",
                        "FIELD_CODE" => array(
                            0 => "",
                            1 => "",
                        ),
                        "PROPERTY_CODE" => array(
                            0 => "ROUTE",
                            1 => "DAYS",
                            2 => "HD_DESC",
                            3 => "YOUTUBE",
                            4 => "VIMEO",
                            5 => "NAME_BY",
                            6 => "ROUTE_BY",
                            7 => "HD_DESC_BY",
                            8 => "NAME_EN",
                            9 => "ROUTE_EN",
                            10 => "HD_DESC_EN",
                            11 => "COUNTRY",
                            12 => "REGIONS",
                            13 => "TOWN",
                            14 => "FOOD",
                            15 => "TOURTYPE",
                            16 => "TRANSPORT",
                            17 => "HOTEL",
                            18 => "SERVICES",
                            19 => "ADDRESS",
                            20 => "MAP",
                            21 => "TYPE",
                            22 => "MAP_SCALE",
                            23 => "PICTURES",
                            24 => "",
                        ),
                        "DETAIL_URL" => "/tourism/cognitive-tourism/#ELEMENT_CODE#/?booking[id][]=#ELEMENT_ID#",
                        "SECTION_URL" => "/tourism/what-to-see/",
                        "IBLOCK_URL" => "/tourism/what-to-see/",
                        "SET_TITLE" => "N",
                        "SET_LAST_MODIFIED" => "N",
                        "MESSAGE_404" => "N",
                        "SET_STATUS_404" => "N",
                        "SHOW_404" => "N",
                        "FILE_404" => "",
                        "INCLUDE_IBLOCK_INTO_CHAIN" => "N",
                        "CACHE_FILTER" => "N",
                        "CACHE_GROUPS" => "Y",
                        "CACHE_TIME" => "36000000",
                        "CACHE_TYPE" => "A",
                        "DISPLAY_TOP_PAGER" => "N",
                        "DISPLAY_BOTTOM_PAGER" => "N",
                        "PAGER_TITLE" => "",
                        "PAGER_TEMPLATE" => "",
                        "PAGER_SHOW_ALWAYS" => "N",
                        "PAGER_DESC_NUMBERING" => "N",
                        "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
                        "PAGER_SHOW_ALL" => "N",
                        "PAGER_BASE_LINK_ENABLE" => "N",
                        "PAGER_PARAMS_NAME" => "",
                        "DISPLAY_DATE" => "N",
                        "DISPLAY_NAME" => "Y",
                        "DISPLAY_PICTURE" => "Y",
                        "DISPLAY_PREVIEW_TEXT" => "Y",
                        "PREVIEW_TRUNCATE_LEN" => "",
                        "ACTIVE_DATE_FORMAT" => "d.m.Y",
                        "USE_PERMISSIONS" => "N",
                        "FILTER_NAME" => "arrFilterExcursionShort",
                        "HIDE_LINK_WHEN_NO_DETAIL" => "N",
                        "CHECK_DATES" => "Y",
                        "COMPONENT_TEMPLATE" => "tours",
                        "AJAX_MODE" => "N",
                        "AJAX_OPTION_JUMP" => "N",
                        "AJAX_OPTION_STYLE" => "Y",
                        "AJAX_OPTION_HISTORY" => "N",
                        "AJAX_OPTION_ADDITIONAL" => "",
                        "SET_BROWSER_TITLE" => "Y",
                        "SET_META_KEYWORDS" => "Y",
                        "SET_META_DESCRIPTION" => "Y",
                        "ADD_SECTIONS_CHAIN" => "Y",
                        "PARENT_SECTION" => "",
                        "PARENT_SECTION_CODE" => "",
                        "INCLUDE_SUBSECTIONS" => "Y",
                        "MAKE_PRICING" => "Y",
                        "OBJECT_TYPE" => "excursions",
                        "STRICT_SECTION_CHECK" => "N",
                        'MORE_LINK' => 'tourism/cognitive-tourism/?set_filter=y&ruToursFilter_211=' . $keyCrc,
                        'ANCHOR' => 'iblock-excursions'
                    ),
                    false
                );?>
            <?endif;?>

            <?if($arParams["VIEW_DETAIL_TOUR_ON_ELEMENT"]=="Y" && $ElementID):?>
                <?
                $GLOBALS['arrFilterToursShort']["PROPERTY_SIGHTS"] = $ElementID;
                $GLOBALS['arrFilterToursShort']["!PROPERTY_IS_EXCURSION_TOUR"] = false;

                switch (SITE_ID){
                    case 's1':
                        $GLOBALS['arrFilterExcursionShort']['PROPERTY_SHOWONPAGE'] = 323;
                        break;
                    case 'en':
                        $GLOBALS['arrFilterExcursionShort']['PROPERTY_SHOWONPAGE'] = 325;
                        break;
                    case 'by':
                        $GLOBALS['arrFilterExcursionShort']['PROPERTY_SHOWONPAGE'] = 324;
                        break;
                }
                ?>
                <?$APPLICATION->IncludeComponent(
                    "bitrix:news.list",
                    "tours-short",
                    array(
                        "TITLE_LIST" => GetMessage("OTHER_MULTITOURS"),
                        "IBLOCK_TYPE" => "Tourproduct",
                        "IBLOCK_ID" => "33",
                        "NEWS_COUNT" => "3",
                        "SORT_BY1" => "RAND",
                        "SORT_ORDER1" => "DESC",
                        "SORT_BY2" => "SORT",
                        "SORT_ORDER2" => "ASC",
                        "FIELD_CODE" => array(
                            0 => "",
                            1 => "",
                        ),
                        "PROPERTY_CODE" => array(
                            0 => "ROUTE",
                            1 => "DAYS",
                            2 => "HD_DESC",
                            3 => "YOUTUBE",
                            4 => "VIMEO",
                            5 => "NAME_BY",
                            6 => "ROUTE_BY",
                            7 => "HD_DESC_BY",
                            8 => "NAME_EN",
                            9 => "ROUTE_EN",
                            10 => "HD_DESC_EN",
                            11 => "COUNTRY",
                            12 => "REGIONS",
                            13 => "TOWN",
                            14 => "FOOD",
                            15 => "TOURTYPE",
                            16 => "TRANSPORT",
                            17 => "HOTEL",
                            18 => "SERVICES",
                            19 => "ADDRESS",
                            20 => "MAP",
                            21 => "TYPE",
                            22 => "MAP_SCALE",
                            23 => "PICTURES",
                            24 => "",
                        ),
                        "DETAIL_URL" => "/tourism/cognitive-tourism/#ELEMENT_CODE#/?booking[id][]=#ELEMENT_ID#",
                        "SECTION_URL" => "/tourism/what-to-see/",
                        "IBLOCK_URL" => "/tourism/what-to-see/",
                        "SET_TITLE" => "N",
                        "SET_LAST_MODIFIED" => "N",
                        "MESSAGE_404" => "N",
                        "SET_STATUS_404" => "N",
                        "SHOW_404" => "N",
                        "FILE_404" => "",
                        "INCLUDE_IBLOCK_INTO_CHAIN" => "N",
                        "CACHE_FILTER" => "N",
                        "CACHE_GROUPS" => "Y",
                        "CACHE_TIME" => "36000000",
                        "CACHE_TYPE" => "A",
                        "DISPLAY_TOP_PAGER" => "N",
                        "DISPLAY_BOTTOM_PAGER" => "N",
                        "PAGER_TITLE" => "",
                        "PAGER_TEMPLATE" => "",
                        "PAGER_SHOW_ALWAYS" => "N",
                        "PAGER_DESC_NUMBERING" => "N",
                        "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
                        "PAGER_SHOW_ALL" => "N",
                        "PAGER_BASE_LINK_ENABLE" => "N",
                        "PAGER_PARAMS_NAME" => "",
                        "DISPLAY_DATE" => "N",
                        "DISPLAY_NAME" => "Y",
                        "DISPLAY_PICTURE" => "Y",
                        "DISPLAY_PREVIEW_TEXT" => "Y",
                        "PREVIEW_TRUNCATE_LEN" => "",
                        "ACTIVE_DATE_FORMAT" => "d.m.Y",
                        "USE_PERMISSIONS" => "N",
                        "FILTER_NAME" => "arrFilterToursShort",
                        "HIDE_LINK_WHEN_NO_DETAIL" => "N",
                        "CHECK_DATES" => "Y",
                        "COMPONENT_TEMPLATE" => "tours",
                        "AJAX_MODE" => "N",
                        "AJAX_OPTION_JUMP" => "N",
                        "AJAX_OPTION_STYLE" => "Y",
                        "AJAX_OPTION_HISTORY" => "N",
                        "AJAX_OPTION_ADDITIONAL" => "",
                        "SET_BROWSER_TITLE" => "Y",
                        "SET_META_KEYWORDS" => "Y",
                        "SET_META_DESCRIPTION" => "Y",
                        "ADD_SECTIONS_CHAIN" => "Y",
                        "PARENT_SECTION" => "",
                        "PARENT_SECTION_CODE" => "",
                        "INCLUDE_SUBSECTIONS" => "Y",
                        "MAKE_PRICING" => "Y",
                        "OBJECT_TYPE" => "excursions",
                        "STRICT_SECTION_CHECK" => "N",
                        'MORE_LINK' => 'tourism/tours-in-belarus/?set_filter=y&arrFilter_211=' . $keyCrc,
                        'ANCHOR' => 'iblock-tours'
                    ),
                    false
                );?>
            <?endif;?>

            <?php
            if($arParams["VIEW_DETAIL_HOTELS_ON_TOWN"]=="Y" && $ElementID){
                $res = CIBlockElement::GetProperty($arParams["IBLOCK_ID"], $ElementID, "sort", "asc", array("CODE" => "TOWN"));
                if ($ob = $res->GetNext()){
                    $GLOBALS['arrFilterHotelsTown']["PROPERTY_TOWN"] = $ob['VALUE'];
                    $locationCrc = abs(crc32($ob['VALUE']));

                    $APPLICATION->IncludeComponent(
                        "bitrix:news.list",
                        "hotels-short",
                        array(
                            "TITLE_LIST" => GetMessage("OTHER_HOTELS"),
                            "IBLOCK_TYPE" => "Dictionaries",
                            "IBLOCK_ID" => "7",
                            "NEWS_COUNT" => "3",
                            "SORT_BY1" => "RAND",
                            "SORT_ORDER1" => "",
                            "SORT_BY2" => "ACTIVE_FROM",
                            "SORT_ORDER2" => "ASC",
                            "FIELD_CODE" => array(
                                0 => "",
                                1 => "",
                            ),
                            "PROPERTY_CODE" => array(
                                0 => "ADDRESS",
                                1 => "MAP",
                                2 => "YOUTUBE",
                                3 => "VIMEO",
                                4 => "HD_DESC",
                                5 => "NAME_BY",
                                6 => "HD_DESC_BY",
                                7 => "COUNTRY",
                                8 => "REGION",
                                9 => "TOWN",
                                10 => "TYPE",
                                11 => "MAP_SCALE",
                                12 => "DETAIL_TEXT",
                                13 => "PREVIEW_TEXT",
                                14 => "REGIONS",
                                15 => "PICTURES",
                                16 => "",
                            ),
                            "DETAIL_URL" => "/tourism/where-to-stay/#ELEMENT_CODE#/?booking[id][]=#ELEMENT_ID#",
                            "SECTION_URL" => "/tourism/where-to-stay/",
                            "IBLOCK_URL" => "/tourism/where-to-stay/",
                            "SET_TITLE" => "N",
                            "SET_LAST_MODIFIED" => "N",
                            "MESSAGE_404" => "N",
                            "SET_STATUS_404" => "N",
                            "SHOW_404" => "N",
                            "FILE_404" => "",
                            "INCLUDE_IBLOCK_INTO_CHAIN" => "N",
                            "CACHE_FILTER" => "N",
                            "CACHE_GROUPS" => "Y",
                            "CACHE_TIME" => "36000000",
                            "CACHE_TYPE" => "A",
                            "DISPLAY_TOP_PAGER" => "N",
                            "DISPLAY_BOTTOM_PAGER" => "N",
                            "PAGER_TITLE" => "",
                            "PAGER_TEMPLATE" => "",
                            "PAGER_SHOW_ALWAYS" => "N",
                            "PAGER_DESC_NUMBERING" => "N",
                            "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
                            "PAGER_SHOW_ALL" => "N",
                            "PAGER_BASE_LINK_ENABLE" => "N",
                            "PAGER_PARAMS_NAME" => "",
                            "DISPLAY_DATE" => "N",
                            "DISPLAY_NAME" => "Y",
                            "DISPLAY_PICTURE" => "Y",
                            "DISPLAY_PREVIEW_TEXT" => "Y",
                            "PREVIEW_TRUNCATE_LEN" => "",
                            "ACTIVE_DATE_FORMAT" => "d.m.Y",
                            "USE_PERMISSIONS" => "N",
                            "FILTER_NAME" => "arrFilterHotelsTown",
                            "HIDE_LINK_WHEN_NO_DETAIL" => "N",
                            "CHECK_DATES" => "Y",
                            "COMPONENT_TEMPLATE" => "hotels",
                            "AJAX_MODE" => "N",
                            "AJAX_OPTION_JUMP" => "N",
                            "AJAX_OPTION_STYLE" => "Y",
                            "AJAX_OPTION_HISTORY" => "N",
                            "AJAX_OPTION_ADDITIONAL" => "",
                            "SET_BROWSER_TITLE" => "Y",
                            "SET_META_KEYWORDS" => "Y",
                            "SET_META_DESCRIPTION" => "Y",
                            "ADD_SECTIONS_CHAIN" => "Y",
                            "PARENT_SECTION" => "",
                            "PARENT_SECTION_CODE" => "",
                            "INCLUDE_SUBSECTIONS" => "Y",
                            "STRICT_SECTION_CHECK" => "N",
                            'MORE_LINK' => 'tourism/where-to-stay/?set_filter=y&ruAccomFilter_45=' . $locationCrc,
                            'ANCHOR' => 'iblock-hotels'
                        ),
                        false
                    );
                }
            }
            ?>

        </div>
    </div>
</div>

<p>
    <a href="<?=$arResult["FOLDER"].$arResult["URL_TEMPLATES"]["news"]?>" class="awe-btn awe-btn-5 arrow-right awe-btn-lager text-uppercase float-right mt-20 mr-20"><?=GetMessage("T_NEWS_DETAIL_BACK")?></a>
</p>