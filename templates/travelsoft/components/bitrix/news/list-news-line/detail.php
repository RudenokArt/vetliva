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

<?if(strlen($arParams["TEMPLATE_DETAIL"]) && $arParams["TEMPLATE_DETAIL"] == "event"):?>

    <?$APPLICATION->ShowViewContent('head-detail');?>
    <div class="detail-cn myclass">
    <div class="row check-rates">

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
                "ADD_ELEMENT_CHAIN" => (isset($arParams["ADD_ELEMENT_CHAIN"]) ? $arParams["ADD_ELEMENT_CHAIN"] : '')
            ),
            $component
        );?>
        <?
        # ???????????????????? ?????????????????? ????????????????
        $APPLICATION->IncludeComponent(
            "travelsoft:history.page.counter",
            "",
            Array(
                "ID" => $ElementID,
                "USER_GROUPS" => array("1","8")
            )
        );?>

    </div>
    </div>

<?else:?>

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
      	"ADD_ELEMENT_CHAIN" => (isset($arParams["ADD_ELEMENT_CHAIN"]) ? $arParams["ADD_ELEMENT_CHAIN"] : '')
      ),
      $component
      );?>
<?endif?>
<?if($USER->GetID() == 43):?>
    <?if($arParams["SHOW_REVIEWS_BLOCK"] == "Y"):?>
		<?if(showReviews($ElementID)):?>
                    <section class="review-detail detail-cn">
                            <?$GLOBALS['arrFilterReviews']["PROPERTY_ITEM"] = $arResult["ID"];?>
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
                                        0 => "DATE_CREATE"
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
		<?endif?>
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
                                "CUSTOM_TITLE_PREVIEW_TEXT" => "??????????????????????",
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
                            <p><?=GetMessage('ADD_REVIEWS_TEXT')?></p>
                        <?endif?>
                    </div>
                </div>
            </section>
        <?endif?>
<?endif?>

<?/*if($arParams["IBLOCK_ID"] == BLOG_IBLOCK_ID || $arParams["IBLOCK_ID"] == 36):?>
    <?if(showComments($ElementID)):?>
        <section class="review-detail detail-cn">
            <?$GLOBALS['arrFilterReviews']["PROPERTY_ITEM"] = $ElementID;?>
            <?$APPLICATION->IncludeComponent(
	"bitrix:news.list", 
	"comments", 
	array(
		"IBLOCK_TYPE" => "Reviews",
		"IBLOCK_ID" => "63",
		"NEWS_COUNT" => "50",
		"SORT_BY1" => "ACTIVE_FROM",
		"SORT_ORDER1" => "ASC",
		"SORT_BY2" => "SORT",
		"SORT_ORDER2" => "ASC",
		"FIELD_CODE" => array(
			0 => "PREVIEW_TEXT",
			1 => "DATE_CREATE",
			2 => "ACTIVE",
			3 => "",
		),
		"PROPERTY_CODE" => array(
			0 => "USER_NAME",
			1 => "USER",
			2 => "ITEM",
			3 => "",
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
		"COMPONENT_TEMPLATE" => "comments",
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
		"DISPLAY_RATING_BLOCK" => "Y"
	),
	false
);?>
        </section>
    <?endif;?>
    <section class="detail-footer detail-cn">
        <div class="text-center">
            <div class="review-more">
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
                        "CUSTOM_TITLE_PREVIEW_TEXT" => "??????????????????????",
                        "CUSTOM_TITLE_TAGS" => "",
                        "DEFAULT_INPUT_SIZE" => "30",
                        "DETAIL_TEXT_USE_HTML_EDITOR" => "N",
                        "ELEMENT_ASSOC" => "PROPERTY_ID",
                        "ELEMENT_ASSOC_PROPERTY" => "",
                        "GROUPS" => array("2"),
                        "IBLOCK_ID" => "63",
                        "IBLOCK_TYPE" => "Reviews",
                        "LEVEL_LAST" => "Y",
                        "LIST_URL" => "",
                        "MAX_FILE_SIZE" => "0",
                        "MAX_LEVELS" => "100000",
                        "MAX_USER_ENTRIES" => "100000",
                        "PREVIEW_TEXT_USE_HTML_EDITOR" => "N",
                        "PROPERTY_CODES" => array("893", "894", "895", "PREVIEW_TEXT","NAME"),
                        "PROPERTY_CODES_REQUIRED" => array("893", "894", "PREVIEW_TEXT"),
                        "RESIZE_IMAGES" => "N",
                        "SEF_MODE" => "N",
                        "STATUS" => "ANY",
                        "STATUS_NEW" => "NEW",
                        "USER_MESSAGE_ADD" => GetMessage('ADD_COMMENT_OK_TEXT'),
                        "SUBMIT_TEXT" => "????????????????",
                        "TITLE_FORM_TEXT" => GetMessage('ADD_COMMENT_TITLE_TEXT'),
                        "USER_MESSAGE_EDIT" => "",
                        "USE_CAPTCHA" => "Y",
                        "ELEMENT_ID" => $ElementID,
                        "EVENT_MESSAGE_ID" => array(90)
                    )
                );?>
            </div>
        </div>
    </section>
<?endif*/?>

   <p><a href="<?=$arResult["FOLDER"].$arResult["URL_TEMPLATES"]["news"]?>" class="awe-btn awe-btn-5 arrow-right awe-btn-lager text-uppercase float-right mt-20"><?=GetMessage("T_NEWS_DETAIL_BACK")?></a></p>
<?
   $res = CIBlockElement::GetProperty($arParams["IBLOCK_ID"], $ElementID, "sort", "asc", array("CODE" => "TOWN"));
       if ($ob = $res->GetNext())
       {
           $VALUE = $ob['VALUE'];
       }
   ?>
<?
   $res = CIBlockElement::GetProperty($arParams["IBLOCK_ID"], $ElementID, "sort", "asc", array("CODE" => "SANATORIUM"));
		$VALUE_SANATORIUM = array();
		while($ob = $res -> GetNext())
		{
			$VALUE_SANATORIUM[] = $ob['VALUE'];
		}
		if(empty($VALUE_SANATORIUM[0]))
		{
			unset($VALUE_SANATORIUM);
		}
/*if ($ob = $res->GetNext())
       {
           $VALUE_SANATORIUM = $ob['VALUE'];
       }
*/
   ?>
<?
   $res = CIBlockElement::GetProperty($arParams["IBLOCK_ID"], $ElementID, "sort", "asc", array("CODE" => "ACCOMODATION"));
	$VALUE_ACCOMODATION = array();
    while ($ob = $res->GetNext())
    {
        $VALUE_ACCOMODATION[] = $ob['VALUE'];
    }
	if(empty($VALUE_ACCOMODATION[0]))
	{
		unset($VALUE_ACCOMODATION);
	}
   ?>
<?
   $res = CIBlockElement::GetProperty($arParams["IBLOCK_ID"], $ElementID, "sort", "asc", array("CODE" => "TOUR"));
	$VALUE_TOUR = array();
	while ($ob = $res->GetNext())
    {
        $VALUE_TOUR[] = $ob['VALUE'];
    }
	if(empty($VALUE_TOUR[0]))
	{
		unset($VALUE_TOUR);
	}
   ?>
<?
   $res = CIBlockElement::GetProperty($arParams["IBLOCK_ID"], $ElementID, "sort", "asc", array("CODE" => "MULTITOUR"));
	$VALUE_MULTITOUR = array();
       while($ob = $res->GetNext())
       {
           $VALUE_MULTITOUR[] = $ob['VALUE'];
       }
		if(empty($VALUE_MULTITOUR[0]))
		{
			unset($VALUE_MULTITOUR);
		}
   ?>
<?
   $res = CIBlockElement::GetProperty($arParams["IBLOCK_ID"], $ElementID, "sort", "asc", array("CODE" => "ATTRACTION"));
	$VALUE_ATTRACTION = array();
       while($ob = $res->GetNext())
       {
           $VALUE_ATTRACTION[] = $ob['VALUE'];
       }
		if(empty($VALUE_ATTRACTION[0]))
		{
			unset($VALUE_ATTRACTION);
		}
   ?>
<div style="clear: both;"></div>

<?if($arParams["VIEW_DETAIL_TOUR_ON_ELEMENT"]=="Y" && $ElementID):?>
      <?$GLOBALS['arrFilterTourEl']["PROPERTY_SIGHTS"] = $ElementID;?>
      <?$APPLICATION->IncludeComponent(
	"bitrix:news.list", 
	"tours", 
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
		"FILTER_NAME" => "arrFilterTourEl",
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
		"STRICT_SECTION_CHECK" => "N"
	),
	false
);?>
<?endif;?>
<?
		if($arParams["VIEW_DETAIL_ATTR"]=="Y" && $VALUE_ATTRACTION)
		{
			$GLOBALS['arrFilterSights']["ID"] = $VALUE_ATTRACTION;
			$APPLICATION->IncludeComponent(
				"bitrix:news.list", 
				"sights", 
				array(
					"TITLE_LIST" => GetMessage("OTHER_ATTRACTIONS"),
					"IBLOCK_TYPE" => "Dictionaries",
					"IBLOCK_ID" => "6",
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
						0 => "ADDRESS",
						1 => "MAP",
						2 => "MAP_SCALE",
						3 => "PREVIEW_TEXT",
						4 => "DETAIL_TEXT",
						5 => "YOUTUBE",
						6 => "VIMEO",
						7 => "NAME_EN",
						8 => "ADDRESS_EN",
						9 => "PREVIEW_TEXT_EN",
						10 => "DETAIL_TEXT_EN",
						11 => "NAME_BY",
						12 => "ADDRESS_BY",
						13 => "PREVIEW_TEXT_BY",
						14 => "DETAIL_TEXT_BY",
						15 => "COUNTRY",
						16 => "REGION",
						17 => "TOWN",
						18 => "TYPE",
						19 => "REGIONS",
						20 => "HD_DESC",
						21 => "PICTURES",
						22 => "",
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
					"AJAX_OPTION_ADDITIONAL" => "",
					"SET_BROWSER_TITLE" => "Y",
					"SET_META_KEYWORDS" => "Y",
					"SET_META_DESCRIPTION" => "Y",
					"ADD_SECTIONS_CHAIN" => "Y",
					"PARENT_SECTION" => "",
					"PARENT_SECTION_CODE" => "",
					"INCLUDE_SUBSECTIONS" => "Y",
					"STRICT_SECTION_CHECK" => "N"
				),
				false
			);
		}
?>
<?if($arParams["VIEW_DETAIL_TOUR"]=="Y" && $VALUE_TOUR): ?>
      <?$GLOBALS['arrFilterTour']["ID"] = $VALUE_TOUR;
		$GLOBALS['arrFilterTour']['PROPERTY_IS_EXCURSION_TOUR'] = false;?>
      <?$APPLICATION->IncludeComponent(
	"bitrix:news.list", 
	"tours", 
	array(
		"TITLE_LIST" => GetMessage("OTHER_TOURS"),
		"IBLOCK_TYPE" => "Tourproduct",
		"IBLOCK_ID" => "33",
		"NEWS_COUNT" => "3",
		"SORT_BY1" => "ACTIVE_FROM",
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
			7 => "NAME_EN",
			8 => "ROUTE_EN",
			9 => "HD_DESC_EN",
			10 => "COUNTRY",
			11 => "REGIONS",
			12 => "TOWN",
			13 => "FOOD",
			14 => "TOURTYPE",
			15 => "TRANSPORT",
			16 => "HOTEL",
			17 => "SERVICES",
			18 => "ADDRESS",
			19 => "MAP",
			20 => "TYPE",
			21 => "MAP_SCALE",
			22 => "PICTURES",
			23 => "",
		),
		"DETAIL_URL" => "/tourism/cognitive-tourism/#ELEMENT_CODE#/?booking[id][]=#ELEMENT_ID#",
		"SECTION_URL" => "/tourism/cognitive-tourism/",
		"IBLOCK_URL" => "/tourism/cognitive-tourism/",
		"SET_TITLE" => "N",
		"SET_LAST_MODIFIED" => "N",
		"MESSAGE_404" => "N",
		"SET_STATUS_404" => "N",
		"SHOW_404" => "N",
		"FILE_404" => "",
		"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
		"CACHE_FILTER" => "N",
		"CACHE_GROUPS" => "Y",
		"CACHE_TIME" => "0",
		"CACHE_TYPE" => "N",
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
		"FILTER_NAME" => "arrFilterTour",
		"HIDE_LINK_WHEN_NO_DETAIL" => "N",
		"CHECK_DATES" => "Y",
		"COMPONENT_TEMPLATE" => "tours",
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
		"INCLUDE_SUBSECTIONS" => "Y",
		"MAKE_PRICING" => "Y",
		"OBJECT_TYPE" => "excursions"
	),
	false
);?>
<?endif;?>
<?if($arParams["VIEW_DETAIL_MULTITOUR"]=="Y" && $VALUE_MULTITOUR): ?>
      <?
//var_dump($VALUE_MULTITOUR);
		$GLOBALS['arrFilterMultitour']["ID"] = $VALUE_MULTITOUR;
		$GLOBALS['arrFilterMultitour']['!PROPERTY_IS_EXCURSION_TOUR'] = false;?>
      <?$APPLICATION->IncludeComponent(
	"bitrix:news.list", 
	"tours", 
	array(
		"TITLE_LIST" => GetMessage("OTHER_MULTITOURS"),
		"IBLOCK_TYPE" => "Tourproduct",
		"IBLOCK_ID" => "33",
		"NEWS_COUNT" => "3",
		"SORT_BY1" => "ACTIVE_FROM",
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
			7 => "NAME_EN",
			8 => "ROUTE_EN",
			9 => "HD_DESC_EN",
			10 => "COUNTRY",
			11 => "REGIONS",
			12 => "TOWN",
			13 => "FOOD",
			14 => "TOURTYPE",
			15 => "TRANSPORT",
			16 => "HOTEL",
			17 => "SERVICES",
			18 => "ADDRESS",
			19 => "MAP",
			20 => "TYPE",
			21 => "MAP_SCALE",
			22 => "PICTURES",
			23 => "",
		),
		"DETAIL_URL" => "/tourism/tours-in-belarus/#ELEMENT_CODE#/?booking[id][]=#ELEMENT_ID#",
		"SECTION_URL" => "/tourism/tours-in-belarus/",
		"IBLOCK_URL" => "/tourism/tours-in-belarus/",
		"SET_TITLE" => "N",
		"SET_LAST_MODIFIED" => "N",
		"MESSAGE_404" => "N",
		"SET_STATUS_404" => "N",
		"SHOW_404" => "N",
		"FILE_404" => "",
		"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
		"CACHE_FILTER" => "N",
		"CACHE_GROUPS" => "Y",
		"CACHE_TIME" => "0",
		"CACHE_TYPE" => "N",
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
		"FILTER_NAME" => "arrFilterMultitour",
		"HIDE_LINK_WHEN_NO_DETAIL" => "N",
		"CHECK_DATES" => "Y",
		"COMPONENT_TEMPLATE" => "tours",
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
		"INCLUDE_SUBSECTIONS" => "Y",
		"MAKE_PRICING" => "Y",
		"OBJECT_TYPE" => "excursions"
	),
	false
);?>
   </section>
<?endif;?>
<?if($arParams["VIEW_DETAIL_SITES_ON_TOWN"]=="Y" && $ElementID):?>
      <?$GLOBALS['arrFilterSightsTown']["PROPERTY_TOWN"] = $VALUE;?>
      <?$APPLICATION->IncludeComponent(
	"bitrix:news.list", 
	"sights", 
	array(
		"TITLE_LIST" => GetMessage("OTHER_ATTRACTIONS"),
		"IBLOCK_TYPE" => "Dictionaries",
		"IBLOCK_ID" => "6",
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
			0 => "ADDRESS",
			1 => "MAP",
			2 => "MAP_SCALE",
			3 => "PREVIEW_TEXT",
			4 => "DETAIL_TEXT",
			5 => "YOUTUBE",
			6 => "VIMEO",
			7 => "NAME_EN",
			8 => "ADDRESS_EN",
			9 => "PREVIEW_TEXT_EN",
			10 => "DETAIL_TEXT_EN",
			11 => "NAME_BY",
			12 => "ADDRESS_BY",
			13 => "PREVIEW_TEXT_BY",
			14 => "DETAIL_TEXT_BY",
			15 => "COUNTRY",
			16 => "REGION",
			17 => "TOWN",
			18 => "TYPE",
			19 => "REGIONS",
			20 => "HD_DESC",
			21 => "PICTURES",
			22 => "",
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
		"FILTER_NAME" => "arrFilterSightsTown",
		"HIDE_LINK_WHEN_NO_DETAIL" => "N",
		"CHECK_DATES" => "Y",
		"COMPONENT_TEMPLATE" => "sights",
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
		"STRICT_SECTION_CHECK" => "N"
	),
	false
);?>
<?endif;?>
<?if($arParams["VIEW_DETAIL_SANATORIUM"]=="Y" && $VALUE_SANATORIUM):?>
      <?$GLOBALS['arrFilterSanatorium']["ID"] = $VALUE_SANATORIUM;?>
      <?$APPLICATION->IncludeComponent(
	"bitrix:news.list", 
	"hotels", 
	array(
		"TITLE_LIST" => GetMessage("SANATORIUM"),
		"IBLOCK_TYPE" => "Dictionaries",
		"IBLOCK_ID" => "8",
		"NEWS_COUNT" => "3",
		"SORT_BY1" => "ACTIVE_FROM",
		"SORT_ORDER1" => "DESC",
		"SORT_BY2" => "SORT",
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
			5 => "FEATURES",
			6 => "DISTANCE_CENTER",
			7 => "ADDRESS_BY",
			8 => "HD_DESC_BY",
			9 => "FEATURES_BY",
			10 => "NAME_EN",
			11 => "ADDRESS_EN",
			12 => "HD_DESC_EN",
			13 => "FEATURES_EN",
			14 => "COUNTRY",
			15 => "REGION",
			16 => "TOWN",
			17 => "TYPE",
			18 => "MAP_SCALE",
			19 => "DETAIL_TEXT",
			20 => "PREVIEW_TEXT",
			21 => "REGIONS",
			22 => "PICTURES",
			23 => "",
		),
		"DETAIL_URL" => "/tourism/health-tourism/#ELEMENT_CODE#/?booking[id][]=#ELEMENT_ID#",
		"SECTION_URL" => "/tourism/health-tourism/",
		"IBLOCK_URL" => "/tourism/health-tourism/",
		"SET_TITLE" => "N",
		"SET_LAST_MODIFIED" => "N",
		"MESSAGE_404" => "N",
		"SET_STATUS_404" => "N",
		"SHOW_404" => "N",
		"FILE_404" => "",
		"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
		"CACHE_FILTER" => "N",
		"CACHE_GROUPS" => "Y",
		"CACHE_TIME" => "0",
		"CACHE_TYPE" => "N",
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
		"FILTER_NAME" => "arrFilterSanatorium",
		"HIDE_LINK_WHEN_NO_DETAIL" => "N",
		"CHECK_DATES" => "Y",
		"COMPONENT_TEMPLATE" => "hotels",
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
		"INCLUDE_SUBSECTIONS" => "Y",
            "MAKE_PRICING" => "Y",
                                    "OBJECT_TYPE" => "sanatorium",
                                    "FILTER_BY_PRICES_FOR_CITIZEN" => "Y"
	),
	false
);?>
<?endif;?>
<?if($arParams["VIEW_DETAIL_ACCOMODATION"]=="Y" && $VALUE_ACCOMODATION):?>
      <?$GLOBALS['arrFilterAccomodations']["ID"] = $VALUE_ACCOMODATION;?>
      <?$APPLICATION->IncludeComponent(
         "bitrix:news.list",
         "hotels",
         Array(
			"TITLE_LIST" => GetMessage("OTHER_HOTELS"),
             "IBLOCK_TYPE" => "Dictionaries",
             "IBLOCK_ID" => 7,
             "NEWS_COUNT" => 3,
             "SORT_BY1" => "ACTIVE_FROM",
             "SORT_ORDER1" => "DESC",
             "SORT_BY2" => "SORT",
             "SORT_ORDER2" => "ASC",
             "FIELD_CODE" => Array(),
             "PROPERTY_CODE" => array(
                 0 => "COUNTRY",
                 1 => "REGION",
                 2 => "TOWN",
                 3 => "ADDRESS",
                 4 => "TYPE",
                 5 => "MAP",
                 6 => "MAP_SCALE",
                 7 => "DETAIL_TEXT",
                 8 => "YOUTUBE",
                 9 => "VIMEO",
                 10 => "PREVIEW_TEXT",
                 11 => "REGIONS",
                 12 => "HD_DESC",
                 13 => "PICTURES",
                 14 => "",
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
             "CACHE_TIME" => "0",
             "CACHE_TYPE" => "N",
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
             "FILTER_NAME" => "arrFilterAccomodations",
             "HIDE_LINK_WHEN_NO_DETAIL" => "N",
             "CHECK_DATES" => "Y",
             "MAKE_PRICING" => "Y",
                                    "OBJECT_TYPE" => "placements"
         ),
         false
         );?>
<?endif;?>
<?if($arParams["VIEW_DETAIL_HOTELS_ON_TOWN"]=="Y" && $ElementID):?>
      <?$GLOBALS['arrFilterHotelsTown']["PROPERTY_TOWN"] = $VALUE;?>
      <?$APPLICATION->IncludeComponent(
	"bitrix:news.list", 
	"hotels", 
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
		"STRICT_SECTION_CHECK" => "N"
	),
	false
);?>
<?endif;?>
<?if($arParams["VIEW_DETAIL_NEWS_ON_TOWN"]=="Y" && $ElementID):?>
      <?$GLOBALS['arrFilterNewsTown']["PROPERTY_TOWN"] = $VALUE;?>
      <?$APPLICATION->IncludeComponent(
         "bitrix:news.list",
         "sights",
         Array(
			"TITLE_LIST" => GetMessage("OTHER_NEWS"),
             "IBLOCK_TYPE" => "Information",
             "IBLOCK_ID" => 28,
             "NEWS_COUNT" => 2,
             "SORT_BY1" => "ACTIVE_FROM",
             "SORT_ORDER1" => "DESC",
             "SORT_BY2" => "SORT",
             "SORT_ORDER2" => "ASC",
             "FIELD_CODE" => Array(),
             "PROPERTY_CODE" => array(
                 0 => "COUNTRY",
                 1 => "REGION",
                 2 => "TOWN",
                 3 => "ADDRESS",
                 4 => "TYPE",
                 5 => "MAP",
                 6 => "MAP_SCALE",
                 7 => "DETAIL_TEXT",
                 8 => "YOUTUBE",
                 9 => "VIMEO",
                 10 => "PREVIEW_TEXT",
                 11 => "REGIONS",
                 12 => "HD_DESC",
                 13 => "PICTURES",
                 14 => "",
             ),
             "DETAIL_URL" => "/poster/news/#ELEMENT_CODE#/",
             "SECTION_URL" => "/poster/news/",
             "IBLOCK_URL" => "/poster/news/",
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
             "FILTER_NAME" => "arrFilterNewsTown",
             "HIDE_LINK_WHEN_NO_DETAIL" => "N",
             "CHECK_DATES" => "Y",
         ),
         false
         );?>
<?endif;?>
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
<hr />
<h3><?=GetMessage("CATEGORIES")?></h3>
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