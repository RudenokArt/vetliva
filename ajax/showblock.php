<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
if ($_REQUEST['destinations']=='Y'){
    ob_start();
    $APPLICATION->IncludeComponent(
	"bitrix:news.list", 
	"spo-index-carousel_", 
    	array(
            "time" => time(),
    		"ACTIVE_DATE_FORMAT" => "d.m.Y",
    		"ADD_SECTIONS_CHAIN" => "N",
    		"AJAX_MODE" => "N",
    		"AJAX_OPTION_ADDITIONAL" => "",
    		"AJAX_OPTION_HISTORY" => "N",
    		"AJAX_OPTION_JUMP" => "N",
    		"AJAX_OPTION_STYLE" => "Y",
    		"CACHE_FILTER" => "N",
    		"CACHE_GROUPS" => "N",
    		"CACHE_TIME" => "604800",
    		"CACHE_TYPE" => "N",
    		"CHECK_DATES" => "Y",
    		"COMPONENT_TEMPLATE" => "spo-index-carousel_",
    		"CURRENCY_CURRENT" => "",
    		"DETAIL_URL" => "",
    		"DISPLAY_BOTTOM_PAGER" => "N",
    		"DISPLAY_DATE" => "Y",
    		"DISPLAY_NAME" => "Y",
    		"DISPLAY_PICTURE" => "Y",
    		"DISPLAY_PREVIEW_TEXT" => "Y",
    		"DISPLAY_TOP_PAGER" => "N",
    		"FIELD_CODE" => array(
    			0 => "",
    			1 => "",
    		),
    		"FILTER_NAME" => "",
    		"HIDE_LINK_WHEN_NO_DETAIL" => "N",
    		"IBLOCK_ID" => "8",
    		"IBLOCK_TYPE" => "Dictionaries",
    		"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
    		"INCLUDE_SUBSECTIONS" => "Y",
    		"MESSAGE_404" => "",
    		"NEWS_COUNT" => "2",
    		"PAGER_BASE_LINK_ENABLE" => "N",
    		"PAGER_DESC_NUMBERING" => "N",
    		"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
    		"PAGER_SHOW_ALL" => "N",
    		"PAGER_SHOW_ALWAYS" => "N",
    		"PAGER_TEMPLATE" => ".default",
    		"PAGER_TITLE" => "Новости",
    		"PARENT_SECTION" => "",
    		"PARENT_SECTION_CODE" => "",
    		"PREVIEW_TRUNCATE_LEN" => "",
    		"PROPERTY_CODE" => array(
    			0 => "",
    			1 => "PREVIEW_TEXT",
    			2 => "DISCOUNT",
    			3 => "OLD_PRICE",
    			4 => "PRICE",
    			5 => "PICTURES",
    			6 => "CURRENCY",
    			7 => "ATTRACTION",
    			8 => "SANATORIUM",
    			9 => "ACCOMODATION",
    			10 => "COUNTRY",
    			11 => "",
    		),
    		"SET_BROWSER_TITLE" => "N",
    		"SET_LAST_MODIFIED" => "N",
    		"SET_META_DESCRIPTION" => "N",
    		"SET_META_KEYWORDS" => "N",
    		"SET_STATUS_404" => "N",
    		"SET_TITLE" => "N",
    		"SHOW_404" => "N",
    		"SORT_BY1" => "SORT",
    		"SORT_BY2" => "NAME",
    		"SORT_ORDER1" => "ASC",
    		"SORT_ORDER2" => "ASC",
    		"STRICT_SECTION_CHECK" => "N"
    	),
    	false
    );
    $content = ob_get_clean();
    echo json_encode($content);
    exit();
}
if ($_REQUEST['favorites']=='Y') {
    ob_start();
    $APPLICATION->IncludeComponent(
    	"travelsoft:favorites.add",
    	"",
    	Array(
            "SHORT_DISPLAY"=>$_REQUEST['short_display'],
    		"OBJECT_ID" => $_REQUEST["object_id"],
    		"OBJECT_TYPE" => $_REQUEST["object_type"],
            "STORE_ID" => $_REQUEST["store_id"]
    	)
    );
    $content = ob_get_clean();
    echo json_encode($content);
    exit();
}