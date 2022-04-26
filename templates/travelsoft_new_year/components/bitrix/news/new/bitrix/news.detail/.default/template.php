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

$this->addExternalJs(SITE_TEMPLATE_PATH . "/js/slide.js");
$this->addExternalCss(SITE_TEMPLATE_PATH . "/css/webui-popover/jquery.webui-popover.min.css");
$this->addExternalJs(SITE_TEMPLATE_PATH . "/js/webui-popover/jquery.webui-popover.min.js");
/* $arMenu = array();
  foreach ($arResult["DISPLAY_PROPERTIES"] as $menu){
  if(!empty($menu["DISPLAY_VALUE"])){
  $arMenu[$menu["ID"]] = array(
  "NAME" => $menu["NAME"],
  "ANCHOR" => "hotel_".mb_strtolower($menu["CODE"])
  );
  }
  } */
$p=$arResult["PROPERTIES"];
$scroll[] = array();
?>
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
<?$this->SetViewTarget("head-detail");?>
<section class="head-detail">
    <div class="head-dt-cn">
        <div class="row">
            <div class="col-sm-7">
                <h1><? echo LANGUAGE_ID == "ru" ? $arResult["NAME"] : $arResult["PROPERTIES"]["NAME" . POSTFIX_PROPERTY]["VALUE"] ?> </h1>
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
						<?/*= substr2($arResult["DISPLAY_PROPERTIES"]["CAT_ID" . POSTFIX_PROPERTY]["DISPLAY_VALUE"]); */?>
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
            </div>
            <div class="col-sm-5 text-right">
                <p class="price-book">
                    <? /* if ($arResult["booking_price_result"]) :
                      \Bitrix\Main\Loader::includeModule("travelsoft.currency");?>
                      <?=GetMessage('FROM')?> <span><?
                      $currency = \travelsoft\Currency::getInstance();
                      $current_rate = current($arResult["booking_price_result"][0]["RATE"]);
                      echo $currency->convertCurrency($current_rate["PRICE"], $current_rate["PRICE_CURRENCY_ID"]);
                      ?></span>
                      <?endif */ ?>
                </p>
            </div>
        </div>
    </div>
</section>
<?
if (count($arResult["PROPERTIES"]["PICTURES"]["VALUE"]) == 1):
    $an_file = CFile::ResizeImageGet($arResult["PROPERTIES"]["PICTURES"]["VALUE"][0], array('width' => 1170, 'height' => 641), BX_RESIZE_IMAGE_EXACT, true);
    $pre_photo = $an_file["src"];
    ?>
    <img src="<?= $pre_photo ?>" alt="<?= $arResult["PROPERTIES"]["PICTURES"]["DESCRIPTION"][0] ?>" class="img-responsive">
<? elseif (count($arResult["PROPERTIES"]["PICTURES"]["VALUE"]) > 1): ?>
    <section class="detail-slider">
        <div class="slide-room-lg">
            <div id="slide-room-lg">
                <?$i=0;
                foreach ($arResult["PROPERTIES"]["PICTURES"]["VALUE"] as $item):
                    $file_big = CFile::ResizeImageGet($item, Array('width' => 1170, 'height' => 640), BX_RESIZE_IMAGE_EXACT, true, $arWaterMark);
                    $img_count++;
                    ?>
                    <img src="<?= $file_big["src"]; ?>"  alt="<?= $arResult["PROPERTIES"]["PICTURES"]["DESCRIPTION"][$i] ?>">
        <? $i++;
    endforeach; ?>
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
    endforeach; ?>

                    </div>
                </div>
            </div>
        </div>
        <!-- End Thumnail Image -->
    </section>
<? endif; ?>
<!-- Hotel Content One -->
<section class="hotel-content check-rates detail-cn" id="hotel-content">
    <div class="row">                        
        <div class="col-lg-3 detail-sidebar">
            <div class="hight-light">
                <div class="row">
                    <div class="scroll-heading col-xs-12 col-sm-12 col-md-6 col-lg-12">
                        <? if (!empty($arResult["PROPERTIES"]["ADDRESS" . POSTFIX_PROPERTY]["VALUE"])): 
							$adress = '';?>
                            <address class="address">
								<b><?= GetMessage('ADDRESS') ?>:</b> <?$adress = substr2($arResult["PROPERTIES"]["ADDRESS".POSTFIX_PROPERTY]["VALUE"], 200);?>
								  <?if (!empty($arResult["PROPERTIES"]["REGIONS"]["VALUE"])) {
									 $region = strip_tags($arResult["DISPLAY_PROPERTIES"]["REGIONS"]["DISPLAY_VALUE"]);
									 if (LANGUAGE_ID != "ru") {
										$prop = getIBElementProperties($arResult["PROPERTIES"]["REGIONS"]["VALUE"]);
										$region = $prop["NAME".POSTFIX_PROPERTY]["VALUE"];
									 }
									 }
									 if (!empty($arResult["PROPERTIES"]["TOWN"]["VALUE"])) {
									 $town = strip_tags($arResult["DISPLAY_PROPERTIES"]["TOWN"]["DISPLAY_VALUE"]);
									 if (LANGUAGE_ID != "ru") {
										$prop = getIBElementProperties($arResult["PROPERTIES"]["TOWN"]["VALUE"]);
										$town = trim($prop["NAME".POSTFIX_PROPERTY]["VALUE"]);
									 }
									 }
									 if (!empty($arResult["PROPERTIES"]["COUNTRY"]["VALUE"])) {
									 $country = strip_tags($arResult["DISPLAY_PROPERTIES"]["COUNTRY"]["DISPLAY_VALUE"]);
									 if (LANGUAGE_ID != "ru") {
										$prop = getIBElementProperties($arResult["PROPERTIES"]["COUNTRY"]["VALUE"]);
										$country = $prop["NAME".POSTFIX_PROPERTY]["VALUE"];
									 }
									 }
									 ?>

									<?if ($town):?><?$adress .= ", ".$town;?><?if($region):?><?$adress .= ", ";?><?endif;?><?endif;?>
									<?if ($region):?><?$adress .= $region;?><?endif;?>
									<?if ($country):?><?$adress .= ", ".$country;?><?endif;?>
									<?echo $adress;?>


								<? if (!empty($arResult["PROPERTIES"]["ADDRESSBY"]["VALUE"])): ?><br><b><?= GetMessage('ADDRESS_BY') ?></b> <?= substr2($arResult["PROPERTIES"]["ADDRESSBY"]["VALUE"]) ?><?endif;?>
								<? if (!empty($arResult["PROPERTIES"]["SITE"]["VALUE"])): ?><br><b><?= GetMessage('SITE') ?></b> <?= $arResult["PROPERTIES"]["SITE"]["VALUE"] ?><?endif;?>
								<? if (!empty($arResult["PROPERTIES"]["TIME_FROM"]["VALUE"])): ?><br><b><?= GetMessage('TIME_FROM') ?></b> <?= $arResult["PROPERTIES"]["TIME_FROM"]["VALUE"] ?><?endif;?>
								<? if (!empty($arResult["PROPERTIES"]["TIME_TO"]["VALUE"])): ?><br><b><?= GetMessage('TIME_TO') ?></b> <?= $arResult["PROPERTIES"]["TIME_TO"]["VALUE"] ?><?endif;?>
								<? if (!empty($arResult["PROPERTIES"]["YEAR"]["VALUE"])): ?><br><b><?= GetMessage('YEAR') ?></b> <?= $arResult["PROPERTIES"]["YEAR"]["VALUE"] ?><?endif;?>
								<? if (!empty($arResult["PROPERTIES"]["YEAR_RE"]["VALUE"])): ?><br><b><?= GetMessage('YEAR_RE') ?></b> <?= $arResult["PROPERTIES"]["YEAR_RE"]["VALUE"] ?><?endif;?>
								<? if (!empty($arResult["PROPERTIES"]["DISTANCE_MINSK"]["VALUE"])): ?><br><b><?= GetMessage('DISTANCE_MINSK') ?></b> <?= substr2($arResult["PROPERTIES"]["DISTANCE_MINSK"]["VALUE"], 100); ?> km<? endif ?>
								<? if (!empty($arResult["PROPERTIES"]["DISTANCE_CENTER"]["VALUE"])): ?><br><b><?= GetMessage('DISTANCE_CENTER') ?></b> <?= substr2($arResult["PROPERTIES"]["DISTANCE_CENTER"]["VALUE"], 100); ?> km<? endif ?>
								<? if (!empty($arResult["PROPERTIES"]["DISTANCE_AIRPORT"]["VALUE"])): ?><br><b><?= GetMessage('DISTANCE_AIRPORT') ?></b> <?= substr2($arResult["PROPERTIES"]["DISTANCE_AIRPORT"]["VALUE"], 100); ?> km<? endif ?>
								<? if (!empty($arResult["PROPERTIES"]["NEAREST_TOWN"]["VALUE"])): ?>
                                    <?$nearest_town = strip_tags($arResult["DISPLAY_PROPERTIES"]["NEAREST_TOWN"]["DISPLAY_VALUE"]);?>
                                    <?
                                    if (LANGUAGE_ID != "ru") {
                                        $prop = getIBElementProperties($arResult["PROPERTIES"]["NEAREST_TOWN"]["VALUE"]);
                                        $nearest_town = $prop["NAME".POSTFIX_PROPERTY]["VALUE"];
                                    }
                                    ?>
                                    <br><b><?= GetMessage('NEAREST_TOWN') ?></b> <?=$nearest_town?><?if(!empty($arResult["PROPERTIES"]["NEAREST_TOWN_KM"]["VALUE"])):?> (<?= $arResult["PROPERTIES"]["NEAREST_TOWN_KM"]["VALUE"]?> km)<?endif?>
                                <? endif ?>
                            </address>
            <? endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <a name="block_<?= mb_strtolower($arResult["PROPERTIES"]["HD_DESC" . POSTFIX_PROPERTY]["CODE"]) ?>"></a>
        <div class="col-lg-9 hl-customer-like">
<? if (!empty($arResult["PROPERTIES"]["HD_DESC" . POSTFIX_PROPERTY]["VALUE"])): ?>
    <?= $arResult["DISPLAY_PROPERTIES"]["HD_DESC" . POSTFIX_PROPERTY]["DISPLAY_VALUE"] ?>
                <? endif ?>
			<? if ($arResult["IBLOCK_ID"]==8){
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
							<ul class="service-accmd">
								<li><div><img src="/local/templates/travelsoft/images/icon-check.png" alt=""></div><?= implode("</li> <li><div><img src='/local/templates/travelsoft/images/icon-check.png' alt=''></div>", $med_profiles) ?></li>
							</ul>
						</div>
                        <? endif; ?>
			<?}?>
        </div>
    </div>
</section>
<?$this->EndViewTarget();?>
<? if ($GLOBALS["PRICE_CALCULATION_RESULT_HTML"]): ?>
    <?$scroll[] = array("iblock_detail_prices", GetMessage('PRICES'));?>
    <section class="hl-features" id="iblock_detail_prices">
        <div class="hl-features-cn" style="padding-top: 0">
            <!-- Hotel Availability -->
            <?= $GLOBALS["PRICE_CALCULATION_RESULT_HTML"]; ?>
            <!-- Hotel Availability -->
        </div>
    </section>
<? endif ?>
<section class="hl-features detail-cn" id="iblock_detail_services">
        <div class="hl-features-cn">
            <? if (!empty($arResult["DISPLAY_PROPERTIES"]["SERVICES"]["VALUE"])): ?>
                <?$scroll[] = array("iblock_detail_services", GetMessage('SERVICES'));?>
                <div class="featured-service">
                        <h3><?= GetMessage("SERVICES_TITLE") ?></h3>
                        <?foreach ($arResult["SERVICES_GROUP"] as $sectionId => $arServices):?>
                            <div class="list-service-section">
                                <?if ($arResult["SERVICES_SECTIONS"][$sectionId]["PICTURE"]["SRC"]):?>
                                <div class="icon-service" style="float:left; top:2px"><img src="<?= $arResult["SERVICES_SECTIONS"][$sectionId]["PICTURE"]["SRC"]?>"></div>
                                <?endif?>
                                <h4><?= $arResult["SERVICES_SECTIONS"][$sectionId]["TITLE"]?></h4>
                            </div>
                            <ul class="service-accmd">
                                <?foreach ($arServices as $serviceId => $arData):?>
                                <li><div><img src="/local/templates/travelsoft/images/icon-check.png" alt=""></div><?= $arData["TITLE"]?> <?if ($arData["PAID"]):?><a data-content="<?= GetMessage('SERVICE_PAID') ?>">(<i class="fa fa-dollar"></i>)</a><?endif?></li>
                                <?endforeach?>
                            </ul>
                        <?endforeach;?>
                </div>
            <? endif; ?>
            <? if (!empty($arResult["DISPLAY_PROPERTIES"]["MED_SERVICES"]["VALUE"])): ?>
                <div class="featured-service">
                        <h3><?= GetMessage("MED_SERVICES_TITLE") ?></h3>
                        <?foreach ($arResult["MED_SERVICES_SECTIONS"] as $sectionId => $arData):?>
                            <div class="list-service-section">
                                <?if ($arData["PICTURE"]["SRC"]):?>
                                <div class="icon-service" style="float:left; top:2px"><img src="<?= $arData["PICTURE"]["SRC"]?>"></div>
                                <?endif?>
                                <h4><?= $arData["TITLE"]?></h4>
                            </div>
                            <ul class="service-accmd">
                                <?foreach ($arResult["MED_SERVICES_GROUP"][$sectionId] as $serviceId => $arDataServ):?>
                                <li><div><img src="/local/templates/travelsoft/images/icon-check.png" alt=""></div><?= $arDataServ["TITLE"]?> <?if ($arDataServ["PAID"]):?><a data-content="<?= GetMessage('SERVICE_PAID') ?>">(<i class="fa fa-dollar"></i>)</a><?endif?></li>
                                <?endforeach?>
                            </ul>
                        <?endforeach;?>
                </div>
            <? endif; ?>
            <? if (!empty($arResult["PROPERTIES"]["HD_DESCCHILD" . POSTFIX_PROPERTY]["VALUE"])): ?>
                <div class="featured-service">
                    <div class="policies-item detail-ul">
                        <h3><?= $arResult["PROPERTIES"]["HD_DESCCHILD" . POSTFIX_PROPERTY]["NAME"] ?></h3>
                        <?= $arResult["DISPLAY_PROPERTIES"]["HD_DESCCHILD" . POSTFIX_PROPERTY]["DISPLAY_VALUE"] ?>
                    </div>
                </div>
<? endif ?>
	 		<? if (!empty($arResult["PROPERTIES"]["LANG2"]["VALUE"])): ?>                        
				<div class="featured-service">
                    <h3><?= GetMessage('LANG') ?></h3>
                    <ul class="service-spoken">
					<li><img src="<?= SITE_TEMPLATE_PATH ?>/images/icon-check.png" alt="">
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
                </div>
            <? elseif (!empty($arResult["PROPERTIES"]["LANG" . POSTFIX_PROPERTY]["VALUE"])): ?>
                <div class="featured-service">
                    <h3><?= GetMessage('LANG') ?></h3>
                    <ul class="service-spoken">
                        <? foreach ($arResult["PROPERTIES"]["LANG" . POSTFIX_PROPERTY]["VALUE"] as $lang): ?>
                            <li><img src="<?= SITE_TEMPLATE_PATH ?>/images/icon-check.png" alt=""><?= $lang ?></li>
                        <? endforeach ?>
                    </ul>
                </div>
<? endif ?>
        </div>
</section>
<? if (!empty($arResult["PROPERTIES"]["HD_DESCROOM" . POSTFIX_PROPERTY]["VALUE"])): ?>
<!-- Details-Policies -->
<section class="details-policies detail-cn" id="iblock_detail_descroom">
        <div class="details-policies-cn">
                <?$scroll[] = array("iblock_detail_descroom", GetMessage('HD_DESCROOM'));?>
                <a name="iblock_detail_descroom" id="iblock_detail_descroom"></a>
                <div class="policies-item detail-ul">
                    <h3><?= $arResult["PROPERTIES"]["HD_DESCROOM" . POSTFIX_PROPERTY]["NAME"] ?></h3>
                    <?= $arResult["DISPLAY_PROPERTIES"]["HD_DESCROOM" . POSTFIX_PROPERTY]["DISPLAY_VALUE"] ?>
                </div>
        </div>
</section>
<? endif ?>
<? if (!empty($arResult["PROPERTIES"]["HD_DESCMEAL" . POSTFIX_PROPERTY]["VALUE"])): ?>
<section class="details-policies detail-cn" id="iblock_detail_descmeal">
    <div class="details-policies-cn">
                <?$scroll[] = array("iblock_detail_descmeal", GetMessage('HD_DESCMEAL'));?>
                <a name="iblock_detail_descmeal" id="iblock_detail_descmeal"></a>
                <div class="policies-item detail-ul">
                    <h3><?= $arResult["PROPERTIES"]["HD_DESCMEAL" . POSTFIX_PROPERTY]["NAME"] ?></h3>
                    <?= $arResult["DISPLAY_PROPERTIES"]["HD_DESCMEAL" . POSTFIX_PROPERTY]["DISPLAY_VALUE"] ?>
                </div>
    </div>
</section>
<? endif ?>
<? if (!empty($arResult["PROPERTIES"]["HD_DESCSPORT" . POSTFIX_PROPERTY]["VALUE"])): ?>
<section class="details-policies detail-cn" id="iblock_detail_descsport">
    <div class="details-policies-cn">
        <?$scroll[] = array("iblock_detail_descsport", GetMessage('HD_DESCSPORT'));?>
        <a name="iblock_detail_descsport" id="iblock_detail_descsport"></a>
        <div class="policies-item detail-ul">
            <h3><?= $arResult["PROPERTIES"]["HD_DESCSPORT" . POSTFIX_PROPERTY]["NAME"] ?></h3>
            <?= $arResult["DISPLAY_PROPERTIES"]["HD_DESCSPORT" . POSTFIX_PROPERTY]["DISPLAY_VALUE"] ?>
        </div>
    </div>
</section>
<? endif ?>
<? if (!empty($arResult["PROPERTIES"]["HD_DESCSERVICE" . POSTFIX_PROPERTY]["VALUE"])): ?>
<section class="details-policies detail-cn" id="iblock_detail_actions">
    <div class="details-policies-cn">
		<?$scroll[] = array("iblock_detail_actions", $arResult["PROPERTIES"]["HD_DESCSERVICE" . POSTFIX_PROPERTY]["NAME"]);?>
		<a name="iblock_detail_actions" id="iblock_detail_actions"></a>
		<div class="policies-item detail-ul">
			<h3><?= $arResult["PROPERTIES"]["HD_DESCSERVICE" . POSTFIX_PROPERTY]["NAME"] ?></h3>
			<?= $arResult["DISPLAY_PROPERTIES"]["HD_DESCSERVICE" . POSTFIX_PROPERTY]["DISPLAY_VALUE"] ?>
		</div>
	</div>
</section>
<? endif ?>
<? if (!empty($arResult["PROPERTIES"]["HD_ADDINFORMATION" . POSTFIX_PROPERTY]["VALUE"])): ?>
<section class="details-policies detail-cn">
    <div class="details-policies-cn">
        <div class="policies-item detail-ul">
            <h3><?= $arResult["PROPERTIES"]["HD_ADDINFORMATION" . POSTFIX_PROPERTY]["NAME"] ?></h3>
            <?= $arResult["DISPLAY_PROPERTIES"]["HD_ADDINFORMATION" . POSTFIX_PROPERTY]["DISPLAY_VALUE"] ?>
        </div>
    </div>
</section>
<? endif ?>
<? if (!empty($arResult["PROPERTIES"]["YOUTUBE" . POSTFIX_PROPERTY]["VALUE"]) || !empty($arResult["PROPERTIES"]["VIMEO" . POSTFIX_PROPERTY]["VALUE"])): ?>
<section class="details-policies detail-cn" id="iblock_detail_youtube">
    <div class="details-policies-cn">
                <?$scroll[] = array("iblock_detail_youtube", GetMessage('YOUTUBE'));?>
                <a name="iblock_detail_youtube" id="iblock_detail_youtube"></a>
                <div class="policies-item">
                    <h3><?= GetMessage('VIDEO') ?></h3>
                    <? if (!empty($arResult["PROPERTIES"]["YOUTUBE" . POSTFIX_PROPERTY]["VALUE"])): ?>
                        <div class="video-block">
                            <iframe width="100%" style="border: none;" src="https://www.youtube.com/embed/<?= $arResult["PROPERTIES"]["YOUTUBE" . POSTFIX_PROPERTY]["VALUE"] ?>" allowfullscreen=""></iframe>
                        </div>
                    <? endif ?>
                    <? if (!empty($arResult["PROPERTIES"]["VIMEO" . POSTFIX_PROPERTY]["VALUE"])): ?>
                        <div class="video-block">
                            <iframe width="100%" style="border: none;" src="https://player.vimeo.com/video/<?= $arResult["PROPERTIES"]["YOUTUBE" . POSTFIX_PROPERTY]["VALUE"] ?>" allowfullscreen="" frameborder="0" webkitallowfullscreen mozallowfullscreen></iframe>
                        </div>
                    <? endif ?>
                </div>
            <!-- End Details Policies Item -->
    </div>
</section>
<? endif ?>
<? if (!empty($arResult["PROPERTIES"]["MAP"]["VALUE"]) || !empty($arResult["PROPERTIES"]["PROEZD" . POSTFIX_PROPERTY]["VALUE"])): ?>
<section class="about-area details-policies detail-cn" id="iblock_detail_map">
        <div class="details-policies-cn">
            <? if (!empty($arResult["PROPERTIES"]["MAP"]["VALUE"])):?>
                <?$scroll[] = array("iblock_detail_map", GetMessage('MAP'));?>
                <a name="iblock_detail_map"></a>
                <div class="hotel-detail-map">
                    <h3><?= GetMessage('MAP_DETAIL_TITLE') ?></h3>
                    <?
                    $arLatLon = explode(",", $arResult["PROPERTIES"]["MAP"]["VALUE"]);
                    $zoom = "14";
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
                                "TEXT" => $arResult['NAME'],
                                "ICON" => MAP_MARKER_PATH
                            )
                        )
                    );
                    ?>
                    <?$APPLICATION->IncludeComponent("bitrix:map.google.view","on.detail.page",array(
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
                        "API_KEY" => GOOGLE_API_KEY,
						"DEV_MODE"=>'Y'
                        )
                    );?>
                    <!--<div id="hotel-detail-map" data-latlng="54.6100491,28.4351562"></div> -->
                    <p class="about-area-location"><i class="fa fa-map-marker"></i><?= $arResult["PROPERTIES"]["ADDRESS" . POSTFIX_PROPERTY]["VALUE"] ?></p>
                </div>
<? endif ?>
<? if (!empty($arResult["PROPERTIES"]["PROEZD" . POSTFIX_PROPERTY]["VALUE"])): ?>
                <div class="about-area-text">
                    <h3><?= $arResult["PROPERTIES"]["PROEZD" . POSTFIX_PROPERTY]["NAME"] ?></h3>
					<p>
						<?= $arResult["DISPLAY_PROPERTIES"]["PROEZD" . POSTFIX_PROPERTY]["DISPLAY_VALUE"] ?>
					</p>
                </div>
<? endif ?>

        </div>
</section>
<?endif?>
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

<?$this->SetViewTarget("menu-item-detail");?>
<?if(!empty($scroll)):?>

        <?foreach($scroll as $s):?>
            <?if(!empty($s)):?>
                <li><a href="#<?= $s[0]?>" class="anchor"><?= $s[1]?></a></li>
            <?endif?>
        <?endforeach?>

<?endif?>
<?$this->EndViewTarget();?>

<script>
    (function(){
        function initPopover(){
            $('.service-accmd a').webuiPopover({
                placement: "right",
                trigger: "hover"
            });
        }
        initPopover();
    })();
</script>