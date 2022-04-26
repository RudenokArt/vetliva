<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();
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

use Bitrix\Highloadblock as HL;
?>

<? if ($arParams["USE_RSS"] == "Y"): ?>
    <?
    if (method_exists($APPLICATION, 'addheadstring'))
        $APPLICATION->AddHeadString('<link rel="alternate" type="application/rss+xml" title="' . $arResult["FOLDER"] . $arResult["URL_TEMPLATES"]["rss"] . '" href="' . $arResult["FOLDER"] . $arResult["URL_TEMPLATES"]["rss"] . '" />');
    ?>
    <a href="<?= $arResult["FOLDER"] . $arResult["URL_TEMPLATES"]["rss"] ?>" title="rss" target="_self"><img alt="RSS" src="<?= $templateFolder ?>/images/gif-light/feed-icon-16x16.gif" border="0" align="right" /></a>
<? endif ?>

<? if ($arParams["USE_SEARCH"] == "Y"): ?>
    <?= GetMessage("SEARCH_LABEL") ?><?
    $APPLICATION->IncludeComponent(
            "bitrix:search.form", "flat", Array(
        "PAGE" => $arResult["FOLDER"] . $arResult["URL_TEMPLATES"]["search"]
            ), $component
    );
    ?>

<?
endif;
// css
$this->addExternalCss(SITE_TEMPLATE_PATH . "/css/date/daterangepicker.min.css");
$this->addExternalCss(SITE_TEMPLATE_PATH . "/css/icomoon/style.min.css");
$this->addExternalCss(SITE_TEMPLATE_PATH . "/css/select2/select2.min.css");

// plugins js
$this->addExternalJs(SITE_TEMPLATE_PATH . "/js/date/moment.min.js");
$this->addExternalJs(SITE_TEMPLATE_PATH . "/js/date/moment_locales.min.js");
$this->addExternalJs(SITE_TEMPLATE_PATH . "/js/date/daterangepicker.js");
$this->addExternalJs(SITE_TEMPLATE_PATH . "/js/select2/select2.min.js");

// can use $.travelsoft
$this->addExternalJs(SITE_TEMPLATE_PATH . "/js/jquery.travelsoft.min.js");

if ($arParams["MAKE_PRICING"] && \Bitrix\Main\Loader::includeModule("travelsoft.booking.dev.tools") && \Bitrix\Main\Loader::includeModule("highloadblock") && \Bitrix\Main\Loader::includeModule("travelsoft.currency")) {

    $arFilter = array("IBLOCK_ID" => $arParams["IBLOCK_ID"], "ACTIVE" => "Y");
    $complex_logic = array();

    $_is_booking_request = !empty($arParams["__BOOKING_REQUEST"]);

    if ($_is_booking_request) {

        $complex_logic = array(array(
                "LOGIC" => "OR",
                array("PROPERTY_TOWN" => $arParams["__BOOKING_REQUEST"]['id']),
                array("PROPERTY_REGION" => $arParams["__BOOKING_REQUEST"]['id']),
                array("PROPERTY_SIGHT" => $arParams["__BOOKING_REQUEST"]['id'])
        ));
    }

    $arElements = $APPLICATION->IncludeComponent("travelsoft:travelsoft.iblock.getlist.byfilter", "", Array(
        "CACHE_TIME" => "3600",
        "CACHE_TYPE" => "A",
        "CNT" => "",
        "FILTER" => array_merge($arFilter, $complex_logic),
        "FILTER_NAME" => "",
        "ORDER" => false,
        "RETURN_RESULT" => "Y",
        "SORT" => false,
        "SELECT" => array("ID"),
        "TITLE" => ""
            )
    );
    if ($arElements["ELEMENT_ID"]) {
        
        $parameters = $arParams["__BOOKING_REQUEST"];
        $parameters["id"] = $arElements["ELEMENT_ID"];

        $result = $APPLICATION->IncludeComponent(
                "travelsoft:travelsoft.service.price.result", "on.detail.page.render", Array(
            "RETURN_RESULT" => "Y",
            "FILTER_BY_PRICES_FOR_CITIZEN" => $arParams["FILTER_BY_PRICES_FOR_CITIZEN"] == "Y" ? "Y" : "N",
            "TYPE" => $arParams["TYPE"],
            "MAKE_ORDER_PAGE" => "/booking/",
            "POSTFIX_PROPERTY" => POSTFIX_PROPERTY,
            "__BOOKING_REQUEST" => $parameters,
                    "MP" => "Y"
                )
        );
        
        
    }
    
    if ($_is_booking_request && $arParams["FILTER_NAME"]) {

            $_res_ids = array_keys($result);

            $GLOBALS[$arParams["FILTER_NAME"]]["ID"] = !empty($_res_ids) ? $_res_ids : array(-1);
        }
    
}

if ($arParams["USE_FILTER"] == "Y"):

    $this->SetViewTarget('smart-filter');
    ?>
    
<div id="filter-area">
    <div data-messages='{"is_hidden": "<?= GetMessage('FILTER_ACCORDION_TITLE')?>","is_show":"<?= GetMessage('FILTER_ACCORDION_TITLE_SHOW')?>"}' style="display: none" id="filter-accordion"><span id="filter-accordion-title"><?= GetMessage('FILTER_ACCORDION_TITLE')?></span> <!--span id="corn">&#9650;</span--></div>
    <?
    $APPLICATION->IncludeComponent(
            "bitrix:news.list", "travelsoft-search-by-name", Array(
        "IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
        "IBLOCK_ID" => $arParams["IBLOCK_ID"],
        "NEWS_COUNT" => 999999999999,
        "SORT_BY1" => $arParams["SORT_BY1"],
        "SORT_ORDER1" => $arParams["SORT_ORDER1"],
        "SORT_BY2" => $arParams["SORT_BY2"],
        "SORT_ORDER2" => $arParams["SORT_ORDER2"],
        "FIELD_CODE" => $arParams["LIST_FIELD_CODE"],
        "PROPERTY_CODE" => $arParams["LIST_PROPERTY_CODE"],
        "DETAIL_URL" => $arResult["FOLDER"] . $arResult["URL_TEMPLATES"]["detail"],
        "SECTION_URL" => $arResult["FOLDER"] . $arResult["URL_TEMPLATES"]["section"],
        "IBLOCK_URL" => $arResult["FOLDER"] . $arResult["URL_TEMPLATES"]["news"],
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
        "CHECK_DATES" => $arParams["CHECK_DATES"]
            ), false
    );
    ?>

    <?
    $APPLICATION->IncludeComponent(
            "bitrix:catalog.smart.filter", "travelsoft-smart-filter", array(
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
        "SEF_FOLDER" => $arResult["FOLDER"],
        "__BOOKING_REQUEST" => $arParams["__BOOKING_REQUEST"]
            ), $component, array('HIDE_ICONS' => 'Y')
    );
    ?>
</div>
    <?
    $this->EndViewTarget();
endif;
?>
<div class="col-md-3 col-md-push-0">
    <div class="sidebar-cn inc-area">
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
        <div class="sidebar-banner">
            <?
            $APPLICATION->IncludeComponent(
                "bitrix:advertising.banner",
                ".default",
                array(
                    "CACHE_TIME" => "0",
                    "CACHE_TYPE" => "A",
                    "NOINDEX" => "Y",
                    "QUANTITY" => "1",
                    "TYPE" => "sidebar",
                    "COMPONENT_TEMPLATE" => ".default"
                ),
                false
            );
            ?>
        </div>
    </div>
</div>
        <?
        $ajaxId = "__travelsoft_ef3b7f993cf1957b8d2007682f9b75fd";

        travelsoft\Ajax::start($ajaxId, true);
        ?>
<?
$APPLICATION->IncludeComponent(
        "travelsoft:travelsoft.news.list", "placements-list", Array(
    "IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
    "IBLOCK_ID" => $arParams["IBLOCK_ID"],
    "NEWS_COUNT" => $arParams["NEWS_COUNT"],
    "SORT_BY1" => $arParams["SORT_BY1"],
    "SORT_ORDER1" => $arParams["SORT_ORDER1"],
    "SORT_BY2" => $arParams["SORT_BY2"],
    "SORT_ORDER2" => $arParams["SORT_ORDER2"],
    "FIELD_CODE" => $arParams["LIST_FIELD_CODE"],
    "PROPERTY_CODE" => $arParams["LIST_PROPERTY_CODE"],
    "DETAIL_URL" => $arResult["FOLDER"] . $arResult["URL_TEMPLATES"]["detail"],
    "SECTION_URL" => $arResult["FOLDER"] . $arResult["URL_TEMPLATES"]["section"],
    "IBLOCK_URL" => $arResult["FOLDER"] . $arResult["URL_TEMPLATES"]["news"],
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
    "CALCULATION_PRICE_RESULT" => $result,
            "TYPE" => $arParams["TYPE"],
    "__BOOKING_REQUEST" => $arParams["__BOOKING_REQUEST"]
        ), $component
);
?>
<? travelsoft\Ajax::end($ajaxId, true); ?>
