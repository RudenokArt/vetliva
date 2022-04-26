<?php
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
$this->setFrameMode(false);

use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

if (!$arResult["TRANSFER"]) {
    return false;
}

$transfer = $arResult["TRANSFER"];





ob_start();
$APPLICATION->IncludeComponent(
        "travelsoft:travelsoft.service.price.result", "on.detail.page.render", Array(
    "CACHE_TIME" => 3600,
    "CACHE_TYPE" => "A",
    "FILTER_BY_PRICES_FOR_CITIZEN" => "N",
    "INC_JQUERY" => "N",
    "INC_MAGNIFIC_POPUP" => "N",
    "INC_OWL_CAROUSEL" => "N",
    "MAKE_ORDER_PAGE" => "/booking/",
    "TYPE" => "transfers",
    "POSTFIX_PROPERTY" => POSTFIX_PROPERTY,
    "__BOOKING_REQUEST" => $_REQUEST["booking"],
    "FOLDER_URL" => getCalculateDetailLink("/", $_REQUEST["booking"], array("active_tab" => "transfer_tab"))
        )
);
$PRICE_HTML = ob_get_clean();
?>



<section class="head-detail">
    <div class="head-dt-cn">
        <div class="row">
            <h1><?= $transfer->name ?></h1>
        </div>
    </div>
</section>

<div class="detail-cn myclass">
    <div class="row check-rates">
        <div class="col-sm-3 hidden-sm hidden-xs col-lg-3 detail-sidebar">
            <div class="scrolly scrollspy-sidebar sidebar-detail scroll-heading" role="complementary" data-offset="20" style="position: fixed; top: 70px;">
                <?/*<address class="address">
                    <b><?= Loc::getMessage("DISTANCE") ?> </b> <?= $transfer->formattedDistance(Loc::getMessage("DISTANCE_TPL")) ?><br>
                    <b><?= Loc::getMessage("TRAVEL_TIME") ?> </b> <?= $transfer->formattedTravelTime(Loc::getMessage("TRAVEL_TIME_TPL")) ?>
                </address>*/?>
                <ul class="nav">
<? if ($PRICE_HTML): ?>
                        <li class=""><a href="#iblock_detail_prices" class="anchor"><?= Loc::getMessage("PRICES") ?></a></li>
                    <? endif ?>
                    <li class="active"><a href="#iblock_detail_map" class="anchor"><?= Loc::getMessage("ROUTE") ?></a></li>
                </ul>
                <div style="width: 100%; height: 20px;"></div>
            </div>
        </div>
        <div class="col-lg-9 check-rates-cn section-list">
            <section id="iblock_detail_prices">
<?= $PRICE_HTML ?>
            </section>
            <section class="about-area details-policies detail-cn" id="iblock_detail_map">
                <div style="width: 100%; height: 400px" id="roadmap"></div>
                <? $this->addExternalJs(SITE_TEMPLATE_PATH . "/js/MapAdapter/MapAdapter.js"); ?>
                <script>
                    $(document).ready(function () {
                        var mapAdapter = new MapAdapter({
                            map_id: "roadmap",
                            center: {
                                lat: 53.53,
                                lng: 27.34
                            },
                            object: "ymaps",
                            zoom: 14
                        });
                        mapAdapter.drawRoute(
<?=
\Bitrix\Main\Web\Json::encode(array(
    array(
        "fullcoord"=>[$transfer->point_A->lat, $transfer->point_A->lng],
        "lat" => $transfer->point_A->lat,
        "lng" => $transfer->point_A->lng,
        "title" => $transfer->point_A->name,
        "content" => "<div style='color:red'><b>" . $transfer->point_A->name . "</b></div>"
    ),
    array(
        "fullcoord"=>[$transfer->point_B->lat, $transfer->point_B->lng],
        "lat" => $transfer->point_B->lat,
        "lng" => $transfer->point_B->lng,
        "title" => $transfer->point_B->name,
        "content" => "<div style='color:red'><b>" . $transfer->point_B->name . "</b></div>"
    ),
))
?>
                        );
                    });

                </script>
<? /* $this->addExternalJs("https://maps.googleapis.com/maps/api/js?key=".GOOGLE_API_KEY);?>
  <? $this->addExternalJs($templateFolder . "/jquery-custom-google-map-lib.js"); ?>
  <div style="width: 100%; height: 400px" id="roadmap"></div>
  <script>
  (function (gm) {
  // init map and draw route
  gm.createGoogleMap("roadmap", {center: gm.LatLng(0, 0), zoom: 5})
  .drawRoute(<?=
  \Bitrix\Main\Web\Json::encode(array(
  array(
  "lat" => $transfer->point_A->lat,
  "lng" => $transfer->point_A->lng,
  "title" => $transfer->point_A->name,
  "infoWindow" => "<div style='color:red'><b>" . $transfer->point_A->name . "</b></div>"
  ),
  array(
  "lat" => $transfer->point_B->lat,
  "lng" => $transfer->point_B->lng,
  "title" => $transfer->point_B->name,
  "infoWindow" => "<div style='color:red'><b>" . $transfer->point_B->name . "</b></div>"
  ),
  ))
  ?>);
  })(window.GoogleMapFunctionsContainer)
  </script> */ ?>
            </section>
        </div>
    </div>
</div>
</div>

