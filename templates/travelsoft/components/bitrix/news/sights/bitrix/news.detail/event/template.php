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

$this->addExternalCss(SITE_TEMPLATE_PATH . "/css/slider-prop.css");

$this->addExternalJs(SITE_TEMPLATE_PATH . "/js/jquery.sliderPro.min.js");
$this->addExternalJs(SITE_TEMPLATE_PATH . "/js/slide.js");
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
$water = '/local/templates/travelsoft/images/logo-waterm.png';
?>
<? $this->SetViewTarget("head-detail"); ?>
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
<div class="slider-container">
    <div id="search-preloader-slider" class="reloader-postion">

        <div id="search-page-loading">
            <div></div>
        </div>
    </div>
    <div style="margin-top: 20px;">
        <div id="slider-room" class="slider-pro">
            <div class="sp-slides">
                <?
                $i = 0;
                foreach ($arResult["PROPERTIES"]["PICTURES"]["VALUE"] as $item):
                    $file_big = CFile::ResizeImageGet($item, Array('width' => 1170, 'height' => 640), BX_RESIZE_IMAGE_EXACT, true, $arWaterMark);
                    $img_count++;
                    ?>
                    <div class="sp-slide">
                        <img src="" data-src="<?= $file_big["src"]; ?>"  alt="<?= $arResult["PROPERTIES"]["PICTURES"]["DESCRIPTION"][$i] ?>">
                    </div>
                    <? $i++;
                endforeach;
                ?>
            </div>
            <div class="sp-thumbnails">
                <?
                $i = 0;
                foreach ($arResult["PROPERTIES"]["PICTURES"]["VALUE"] as $item):
                    $file_small = CFile::ResizeImageGet($item, Array('width' => 220, 'height' => 100), BX_RESIZE_IMAGE_EXACT, true);
                    $img_count++;
                    ?>
                    <div class="sp-thumbnail">

                        <img src="" data-src="<?= $file_small["src"]; ?>" alt="<?= $arResult["PROPERTIES"]["PICTURES"]["DESCRIPTION"][$i] ?>">
                    </div>
                    <? $i++;
                endforeach;
                ?>
            </div>
        </div>


    </div>
</div>
<? endif; ?>
<!-- Hotel Content One -->
<section class="hotel-content check-rates detail-cn" id="hotel-content">
    <div class="row">                        
        <div class="col-lg-3 detail-sidebar">
            <div class="hight-light">
                <div class="row">
                    <div class="scroll-heading col-xs-12 col-sm-12 col-md-6 col-lg-12">

                            <address class="address">
                                <? if (!empty($arResult["DISPLAY_PROPERTIES"]["PLACE" . POSTFIX_PROPERTY]["VALUE"])): ?><br><b><?= GetMessage('PLACE') ?></b> <?= strip_tags($arResult["DISPLAY_PROPERTIES"]["PLACE" . POSTFIX_PROPERTY]["DISPLAY_VALUE"]) ?><? endif; ?>
                                <? if (!empty($arResult["DISPLAY_PROPERTIES"]["NUMBER"]["VALUE"])): ?><br><b><?= GetMessage('NUMBER') ?></b> <?= strip_tags($arResult["DISPLAY_PROPERTIES"]["NUMBER"]["DISPLAY_VALUE"]) ?><? endif; ?>
                                <? if (!empty($arResult["DISPLAY_PROPERTIES"]["DATE_FROM"]["VALUE"]) && $arResult["IBLOCK_ID"] == EVENTS_IBLOCK_ID): ?><br><b><?= GetMessage('DATE_FROM') ?></b>
                                    <?$first_date = $arResult["DISPLAY_PROPERTIES"]["DATE_FROM"]["VALUE"][0];$end_date = count($arResult["DISPLAY_PROPERTIES"]["DATE_FROM"]["VALUE"]) > 1 ? $arResult["DISPLAY_PROPERTIES"]["DATE_FROM"]["VALUE"][0] : '';?>
                                    <?foreach($arResult["DISPLAY_PROPERTIES"]["DATE_FROM"]["VALUE"] as $keydate=>$date_val):?>
                                        <?$date = MakeTimeStamp($date_val, "DD.MM.YYYY");?>
                                        <?if($date < MakeTimeStamp($first_date, "DD.MM.YYYY")){
                                            $first_date = $date_val;
                                        }?>
                                        <?if(!empty($end_date) && $date > MakeTimeStamp($end_date, "DD.MM.YYYY")){
                                            $end_date = $date_val;
                                        }?>
                                    <?endforeach?>
                                    <br><?=date("d.m.Y", MakeTimeStamp($first_date, "DD.MM.YYYY"))?><?if(!empty($end_date)):?> - <?=date("d.m.Y", MakeTimeStamp($end_date, "DD.MM.YYYY"))?><?endif?>
                                <? endif; ?>
                            </address>
                    </div>
                </div>
            </div>
        </div>
        <a name="block_<?= mb_strtolower($arResult["PROPERTIES"]["DETAIL_TEXT" . POSTFIX_PROPERTY]["CODE"]) ?>"></a>
        <div class="col-lg-9 hl-customer-like">
            <? if (!empty($arResult["PROPERTIES"]["DETAIL_TEXT" . POSTFIX_PROPERTY]["VALUE"])): ?>
                <?= $arResult["DISPLAY_PROPERTIES"]["DETAIL_TEXT" . POSTFIX_PROPERTY]["DISPLAY_VALUE"] ?>
            <? endif ?>
        </div>
    </div>
</section>
        <? $this->EndViewTarget(); ?>
<?/* if (!empty($arResult["PROPERTIES"]["MAP"]["VALUE"]) || !empty($arResult["PROPERTIES"]["PROEZD" . POSTFIX_PROPERTY]["VALUE"])): */?><!--
    <section class="about-area details-policies detail-cn" id="iblock_detail_map">
        <div class="details-policies-cn">
    <?/* if (!empty($arResult["PROPERTIES"]["MAP"]["VALUE"])): */?>
        <?/* $scroll[] = array("iblock_detail_map", GetMessage('MAP')); */?>
                <a name="iblock_detail_map"></a>
                <div class="hotel-detail-map">
                    <h3><?/*= GetMessage('MAP_DETAIL_TITLE') */?></h3>
                    <div style="width: 100%; height: 400px" id="placement_location_map"></div>
                    <?/*
                    $arLatLon = explode(",", $arResult["PROPERTIES"]["MAP"]["VALUE"]);
                    $this->addExternalJs(SITE_TEMPLATE_PATH . "/js/MapAdapter/MapAdapter.js");
                    */?>
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
                                lat: <?/*= $arLatLon[0]*/?>,
                                lng: <?/*= $arLatLon[1]*/?>,
                                icon: "<?/*= MAP_MARKER_PATH*/?>",
                                title: "<?/*= $arResult['NAME']*/?>",
                                content: "<span style='color: #264B87'><?/*= $arResult['NAME']*/?></span>"
                            });
                        });

                    </script>
                    <p class="about-area-location"><i class="fa fa-map-marker"></i><?/*= $arResult["PROPERTIES"]["ADDRESS" . POSTFIX_PROPERTY]["VALUE"] */?></p>
                </div>
    <?/* endif */?>
        </div>
    </section>
--><?/* endif */?>
<? $this->SetViewTarget("menu-item-detail"); ?>
<? if (!empty($scroll)): ?>

    <? foreach ($scroll as $s): ?>
        <? if (!empty($s)): ?>
            <li><a href="#<?= $s[0] ?>" class="anchor"><?= $s[1] ?></a></li>
        <? endif ?>
    <? endforeach ?>

<? endif ?>
<? $this->EndViewTarget(); ?>

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