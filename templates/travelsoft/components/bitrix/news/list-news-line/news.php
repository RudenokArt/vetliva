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

use \Bitrix\Main\Localization\Loc;

$APPLICATION->AddViewContent('map-link', '<a class="btn-lnk-map" href="'.SITE_DIR.'belarus/map/?TYPE[]='.$arParams['IBLOCK_ID'].'" title="'.Loc::getMessage('T_ITEMS_LIST_MAP_LINK').'">'.Loc::getMessage('T_ITEMS_LIST_MAP_LINK').'</a>');

$is_mobile = check_smartphone();
if ($is_mobile):?>
<script>
(function ($) {
$(document).ready(function (){
	$(".magnificbutton").magnificPopup({
        type: "inline",
		mainClass: 'mfp-wide-sights-mobile',
        midClick: true
    });
});
})(jQuery, document);
</script>
<?endif;
if($arParams["USE_RSS"]=="Y"):
	if(method_exists($APPLICATION, 'addheadstring'))
		$APPLICATION->AddHeadString('<link rel="alternate" type="application/rss+xml" title="'.$arResult["FOLDER"].$arResult["URL_TEMPLATES"]["rss"].'" href="'.$arResult["FOLDER"].$arResult["URL_TEMPLATES"]["rss"].'" />');
	?>
	<a href="<?=$arResult["FOLDER"].$arResult["URL_TEMPLATES"]["rss"]?>" title="rss" target="_self"><img alt="RSS" src="<?=$templateFolder?>/images/gif-light/feed-icon-16x16.gif" border="0" align="right" /></a>
<?endif;

if($arParams["USE_SEARCH"]=="Y"):?>
<?=GetMessage("SEARCH_LABEL")?><?$APPLICATION->IncludeComponent(
	"bitrix:search.form",
	"flat",
	Array(
		"PAGE" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["search"]
	),
	$component
);?>

<?endif;

// css
$this->addExternalCss(SITE_TEMPLATE_PATH . "/css/date/daterangepicker.min.css");
$this->addExternalCss(SITE_TEMPLATE_PATH . "/css/icomoon/style.min.css");
$this->addExternalCss(SITE_TEMPLATE_PATH . "/css/select2/select2.min.css");

// plugins js
$this->addExternalJs(SITE_TEMPLATE_PATH . "/js/date/moment.min.js");
$this->addExternalJs(SITE_TEMPLATE_PATH . "/js/date/moment_locales.min.js");
$this->addExternalJs(SITE_TEMPLATE_PATH . "/js/date/daterangepicker.js");
$this->addExternalJs(SITE_TEMPLATE_PATH ."/js/select2/select2.min.js");

// can use $.travelsoft
$this->addExternalJs(SITE_TEMPLATE_PATH . "/js/jquery.travelsoft.min.js");

if($arParams["IBLOCK_ID"] == 36):

$APPLICATION->IncludeComponent("travelsoft:iblock.element.reviews.add.form", ".default", array(
	"COMPONENT_TEMPLATE" => ".default",
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
		"ELEMENT_ID" => "",
		"EVENT_MESSAGE_ID" => $arParams["EVENT_MESSAGE_ID"],
		"GROUPS" => array(
			0 => "2",
		),
		"IBLOCK_ID" => "36",
		"IBLOCK_TYPE" => "Reviews",
		"LEVEL_LAST" => "Y",
		"LIST_URL" => "",
		"MAX_FILE_SIZE" => "0",
		"MAX_LEVELS" => "100000",
		"MAX_USER_ENTRIES" => "100000",
		"PREVIEW_TEXT_USE_HTML_EDITOR" => "N",
		"PROPERTY_CODES" => array(
			0 => "242",
			1 => "243",
			2 => "246",		
			3 => "606",
			4 => "618",
			5 => "853",
			6 => "856",
			7 => "PREVIEW_TEXT",
		),
		"PROPERTY_CODES_REQUIRED" => array(
			0 => "242",
			1 => "PREVIEW_TEXT",
		),
		"RESIZE_IMAGES" => "N",
		"SEF_MODE" => "N",
		"STATUS" => "ANY",
		"STATUS_NEW" => "NEW",
		"USER_MESSAGE_ADD" => GetMessage("ADD_REVIEWS_OK_TEXT"),
		"USER_MESSAGE_EDIT" => "",
		"USE_CAPTCHA" => "Y"
	),
	false,
	array(
	"ACTIVE_COMPONENT" => "Y"
	)
);?>
	<!-- Проверка юзер агента для адаптации размера кнопки "оставить отзыв" -->
	<?
	function is_Mobile() { 
		return preg_match("/(android|avantgo|Mobile|Phone|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
	}
	?>
	<? if(is_Mobile()): ?>
	<script>
	$(document).ready(function () {
		$(".btn-add-review-wrap").css("width", "70%");
		$(".btn-add-review").css("width", "70%");
		/*$(".btn-add-review1").css("width", "70%");*/
		$(".btn-add-review1:hover").css("width", "70%");
	});
	</script>
	<? endif; ?>
<?endif;

if($arParams["USE_FILTER"]=="Y"): 
if ($is_mobile):?>
    <div class="searchbyname-block-content <?if((CSite::InDir('/belarus/made-in-belarus/')) || (CSite::InDir('/belarus/getting-there/'))):?>made-in-content<?endif;?>" style="display: none;">
    <?$APPLICATION->IncludeComponent(
    	"bitrix:news.list", 
    	"travelsoft-search-by-name", 
    	array(
    		"IBLOCK_TYPE" => "-",
    		"IBLOCK_ID" => $arParams["IBLOCK_ID"],
    		"NEWS_COUNT" => 1,//"2000",
    		"SORT_BY1" => $arParams["SORT_BY1"],
    		"SORT_ORDER1" => $arParams["SORT_ORDER1"],
    		"SORT_BY2" => $arParams["SORT_BY2"],
    		"SORT_ORDER2" => $arParams["SORT_ORDER2"],
    		"FIELD_CODE" =>[],
    		"PROPERTY_CODE" => [],
    		"DETAIL_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["detail"],
    		"SECTION_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["section"],
    		"IBLOCK_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["news"],
    		"DISPLAY_PANEL" => $arParams["DISPLAY_PANEL"],
    		"SET_TITLE" => "N",
    		"SET_LAST_MODIFIED" => "N",
    		"MESSAGE_404" => $arParams["MESSAGE_404"],
    		"SET_STATUS_404" => "N",
    		"SHOW_404" => "N",
    		"FILE_404" => $arParams["FILE_404"],
    		"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
    		"CACHE_TYPE" => "A",
    		"CACHE_TIME" => $arParams["CACHE_TIME"],
    		"CACHE_FILTER" => "N",
    		"CACHE_GROUPS" => "N",
    		"DISPLAY_TOP_PAGER" => "N",
    		"DISPLAY_BOTTOM_PAGER" => "N",
    		"PAGER_TITLE" => $arParams["PAGER_TITLE"],
    		"PAGER_TEMPLATE" => $arParams["PAGER_TEMPLATE"],
    		"PAGER_SHOW_ALWAYS" => "N",
    		"PAGER_DESC_NUMBERING" => "N",
    		"PAGER_DESC_NUMBERING_CACHE_TIME" => $arParams["PAGER_DESC_NUMBERING_CACHE_TIME"],
    		"PAGER_SHOW_ALL" => "N",
    		"PAGER_BASE_LINK_ENABLE" => "N",
    		"PAGER_BASE_LINK" => $arParams["PAGER_BASE_LINK"],
    		"PAGER_PARAMS_NAME" => $arParams["PAGER_PARAMS_NAME"],
    		"DISPLAY_DATE" => $arParams["DISPLAY_DATE"],
    		"DISPLAY_NAME" => "Y",
    		"DISPLAY_PICTURE" => $arParams["DISPLAY_PICTURE"],
    		"DISPLAY_PREVIEW_TEXT" => $arParams["DISPLAY_PREVIEW_TEXT"],
    		"PREVIEW_TRUNCATE_LEN" => $arParams["PREVIEW_TRUNCATE_LEN"],
    		"ACTIVE_DATE_FORMAT" => $arParams["LIST_ACTIVE_DATE_FORMAT"],
    		"USE_PERMISSIONS" => $arParams["USE_PERMISSIONS"],
    		"GROUP_PERMISSIONS" => $arParams["GROUP_PERMISSIONS"],
    		"FILTER_NAME" => $arParams["FILTER_NAME"],
    		"HIDE_LINK_WHEN_NO_DETAIL" => "N",
    		"CHECK_DATES" => "N",
    		"COMPONENT_TEMPLATE" => "travelsoft-search-by-name",
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
    );?>
    </div>
    <?endif;?>
    <?$this->SetViewTarget('smart-filter');?>
    <div id="filter-area" <?if ($is_mobile):?> class="header-auth-form mfp-hide"<?else:?>style="margin-top: 20px;"<?endif;?>>
    <?if ($is_mobile):?><a href="<?=$APPLICATION->GetCurPageParam("del_filter=Y", array("set_filter", "del_filter"), false) ?>" onclick="$('.magnificbutton').magnificPopup('close');" class="mobile-reset-filtr sorting" ><?=GetMessage('FILTER_RESET') ?></a><?endif;?>
    
<?//Если мы не в разделе "Отзывы", то показываем поиск по названию
if(strpos($_SERVER["REQUEST_URI"], "/about/reviews/") === FALSE && !$is_mobile):?>
<?$APPLICATION->IncludeComponent(
	"bitrix:news.list", 
	"travelsoft-search-by-name", 
	array(
		"IBLOCK_TYPE" => "-",
		"IBLOCK_ID" => $arParams["IBLOCK_ID"],
		"NEWS_COUNT" => 1,//"2000",
		"SORT_BY1" => $arParams["SORT_BY1"],
		"SORT_ORDER1" => $arParams["SORT_ORDER1"],
		"SORT_BY2" => $arParams["SORT_BY2"],
		"SORT_ORDER2" => $arParams["SORT_ORDER2"],
		"FIELD_CODE" =>[],
		"PROPERTY_CODE" => [],
		"DETAIL_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["detail"],
		"SECTION_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["section"],
		"IBLOCK_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["news"],
		"DISPLAY_PANEL" => $arParams["DISPLAY_PANEL"],
		"SET_TITLE" => "N",
		"SET_LAST_MODIFIED" => "N",
		"MESSAGE_404" => $arParams["MESSAGE_404"],
		"SET_STATUS_404" => "N",
		"SHOW_404" => "N",
		"FILE_404" => $arParams["FILE_404"],
		"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => $arParams["CACHE_TIME"],
		"CACHE_FILTER" => "N",
		"CACHE_GROUPS" => "N",
		"DISPLAY_TOP_PAGER" => "N",
		"DISPLAY_BOTTOM_PAGER" => "N",
		"PAGER_TITLE" => $arParams["PAGER_TITLE"],
		"PAGER_TEMPLATE" => $arParams["PAGER_TEMPLATE"],
		"PAGER_SHOW_ALWAYS" => "N",
		"PAGER_DESC_NUMBERING" => "N",
		"PAGER_DESC_NUMBERING_CACHE_TIME" => $arParams["PAGER_DESC_NUMBERING_CACHE_TIME"],
		"PAGER_SHOW_ALL" => "N",
		"PAGER_BASE_LINK_ENABLE" => "N",
		"PAGER_BASE_LINK" => $arParams["PAGER_BASE_LINK"],
		"PAGER_PARAMS_NAME" => $arParams["PAGER_PARAMS_NAME"],
		"DISPLAY_DATE" => $arParams["DISPLAY_DATE"],
		"DISPLAY_NAME" => "Y",
		"DISPLAY_PICTURE" => $arParams["DISPLAY_PICTURE"],
		"DISPLAY_PREVIEW_TEXT" => $arParams["DISPLAY_PREVIEW_TEXT"],
		"PREVIEW_TRUNCATE_LEN" => $arParams["PREVIEW_TRUNCATE_LEN"],
		"ACTIVE_DATE_FORMAT" => $arParams["LIST_ACTIVE_DATE_FORMAT"],
		"USE_PERMISSIONS" => $arParams["USE_PERMISSIONS"],
		"GROUP_PERMISSIONS" => $arParams["GROUP_PERMISSIONS"],
		"FILTER_NAME" => $arParams["FILTER_NAME"],
		"HIDE_LINK_WHEN_NO_DETAIL" => "N",
		"CHECK_DATES" => "N",
		"COMPONENT_TEMPLATE" => "travelsoft-search-by-name",
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
);?>
<?endif;?>

<?$APPLICATION->IncludeComponent(
        "bitrix:catalog.smart.filter",
        "travelsoft-smart-filter",
        array(
                "IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
                "IBLOCK_ID" => $arParams["IBLOCK_ID"],
                "SECTION_ID" => "",
                "FILTER_NAME" => $arParams["FILTER_NAME"],
                "PRICE_CODE" => "",
                "CACHE_TYPE" => $arParams["CACHE_TYPE"],
                "CACHE_TIME" => $arParams["CACHE_TIME"],
			    "CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
                "SAVE_IN_SESSION" => "N",
                "FILTER_VIEW_MODE" => "",
                "XML_EXPORT" => "N",
                "SECTION_TITLE" => "NAME",
                "SECTION_DESCRIPTION" => "DESCRIPTION",
                'HIDE_NOT_AVAILABLE' => "Y",
                "TEMPLATE_THEME" => "",
                'CONVERT_CURRENCY' => "",
                'CURRENCY_ID' => "",
                "SEF_MODE" => "N",
                "SEF_RULE" => "",
                "SMART_FILTER_PATH" => "",
                "PAGER_PARAMS_NAME" => "",
                "INSTANT_RELOAD" => "N",
        ),
        $component,
        array('HIDE_ICONS' => 'Y')
);?></div><?
$this->EndViewTarget();
endif;
?>

<!--<div class="col-md-3 col-md-push-0">
    <div class="sidebar-cn inc-area" >-->
<?/*
	$APPLICATION->IncludeComponent(
			"bitrix:main.include", "", Array(
		"AREA_FILE_RECURSIVE" => "Y",
		"AREA_FILE_SHOW" => "sect",
		"AREA_FILE_SUFFIX" => "inc",
		"EDIT_TEMPLATE" => ""
			)
	);
*/?>
    <!--</div>
</div>-->

<?
if($arParams["IBLOCK_ID"] == EVENTS_IBLOCK_ID){

    ?>
    <div class="col-md-3 col-md-push-0">
        <div class="sidebar-cn">
            <?
            $APPLICATION->IncludeComponent(
                "bitrix:main.include", "", Array(
                    "AREA_FILE_RECURSIVE" => "Y",
                    "AREA_FILE_SHOW" => "sect",
                    "AREA_FILE_SUFFIX" => "inc",
                    "EDIT_TEMPLATE" => ""
                )
            );
            ?>
        </div>
    </div>
    <div class="col-md-9 col-md-pull-0 content-page-detail">
        <h1><?= $APPLICATION->ShowTitle(false) ?></h1>
    <?

}

if (!$GLOBALS[$arParams["FILTER_NAME"]]["><PROPERTY_128"]) {
    $GLOBALS[$arParams["FILTER_NAME"]][">=PROPERTY_128"] = date("Y-m-d h:i:s");
}
/*if (!$GLOBALS[$arParams["FILTER_NAME"]]["><PROPERTY_955"]) {
    $GLOBALS[$arParams["FILTER_NAME"]][">=PROPERTY_955"] = date("Y-m-d h:i:s");
}*/

$ajaxId = "__travelsoft_ef3b7f993cf1957b8d2007682f9b75fd";
travelsoft\Ajax::start($ajaxId, true);
$template = strlen($arParams["TEMPLATE_LIST"]) ? $arParams["TEMPLATE_LIST"] : "";
?>

<?$APPLICATION->IncludeComponent(
	"bitrix:news.list",
	$template,
	Array(
		"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
		"IBLOCK_ID" => $arParams["IBLOCK_ID"],
		"NEWS_COUNT" => $arParams["NEWS_COUNT"],
        "SORT_BY1" => $arParams["SORT_BY1"],
		"SORT_ORDER1" => $arParams["SORT_ORDER1"],
		"SORT_BY2" => $arParams["SORT_BY2"],
		"SORT_ORDER2" => $arParams["SORT_ORDER2"],
		"FIELD_CODE" => $arParams["LIST_FIELD_CODE"],
		"PROPERTY_CODE" => $arParams["LIST_PROPERTY_CODE"],
		"DETAIL_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["detail"],
		"SECTION_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["section"],
		"IBLOCK_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["news"],
		"DISPLAY_PANEL" => $arParams["DISPLAY_PANEL"],
		"SET_TITLE" => $arParams["SET_TITLE"],
		"SET_LAST_MODIFIED" => $arParams["SET_LAST_MODIFIED"],
		"MESSAGE_404" => $arParams["MESSAGE_404"],
		"SET_STATUS_404" => $arParams["SET_STATUS_404"],
		"SHOW_404" => $arParams["SHOW_404"],
		"FILE_404" => $arParams["FILE_404"],
		"INCLUDE_IBLOCK_INTO_CHAIN" => $arParams["INCLUDE_IBLOCK_INTO_CHAIN"],
		"CACHE_TYPE" => $arParams["CACHE_TYPE"],
		"CACHE_TIME" => $arParams["CACHE_TIME"],
		"CACHE_FILTER" => $arParams["CACHE_FILTER"],
		"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
		"DISPLAY_TOP_PAGER" => $arParams["DISPLAY_TOP_PAGER"],
		"DISPLAY_BOTTOM_PAGER" => $arParams["DISPLAY_BOTTOM_PAGER"],
		"PAGER_TITLE" => $arParams["PAGER_TITLE"],
		"PAGER_TEMPLATE" => $arParams["PAGER_TEMPLATE"],
		"PAGER_SHOW_ALWAYS" => $arParams["PAGER_SHOW_ALWAYS"],
		"PAGER_DESC_NUMBERING" => $arParams["PAGER_DESC_NUMBERING"],
		"PAGER_DESC_NUMBERING_CACHE_TIME" => $arParams["PAGER_DESC_NUMBERING_CACHE_TIME"],
		"PAGER_SHOW_ALL" => $arParams["PAGER_SHOW_ALL"],
		"PAGER_BASE_LINK_ENABLE" => $arParams["PAGER_BASE_LINK_ENABLE"],
		"PAGER_BASE_LINK" => $arParams["PAGER_BASE_LINK"],
		"PAGER_PARAMS_NAME" => $arParams["PAGER_PARAMS_NAME"],
		"DISPLAY_DATE" => $arParams["DISPLAY_DATE"],
		"DISPLAY_NAME" => "Y",
		"DISPLAY_PICTURE" => $arParams["DISPLAY_PICTURE"],
		"DISPLAY_PREVIEW_TEXT" => $arParams["DISPLAY_PREVIEW_TEXT"],
		"PREVIEW_TRUNCATE_LEN" => $arParams["PREVIEW_TRUNCATE_LEN"],
		"ACTIVE_DATE_FORMAT" => $arParams["LIST_ACTIVE_DATE_FORMAT"],
		"USE_PERMISSIONS" => $arParams["USE_PERMISSIONS"],
		"GROUP_PERMISSIONS" => $arParams["GROUP_PERMISSIONS"],
		"FILTER_NAME" => $arParams["FILTER_NAME"],
		"HIDE_LINK_WHEN_NO_DETAIL" => $arParams["HIDE_LINK_WHEN_NO_DETAIL"],
		"CHECK_DATES" => $arParams["CHECK_DATES"],
	),
	$component
);?>
<?travelsoft\Ajax::end($ajaxId, true);?>
<div class="row">

	<div style="float:right;">
        <?$APPLICATION->IncludeComponent("bitrix:main.include","",Array(
            "AREA_FILE_SHOW" => "sect",
            "AREA_FILE_SUFFIX" => "block-seotext-".LANGUAGE_ID,
            "AREA_FILE_RECURSIVE" => "N",
            "EDIT_TEMPLATE" => ""
        )
    );
    ?>
    </div>
</div>
