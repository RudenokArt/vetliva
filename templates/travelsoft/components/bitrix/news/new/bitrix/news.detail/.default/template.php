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

use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

$this->addExternalCss($templateFolder . "/snackbars.css");
//$this->addExternalCss(SITE_TEMPLATE_PATH . "/css/slider-prop.css");
$this->addExternalJs(SITE_TEMPLATE_PATH . "/js/slide.js");
$this->addExternalJs(SITE_TEMPLATE_PATH . "/js/jquery.sliderPro.min.js");



$this->addExternalCss(SITE_TEMPLATE_PATH . "/css/webui-popover/jquery.webui-popover.min.css");
$this->addExternalJs(SITE_TEMPLATE_PATH . "/js/webui-popover/jquery.webui-popover.min.js");

$this->addExternalCss(SITE_TEMPLATE_PATH . "/css/magnific-popup.css");
$this->addExternalJs(SITE_TEMPLATE_PATH . "/js/jquery.magnific-popup.js");
/* $arMenu = array();
  foreach ($arResult["DISPLAY_PROPERTIES"] as $menu){
  if(!empty($menu["DISPLAY_VALUE"])){
  $arMenu[$menu["ID"]] = array(
  "NAME" => $menu["NAME"],
  "ANCHOR" => "hotel_".mb_strtolower($menu["CODE"])
  );
  }
  } */
$p = $arResult["PROPERTIES"];
$scroll[] = array();
?><?
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
<? $this->SetViewTarget("head-detail"); ?>

<? if($arResult["IBLOCK_ID"] == 8 && $arResult["PROPERTIES"]["SCH_SHOW"]["VALUE"]): ?>
    <? $site_suff = (LANGUAGE_ID != "en")?LANGUAGE_ID:"com"; ?>
    <!-- Разметка schema для раздела санатории -->
    <div itemscope itemtype="http://schema.org/Product" style="display:none">
        <span itemprop="brand">VETLIVA – Санаторий</span>
        <span itemprop="name"><?=$arResult["NAME"];?></span>
        <meta itemprop="sku" content="<?=$arResult["PROPERTIES"]["SCH_SKU"]["VALUE"];?>" />
        <meta itemprop="mpn" content="<?=$arResult["PROPERTIES"]["SCH_MPN"]["VALUE"];?>" />
        <img loading="lazy"  itemprop="image" src="<?=$arResult["PROPERTIES"]["SCH_IMG_PATH"]["VALUE"];?>" />
        <span itemprop="description"><?=$arResult["DISPLAY_PROPERTIES"]["SCH_SHORT_DSCR"]["DISPLAY_VALUE"];?></span>
        <span itemprop="offers" itemscope itemtype="http://schema.org/Offer">
		<meta itemprop="priceValidUntil" content="<?=$arResult["PROPERTIES"]["SCH_PRICE_UNTIL"]["VALUE"]; ?>" />
		<meta itemprop="priceCurrency" content="BYN" />
        <span itemprop="price"><?=$arResult["PROPERTIES"]["SCH_PRICE"]["VALUE"];?></span>
        <link itemprop="availability" href="http://schema.org/PreOrder"/>
		<a href="<?="https://vetliva.".$site_suff."/tourism/health-tourism/".$arResult["CODE"]."/"."?booking[id][]=".$arResult["ID"];?>" itemprop="url"><?=$arResult["NAME"];?></a>
    </span>
        <div itemprop="review" itemscope itemtype="http://schema.org/Review">
        <span itemprop="reviewRating" itemscope itemtype="http://schema.org/Rating">        
            <meta itemprop="worstRating" content = "1">
            <meta itemprop="bestRating"  content = "5">
            <span itemprop="ratingValue"><?=$arResult["PROPERTIES"]["SCH_REVIEW_R"]["VALUE"];?></span>
        </span>
            <span itemprop="author" itemscope itemtype="http://schema.org/Person">
            <span itemprop="name"><?=$arResult["PROPERTIES"]["SCH_REVIEW_A"]["VALUE"];?></span>
        </span>
            <meta itemprop="datePublished" content="<?=$arResult["PROPERTIES"]["SCH_REVIEW_D"]["VALUE"];?>" />
            <div itemprop="reviewBody"><?=$arResult["PROPERTIES"]["SCH_REVIEW_T"]["VALUE"];?></div>
        </div>
        <div itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating">
            <span itemprop="ratingValue"><?=$arResult["PROPERTIES"]["SCH_RATING"]["VALUE"];?></span>
            <meta itemprop="bestRating" content="5"/>
            <meta itemprop="worstRating" content="1"/>
            <span itemprop="ratingCount"><?=$arResult["PROPERTIES"]["SCH_REVIEW_COUNT"]["VALUE"];?></span>
            <span itemprop="reviewCount"><?=$arResult["PROPERTIES"]["SCH_REVIEW_COUNT"]["VALUE"];?></span>
        </div>
    </div>
<? endif; ?>

<section class="head-detail">
    <div class="head-dt-cn">
        <div class="row">
            <div class="col-sm-7 col-md-7">
                <h1><? echo LANGUAGE_ID == "ru" ? $arResult["NAME"] : $arResult["PROPERTIES"]["NAME" . POSTFIX_PROPERTY]["VALUE"] ?> </h1>
				<? if($arResult["PROPERTIES"]["CAT_ID"]["VALUE"] != '4169' && !empty($arResult["PROPERTIES"]["CAT_ID"]["VALUE"])): ?>
                <div class="start-address">
                    <span class="star">
                        <? if ($arResult["PROPERTIES"]["CAT_ID"]["VALUE"] == '1491'): ?>
                            <i class="glyphicon glyphicon-star"></i>
                            <i class="glyphicon glyphicon-star"></i>
                            <i class="glyphicon glyphicon-star"></i>
                            <i class="glyphicon glyphicon-star"></i>
                            <i class="glyphicon glyphicon-star"></i>
                        <? elseif ($arResult["PROPERTIES"]["CAT_ID"]["VALUE"] == '1492'): ?>
                            <i class="glyphicon glyphicon-star"></i>
                            <i class="glyphicon glyphicon-star"></i>
                            <i class="glyphicon glyphicon-star"></i>
                            <i class="glyphicon glyphicon-star"></i>
                        <? elseif ($arResult["PROPERTIES"]["CAT_ID"]["VALUE"] == '1493'): ?>
                            <i class="glyphicon glyphicon-star"></i>
                            <i class="glyphicon glyphicon-star"></i>
                            <i class="glyphicon glyphicon-star"></i>
                        <? elseif ($arResult["PROPERTIES"]["CAT_ID"]["VALUE"] == '1494'): ?>
                            <i class="glyphicon glyphicon-star"></i>
                            <i class="glyphicon glyphicon-star"></i>
                        <? else: ?>
                            <? /* = substr2($arResult["DISPLAY_PROPERTIES"]["CAT_ID" . POSTFIX_PROPERTY]["DISPLAY_VALUE"]); */ ?>
                            <?
                            $cat = null;
                            if ($p["CAT_ID"]["VALUE"]) {
                                $p["CAT_ID"]["VALUE"] = (array) $p["CAT_ID"]["VALUE"];
                                $db_res_cat = CIBlockElement::GetList(false, array("ACTIVE" => "Y", "IBLOCK_ID" => $p["TRANSPORT"]["LINK_IBLOCK_ID"], "ID" => $p["CAT_ID"]["VALUE"]), false, false, array("ID", "NAME", "PROPERTY_NAME" . POSTFIX_PROPERTY));
                                $cat = null;
                                while ($res = $db_res_cat->Fetch()) {
                                    $cat[] = $res["PROPERTY_NAME" . POSTFIX_PROPERTY . "_VALUE"] ? $res["PROPERTY_NAME" . POSTFIX_PROPERTY . "_VALUE"] : $res["NAME"];
                                }
                            }
                            if ($cat):
                                ?>
                                <?= implode(", ", $cat) ?><br>
                            <? endif; ?>
                        <? endif; ?>
                    </span>
                </div>
			<? endif; ?>
            </div>
            <div class="col-sm-5 col-md-5 col-xs-12 text-right">     
			<div style="order: 1; margin-left: 10px">
                    <?= $GLOBALS["favorites_html"]?>
                </div>
                <div style="transform: translateY(1px);">
                    <?if($arParams["IBLOCK_ID"] == PLACEMENTS_IBLOCK_ID || $arParams["IBLOCK_ID"] == SANATORIUM_IBLOCK_ID):?>
                        <input name="toCompare" type="checkbox" value="<?=$arResult["ID"]?>" <?if(($arParams["IBLOCK_ID"] == PLACEMENTS_IBLOCK_ID && isset($_SESSION['toCompare']['placement']) && in_array($arResult["ID"],$_SESSION['toCompare']['placement'])) || ($arParams["IBLOCK_ID"] == SANATORIUM_IBLOCK_ID && isset($_SESSION['toCompare']['sanatorium']) && in_array($arResult["ID"],$_SESSION['toCompare']['sanatorium']))):?>checked<?endif?>> <?=GetMessage('TO_COMPARE')?>
                    <?endif?>
                </div>
            </div>
        </div>
    </div>
</section>

<?if(!empty($arResult["PROPERTIES"]["PICTURES"]["VALUE"])):?>
    <div class="detail-slide-wrap">
        <div id="<?=(count($arResult["PROPERTIES"]["PICTURES"]["VALUE"]) > 2) ? 'slider-line' : 'slider-line-left'?>" class="slider-pro slider-pro-fb">
            <div class="sp-slides">
                <?
                $i = 0;
                foreach ($arResult["PROPERTIES"]["PICTURES"]["VALUE"] as $item):
                    $file_small = CFile::ResizeImageGet($item, array('width' => 500, 'height' => 200), BX_RESIZE_IMAGE_PROPORTIONAL, true);
                    $file_big = CFile::ResizeImageGet($item, array('width' => 1920, 'height' => 920), BX_RESIZE_IMAGE_PROPORTIONAL, true, $arWaterMark);
                    $img_count++;
                    ?>
                    <div class="sp-slide">
                        <a href="<?= $file_big["src"]; ?>">
                            <img class="sp-image" src="<?= $file_small["src"]; ?>"
                                 data-src="<?= $file_small["src"]; ?>"
                                 data-retina="<?= $file_small["src"]; ?>"/>
                        </a>
                    </div>
                    <? $i++;
                endforeach;
                ?>
            </div>
        </div>
    </div>
<?endif;?>




<!-- Hotel Content One -->
<section class="hotel-contents check-rates detail-cn" id="hotel-content">
    <div class="row">
        <div class="col-lg-12 detail-sidebar">
            <address class="address">
                <? if (!empty($arResult["PROPERTIES"]["ADDRESS" . POSTFIX_PROPERTY]["VALUE"])):
                    $adress = '';
                    ?>

                    <? if (!empty($arResult["DISPLAY_PROPERTIES"]["TYPE"]["VALUE"]) && $arResult["IBLOCK_ID"] == PLATFORM_IBLOCK_ID): ?>
                    <br><b><?= GetMessage('TYPE') ?></b>
                    <?
                    if (LANGUAGE_ID != "ru") {
                        $prop = getIBElementProperties($arResult["PROPERTIES"]["TYPE"]["VALUE"][0]);
                        $type = $prop["NAME" . POSTFIX_PROPERTY]["VALUE"];
                        echo $type;
                    }
                    else {
                        echo substr2($arResult["DISPLAY_PROPERTIES"]["TYPE"]["DISPLAY_VALUE"]);
                    }
                    ?>
                <? endif; ?>
                    <br><b><?= GetMessage('ADDRESS') ?>:</b> <? $adress = substr2($arResult["PROPERTIES"]["ADDRESS" . POSTFIX_PROPERTY]["VALUE"], 200); ?>
                    <?
                    if (!empty($arResult["PROPERTIES"]["REGIONS"]["VALUE"])) {
                        $region = strip_tags($arResult["DISPLAY_PROPERTIES"]["REGIONS"]["DISPLAY_VALUE"]);
                        if (LANGUAGE_ID != "ru") {
                            $prop = getIBElementProperties($arResult["PROPERTIES"]["REGIONS"]["VALUE"]);
                            $region = $prop["NAME" . POSTFIX_PROPERTY]["VALUE"];
                        }
                    }
                    if (!empty($arResult["PROPERTIES"]["TOWN"]["VALUE"])) {
                        $town = strip_tags($arResult["DISPLAY_PROPERTIES"]["TOWN"]["DISPLAY_VALUE"]);
                        if (LANGUAGE_ID != "ru") {
                            $prop = getIBElementProperties($arResult["PROPERTIES"]["TOWN"]["VALUE"]);
                            $town = trim($prop["NAME" . POSTFIX_PROPERTY]["VALUE"]);
                        }
                    }
                    if (!empty($arResult["PROPERTIES"]["REGION"]["VALUE"])) {
                        $obl = strip_tags($arResult["DISPLAY_PROPERTIES"]["REGION"]["DISPLAY_VALUE"]);
                        if (LANGUAGE_ID != "ru") {
                            $prop = getIBElementProperties($arResult["PROPERTIES"]["REGION"]["VALUE"]);
                            $obl = $prop["NAME" . POSTFIX_PROPERTY]["VALUE"];
                        }
                    }
                    if (!empty($arResult["PROPERTIES"]["COUNTRY"]["VALUE"])) {
                        $country = strip_tags($arResult["DISPLAY_PROPERTIES"]["COUNTRY"]["DISPLAY_VALUE"]);
                        if (LANGUAGE_ID != "ru") {
                            $prop = getIBElementProperties($arResult["PROPERTIES"]["COUNTRY"]["VALUE"]);
                            $country = $prop["NAME" . POSTFIX_PROPERTY]["VALUE"];
                        }
                    }
                    if (!empty($arResult["PROPERTIES"]["CATEGORY"]["VALUE"])) {
                        $category = strip_tags($arResult["DISPLAY_PROPERTIES"]["CATEGORY"]["DISPLAY_VALUE"]);
                        if (LANGUAGE_ID != "ru") {
                            $prop = getIBElementProperties($arResult["PROPERTIES"]["CATEGORY"]["VALUE"]);
                            $category = $prop["NAME" . POSTFIX_PROPERTY]["VALUE"];
                        }
                    }
                    ?>

                    <? if ($town): ?><? $adress .= ", " . $town; ?><? endif; ?>
                    <? if ($region): ?><? $adress .= ", " . $region; ?><? endif; ?>
                    <? if ($obl): ?><? $adress .= ", " . $obl; ?><? endif; ?>
                    <? if ($country): ?><? $adress .= ", " . $country; ?><? endif; ?>
                    <? echo $adress; ?>

                    <?if($arResult['PROPERTIES']['MAP']['VALUE']):?>
                   
                    <div class="show-map__wrapper">
                        <a
                                href="javascript:;"
                                title="<?= GetMessage('T_PLACEMENT_LIST_SHOW_MAP') ?>"
                                class="show-map"
                                data-id="<?=$arResult['ID']?>"
                        ><?= GetMessage('T_SHOW_MAP') ?></a>
                    </div>
                <?endif?>

                    <? if (!empty($arResult["PROPERTIES"]["ADDRESSBY"]["VALUE"])): ?><br><b><?= GetMessage('ADDRESS_BY') ?></b> <?= substr2($arResult["PROPERTIES"]["ADDRESSBY"]["VALUE"]) ?><? endif; ?>
                    <? if (!empty($arResult["PROPERTIES"]["SITE"]["VALUE"])): ?>
                        <br><b><?= GetMessage('SITE') ?></b> <?= $arResult["PROPERTIES"]["SITE"]["VALUE"] ?><? endif; ?>
                    <? if(!empty($category)): ?> <b><?= GetMessage('CATEGORY') ?></b> <?=$category?> <? endif; ?>
                    <? if (!empty($arResult["PROPERTIES"]["TIME_FROM"]["VALUE"])): ?><br><b><?= GetMessage('TIME_FROM') ?></b> <?= $arResult["PROPERTIES"]["TIME_FROM"]["VALUE"] ?><? endif; ?>
                    <? if (!empty($arResult["PROPERTIES"]["TIME_TO"]["VALUE"])): ?><br><b><?= GetMessage('TIME_TO') ?></b> <?= $arResult["PROPERTIES"]["TIME_TO"]["VALUE"] ?><? endif; ?>
                    <? if (!empty($arResult["PROPERTIES"]["YEAR"]["VALUE"])): ?><br><b><?= GetMessage('YEAR') ?></b> <?= $arResult["PROPERTIES"]["YEAR"]["VALUE"] ?><? endif; ?>
                    <? if (!empty($arResult["PROPERTIES"]["YEAR_RE"]["VALUE"])): ?><br><b><?= GetMessage('YEAR_RE') ?></b> <?= $arResult["PROPERTIES"]["YEAR_RE"]["VALUE"] ?><? endif; ?>
                    <? if (!empty($arResult["PROPERTIES"]["DISTANCE_MINSK"]["VALUE"])): ?><br><b><?= GetMessage('DISTANCE_MINSK') ?></b> <?= substr2($arResult["PROPERTIES"]["DISTANCE_MINSK"]["VALUE"], 100); ?> km<? endif ?>
                    <? if (!empty($arResult["PROPERTIES"]["DISTANCE_CENTER"]["VALUE"])): ?><br><b><?= GetMessage('DISTANCE_CENTER') ?></b> <?= substr2($arResult["PROPERTIES"]["DISTANCE_CENTER"]["VALUE"], 100); ?> km<? endif ?>
                    <? if (!empty($arResult["PROPERTIES"]["DISTANCE_AIRPORT"]["VALUE"])): ?><br><b><?= GetMessage('DISTANCE_AIRPORT') ?></b> <?= substr2($arResult["PROPERTIES"]["DISTANCE_AIRPORT"]["VALUE"], 100); ?> km<? endif ?>
                    <? if (!empty($arResult["PROPERTIES"]["NEAREST_TOWN"]["VALUE"])): ?>
                    <? $nearest_town = strip_tags($arResult["DISPLAY_PROPERTIES"]["NEAREST_TOWN"]["DISPLAY_VALUE"]); ?>
                    <?
                    if (LANGUAGE_ID != "ru") {
                        $prop = getIBElementProperties($arResult["PROPERTIES"]["NEAREST_TOWN"]["VALUE"]);
                        $nearest_town = $prop["NAME" . POSTFIX_PROPERTY]["VALUE"];
                    }
                    ?>
                    <br><b><?= GetMessage('NEAREST_TOWN') ?></b> <?= $nearest_town ?><? if (!empty($arResult["PROPERTIES"]["NEAREST_TOWN_KM"]["VALUE"])): ?> (<?= $arResult["PROPERTIES"]["NEAREST_TOWN_KM"]["VALUE"] ?> km)<? endif ?>
                <? endif ?>
                <? endif; ?>



                <? if (!empty($arResult["DISPLAY_PROPERTIES"]["CAPACITY"]["VALUE"])): ?><br><b><?= GetMessage('CAPACITY') ?></b> <?= substr2($arResult["DISPLAY_PROPERTIES"]["CAPACITY"]["DISPLAY_VALUE"]) ?><? endif; ?>
            </address>
        </div>
        <a name="block_<?= mb_strtolower($arResult["PROPERTIES"]["HD_DESC" . POSTFIX_PROPERTY]["CODE"]) ?>"></a>
        <div class="col-lg-9 hl-customer-like">

            <? if (!empty($arResult["PROPERTIES"]["REF_NEW_YEAR"]["VALUE"])): ?>
                <div class="catalog_element element-desc">
					<a class="btn-for-popup" href="<?="https://vetliva.ru/tourism/tours-in-belarus/".$arResult["PROPERTIES"]["REF_NEW_YEAR"]["VALUE"]?>"><?=GetMessage("REF_NY")?></a><img src="/local/templates/travelsoft/components/bitrix/news/new/bitrix/news.detail/.default/gift-min.jpg" width="60px">
				</div>
            <? endif ?>
            <? if (!empty($arResult["PROPERTIES"]["HD_DESC" . POSTFIX_PROPERTY]["VALUE"])): ?>
                <div class="catalog_element element-desc"><div><?= $arResult["DISPLAY_PROPERTIES"]["HD_DESC" . POSTFIX_PROPERTY]["DISPLAY_VALUE"] ?></div>
					<div class="options-gradient"></div>
				</div>

				<div class="mobile-more-btn"><?=GetMessage("BUTTON_DETAIL")?></div>
            <? endif ?>

            <?
            if ($arResult["IBLOCK_ID"] == 8) {
                $med_profiles = null;
                if ($p["TYPE"]["VALUE"]) {
                    $p["TYPE"]["VALUE"] = (array) $p["TYPE"]["VALUE"];
                    $db_res_med_profiles = CIBlockElement::GetList(false, array("ACTIVE" => "Y", "IBLOCK_ID" => $p["TYPE"]["LINK_IBLOCK_ID"], "ID" => $p["TYPE"]["VALUE"]), false, false, array("ID", "NAME", "PROPERTY_NAME" . POSTFIX_PROPERTY));
                    $med_profiles = null;
                    while ($res = $db_res_med_profiles->Fetch()) {
                        $med_profiles[] = $res["PROPERTY_NAME" . POSTFIX_PROPERTY . "_VALUE"] ? $res["PROPERTY_NAME" . POSTFIX_PROPERTY . "_VALUE"] : $res["NAME"];
                    }
                }
                if ($med_profiles):
                    ?>
                    <div class="featured-service">
                        <h4><?= GetMessage("MED_PROFILES") ?></h4>
                        <ul class="service-accmd popover-ul">
                            <li><div><img loading="lazy"  src="/local/templates/travelsoft/images/icon-check.png" alt=""></div><?= implode("</li> <li><div><img src='/local/templates/travelsoft/images/icon-check.png' alt=''></div>", $med_profiles) ?></li>
                        </ul>
                    </div>
                <? endif; ?>
            <? } ?>
        </div>
    </div>

</section>

<? $this->EndViewTarget(); ?>

<? if (!empty($arResult["PROPERTIES"]["HD_DESCSERVICE" . POSTFIX_PROPERTY]["VALUE"])): ?>
    <section class="details-policies detail-cn catalog_element tab-pane fade" id="iblock_detail_actions">
        <div class="details-policies-cn">
            <? $scroll[] = array("iblock_detail_actions", $arResult["PROPERTIES"]["HD_DESCSERVICE" . POSTFIX_PROPERTY]["NAME"]); ?>
            <a name="iblock_detail_actions" id="iblock_detail_actions"></a>
            <div class="policies-item detail-ul">
                <h3><?= $arResult["PROPERTIES"]["HD_DESCSERVICE" . POSTFIX_PROPERTY]["NAME"] ?></h3>
                <?= $arResult["DISPLAY_PROPERTIES"]["HD_DESCSERVICE" . POSTFIX_PROPERTY]["DISPLAY_VALUE"] ?>
				<? if(!empty($arResult["PROPERTIES"]["NEW_YEAR_FILE"]["VALUE"])): ?>
				<ul>
					<li>
						<?=$arResult["DISPLAY_PROPERTIES"]["NEW_YEAR_FILE"]["FILE_VALUE"]["ORIGINAL_NAME"].": "?>
						<a href="<?=$arResult["DISPLAY_PROPERTIES"]["NEW_YEAR_FILE"]["FILE_VALUE"]["SRC"] ?>" target="_blank">
						<?=GetMessage("LABEL_LOAD")?>
						</a>
					</li>
				</ul>
				<? endif; ?>
            </div>
        </div>
		<div class="options-gradient"></div>
    </section>
	<div class="mobile-more-btn"><?=GetMessage("BUTTON_DETAIL")?></div>
<? endif ?>

<? if (!empty($arResult["PROPERTIES"]["FILE"]["VALUE"])): ?>
    <section class="details-policies detail-cn tab-pane fade" id="iblock_detail_medfiles">
        <div class="details-policies-cn">
		<? $scroll[] = array("iblock_detail_medfiles", GetMessage("MEDFILES_TITLE")); ?>
            <div class="policies-item detail-ul">
                <h3><?=GetMessage("MEDFILES_TITLE") ?></h3>
					<ul>
						<? if($arResult["DISPLAY_PROPERTIES"]["FILE"]["FILE_VALUE"][0]): ?>
							<? foreach($arResult["DISPLAY_PROPERTIES"]["FILE"]["FILE_VALUE"] as $file): ?>
							<li>
								<?=$file["ORIGINAL_NAME"].": "?>
								<a href="<?=$file["SRC"] ?>" target="_blank">
									<?=GetMessage("LABEL_LOAD")?>
								</a>
							</li>
							<? endforeach; ?>
						<? else: ?>
						<li>
							<?=$arResult["DISPLAY_PROPERTIES"]["FILE"]["FILE_VALUE"]["ORIGINAL_NAME"].": "?>
							<a href="<?=$arResult["DISPLAY_PROPERTIES"]["FILE"]["FILE_VALUE"]["SRC"] ?>" target="_blank">
								<?=GetMessage("LABEL_LOAD")?>
							</a>
						</li>
						<? endif; ?>
					</ul>
            </div>
        </div>
    </section>
<? endif ?>

			<? if (!empty($arResult["DISPLAY_PROPERTIES"]["PROMOTION_DISCOUNT" . POSTFIX_PROPERTY]["DISPLAY_VALUE"])): ?>
				<section class="details-policies detail-cn tab-pane fade" id="iblock_detail_discount">
					<div class="details-policies-cn">
						<? $scroll[] = array("iblock_detail_discount", GetMessage('PROMOTION_DISCOUNT')); ?>
						<a name="iblock_detail_discount" id="iblock_detail_discount"></a>
						<div class="policies-item detail-ul">
							<h3><?= $arResult["PROPERTIES"]["PROMOTION_DISCOUNT" . POSTFIX_PROPERTY]["NAME"] ?></h3>
							<?= $arResult["DISPLAY_PROPERTIES"]["PROMOTION_DISCOUNT" . POSTFIX_PROPERTY]["DISPLAY_VALUE"] ?>
						</div>
					</div>
				</section>
				
			<? endif ?>

<? if ($GLOBALS["PRICE_CALCULATION_RESULT_HTML"] && $arResult["IBLOCK_ID"] != PLATFORM_IBLOCK_ID): ?>
    <? $scroll[] = array("iblock_detail_prices", GetMessage('PRICES')); $key_active = count($scroll)-1; ?>
    <section class="hl-features tab-pane fade in active" id="iblock_detail_prices">
        <div class="hl-features-cn" style="padding-top: 0">
            <!-- Hotel Availability -->
            <?= $GLOBALS["PRICE_CALCULATION_RESULT_HTML"]; ?>
            <!-- Hotel Availability -->
        </div>
    </section>
<? endif ?>

<section class="hl-features detail-cn  tab-pane fade
<? if ($arParams["IBLOCK_ID"] == SANATORIUM_IBLOCK_ID || $arParams["IBLOCK_ID"] == PLACEMENTS_IBLOCK_ID): ?> sanatorium-element <?else:?>catalog_element   <? endif; ?>"
         id="iblock_detail_services">
    <!-- <div class="hl-features-cn"> -->
    <? if (!empty($arResult["DISPLAY_PROPERTIES"]["SERVICES"]["VALUE"])): ?>
        <? $scroll[] = array("iblock_detail_services", GetMessage('SERVICES')); ?>
        <div class="featured-service">
            <h3><?= GetMessage("SERVICES_TITLE") ?></h3>
			
		
		<? if ($arParams["IBLOCK_ID"] == SANATORIUM_IBLOCK_ID || $arParams["IBLOCK_ID"] == PLACEMENTS_IBLOCK_ID): ?>
			<section class="overall-services catalog_element">
		 <? endif; ?>
            <? foreach ($arResult["SERVICES_GROUP"] as $sectionId => $arServices): ?>
                <div class="list-service-section">
                    <? if ($arResult["SERVICES_SECTIONS"][$sectionId]["PICTURE"]["SRC"]): ?>
                        <div class="icon-service" style="float:left; top:2px"><img loading="lazy"  src="<?= $arResult["SERVICES_SECTIONS"][$sectionId]["PICTURE"]["SRC"] ?>"></div>
                    <? endif ?>
                    <h4><?= $arResult["SERVICES_SECTIONS"][$sectionId]["TITLE"] ?></h4>
                </div>
                <ul class="service-accmd popover-ul">
                    <? if ($arResult["SERVICES_SECTIONS"][$sectionId]["TITLE"] == "Домашние животные" | $arResult["SERVICES_SECTIONS"][$sectionId]["TITLE"] == "Хатнія жывёлы" | $arResult["SERVICES_SECTIONS"][$sectionId]["TITLE"] == "Pets"): ?>
                        <? foreach ($arServices as $serviceId => $arData): ?>
                            <? $paid_data_content = ''; ?>
                            <? if ($arData["PAID"]): ?>
                                <? if (isset($arResult["COST_SERVICES"]["ITEMS"]) && !empty($arResult["COST_SERVICES"]["ITEMS"][$serviceId])): ?>
                                    <?
                                    if (!empty($arResult["COST_SERVICES"]["ITEMS"][$serviceId]["PRICE"]) && !empty($arResult["COST_SERVICES"]["ITEMS"][$serviceId]["CURRENCY"])) {
                                        $paid_data_content .= GetMessage("COST_SERVICE") . " " . $arResult["COST_SERVICES"]["ITEMS"][$serviceId]["PRICE"] . " " . $arResult["COST_SERVICES"]["ITEMS"][$serviceId]["CURRENCY"] . ".<br>";
                                    }
                                    if (!empty($arResult["COST_SERVICES"]["ITEMS"][$serviceId]["DESCRIPTION"])) {
                                        $paid_data_content .= $arResult["COST_SERVICES"]["ITEMS"][$serviceId]["DESCRIPTION"];
                                    }
                                    ?>
                                <? endif ?>
                            <? endif ?>
                            <li><?= $arData["TITLE"] ?> <? if ($arData["PAID"]): ?><a data-content="<? if (!empty($paid_data_content)): ?><?= htmlspecialchars_decode($paid_data_content) ?><? else: ?><?= GetMessage('SERVICE_PAID') ?><? endif ?>">(<i class="fa fa-dollar"></i>)</a><? endif ?></li>
                        <? endforeach ?>
                    <? else: ?>
                        <? foreach ($arServices as $serviceId => $arData): ?>
                            <? $paid_data_content = ''; ?>
                            <? if (isset($arResult["COST_SERVICES"]["ITEMS"]) && !empty($arResult["COST_SERVICES"]["ITEMS"][$serviceId])): ?>
                                <?
                                if (!empty($arResult["COST_SERVICES"]["ITEMS"][$serviceId]["PRICE"]) && !empty($arResult["COST_SERVICES"]["ITEMS"][$serviceId]["CURRENCY"])) {
                                    $paid_data_content .= GetMessage("COST_SERVICE") . " " . $arResult["COST_SERVICES"]["ITEMS"][$serviceId]["PRICE"] . " " . $arResult["COST_SERVICES"]["ITEMS"][$serviceId]["CURRENCY"] . ".<br>";
                                }
                                if (!empty($arResult["COST_SERVICES"]["ITEMS"][$serviceId]["DESCRIPTION"])) {
                                    $paid_data_content .= $arResult["COST_SERVICES"]["ITEMS"][$serviceId]["DESCRIPTION"];
                                }
                                ?>
                            <? endif ?>
                            <li><div><img loading="lazy"  src="/local/templates/travelsoft/images/icon-check.png" alt=""></div><?= $arData["TITLE"] ?> <? if ($arData["PAID"]): ?><a data-content="<? if (!empty($paid_data_content)): ?><?= htmlspecialchars_decode($paid_data_content) ?><? else: ?><?= GetMessage('SERVICE_PAID') ?><? endif ?>">(<i class="fa fa-dollar"></i>)</a><? endif ?></li>
                        <? endforeach ?>
                    <? endif ?>
                </ul>
            <? endforeach; ?>
		<? if ($arParams["IBLOCK_ID"] == SANATORIUM_IBLOCK_ID || $arParams["IBLOCK_ID"] == PLACEMENTS_IBLOCK_ID): ?>
			<div class="options-gradient"></div>
			</section>
		<? endif; ?>
		<? if ($arParams["IBLOCK_ID"] == SANATORIUM_IBLOCK_ID || $arParams["IBLOCK_ID"] == PLACEMENTS_IBLOCK_ID): ?><div class="mobile-more-btn"><?=GetMessage("BUTTON_DETAIL")?></div><? endif; ?>
        </div>
	
    <? endif; ?>

    <!-- </div> -->
	<? if ($arParams["IBLOCK_ID"] == SANATORIUM_IBLOCK_ID || $arParams["IBLOCK_ID"] == PLACEMENTS_IBLOCK_ID): ?>
	
	<? else: ?>
		<div class="options-gradient"></div>
	<? endif; ?>
	
</section>

<? if (!empty($arResult["DISPLAY_PROPERTIES"]["MED_SERVICES"]["VALUE"])): ?>
    <section class="featured-service tab-pane fade" id="iblock_detail_med-services">
        <? $scroll[] = array("iblock_detail_med-services", GetMessage('MED_SERVICES_TITLE')); ?>
        <a name="iblock_detail_med-services" id="iblock_detail_med-services"></a>
        <h3><?= GetMessage("MED_SERVICES_TITLE") ?></h3>
        <section class="overall-med_services catalog_element">
            <? foreach ($arResult["MED_SERVICES_SECTIONS"] as $sectionId => $arData): ?>
                <div class="list-service-section">
                    <? if ($arData["PICTURE"]["SRC"]): ?>
                        <div class="icon-service" style="float:left; top:2px"><img loading="lazy"  src="<?= $arData["PICTURE"]["SRC"] ?>"></div>
                    <? endif ?>
                    <h4><?= $arData["TITLE"] ?></h4>
                </div>
                <ul class="service-accmd">
                    <? foreach ($arResult["MED_SERVICES_GROUP"][$sectionId] as $serviceId => $arDataServ): ?>
                        <li><div><img loading="lazy"  src="/local/templates/travelsoft/images/icon-check.png" alt=""></div>
                            <? if ($arParams["IBLOCK_ID"] == SANATORIUM_IBLOCK_ID && isset($arResult["DISPLAY_PROPERTIES"]["MED_SERVICES"]["DESC"]) && isset($arResult["DISPLAY_PROPERTIES"]["MED_SERVICES"]["DESC"][$serviceId]) && !empty($arResult["DISPLAY_PROPERTIES"]["MED_SERVICES"]["DESC"][$serviceId])): ?>
                                <a id="show-medservice-popup" data-id="<?= $serviceId ?>" href="#medservice-popup" class="medservice"><?= $arDataServ["TITLE"] ?></a>
                                <? if ($arResult["DISPLAY_PROPERTIES"]["MED_SERVICES"]["DESC"][$serviceId]["VIDEO"] == "Y"): ?>
                                    <a id="show-medservice-popup" data-video="<?= $serviceId ?>" href="#medservice-popup" class="medservice"><img loading="lazy"  src="<?= ICON_YOUTUBE_PATH ?>" alt="<?= GetMessage('WATCH_VIDEO') ?>" title="<?= GetMessage('WATCH_VIDEO') ?>"></a>
                                <? endif ?>
                            <? else: ?>
                                <?= $arDataServ["TITLE"] ?>
                            <? endif ?>
                            <? /* = $arDataServ["TITLE"] */ ?>
                            <? if ($arDataServ["PAID"]): ?><a data-content="<?= GetMessage('SERVICE_PAID') ?>">(<i class="fa fa-dollar"></i>)</a><? endif ?>
                        </li>
                    <? endforeach ?>
                </ul>
            <? endforeach; ?>
            <div class="options-gradient"></div>
        </section>

        <div class="mobile-more-btn"><?=GetMessage("BUTTON_DETAIL")?></div>
    </section>
<? endif; ?>





<? if (!empty($arResult["PROPERTIES"]["HD_DESCCHILD" . POSTFIX_PROPERTY]["VALUE"])): ?>
    <section class="featured-service tab-pane fade" id="iblock_detail_service-children">
        <? $scroll[] = array("iblock_detail_service-children", GetMessage('HD_DESCCHILD')); ?>
        <div class="policies-item detail-ul">
            <a name="iblock_detail_child-spoken" id="iblock_detail_service-children"></a>
            <h3><?= $arResult["PROPERTIES"]["HD_DESCCHILD" . POSTFIX_PROPERTY]["NAME"] ?></h3>
            <?= $arResult["DISPLAY_PROPERTIES"]["HD_DESCCHILD" . POSTFIX_PROPERTY]["DISPLAY_VALUE"] ?>
        </div>
    </section>
<? endif ?>



<? if (!empty($arResult["PROPERTIES"]["LANG2"]["VALUE"])): ?>
    <section class="featured-service tab-pane fade" id="iblock_detail_service-spoken">
        <? $scroll[] = array("iblock_detail_service-spoken", GetMessage('LANG')); ?>
        <a name="iblock_detail_service-spoken" id="iblock_detail_service-spoken"></a>
        <h3><?= GetMessage('LANG') ?></h3>
        <ul class="service-spoken">
            <li><img loading="lazy"  src="<?= SITE_TEMPLATE_PATH ?>/images/icon-check.png" alt="">
                <?
                $lang = null;
                if ($p["LANG2"]["VALUE"]) {
                    $p["LANG2"]["VALUE"] = (array) $p["LANG2"]["VALUE"];
                    $db_res_lang = CIBlockElement::GetList(false, array("ACTIVE" => "Y", "IBLOCK_ID" => $p["LANG2"]["LINK_IBLOCK_ID"], "ID" => $p["LANG2"]["VALUE"]), false, false, array("ID", "NAME", "PROPERTY_NAME" . POSTFIX_PROPERTY));
                    $lang = null;
                    while ($res = $db_res_lang->Fetch()) {
                        $lang[] = $res["PROPERTY_NAME" . POSTFIX_PROPERTY . "_VALUE"] ? $res["PROPERTY_NAME" . POSTFIX_PROPERTY . "_VALUE"] : $res["NAME"];
                    }
                }
                if ($lang):
                ?>
                <?= implode("</li><li><img src='/local/templates/travelsoft/images/icon-check.png' alt=''>", $lang) ?></li>
            <? endif; ?>
        </ul>
    </section>
<? elseif (!empty($arResult["PROPERTIES"]["LANG" . POSTFIX_PROPERTY]["VALUE"])): ?>

    <section class="featured-service tab-pane fade" id="iblock_detail_service-spoken">
        <? $scroll[] = array("iblock_detail_service-spoken", GetMessage('LANG')); ?>
        <a name="iblock_detail_service-spoken" id="iblock_detail_service-spoken"></a>
        <h3><?= GetMessage('LANG') ?></h3>
        <ul class="service-spoken">
            <? foreach ($arResult["PROPERTIES"]["LANG" . POSTFIX_PROPERTY]["VALUE"] as $lang): ?>
                <li><img loading="lazy"  src="<?= SITE_TEMPLATE_PATH ?>/images/icon-check.png" alt=""><?= $lang ?></li>
            <? endforeach ?>
        </ul>
    </section>
<? endif ?>




<div class="mobile-more-btn"><?=GetMessage("BUTTON_DETAIL")?></div>

<? if (!empty($arResult["PROPERTIES"]["HD_DESCMEAL" . POSTFIX_PROPERTY]["VALUE"])): ?>
    <section class="details-policies detail-cn catalog_element tab-pane fade" id="iblock_detail_descmeal">
        <div class="details-policies-cn">
            <? $scroll[] = array("iblock_detail_descmeal", GetMessage('HD_DESCMEAL')); ?>
            <a name="iblock_detail_descmeal" id="iblock_detail_descmeal"></a>
            <div class="policies-item detail-ul">
                <h3><?= $arResult["PROPERTIES"]["HD_DESCMEAL" . POSTFIX_PROPERTY]["NAME"] ?></h3>
                <?= $arResult["DISPLAY_PROPERTIES"]["HD_DESCMEAL" . POSTFIX_PROPERTY]["DISPLAY_VALUE"] ?>
            </div>
        </div>
		<div class="options-gradient"></div>
    </section>
	<div class="mobile-more-btn"><?=GetMessage("BUTTON_DETAIL")?></div>
<? endif ?>
<? if (!empty($arResult["PROPERTIES"]["HD_DESCFEE" . POSTFIX_PROPERTY]["VALUE"])): ?>
    <section class="details-policies detail-cn tab-pane fade" id="iblock_detail_descmeal">
        <div class="details-policies-cn">
            <? $scroll[] = array("iblock_detail_descmeal", GetMessage('HD_DESCFEE')); ?>
            <a name="iblock_detail_descmeal" id="iblock_detail_descmeal"></a>
            <div class="policies-item detail-ul">
                <h3><?= $arResult["PROPERTIES"]["HD_DESCFEE" . POSTFIX_PROPERTY]["NAME"] ?></h3>
                <?= $arResult["DISPLAY_PROPERTIES"]["HD_DESCFEE" . POSTFIX_PROPERTY]["DISPLAY_VALUE"] ?>
            </div>
        </div>
    </section>
<? endif ?>
<? if (!empty($arResult["PROPERTIES"]["HD_DESCSPORT" . POSTFIX_PROPERTY]["VALUE"])): ?>
    <section class="details-policies detail-cn catalog_element tab-pane fade" id="iblock_detail_descsport">
        <div class="details-policies-cn">
            <? $scroll[] = array("iblock_detail_descsport", GetMessage('HD_DESCSPORT')); ?>
            <a name="iblock_detail_descsport" id="iblock_detail_descsport"></a>
            <div class="policies-item detail-ul">
                <h3><?= $arResult["PROPERTIES"]["HD_DESCSPORT" . POSTFIX_PROPERTY]["NAME"] ?></h3>
                <?= $arResult["DISPLAY_PROPERTIES"]["HD_DESCSPORT" . POSTFIX_PROPERTY]["DISPLAY_VALUE"] ?>
            </div>
        </div>
		<div class="options-gradient"></div>
    </section>
	<div class="mobile-more-btn"><?=GetMessage("BUTTON_DETAIL")?></div>
<? endif ?>
<? if (!empty($arResult["PROPERTIES"]["HD_ADDINFORMATION" . POSTFIX_PROPERTY]["VALUE"])): ?>
    <section class="details-policies detail-cn catalog_element tab-pane fade" id="iblock_detail_add-information">
        <div class="details-policies-cn">
		   <?if(CSite::InDir('/tourism/health-tourism/')):?><? $scroll[] = array("iblock_detail_add-information", $arResult["PROPERTIES"]["HD_ADDINFORMATION" . POSTFIX_PROPERTY]["NAME"]); ?><?endif;?> 
            <a name="iblock_detail_add-information" id="iblock_detail_add-information"></a>
            <div class="policies-item detail-ul">
                <h3><?= $arResult["PROPERTIES"]["HD_ADDINFORMATION" . POSTFIX_PROPERTY]["NAME"] ?></h3>
                <?= $arResult["DISPLAY_PROPERTIES"]["HD_ADDINFORMATION" . POSTFIX_PROPERTY]["DISPLAY_VALUE"] ?>
            </div>
        </div>
		<div class="options-gradient"></div>
    </section>
	<div class="mobile-more-btn"><?=GetMessage("BUTTON_DETAIL")?></div>
<? endif ?>
<? if (!empty($arResult["PROPERTIES"]["RESORT_FEE" . POSTFIX_PROPERTY]["VALUE"])): ?>
    <section class="details-policies detail-cn tab-pane fade" id="iblock_detail_resort_free">
        <div class="details-policies-cn">
            <? $scroll[] = array("iblock_detail_resort_free", GetMessage('RESORT_FEE')); ?>
            <a name="iblock_detail_resort_free" id="iblock_detail_resort_free"></a>
            <div class="policies-item detail-ul">
                <h3><?= GetMessage('RESORT_FEE') ?></h3>
                <?= $arResult["DISPLAY_PROPERTIES"]["RESORT_FEE" . POSTFIX_PROPERTY]["DISPLAY_VALUE"] ?>
            </div>
        </div>
    </section>
<? endif ?>
<? if (!empty($arResult["PROPERTIES"]["CANCELLATION_POLICY" . POSTFIX_PROPERTY]["VALUE"])): ?>
    <section class="details-policies detail-cn tab-pane fade" id="iblock_detail_cancellation_policy">
        <div class="details-policies-cn">
            <? $scroll[] = array("iblock_detail_cancellation_policy", GetMessage('CANCELLATION_POLICY')); ?>
            <a name="iblock_detail_cancellation_policy" id="iblock_detail_cancellation_policy"></a>
            <div class="policies-item detail-ul">
                <h3><?= GetMessage('CANCELLATION_POLICY') ?></h3>
                <?= $arResult["DISPLAY_PROPERTIES"]["CANCELLATION_POLICY" . POSTFIX_PROPERTY]["DISPLAY_VALUE"] ?>
            </div>
        </div>
		
    </section>
	
<? endif ?>
<? if (!empty($arResult["PROPERTIES"]["YOUTUBE" . POSTFIX_PROPERTY]["VALUE"]) || !empty($arResult["PROPERTIES"]["VIMEO" . POSTFIX_PROPERTY]["VALUE"])): ?>
    <section class="details-policies detail-cn tab-pane fade" id="iblock_detail_youtube">
        <div class="details-policies-cn">
            <? $scroll[] = array("iblock_detail_youtube", GetMessage('YOUTUBE')); ?>
            <a name="iblock_detail_youtube" id="iblock_detail_youtube"></a>
            <div class="policies-item">
                <h3><?= GetMessage('VIDEO') ?></h3>
                <? if (!empty($arResult["PROPERTIES"]["YOUTUBE" . POSTFIX_PROPERTY]["VALUE"])): ?>
                    <div class="video-block">
                        <link rel="preload" href="https://player.vimeo.com/video/<?= $arResult["PROPERTIES"]["YOUTUBE" . POSTFIX_PROPERTY]["VALUE"] ?>" as="document">
                        <iframe width="100%" style="border: none;" src="https://www.youtube.com/embed/<?= $arResult["PROPERTIES"]["YOUTUBE" . POSTFIX_PROPERTY]["VALUE"] ?>" allowfullscreen=""></iframe>
                    </div>
                <? endif ?>
                <? if (!empty($arResult["PROPERTIES"]["VIMEO" . POSTFIX_PROPERTY]["VALUE"])): ?>
                    <div class="video-block">
                        <link rel="preload" href="https://player.vimeo.com/video/<?= $arResult["PROPERTIES"]["VIMEO" . POSTFIX_PROPERTY]["VALUE"] ?>" as="document">
                        <iframe width="100%" style="border: none;" src="https://player.vimeo.com/video/<?= $arResult["PROPERTIES"]["VIMEO" . POSTFIX_PROPERTY]["VALUE"] ?>" allowfullscreen="" frameborder="0" webkitallowfullscreen mozallowfullscreen></iframe>
                    </div>
                <? endif ?>
            </div>
            <!-- End Details Policies Item -->
        </div>
    </section>
<? endif ?>
<?php// if(!empty($arResult["PROPERTIES"]["PROEZD" . POSTFIX_PROPERTY]["VALUE"])):?>
    <!-- Проверка юзер агента для адаптации дизайна кнопки под моб. версию  -->
    <?
    function isMobile() {
        return preg_match("/(android|avantgo|Mobile|Phone|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
    }
    ?>
    <? if(isMobile()): ?>
        <script>
          $(document).ready(function () {
            $('#before_btn_transfer').attr('style', 'width:100%;padding-bottom:1.1%;padding-top:1%;color:#1E3C6E;background-color:#e5f4fa');
            $('.btnTransferChng').attr('style', 'background-color:#e5f4fa;');
            $('.btnTransferChng').append('<br />');
            $('#btn_transfer').attr('width', '50%');
            $('#btn_transfer').wrap('<center></center>');
          });
        </script>
    <? endif; ?>
    <section class="about-area details-policies detail-cn tab-pane fade" id="iblock_detail_proezd">
        <div class="details-policies-cn">
            <? $scroll[] = array("iblock_detail_proezd", $arResult["PROPERTIES"]["PROEZD" . POSTFIX_PROPERTY]["NAME"]); ?>
            <div class="about-area-text" style="margin-top:1.5%">
                <h3>
                    <?= $arResult["PROPERTIES"]["PROEZD" . POSTFIX_PROPERTY]["NAME"] ?>
                </h3>
                <?if(!empty($arResult["PROPERTIES"]["PROEZDCAR" . POSTFIX_PROPERTY]["VALUE"]) || !empty($arResult["PROPERTIES"]["PROEZDPUBLIC" . POSTFIX_PROPERTY]["VALUE"])):?>
                    <?if(!empty($arResult["PROPERTIES"]["PROEZDCAR" . POSTFIX_PROPERTY]["VALUE"])):?>
                    <div class="getting_transfer">
                        <div class="subtitle">
                            <img src="<?=SITE_TEMPLATE_PATH ?>/images/icon/sedan-car.svg"><span><?=GetMessage("GETTING_CAR");?></span>
                        </div>
                        <div class="textblock">
                            <?=$arResult["PROPERTIES"]["PROEZDCAR" . POSTFIX_PROPERTY]["~VALUE"]['TEXT']?>
                        </div>
                    </div>
                    <?endif;?>
                    <?if(!empty($arResult["PROPERTIES"]["PROEZDPUBLIC" . POSTFIX_PROPERTY]["VALUE"])):?>
                    <div class="getting_transfer">
                        <div class="subtitle">
							<img src="<?=SITE_TEMPLATE_PATH ?>/images/icon/bus.svg"><span><?=GetMessage("GETTING_PUBLIC");?></span>
                        </div>
                        <div class="textblock">
                            <?=$arResult["PROPERTIES"]["PROEZDPUBLIC" . POSTFIX_PROPERTY]["~VALUE"]['TEXT']?>
                        </div>
                    </div>
                    <?endif;?>
                <?elseif (!empty($arResult["PROPERTIES"]["PROEZD" . POSTFIX_PROPERTY]["VALUE"])):?>
                    <?/*<div id='before_btn_transfer' style="width:75%;float:left;padding-bottom:1.1%;padding-top:1%;color:#1E3C6E;background-color:#e5f4fa">
                        <b><?=GetMessage("TEXT_BEFORE_BTN");?></b>
                    </div>
                    <div class="btnTransferChng">
                        <a href='../../../bitrix/click.php?event1=btn_transfer&amp;event2=click&amp;goto=../../../tourism/transfer/' target="_blank">
                            <img loading="lazy"  id='btn_transfer' src="<?=SITE_TEMPLATE_PATH.'/images/transfer_icon_btn_short'.POSTFIX_PROPERTY.'.png'?>" />
                        </a>
                    </div>*/?>
                    <p style="margin-top:6%">
                        <?= $arResult["DISPLAY_PROPERTIES"]["PROEZD" . POSTFIX_PROPERTY]["DISPLAY_VALUE"] ?>
                    </p>
                <?endif;?>
                <div class="getting_transfer">
                    <div class="subtitle">
						<img src="<?=SITE_TEMPLATE_PATH ?>/images/icon/destination.svg"><span><?=GetMessage("TEXT_BEFORE_BTN");?></span>
                    </div>
                    <div class="textblock btnTransferblock">
                        <div class="btnTransferChng">
                            <a href='../../../bitrix/click.php?event1=btn_transfer&amp;event2=click&amp;goto=../../../tourism/transfer/' target="_blank">
                                <?=GetMessage("GETTING_TRANSFER")?>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php //endif?>
<?/* if (!empty($arResult["PROPERTIES"]["MAP"]["VALUE"]) || !empty($arResult["PROPERTIES"]["PROEZD" . POSTFIX_PROPERTY]["VALUE"])): ?>
    <section class="about-area details-policies detail-cn" id="iblock_detail_map">
        <div class="details-policies-cn">
            <? if (!empty($arResult["PROPERTIES"]["MAP"]["VALUE"])): ?>
                <? $scroll[] = array("iblock_detail_map", GetMessage('MAP')); ?>
                <a name="iblock_detail_map"></a>
                <div class="hotel-detail-map">
                    <h3><?= GetMessage('MAP_DETAIL_TITLE') ?></h3>
                    <div style="width: 100%; height: 400px" id="placement_location_map"></div>
                    <?
                    $arLatLon = explode(",", $arResult["PROPERTIES"]["MAP"]["VALUE"]);
                    $this->addExternalJs(SITE_TEMPLATE_PATH . "/js/MapAdapter/MapAdapter.js");
                    ?>
                    <script>
                        $(document).ready(function () {
                            var mapAdapter = new MapAdapter({
                                map_id: "placement_location_map",
                                center: {
                                    lat: 53.53,
                                    lng: 27.34
                                },
                                object: "ymaps",
                                zoom: 14
                            });
                            mapAdapter.addMarker({
                                lat: <?= $arLatLon[0]?>,
                                lng: <?= $arLatLon[1]?>,
                                icon: "<?= MAP_MARKER_PATH?>",
                                title: "<?= $arResult['NAME']?>",
                                content: "<span style='color: #264B87'><?= $arResult['NAME']?></span>"
                            });
                        });

                    </script>
                    <p class="about-area-location"><i class="fa fa-map-marker"></i><?= $arResult["PROPERTIES"]["ADDRESS" . POSTFIX_PROPERTY]["VALUE"] ?></p>
                </div>
            <? endif ?>
			<!-- Проверка юзер агента для адаптации дизайна кнопки под моб. версию  -->
			<?
			function isMobile() {
				return preg_match("/(android|avantgo|Mobile|Phone|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
			}
			?>
			<? if(isMobile()): ?>
			<script>
			$(document).ready(function () {
				$('#before_btn_transfer').attr('style', 'width:100%;padding-bottom:1.1%;padding-top:1%;color:#1E3C6E;background-color:#e5f4fa');
				$('.btnTransferChng').attr('style', 'background-color:#e5f4fa;');
				$('.btnTransferChng').append('<br />');
				$('#btn_transfer').attr('width', '50%');
				$('#btn_transfer').wrap('<center></center>');
			});
			</script>
			<? else: ?>
			<!-- стили для плавноого изменения цвета кнопки -->
			<style>
				.btnTransferChng{
					width:25%;
					float:left;
					background-color:#e5f4fa
				}
				.btnTransferChng:hover {
					background: #1E3c6E;
					position: relative;
					transition: 0.5s;
				}
			</style>
			<? endif; ?>
			<? if (!empty($arResult["PROPERTIES"]["PROEZD" . POSTFIX_PROPERTY]["VALUE"])): ?>
					<? $scroll[] = array("iblock_detail_proezd", $arResult["PROPERTIES"]["PROEZD" . POSTFIX_PROPERTY]["NAME"]); ?>
						<a name="iblock_detail_proezd"></a>
							<div class="about-area-text" style="margin-top:1.5%">
									<h3>
										<?= $arResult["PROPERTIES"]["PROEZD" . POSTFIX_PROPERTY]["NAME"] ?>
									</h3>
								<div id='before_btn_transfer' style="width:75%;float:left;padding-bottom:1.1%;padding-top:1%;color:#1E3C6E;background-color:#e5f4fa">
									<b><?=GetMessage("TEXT_BEFORE_BTN");?></b>
								</div>
								<div class="btnTransferChng">
									<a href='../../../bitrix/click.php?event1=btn_transfer&amp;event2=click&amp;goto=../../../tourism/transfer/' target="_blank">
										<img loading="lazy"  id='btn_transfer' src="<?=SITE_TEMPLATE_PATH.'/images/transfer_icon_btn_short'.POSTFIX_PROPERTY.'.png'?>" />
									</a>
								</div>
								<p style="margin-top:6%">
					<?= $arResult["DISPLAY_PROPERTIES"]["PROEZD" . POSTFIX_PROPERTY]["DISPLAY_VALUE"] ?>
								</p>
							</div>
			<? endif ?>

        </div>
    </section>
<? endif */?>

<? if ($arParams["IBLOCK_ID"] == SANATORIUM_IBLOCK_ID || $arParams["IBLOCK_ID"] == PLACEMENTS_IBLOCK_ID): ?>
    <div id="medservice-popup" class="show-medservice-form mfp-hide">
    </div>
<? endif ?>


<!--
                    <section class="detail-footer detail-cn">
                        <div class="row">
                            <div class="col-lg-3"></div>
                            <div class="col-lg-9">
                                <div class="row">
                                    <div class="col-xs-5 ">
                                        <div class="review-more">
                                            <a href="" title=""><i class="icon"></i> Показать все отзывы</a>
                                        </div>
                                    </div>
                                    <div class="col-xs-7 text-right">
                                        <p class="price-book">
                                            От <span>345</span> BYN / ночь
                                            <a href="#block_prices" title="" class="awe-btn awe-btn-1 awe-btn-lager">Бронировать</a>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
-->


<? $this->SetViewTarget("menu-item-detail"); ?>
<? if (!empty($scroll)): ?>

    <? foreach ($scroll as $key => $s): ?>
        <? if (!empty($s)): ?>
            <li class="<?=($key == $key_active) ? 'active' : ''?>"><a data-toggle="tab" href="#<?= $s[0] ?>"><?= $s[1] ?></a></li>
        <? endif ?>
    <? endforeach ?>

<? endif ?>
<? $this->EndViewTarget(); ?>
<!-- snekbar -->


<?if($arParams["IBLOCK_ID"] == PLACEMENTS_IBLOCK_ID):?>
    <button class="snackbars" id="placementCompare" <?if(isset($_SESSION['toCompare']['placement']) && !empty($_SESSION['toCompare']['placement'])):?>style="display: block"<?else:?>style="display: none"<?endif?>>
        <a href="<?=$arParams["SEF_FOLDER"]?>compare/"><span class="snackbars_text" id="toCompareText">
                     <?=num2word(count($_SESSION['toCompare']['placement']),array(GetMessage('ONE_COMPARE'),GetMessage('TWO_COMPARE'),GetMessage('TEN_COMPARE')))?> <?=GetMessage('TEXT_COMPARE')?>
                </span>
        </a>
        <span class="snackbars_icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/><path d="M0 0h24v24H0z" fill="none"/></svg>
                </span>
    </button>
<?elseif($arParams["IBLOCK_ID"] == SANATORIUM_IBLOCK_ID):?>
    <button class="snackbars" id="sanatoriumCompare" <?if(isset($_SESSION['toCompare']['sanatorium']) && !empty($_SESSION['toCompare']['sanatorium'])):?>style="display: block"<?else:?>style="display: none"<?endif?>>
        <a href="<?=$arParams["SEF_FOLDER"]?>compare/"><span class="snackbars_text" id="toCompareText">
                 <?=num2word(count($_SESSION['toCompare']['sanatorium']),array(GetMessage('ONE_COMPARE'),GetMessage('TWO_COMPARE'),GetMessage('TEN_COMPARE')))?> <?=GetMessage('TEXT_COMPARE')?>
                </span></a>
        <span class="snackbars_icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/><path d="M0 0h24v24H0z" fill="none"/></svg>
            </span>
    </button>
<?endif?>


<?$type_object = $arParams["IBLOCK_ID"] == PLACEMENTS_IBLOCK_ID ? "placement" : ($arParams["IBLOCK_ID"] == SANATORIUM_IBLOCK_ID ? "sanatorium" : '');?>
<script>
  (function () {
    function initPopover() {
      $('.service-accmd.popover-ul a').webuiPopover({
        placement: "right",
        trigger: "hover"
      });
    }
    initPopover();

  })();
</script>

<? /* if(($arParams["IBLOCK_ID"] == SANATORIUM_IBLOCK_ID || $arParams["IBLOCK_ID"] == PLACEMENTS_IBLOCK_ID) && isset($arResult["DISPLAY_PROPERTIES"]["MED_SERVICES"]["DESC"]) && !empty($arResult["DISPLAY_PROPERTIES"]["MED_SERVICES"]["DESC"])): */ ?>
<? if ($arParams["IBLOCK_ID"] == SANATORIUM_IBLOCK_ID || $arParams["IBLOCK_ID"] == PLACEMENTS_IBLOCK_ID): ?>
    <script>
      function num2word(num, words, show_num = true) {

        var num_text = '';
        num = num % 100;
        if (num > 19) {
          num = num % 10;
        }

        if(show_num){
          num_text = num+" ";
        }
        else{
          num_text = "";
        }
        switch (num) {
          case 1: {
            return num_text+words[0];
          }
          case 2: case 3: case 4: {
            return num_text+words[1];
          }
          default: {
            return num_text+words[2];
          }
        }

      }

      (function ($) {

        $("#show-medservice-popup").magnificPopup({
          type: "inline",
          midClick: true
        });

       <?/* var medServicesAr = <?= \Bitrix\Main\Web\Json::encode($arResult["DISPLAY_PROPERTIES"]["MED_SERVICES"]["DESC"]) ?>;*/?>
        var medServicesVideoAr = <?= \Bitrix\Main\Web\Json::encode($arResult["MED_SERVICES_VIDEO"]) ?>;

        var medServiceHtml = '<div class="row form bg-none pad20"><h3 class="bx-title">#medservice_title#</h3><div class="col-md-12">#medservice_content#</div></div>';
        var medServiceHtml_ = '';

        $("#show-medservice-popup.medservice").on("click", function () {

          var medServiceId = $(this).data('id');

          if (typeof medServiceId !== "undefined") {
            
            $.ajax({
                type: "post",
                url: '<?=$templateFolder?>/ajax.php',
                dataType: 'json',
                data: {service_med_id: medServiceId},
                success: function (data) {
                  medServiceHtml_ = medServiceHtml.replace("#medservice_title#", data.NAME);
                  medServiceHtml_ = medServiceHtml_.replace("#medservice_content#", data.DESCRIPTION);
                  $("#medservice-popup").html(medServiceHtml_);
                  $("#show-medservice-popup").magnificPopup("open");
                }
              });

            } else {

            var medServiceVideoId = $(this).data('video');
            var htmlVideo = '<div class="video-block"><iframe width="100%" style="border: none;" src="https://www.youtube.com/embed/' + medServicesVideoAr[medServiceVideoId]["YOUTUBE_CODE"] + '" allowfullscreen=""></iframe></div>';

            medServiceHtml_ = medServiceHtml.replace("#medservice_title#", '<?= GetMessage('VIDEO') ?>: ' + medServicesVideoAr[medServiceVideoId]["NAME"]);
            medServiceHtml_ = medServiceHtml_.replace("#medservice_content#", htmlVideo);
            $("#medservice-popup").html(medServiceHtml_);
            $("#show-medservice-popup").magnificPopup("open");
          }
        });

        var type_object = '<?=$type_object?>';
        var mess_one = "<?=GetMessage('ONE_COMPARE')?>";
        var mess_two = "<?=GetMessage('TWO_COMPARE')?>";
        var mess_ten = "<?=GetMessage('TEN_COMPARE')?>";
        var mess_compare = " <?=GetMessage('TEXT_COMPARE')?>";

        $('input[name="toCompare"]').on("change", function () {

          if(type_object) {
            var toCompareVal = $(this).val();
            if (!$(this).prop("checked")) {
              //отправляем удаление из сравнения
              $.ajax({
                type: "post",
                url: '<?=$templateFolder?>/ajax_compare.php',
                dataType: 'json',
                data: {id: toCompareVal, actionCompare: "delete", typeCompare: type_object},
                success: function (data) {
                  if (data.result[type_object] == 0) {
                    $('#' + type_object + 'Compare').css("display", "none");
                  } else {
                    $('#' + type_object + 'Compare').css("display", "block").find('#toCompareText').text(num2word(data.result[type_object], [mess_one, mess_two, mess_ten])+mess_compare);
                  }
                }
              });
            } else {
              //добавляем в список сравнений
              $.ajax({
                type: "post",
                url: '<?=$templateFolder?>/ajax_compare.php',
                dataType: 'json',
                data: {id: toCompareVal, actionCompare: "add", typeCompare: type_object},
                success: function (data) {
                  $('#' + type_object + 'Compare').css("display", "block").find('#toCompareText').text(num2word(data.result[type_object], [mess_one, mess_two, mess_ten])+mess_compare);
                }
              });
            }
          }

        });

      })(jQuery);
    </script>
<?
endif?>