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
require_once 'bitrix/news.detail/.default/functions.php';
$curentItem = CIBlockElement::GetList(
					false,
					["IBLOCK_ID" => $arParams["IBLOCK_ID"], "CODE" => $arResult["VARIABLES"]["ELEMENT_CODE"]], 
					false, 
					false, 
					["ID", "PROPERTY_TOWN", "PROPERTY_TYPE"]
				  ) -> Fetch();

if (!empty($curentItem))
{
		$GLOBALS["arFilterCurent"] = array(  
			"PROPERTY_TOWN" => $curentItem["PROPERTY_TOWN_VALUE"],
			"PROPERTY_TYPE" => $curentItem["PROPERTY_TYPE_VALUE"],
			"!ID" => $curentItem["ID"]
		);

			ob_start();
			$APPLICATION->IncludeComponent(
				"travelsoft:travelsoft.news.list", "clinics-list-for-curent", Array(
					"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
					"IBLOCK_ID" => $arParams["IBLOCK_ID"],
					"NEWS_COUNT" => 4,
					"FILTER_NAME" => "arFilterCurent",
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
					"MESSAGE_404" => "",
					"SET_STATUS_404" => $arParams["SET_STATUS_404"],
					"SHOW_404" => $arParams["SHOW_404"],
					"FILE_404" => "",
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
					"TYPE" => $arParams["TYPE"],
					"SHOW_PANEL_SORT" => "N",
					"__BOOKING_REQUEST" => $arParams["__BOOKING_REQUEST"]
				)
			);

			$GLOBALS["LIST_OBJECT_FOR_CURENT"] = ob_get_clean();
}
?>

<?$APPLICATION->ShowViewContent('head-detail');?>
<div class="detail-cn myclass">
<div class="row check-rates">
	<div class="col-sm-3 hidden-sm hidden-xs col-lg-3 detail-sidebar" style="float:left">
		<div style="padding-top:20px" class="scrolly scrollspy-sidebar sidebar-detail scroll-heading" role="complementary" data-offset="20">
            <div>
			<?$APPLICATION->ShowViewContent('address-sidebar-detail'); ?>
			</div>
            <ul class="nav">
                <?$APPLICATION->ShowViewContent('menu-item-detail');?>
                <?$APPLICATION->ShowViewContent('menu-item-review');?>
                <?$APPLICATION->ShowViewContent('menu-item-other_offers');?>
            </ul>
			<?$APPLICATION->IncludeComponent(
			"bitrix:advertising.banner", 
			".default", 
			array(
				"CACHE_TIME" => "0",
				"CACHE_TYPE" => "N",
				"NOINDEX" => "N",
				"QUANTITY" => "1",
				"TYPE" => "sidebar",
				"COMPONENT_TEMPLATE" => ".default"
			),
			false
			);?>
        </div>
    </div>
	<div style="padding-top:7px" class="col-lg-9 check-rates-cn section-list">
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
<?$ElementID = $APPLICATION->IncludeComponent(
    "bitrix:news.detail",
    "",
    Array(
        "favorites_html_hash" => md5("__travelsoft__".$GLOBALS["favorites_html"]),
        "DISPLAY_DATE" => $arParams["DISPLAY_DATE"] ,
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
        "SERVICE_HL_ID" => $arParams["SERVICE_HL_ID"],
        "RATES_HL_ID" => $arParams["RATES_HL_ID"],
        "PRICE_HL_ID" => $arParams["PRICE_HL_ID"],
        "QUOTAS_HL_ID" => $arParams["QUOTAS_HL_ID"],
        "PRICE_TYPES_HL_ID" => $arParams['PRICE_TYPES_HL_ID'],
        "PTR_HL_ID" => $arParams["PTR_HL_ID"],
        "PRICE_CALCULATION_HESH" => md5($GLOBALS["PRICE_CALCULATION_RESULT_HTML"]),
        "SEF_FOLDER" => $arParams["SEF_FOLDER"]

    ),
    $component
);?>

<?
# записываем посещение страницы
$APPLICATION->IncludeComponent(
	"travelsoft:vetliva.history.page_counter",
	"",
	Array(
		"ID" => $ElementID,
		"USER_GROUPS" => array("1","8")
	)
);?>       


<!-- Hotel Detail Reviews -->
<section class="review-detail detail-cn" id="iblock_detail_reviews">
    <?$GLOBALS['arrFilterReviews']["PROPERTY_ITEM"] = $ElementID;?>
    <?$APPLICATION->IncludeComponent(
        "bitrix:news.list",
        "clinic_reviews",
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
			"DISPLAY_RATING_BLOCK" => "N"
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
                        "ELEMENT_ASSOC_PROPERTY" => "497",
                        "GROUPS" => array("10"),
                        "IBLOCK_ID" => "36",
                        "IBLOCK_TYPE" => "Reviews",
                        "LEVEL_LAST" => "Y",
                        "LIST_URL" => "",
                        "MAX_FILE_SIZE" => "0",
                        "MAX_LEVELS" => "100000",
                        "MAX_USER_ENTRIES" => "100000",
                        "PREVIEW_TEXT_USE_HTML_EDITOR" => "N",
                        "PROPERTY_CODES" => array("242",  "257", "258", "497", "606", "PREVIEW_TEXT","NAME"),
                        "PROPERTY_CODES_REQUIRED" => array("242","258", "606", "PREVIEW_TEXT"),
                        "RESIZE_IMAGES" => "N",
                        "SEF_MODE" => "N",
                        "STATUS" => "ANY",
                        "STATUS_NEW" => "NEW",
                        "USER_MESSAGE_ADD" => GetMessage('ADD_REVIEWS_OK_TEXT'),
                        "USER_MESSAGE_EDIT" => "",
                        "USE_CAPTCHA" => "Y",
                        "ELEMENT_ID" => $ElementID,
                        "EVENT_MESSAGE_ID" => $arParams["EVENT_MESSAGE_ID"]
                    )
                );?>
            <?else:?>
				<a href="#header-auth-popup" class="show-header-auth-popup">
					<div class="btn-add-review-wrap">
						<img class="btn-add-review" src="<?=SITE_TEMPLATE_PATH."/images/btn_add_review" . POSTFIX_PROPERTY .".png"?>">
						<img class="btn-add-review1" src="<?=SITE_TEMPLATE_PATH."/images/btn_add_review_up" . POSTFIX_PROPERTY .".png"?>">
					</div>
				</a>

                <!--a href="#header-auth-popup" class="show-header-auth-popup add-to-cart awe-btn awe-btn-1 awe-btn-small"><?=GetMessage('ADD_REVIEWS_SUBMIT_TEXT')?></a-->
            <?endif?>

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

        </div>
    </div>
</section>

<? if ($GLOBALS["LIST_OBJECT_FOR_CURENT"] && !empty($GLOBALS["LIST_OBJECT_FOR_CURENT"])): ?>
    <? $this->SetViewTarget("menu-item-other_offers"); ?>
            <li><a href="#iblock_detail_other_offers" class="anchor"><?= GetMessage('OTHER_OFFERS') ?></a></li>
    <? $this->EndViewTarget(); ?>
    <section class="hl-features" id="iblock_detail_other_offers">
        <div class="hl-features-cn" style="padding-top: 0">
            <?= $GLOBALS["LIST_OBJECT_FOR_CURENT"]; ?>
        </div>
    </section>
<? endif ?>
</div>
</div>
</div>

<p><a href="<?=$arResult["FOLDER"].$arResult["URL_TEMPLATES"]["clinics"]?>" class="awe-btn awe-btn-5 arrow-right awe-btn-lager text-uppercase float-right mt-20 mr-20"><?=GetMessage("T_NEWS_DETAIL_BACK")?></a></p>