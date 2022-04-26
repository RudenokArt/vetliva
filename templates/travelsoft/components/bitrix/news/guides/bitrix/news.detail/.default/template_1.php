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
?>
<?$p=$arResult["PROPERTIES"];?>
<? /* h1><?echo LANGUAGE_ID == "ru" ? $arResult["NAME"] : $arResult["PROPERTIES"]["NAME".POSTFIX_PROPERTY]["VALUE"]?> </h1 */ ?>
<? $this->addExternalJs(SITE_TEMPLATE_PATH . "/js/slide.js"); ?>
<?
$arWaterMark = Array(
    array(
        "name" => "watermark",
        "position" => "topright", // Положение
        "type" => "image",
        "size" => "real",
        "file" => NO_PHOTO_PATH_WATERMARK, // Путь к картинке
        "fill" => "exact",
    )
);
?>

<p>
    <? if (!empty($arResult["PROPERTIES"]["DATE_FROM"]["VALUE"][0])): ?>
        <i class="fa fa-calendar"></i> <span <?if($arResult["IBLOCK_ID"] == NEWS_IBLOCK_ID):?>itemprop="datePublished" content="<?=date("Y-m-d", MakeTimeStamp($arResult['PROPERTIES']['DATE_FROM']['VALUE'][0]))?>"<?endif?>><?= substr($arResult["PROPERTIES"]["DATE_FROM"]["VALUE"][0], 0, 10) ?></span>
        <? if (!empty($arResult["PROPERTIES"]["DATE_FROM"]["VALUE"][1])): ?>
            - <?= substr($arResult["PROPERTIES"]["DATE_FROM"]["VALUE"][1], 0, 10) ?>
        <? endif; ?>
    <? endif; ?>
    <? if ($arParams["DISPLAY_DATE"] != "N"): ?><i class="fa fa-calendar"></i> <? echo FormatDateFromDB($arResult["PROPERTIES"]["DATE_NEED"]["VALUE"], 'SHORT'); ?> <? endif; ?>
    <?
    if (!empty($arResult["PROPERTIES"]["REGIONS"]["VALUE"])) {
        $region = strip_tags($arResult["DISPLAY_PROPERTIES"]["REGIONS"]["DISPLAY_VALUE"]);
        if (LANGUAGE_ID != "ru") {
            $prop = getIBElementProperties($arResult["PROPERTIES"]["REGIONS"]["VALUE"]);
            $region = $prop["NAME" . POSTFIX_PROPERTY]["VALUE"];
        }
    }
	if (!empty($arItem["PROPERTIES"]["EXCURTION"]["VALUE"])) {
            $excurtion = strip_tags($arItem["DISPLAY_PROPERTIES"]["EXCURTION"]["DISPLAY_VALUE"]);
            if (LANGUAGE_ID != "ru") {
                $prop = getIBElementProperties($arItem["PROPERTIES"]["EXCURTION"]["VALUE"]);
                $excurtion = $prop["NAME" . POSTFIX_PROPERTY]["VALUE"];
            }
        }
    if (!empty($arResult["PROPERTIES"]["TOWN"]["VALUE"])) {
        $town = strip_tags($arResult["DISPLAY_PROPERTIES"]["TOWN"]["DISPLAY_VALUE"]);
        if (LANGUAGE_ID != "ru") {
            $prop = getIBElementProperties($arResult["PROPERTIES"]["TOWN"]["VALUE"]);
            $town = $prop["NAME" . POSTFIX_PROPERTY]["VALUE"];
        }
        if (!empty($arItem["PROPERTIES"]["ACCOMODATION"]["VALUE"])) {
            $accomodation = strip_tags($arItem["DISPLAY_PROPERTIES"]["ACCOMODATION"]["DISPLAY_VALUE"]);
            if (LANGUAGE_ID != "ru") {
                $prop = getIBElementProperties($arItem["PROPERTIES"]["ACCOMODATION"]["VALUE"]);
                $accomodation = $prop["NAME" . POSTFIX_PROPERTY]["VALUE"];
            }
        }
        if (!empty($arItem["PROPERTIES"]["SANATORIUM"]["VALUE"])) {
            $sanatorium = strip_tags($arItem["DISPLAY_PROPERTIES"]["SANATORIUM"]["DISPLAY_VALUE"]);
            if (LANGUAGE_ID != "ru") {
                $prop = getIBElementProperties($arItem["PROPERTIES"]["SANATORIUM"]["VALUE"]);
                $sanatorium = $prop["NAME" . POSTFIX_PROPERTY]["VALUE"];
            }
        }
        if (!empty($arItem["PROPERTIES"]["ATTRACTION"]["VALUE"])) {
            $attraction = strip_tags($arItem["DISPLAY_PROPERTIES"]["ATTRACTION"]["DISPLAY_VALUE"]);
            if (LANGUAGE_ID != "ru") {
                $prop = getIBElementProperties($arItem["PROPERTIES"]["ATTRACTION"]["VALUE"]);
                $attraction = $prop["NAME" . POSTFIX_PROPERTY]["VALUE"];
            }
        }
    }
    ?>
    <? if (!empty($town) || !empty($region)): ?><i class="fa fa-map-marker"></i><? endif; ?>
	<? /* if ($region): ?><?= $region ?><? endif; */ ?>
	<? /* if ($town): ?> <?= $town ?><? endif; */ ?>
	<?= implode2(array($region, $town, $arResult["DISPLAY_PROPERTIES"]["ADDRESS".POSTFIX_PROPERTY]["DISPLAY_VALUE"])); ?>
    <? if ($accomodation): ?> <?= $accomodation ?><? endif; ?>
    <? if ($sanatorium): ?> <?= $sanatorium ?><? endif; ?>
    <? if ($attraction): ?> <?= $attraction ?><? endif; ?>
	<? if ($excurtion): ?> <?= $excurtion ?><? endif; ?></p>
                        <?
                            $kitchentype = null;
                            if ($p["KITCHEN2"]["VALUE"]) {
                                $p["KITCHEN2"]["VALUE"] = (array) $p["KITCHEN2"]["VALUE"];
                                $db_res_kitchentype = CIBlockElement::GetList(false, array("ACTIVE" => "Y", "IBLOCK_ID" => $p["KITCHEN2"]["LINK_IBLOCK_ID"], "ID" => $p["KITCHEN2"]["VALUE"]), false, false, array("ID", "NAME", "PROPERTY_NAME" . POSTFIX_PROPERTY));
                                $kitchentype = null;
                                while ($res = $db_res_kitchentype->Fetch()) {
                                    $kitchentype[] = $res["PROPERTY_NAME" . POSTFIX_PROPERTY . "_VALUE"] ? $res["PROPERTY_NAME" . POSTFIX_PROPERTY . "_VALUE"] : $res["NAME"];
                                }
                            }
                            if ($kitchentype):
                                ?>
                            <p>
								<i class="fa fa-info-circle blue"></i> <?= GetMessage('KITCHEN_TYPE')?>:	<?= implode(", ", $kitchentype) ?>
							</p>
                        <? endif; ?>
                        <?
                            $type = null;
                            if ($p["TYPE"]["VALUE"]) {
                                $p["TYPE"]["VALUE"] = (array) $p["TYPE"]["VALUE"];
                                $db_res_type = CIBlockElement::GetList(false, array("ACTIVE" => "Y", "IBLOCK_ID" => $p["TYPE"]["LINK_IBLOCK_ID"], "ID" => $p["TYPE"]["VALUE"]), false, false, array("ID", "NAME", "PROPERTY_NAME" . POSTFIX_PROPERTY));
                                $type = null;
                                while ($res = $db_res_type->Fetch()) {
                                    $type[] = $res["PROPERTY_NAME" . POSTFIX_PROPERTY . "_VALUE"] ? $res["PROPERTY_NAME" . POSTFIX_PROPERTY . "_VALUE"] : $res["NAME"];
                                }
                            }
                            if ($type):
                                ?>
                            <p>
								<i class="fa fa-info-circle blue"></i> <?= GetMessage('TYPE')?>	<?= implode(", ", $type) ?>
							</p>
                        <? endif; ?>
<?/*if ($p["TYPE".POSTFIX_PROPERTY]["VALUE"]):?>
                            <p>
								<i class="fa fa-info-circle blue"></i> <?= $p["TYPE".POSTFIX_PROPERTY]["VALUE"] ?>
							</p>
<?endif;*/?>

<?if ($arParams["HIDE_DETAIL_PICTURE"] !== "Y"): ?>
    <? $waterMark = ''; ?>
    <? if ($arParams["NO_SHOW_WATERMARK"] !== "Y"): ?>
        <? $waterMark = $arWaterMark ?>
    <? endif ?>
    <?
    if (count($arResult["PROPERTIES"]["PICTURES"]["VALUE"]) == 1):
        $an_file = CFile::ResizeImageGet($arResult["PROPERTIES"]["PICTURES"]["VALUE"][0], array('width' => 823, 'height' => 428), BX_RESIZE_IMAGE_EXACT, true, $waterMark);
        $pre_photo_915 = $an_file["src"];
        ?>
        <img src="<?= $pre_photo_915 ?>" alt="<?echo LANGUAGE_ID == "ru" ? $arResult["PROPERTIES"]["PICTURES"]["DESCRIPTION"]["0"] : $arResult["PROPERTIES"]["IMG_DESCRIPTION".POSTFIX_PROPERTY]["VALUE"]["0"]?>" class="img-responsive">
    <? elseif (count($arResult["PROPERTIES"]["PICTURES"]["VALUE"]) > 1): ?>
        <section class="detail-slider">
            <!-- Lager Image -->
            <div class="slide-room-lg">
                <div id="slide-room-lg">
                    <?
                    foreach ($arResult["PROPERTIES"]["PICTURES"]["VALUE"] as $key => $item):
                        $file_big = CFile::ResizeImageGet($item, Array('width' => 1170, 'height' => 640), BX_RESIZE_IMAGE_EXACT, true, $waterMark);
                        $img_count++;
                        ?>
                        <img src="<?= $file_big["src"]; ?>"  alt="<?echo LANGUAGE_ID == "ru" ? $arResult["PROPERTIES"]["PICTURES"]["DESCRIPTION"][$key] : $arResult["PROPERTIES"]["IMG_DESCRIPTION".POSTFIX_PROPERTY]["VALUE"][$key]?>">
                        <? $i++;
                    endforeach;
                    ?>
                </div>
            </div>
            <!-- End Lager Image -->
            <!-- Thumnail Image -->
            <div class="slide-room-sm">
                <div class="row">
                    <div class="col-md-8 col-md-offset-2">
                        <div id="slide-room-sm">
                            <?
                            foreach ($arResult["PROPERTIES"]["PICTURES"]["VALUE"] as $item):
                                $file_small = CFile::ResizeImageGet($item, Array('width' => 90, 'height' => 60), BX_RESIZE_IMAGE_EXACT, true);
                                ?>
                                <img src="<?= $file_small["src"]; ?>" alt="">
                                <? $i++;
                            endforeach;
                            ?>

                        </div>
                    </div>
                </div>
            </div>
            <!-- End Thumnail Image -->
        </section>
    <? endif; ?>
<? endif; ?>

<? if (!empty($arResult["PROPERTIES"]["HD_DESC" . POSTFIX_PROPERTY]["VALUE"])): ?>
    <div class="policies-item">
        <p<?if($arResult["IBLOCK_ID"] == NEWS_IBLOCK_ID):?> itemprop="description"<?endif?>>
    <?= $arResult["DISPLAY_PROPERTIES"]["HD_DESC" . POSTFIX_PROPERTY]["DISPLAY_VALUE"] ?>
        </p>
    </div>
<? endif ?>
        <? if (!empty($arResult["PROPERTIES"]["DETAIL_TEXT" . POSTFIX_PROPERTY]["VALUE"])): ?>
    <div class="policies-item">
        <p<?if($arResult["IBLOCK_ID"] == NEWS_IBLOCK_ID):?> itemprop="description"<?endif?>>
    <?= $arResult["DISPLAY_PROPERTIES"]["DETAIL_TEXT" . POSTFIX_PROPERTY]["DISPLAY_VALUE"] ?>
        </p>
    </div>
<? endif ?>
<? if (!empty($arResult["PROPERTIES"]["YOUTUBE" . POSTFIX_PROPERTY]["VALUE"])): ?>
    <div class="policies-item">
        <h4><?= GetMessage("VIDEO") ?></h4>
        <iframe width="100%" height="460" src="https://www.youtube.com/embed/<?= $arResult["PROPERTIES"]["YOUTUBE" . POSTFIX_PROPERTY]["VALUE"] ?>" frameborder="0" allowfullscreen></iframe>
    </div>
<? endif;?>
<? if (!empty($arResult["PROPERTIES"]["VIMEO" . POSTFIX_PROPERTY]["VALUE"])): ?>
    <div class="policies-item">
        <h4><?= GetMessage("VIDEO") ?></h4>
        <iframe width="100%" height="460" src="https://player.vimeo.com/video/<?= $arResult["PROPERTIES"]["VIMEO" . POSTFIX_PROPERTY]["VALUE"] ?>" frameborder="0" allowfullscreen></iframe>
    </div>
<? endif;?>


    <? if (!empty($arResult["PROPERTIES"]["MAP"]["VALUE"])): ?>
    <div class="hotel-detail-map">
        <h4><?= GetMessage("MAP") ?></h4>
        <?
        $arLatLon = explode(",", $arResult["PROPERTIES"]["MAP"]["VALUE"]);
        $zoom = $arResult["PROPERTIES"]["MAP_SCALE"]["VALUE"];
        $arMapSettings = Array
            (
            "google_lat" => $arLatLon[0], //55.738299999994
            "google_lon" => $arLatLon[1], //37.5946
            "google_scale" => $zoom,
            "PLACEMARKS" => Array(
                Array
                    (
                    "LON" => $arLatLon[1],
                    "LAT" => $arLatLon[0],
                    "TEXT" => LANGUAGE_ID == "ru" ? $arResult['NAME' . POSTFIX_PROPERTY] : $arResult['NAME'],
                    "ICON" => MAP_MARKER_PATH
                )
            )
        );
        ?>
        <?
        $APPLICATION->IncludeComponent("bitrix:map.google.view", "on.detail.page", array(
            "INIT_MAP_TYPE" => "MAP",
            "MAP_WIDTH" => "100%",
            "MAP_HEIGHT" => "500",
            "MAP_DATA" => serialize($arMapSettings),
            "CONTROLS" => array(
                "SMALL_ZOOM_CONTROL",
                "TYPECONTROL",
                "SCALELINE"
            ),
            "OPTIONS" => array(
                "ENABLE_SCROLL_ZOOM",
                "ENABLE_DBLCLICK_ZOOM",
                "ENABLE_DRAGGING",
                "ENABLE_KEYBOARD"
            ),
            "API_KEY" => GOOGLE_API_KEY
                )
        );
        ?>
    </div>
	<br>
		<?
		if($arParams["VIEW_DETAIL_TOUR"]=="Y")
		{
			$GLOBALS['arrFilterTour']["PROPERTY_TOWN"] = $arResult["ID"];
			$APPLICATION->IncludeComponent(
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
					"DETAIL_URL" => "/tourism/cognitive-tourism/#ELEMENT_CODE#/",
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
			);
		}
		if($arParams["VIEW_DETAIL_SITES"]=="Y")
		{
			$GLOBALS['arrFilterSights']["PROPERTY_TOWN"] = $arResult["PROPERTIES"]["TOWN"]["VALUE"];
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
<? endif ?>

<script src="//yastatic.net/es5-shims/0.0.2/es5-shims.min.js"></script>
<script src="//yastatic.net/share2/share.js"></script>
<div class="ya-share2" data-services="vkontakte,facebook,odnoklassniki,gplus,twitter" data-counter="" 
						data-lang="<?if (LANGUAGE_ID=="by"){ echo "be";} else {echo LANGUAGE_ID; }?>" 
<?if (!empty($pre_photo_915)):?>
						data-image="https://vetliva.<?=LANGUAGE_ID?><?=$pre_photo_915?>"
<?elseif(!empty($file_big["src"])):?>
						data-image="https://vetliva.<?=LANGUAGE_ID?><?=$file_big["src"]?>"
<?endif;?>
>
</div>

<div style="clear: both;"></div>
