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

if ($arParams["MAKE_PRICING"]) {
    /**
     * результаты поиска цен по услугам
     */
    
    ob_start();
    $APPLICATION->IncludeComponent(
        "travelsoft:travelsoft.service.price.result",
        "on.detail.page.render",
        Array(
            "COMPONENT_TEMPLATE" => "on.detail.page.render",
            "CODE" => array($arResult["VARIABLES"]["ELEMENT_CODE"]),
            "FILTER_BY_PRICES_FOR_CITIZEN" => $arParams["FILTER_BY_PRICES_FOR_CITIZEN"] == "Y" ? "Y" : "N",
            "INC_JQUERY" => "N",
            "INC_MAGNIFIC_POPUP" => "Y",
            "INC_OWL_CAROUSEL" => "N",
            "TYPE" => $arParams["TYPE"],
            "MAKE_ORDER_PAGE" => "/booking/",
            "POSTFIX_PROPERTY" => POSTFIX_PROPERTY,
            "__BOOKING_REQUEST" => $_REQUEST["booking"],
            "FOLDER_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["news"]
        )
    );
    $GLOBALS["PRICE_CALCULATION_RESULT_HTML"] = ob_get_clean();

}

?>
<?$APPLICATION->ShowViewContent('head-detail-tours');?>
<div class="detail-cn myclass">
    <div class="row check-rates">
        <div class="col-sm-3 hidden-sm hidden-xs col-lg-3 detail-sidebar">
            <div class="scrolly scrollspy-sidebar sidebar-detail scroll-heading" role="complementary" data-offset="20">
                <ul class="nav">
                    <?$APPLICATION->ShowViewContent('menu-item-detail-tours');?>
                    <?$APPLICATION->ShowViewContent('menu-item-review');?>
                    <?$APPLICATION->ShowViewContent('menu-item-sights-tours');?>
                    <?$APPLICATION->ShowViewContent('menu-item-hotel-tours');?>
                </ul>
                <div style="width: 100%; height: 20px;"></div>
            </div>
        </div>
        <div class="col-lg-9 check-rates-cn section-list">
<?
$ElementID = $APPLICATION->IncludeComponent(
    "bitrix:news.detail",
    "",
    Array(
        "DISPLAY_DATE" => $arParams["DISPLAY_DATE"],
        "DISPLAY_NAME" => $arParams["DISPLAY_NAME"],
        "DISPLAY_PICTURE" => $arParams["DISPLAY_PICTURE"],
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
        "CACHE_TYPE" => $arParams["CACHE_TYPE"],
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
        "ADD_ELEMENT_CHAIN" => (isset($arParams["ADD_ELEMENT_CHAIN"]) ? $arParams["ADD_ELEMENT_CHAIN"] : ''),
        "PRICE_CALCULATION_HESH" => md5($GLOBALS["PRICE_CALCULATION_RESULT_HTML"])
    ),
    $component
);?>
<?
# записываем посещение страницы
$APPLICATION->IncludeComponent(
	"travelsoft:history.page.counter",
	"",
	Array(
		"ID" => $ElementID,
		"USER_GROUPS" => array("1","8")
	)
);?>
<!-- Hotel Detail Reviews -->

<section class="review-detail detail-cn" id="iblock_detail_reviews">
    <a name="iblock_detail_reviews"></a>
    <?$GLOBALS['arrFilterReviews']["PROPERTY_ITEM"] = $ElementID;?>
    <?$APPLICATION->IncludeComponent(
        "bitrix:news.list",
        "reviews",
        Array(
            "IBLOCK_TYPE" => "Reviews",
            "IBLOCK_ID" => 36,
            "NEWS_COUNT" => 50,
            "SORT_BY1" => "ACTIVE_FROM",
            "SORT_ORDER1" => "DESC",
            "SORT_BY2" => "SORT",
            "SORT_ORDER2" => "ASC",
            "FIELD_CODE" => Array(
                0 => "DATE_CREATE",
				1 => "ACTIVE"
            ),
            "PROPERTY_CODE" => array(
                0 => "USER_NAME",
                1 => "USER_RATING",
                2 => "TRAVEL_DATE",
                3 => "PLUS",
                4 => "MINUS",
                5 => "COUNTRY",
                6 => "CITY",
                7 => "PRICE_QUALITY",
                8 => "LOCATION",
                9 => "STAFF",
                10 => "PURITY",
                11 => "ROOMS",
                12 => "FOOD",
                13 => "RECOMMEND",
                14 => "",
            ),
            "DETAIL_URL" => "",
            "SECTION_URL" => "",
            "IBLOCK_URL" => "",
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
            "DISPLAY_DATE" => "Y",
            "DISPLAY_NAME" => "Y",
            "DISPLAY_PICTURE" => "N",
            "DISPLAY_PREVIEW_TEXT" => "Y",
            "PREVIEW_TRUNCATE_LEN" => "",
            "ACTIVE_DATE_FORMAT" => "d.m.Y",
            "USE_PERMISSIONS" => "N",
            "FILTER_NAME" => "arrFilterReviews",
            "HIDE_LINK_WHEN_NO_DETAIL" => "N",
            "CHECK_DATES" => "Y",
        ),
        false
    );?>
</section>
<!-- End Hotel Detail Reviews -->

<section class="detail-footer detail-cn">
    <div class="text-center">
        <div class="review-more">
            <?if ($USER->IsAuthorized()):?>
                <?$APPLICATION->IncludeComponent(
                    "travelsoft:iblock.element.reviews.add.form",
                    "",
                    Array(
                        "CUSTOM_TITLE_DATE_ACTIVE_FROM" => "",
                        "CUSTOM_TITLE_DATE_ACTIVE_TO" => "",
                        "CUSTOM_TITLE_DETAIL_PICTURE" => "",
                        "CUSTOM_TITLE_DETAIL_TEXT" => "",
                        "CUSTOM_TITLE_IBLOCK_SECTION" => "",
                        "CUSTOM_TITLE_NAME" => "",
                        "CUSTOM_TITLE_PREVIEW_PICTURE" => "",
                        "CUSTOM_TITLE_PREVIEW_TEXT" => "Комментарий",
                        "CUSTOM_TITLE_TAGS" => "",
                        "DEFAULT_INPUT_SIZE" => "30",
                        "DETAIL_TEXT_USE_HTML_EDITOR" => "N",
                        "ELEMENT_ASSOC" => "PROPERTY_ID",
                        "ELEMENT_ASSOC_PROPERTY" => "606",
                        "GROUPS" => array("10"),
                        "IBLOCK_ID" => "36",
                        "IBLOCK_TYPE" => "Reviews",
                        "LEVEL_LAST" => "Y",
                        "LIST_URL" => "",
                        "MAX_FILE_SIZE" => "0",
                        "MAX_LEVELS" => "100000",
                        "MAX_USER_ENTRIES" => "100000",
                        "PREVIEW_TEXT_USE_HTML_EDITOR" => "N",
                        "PROPERTY_CODES" => array("242", "243", "244", "245", "246", "247", "249", "250", "251", "252", "253", "254", "255", "256", "257", "258", "497", "606", "618", "PREVIEW_TEXT","NAME"),
                        "PROPERTY_CODES_REQUIRED" => array("242", "244", "245", "258", "606", "PREVIEW_TEXT"),
                        "RESIZE_IMAGES" => "N",
                        "SEF_MODE" => "N",
                        "STATUS" => "ANY",
                        "STATUS_NEW" => "NEW",
                        "USER_MESSAGE_ADD" => GetMessage('ADD_REVIEWS_OK_TEXT'),
                        "USER_MESSAGE_EDIT" => "",
                        "USE_CAPTCHA" => "N",
                        "ELEMENT_ID" => $ElementID,
                        "EVENT_MESSAGE_ID" => $arParams["EVENT_MESSAGE_ID"]
                    )
                );?>
            <?else:?>
                <a href="#header-auth-popup" class="show-header-auth-popup add-to-cart awe-btn awe-btn-1 awe-btn-small"><?=GetMessage('ADD_REVIEWS_SUBMIT_TEXT')?></a>
            <?endif?>
        </div>
    </div>
</section>

<?$Values[$ElementID] = array();
$res = CIBlockElement::GetList(Array("SORT"=>"ASC"), Array("IBLOCK_ID"=>$arParams["IBLOCK_ID"], "ID"=>$ElementID, "ACTIVE"=>"Y"), false, false, Array("IBLOCK_ID", "ID"));
while($ar_fields = $res->GetNextElement())
{
    $arFields = $ar_fields->GetFields();
    $arProps = $ar_fields->GetProperties();
    $Values[$ElementID]["SIGHTS"] = $arProps["SIGHTS"]["VALUE"];
    $Values[$ElementID]["HOTEL"] = $arProps["HOTEL"]["VALUE"];
}
?>

<?if(!empty($Values[$ElementID]["SIGHTS"])):?>
<section class="details-policies detail-cn" id="iblock_detail_sights">
    <a name="iblock_detail_sights"></a>
    <div class="details-policies-cn">
        <!-- Details Policies Item -->
        <? $GLOBALS['arrFilterSights']["ID"] = $Values[$ElementID]["SIGHTS"] ?>
        <?
        $APPLICATION->IncludeComponent(
            "bitrix:news.list",
            "sights",
            array(
                "TITLE_LIST" => GetMessage("TITLE_SIGHTS"),
                "IBLOCK_TYPE" => "Dictionaries",
                "IBLOCK_ID" => 6,
                "NEWS_COUNT" => "50",
                "SORT_BY1" => "ACTIVE_FROM",
                "SORT_ORDER1" => "DESC",
                "SORT_BY2" => "SORT",
                "SORT_ORDER2" => "ASC",
                "FIELD_CODE" => array(
                    0 => "",
                    1 => "",
                ),
                "PROPERTY_CODE" => array(
                    0 => "",
                    1 => "COUNTRY",
                    2 => "REGION",
                    3 => "TOWN",
                    4 => "ADDRESS",
                    5 => "TYPE",
                    6 => "MAP",
                    7 => "MAP_SCALE",
                    8 => "DETAIL_TEXT",
                    9 => "YOUTUBE",
                    10 => "VIMEO",
                    11 => "PREVIEW_TEXT",
                    12 => "REGIONS",
                    13 => "HD_DESC",
                    14 => "PICTURES",
                    15 => "NAME_BY",
                    16 => "HD_DESC_BY",
                    17 => "DETAIL_TEXT_EN",
                    18 => "PREVIEW_TEXT_EN",
                    19 => "DETAIL_TEXT_BY",
                    20 => "PREVIEW_TEXT_BY",
                    21 => "NAME_BY",
                ),
                "DETAIL_URL" => "/tourism/what-to-see/#ELEMENT_CODE#/",
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
                "FILTER_NAME" => "arrFilterSights",
                "HIDE_LINK_WHEN_NO_DETAIL" => "N",
                "CHECK_DATES" => "Y",
                "COMPONENT_TEMPLATE" => "sights",
                "AJAX_MODE" => "N",
                "AJAX_OPTION_JUMP" => "N",
                "AJAX_OPTION_STYLE" => "Y",
                "AJAX_OPTION_HISTORY" => "N",
                "AJAX_OPTION_ADDITIONAL" => "undefined",
                "SET_BROWSER_TITLE" => "Y",
                "SET_META_KEYWORDS" => "Y",
                "SET_META_DESCRIPTION" => "Y",
                "ADD_SECTIONS_CHAIN" => "Y",
                "PARENT_SECTION" => "",
                "PARENT_SECTION_CODE" => "",
                "INCLUDE_SUBSECTIONS" => "Y"
            ),
            false
        );
        ?>
    </div>
</section>
<?endif?>
<?if(!empty($Values[$ElementID]["HOTEL"])):?>
    <section class="details-policies detail-cn" id="iblock_detail_hotel">
        <a name="iblock_detail_hotel"></a>
        <div class="details-policies-cn">
            <? $GLOBALS['arrFilterHotel']["ID"] = $Values[$ElementID]["HOTEL"]; ?>
            <?
            $APPLICATION->IncludeComponent(
                "bitrix:news.list", "hotels", Array(
                "TITLE_LIST" => GetMessage("TITLE_HOTEL"),
                "IBLOCK_TYPE" => "Dictionaries",
                "IBLOCK_ID" => 7,
                "NEWS_COUNT" => 50,
                "SORT_BY1" => "ACTIVE_FROM",
                "SORT_ORDER1" => "DESC",
                "SORT_BY2" => "SORT",
                "SORT_ORDER2" => "ASC",
                "FIELD_CODE" => Array(),
                "PROPERTY_CODE" => array(
                    0 => "COUNTRY",
                    1 => "REGIONS",
                    2 => "TOWN",
                    3 => "ADDRESS",
                    4 => "MAP",
                    5 => "YOUTUBE",
                    6 => "VIMEO",
                    7 => "SERVICES",
                    8 => "HD_DESC",
                    9 => "SEARCH",
                    10 => "TYPE",
                    11 => "MAP_SCALE",
                    12 => "PICTURES",
                    13 => "",
                ),
                "DETAIL_URL" => "/tourism/where-to-stay/#ELEMENT_CODE#/",
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
                "FILTER_NAME" => "arrFilterHotel",
                "HIDE_LINK_WHEN_NO_DETAIL" => "N",
                "CHECK_DATES" => "Y",
            ), false
            );
            ?>
        </div>
    </section>
<?endif?>

        </div>
    </div>
</div>

<p><a href="<?=$arResult["FOLDER"].$arResult["URL_TEMPLATES"]["news"]?>" class="awe-btn awe-btn-5 arrow-right awe-btn-lager text-uppercase float-right mt-20 mr-20"><?=GetMessage("T_NEWS_DETAIL_BACK")?></a></p>
<?if($arParams["USE_RATING"]=="Y" && $ElementID):?>
    <?$APPLICATION->IncludeComponent(
        "bitrix:iblock.vote",
        "",
        Array(
            "IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
            "IBLOCK_ID" => $arParams["IBLOCK_ID"],
            "ELEMENT_ID" => $ElementID,
            "MAX_VOTE" => $arParams["MAX_VOTE"],
            "VOTE_NAMES" => $arParams["VOTE_NAMES"],
            "CACHE_TYPE" => $arParams["CACHE_TYPE"],
            "CACHE_TIME" => $arParams["CACHE_TIME"],
        ),
        $component
    );?>
<?endif?>
<?if($arParams["USE_CATEGORIES"]=="Y" && $ElementID):
    global $arCategoryFilter;
    $obCache = new CPHPCache;
    $strCacheID = $componentPath.LANG.$arParams["IBLOCK_ID"].$ElementID.$arParams["CATEGORY_CODE"];
    if(($tzOffset = CTimeZone::GetOffset()) <> 0)
        $strCacheID .= "_".$tzOffset;
    if($arParams["CACHE_TYPE"] == "N" || $arParams["CACHE_TYPE"] == "A" && COption::GetOptionString("main", "component_cache_on", "Y") == "N")
        $CACHE_TIME = 0;
    else
        $CACHE_TIME = $arParams["CACHE_TIME"];
    if($obCache->StartDataCache($CACHE_TIME, $strCacheID, $componentPath))
    {
        $rsProperties = CIBlockElement::GetProperty($arParams["IBLOCK_ID"], $ElementID, "sort", "asc", array("ACTIVE"=>"Y","CODE"=>$arParams["CATEGORY_CODE"]));
        $arCategoryFilter = array();
        while($arProperty = $rsProperties->Fetch())
        {
            if(is_array($arProperty["VALUE"]) && count($arProperty["VALUE"])>0)
            {
                foreach($arProperty["VALUE"] as $value)
                    $arCategoryFilter[$value]=true;
            }
            elseif(!is_array($arProperty["VALUE"]) && strlen($arProperty["VALUE"])>0)
                $arCategoryFilter[$arProperty["VALUE"]]=true;
        }
        $obCache->EndDataCache($arCategoryFilter);
    }
    else
    {
        $arCategoryFilter = $obCache->GetVars();
    }
    if(count($arCategoryFilter)>0):
        $arCategoryFilter = array(
            "PROPERTY_".$arParams["CATEGORY_CODE"] => array_keys($arCategoryFilter),
            "!"."ID" => $ElementID,
        );
        ?>
        <hr /><h3><?=GetMessage("CATEGORIES")?></h3>
        <?foreach($arParams["CATEGORY_IBLOCK"] as $iblock_id):?>
        <?$APPLICATION->IncludeComponent(
            "bitrix:news.list",
            $arParams["CATEGORY_THEME_".$iblock_id],
            Array(
                "IBLOCK_ID" => $iblock_id,
                "NEWS_COUNT" => $arParams["CATEGORY_ITEMS_COUNT"],
                "SET_TITLE" => "N",
                "INCLUDE_IBLOCK_INTO_CHAIN" => "N",
                "CACHE_TYPE" => $arParams["CACHE_TYPE"],
                "CACHE_TIME" => $arParams["CACHE_TIME"],
                "CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
                "FILTER_NAME" => "arCategoryFilter",
                "CACHE_FILTER" => "Y",
                "DISPLAY_TOP_PAGER" => "N",
                "DISPLAY_BOTTOM_PAGER" => "N",
            ),
            $component
        );?>
    <?endforeach?>
    <?endif?>
<?endif?>
<?if($arParams["USE_REVIEW"]=="Y" && IsModuleInstalled("forum") && $ElementID):?>
    <hr />
    <?$APPLICATION->IncludeComponent(
        "bitrix:forum.topic.reviews",
        "",
        Array(
            "CACHE_TYPE" => $arParams["CACHE_TYPE"],
            "CACHE_TIME" => $arParams["CACHE_TIME"],
            "MESSAGES_PER_PAGE" => $arParams["MESSAGES_PER_PAGE"],
            "USE_CAPTCHA" => $arParams["USE_CAPTCHA"],
            "PATH_TO_SMILE" => $arParams["PATH_TO_SMILE"],
            "FORUM_ID" => $arParams["FORUM_ID"],
            "URL_TEMPLATES_READ" => $arParams["URL_TEMPLATES_READ"],
            "SHOW_LINK_TO_FORUM" => $arParams["SHOW_LINK_TO_FORUM"],
            "DATE_TIME_FORMAT" => $arParams["DETAIL_ACTIVE_DATE_FORMAT"],
            "ELEMENT_ID" => $ElementID,
            "AJAX_POST" => $arParams["REVIEW_AJAX_POST"],
            "IBLOCK_ID" => $arParams["IBLOCK_ID"],
            "URL_TEMPLATES_DETAIL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["detail"],
        ),
        $component
    );?>
<?endif?>
