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
    $result_price = $APPLICATION->IncludeComponent(
        "travelsoft:travelsoft.service.price.result",
        "on.detail.page.render",
        Array(
            "CACHE_TYPE" => "A",
            "CACHE_TIME" => 3600,
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
            "FOLDER_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["news"],
            "CURRENCY" => travelsoft\Currency::getInstance()->get("current_currency")["iso"]
        )
    );
    $GLOBALS["PRICE_CALCULATION_RESULT_HTML"] = ob_get_clean();
    
    /*$result_price = $APPLICATION->IncludeComponent(
        "travelsoft:travelsoft.service.price.result",
        "on.detail.page.render",
        Array(
            "CACHE_TYPE" => "A",
            "CACHE_TIME" => 3600,
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
            "RETURN_RESULT" => "Y",
            "FOLDER_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["news"],
            "CURRENCY" => travelsoft\Currency::getInstance()->get("current_currency")["iso"]
        )
    );*/

}
//if (empty($result_price)) {
   $_is_booking_request = !empty($arParams["__BOOKING_REQUEST"]);
   if ($_is_booking_request) {
        $arFilter_region = array("IBLOCK_ID" => $arParams["IBLOCK_ID"], "ACTIVE" => "Y");
        $element_type = (CIBlockElement::GetList(false, ["IBLOCK_ID" => $arParams["IBLOCK_ID"], "CODE" => $arResult["VARIABLES"]["ELEMENT_CODE"]], false, false, ["ID", "PROPERTY_IS_EXCURSION_TOUR"])->Fetch())["PROPERTY_IS_EXCURSION_TOUR_VALUE"];
        if (empty($element_type)) $arFilter_region['PROPERTY_IS_EXCURSION_TOUR'] = false; else $arFilter_region['!PROPERTY_IS_EXCURSION_TOUR'] = false;
        $arElements = $APPLICATION->IncludeComponent("travelsoft:travelsoft.iblock.getlist.byfilter", "", Array(
                "CACHE_TIME" => "3600",
                "CACHE_TYPE" => "A",
                "CNT" => "",
                "FILTER" => $arFilter_region,
                "FILTER_NAME" => "",
                "ORDER" => false,
                "RETURN_RESULT" => "Y",
                "SORT" => false,
                "SELECT" => array("ID"),
                "TITLE" => ""
            )
        );
        if ($arElements["ELEMENT_ID"]) {

            $element_id_ = current($arParams["__BOOKING_REQUEST"]["id"]);

            if (($key = array_search($element_id_, $arElements["ELEMENT_ID"])) !== false) {
                unset($arElements["ELEMENT_ID"][$key]);
            }
            $parameters = $arParams["__BOOKING_REQUEST"];
            $parameters["id"] = $arElements["ELEMENT_ID"];
            $result = $APPLICATION->IncludeComponent(
                "travelsoft:travelsoft.service.price.result", "on.detail.page.render", Array(
                    "RETURN_RESULT" => "Y",
                    "CACHE_TIME" => 3600,
                    "CACHE_TYPE" => "A",
                    "FILTER_BY_PRICES_FOR_CITIZEN" => $arParams["FILTER_BY_PRICES_FOR_CITIZEN"] == "Y" ? "Y" : "N",
                    "TYPE" => $arParams["TYPE"],
                    "MAKE_ORDER_PAGE" => "/booking/",
                    "POSTFIX_PROPERTY" => POSTFIX_PROPERTY,
                    "__BOOKING_REQUEST" => $parameters,
                    "MP" => "Y",
                )
            );
        }
        $_res_ids = array_keys($result);
        $GLOBALS["arFilterObjectForRegion"]["ID"] = !empty($_res_ids) ? $_res_ids : array(-1);
        ob_start();
        $APPLICATION->IncludeComponent(
            "travelsoft:travelsoft.news.list", "excursions-list-for-region", Array(
                "IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
                "IBLOCK_ID" => $arParams["IBLOCK_ID"],
                "NEWS_COUNT" => 4,
                "FILTER_NAME" => "arFilterObjectForRegion",
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
                "HIDE_LINK_WHEN_NO_DETAIL" => $arParams["HIDE_LINK_WHEN_NO_DETAIL"],
                "CHECK_DATES" => $arParams["CHECK_DATES"],
                "CALCULATION_PRICE_RESULT" => $result,
                "TYPE" => $arParams["TYPE"],
                "SHOW_PANEL_SORT" => "N",
                "__BOOKING_REQUEST" => $arParams["__BOOKING_REQUEST"]
            )
        );
        $GLOBALS["LIST_OBJECT_FOR_REGION"] = ob_get_clean();
    }
//}
?>
<?$APPLICATION->ShowViewContent('head-detail-tours');?>
<div class="detail-cn myclass">
    <div class="row check-rates">
        <div class="col-sm-3 hidden-sm hidden-xs col-lg-3 detail-sidebar">
            <div class="scrolly scrollspy-sidebar sidebar-detail scroll-heading" role="complementary" data-offset="20">
                <ul class="nav">
                    <?$APPLICATION->ShowViewContent('menu-item-detail-tours');?>
                    <?$APPLICATION->ShowViewContent('menu-item-review');?>

                    <?$APPLICATION->ShowViewContent('menu-item-hotel-tours');?>
                    <?$APPLICATION->ShowViewContent('menu-item-sport-tours');?>
                    <?$APPLICATION->ShowViewContent('menu-item-other_offers');?>
					<?$APPLICATION->ShowViewContent('menu-item-sights-tours');?>
                </ul>
                <div style="width: 100%; height: 20px;"></div>
            </div>
        </div>
        <div class="col-lg-9 check-rates-cn section-list">
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
<?
$ElementID = $APPLICATION->IncludeComponent(
    "bitrix:news.detail",
    "",
    Array(
        "favorites_html_hash" => md5("__travelsoft__".$GLOBALS["favorites_html"]),
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




<?$Values[$ElementID] = array();
$res = CIBlockElement::GetList(Array("SORT"=>"ASC"), Array("IBLOCK_ID"=>$arParams["IBLOCK_ID"], "ID"=>$ElementID, "ACTIVE"=>"Y"), false, false, Array("IBLOCK_ID", "ID"));
while($ar_fields = $res->GetNextElement())
{
    $arFields = $ar_fields->GetFields();
    $arProps = $ar_fields->GetProperties();
    $Values[$ElementID]["SIGHTS"] = $arProps["SIGHTS"]["VALUE"];
    $Values[$ElementID]["HOTEL"] = $arProps["HOTEL"]["VALUE"];
	$Values[$ElementID]["SPORT_OBJ"] = $arProps["SPORT_OBJ"]["VALUE"];
}
?>



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