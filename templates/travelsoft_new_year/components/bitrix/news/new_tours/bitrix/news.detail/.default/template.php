<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();

if (defined('ERROR_404') && ERROR_404 == 'Y')
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
/* $arMenu = array();
  foreach ($arResult["DISPLAY_PROPERTIES"] as $menu){
  if(!empty($menu["DISPLAY_VALUE"])){
  $arMenu[$menu["ID"]] = array(
  "NAME" => $menu["NAME"],
  "ANCHOR" => "hotel_".mb_strtolower($menu["CODE"])
  );
  }
  } */
$htmlMapID = "route-map";
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
$p=$arResult["PROPERTIES"];
$scroll[] = array();
?>
<?$this->SetViewTarget("head-detail-tours");?>
<section class="head-detail">
    <div class="head-dt-cn">
        <div class="row">
            <div class="col-sm-10">
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
    <?= $arResult["DISPLAY_PROPERTIES"]["CAT_ID"]["DISPLAY_VALUE"]; ?>
<? endif; ?>
                    </span>
                </div>
            </div>
            <!--
                                            <div class="col-sm-2 text-right">
                                                <p class="price-book">
                                                    От <span>300</span> BYN / ночь
                                                    <a href="" title="" class="awe-btn awe-btn-1 awe-btn-lager">Бронировать</a>
                                                </p>
                                            </div>
            -->
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
        <!-- Lager Image -->
        <div class="slide-room-lg">
            <div id="slide-room-lg">
                <?
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
            <!-- Hight Light -->
            <div class="hight-light">

                <!-- Vote Text -->
                <div class="row">
                    <!-- Recommend -->

                    <div class="scroll-heading col-xs-12 col-sm-12 col-md-6 col-lg-12">

                        <? if (!empty($arResult["PROPERTIES"]["DAYS"]["VALUE"])): ?>
                            <b><?= GetMessage("DAYS") ?>: </b>
                            <?= $arResult["DISPLAY_PROPERTIES"]["DAYS"]["DISPLAY_VALUE"] ?><br>
                        <? endif; ?>
                        <?
                        $transport = null;
                        if ($p["TRANSPORT"]["VALUE"]) {
                            $p["TRANSPORT"]["VALUE"] = (array) $p["TRANSPORT"]["VALUE"];
                            $db_res_transport = CIBlockElement::GetList(false, array("ACTIVE" => "Y", "IBLOCK_ID" => $p["TRANSPORT"]["LINK_IBLOCK_ID"], "ID" => $p["TRANSPORT"]["VALUE"]), false, false, array("ID", "NAME", "PROPERTY_NAME" . POSTFIX_PROPERTY));
                            $transport = null;
                            while ($res = $db_res_transport->Fetch()) {
                                $transport[] = $res["PROPERTY_NAME" . POSTFIX_PROPERTY . "_VALUE"] ? $res["PROPERTY_NAME" . POSTFIX_PROPERTY . "_VALUE"] : $res["NAME"];
                            }
                        }
                        if ($transport):
                            ?>
                            <b><?= GetMessage("TRANSPORT") ?>: </b>
                            <?= implode(", ", $transport) ?><br>
                        <? endif; ?>
                        <? if (!empty($arResult["PROPERTIES"]["DURATION"]["VALUE"])): ?>
                            <b><?= GetMessage("DURATION") ?>: </b>
                            <?= strip_tags($arResult["DISPLAY_PROPERTIES"]["DURATION"]["DISPLAY_VALUE"]) ?><br>
                        <? endif; ?>
                        <? if (!empty($arResult["PROPERTIES"]["DURATION_TIME"]["VALUE"])): ?>
                            <b><?= GetMessage("DURATION_TIME") ?>: </b>
                            <?= $arResult["DISPLAY_PROPERTIES"]["DURATION_TIME"]["DISPLAY_VALUE"] ?><br>
                        <? endif; ?>
                        <?
                        $theme = null;
                        if ($p["THEME_TOURS"]["VALUE"]) {
                            $p["THEME_TOURS"]["VALUE"] = (array) $p["THEME_TOURS"]["VALUE"];
                            $db_res_theme = CIBlockElement::GetList(false, array("ACTIVE" => "Y", "IBLOCK_ID" => $p["THEME_TOURS"]["LINK_IBLOCK_ID"], "ID" => $p["THEME_TOURS"]["VALUE"]), false, false, array("ID", "NAME", "PROPERTY_NAME" . POSTFIX_PROPERTY));
                            $theme = null;
                            while ($res = $db_res_theme->Fetch()) {
                                $theme[] = $res["PROPERTY_NAME" . POSTFIX_PROPERTY . "_VALUE"] ? $res["PROPERTY_NAME" . POSTFIX_PROPERTY . "_VALUE"] : $res["NAME"];
                            }
                        }
                        if ($theme):
                            ?>
                            <b><?= GetMessage("THEME_TOURS") ?>: </b>
                            <?= implode(", ", $theme) ?><br>
                        <? endif; ?>
                        <?
                        $typetour = null;
                        if ($p["TOURTYPE"]["VALUE"]) {
                            $p["TOURTYPE"]["VALUE"] = (array) $p["TOURTYPE"]["VALUE"];
                            $db_res_typetour = CIBlockElement::GetList(false, array("ACTIVE" => "Y", "IBLOCK_ID" => $p["TOURTYPE"]["LINK_IBLOCK_ID"], "ID" => $p["TOURTYPE"]["VALUE"]), false, false, array("ID", "NAME", "PROPERTY_NAME" . POSTFIX_PROPERTY));
                            $typetour = null;
                            while ($res = $db_res_typetour->Fetch()) {
                                $typetour[] = $res["PROPERTY_NAME" . POSTFIX_PROPERTY . "_VALUE"] ? $res["PROPERTY_NAME" . POSTFIX_PROPERTY . "_VALUE"] : $res["NAME"];
                            }
                        }
                        if ($typetour):
                            ?>
                            <b><?= GetMessage("TOURTYPE") ?>: </b>
                            <?= implode(", ", $typetour) ?><br>
                        <? endif; ?>
                        <? $city = null;
                        if ($p["CITY"]["VALUE"]) {
                            $p["CITY"]["VALUE"] = (array) $p["CITY"]["VALUE"];
                            $db_res_city = CIBlockElement::GetList(false, array("ACTIVE" => "Y", "IBLOCK_ID" => $p["CITY"]["LINK_IBLOCK_ID"], "ID" => $p["CITY"]["VALUE"]), false, false, array("ID", "NAME", "PROPERTY_NAME" . POSTFIX_PROPERTY));
                            $city = null;
                            while ($res = $db_res_city->Fetch()) {
                                $city[] = $res["PROPERTY_NAME" . POSTFIX_PROPERTY . "_VALUE"] ? $res["PROPERTY_NAME" . POSTFIX_PROPERTY . "_VALUE"] : $res["NAME"];
                            }
                        }
                        if ($city):
                            ?>
                            <b><?= GetMessage("CITY") ?>: </b>
                            <?= implode(", ", $city) ?><br>
                        <? endif; ?>
                        <!-- End Quote -->
                    </div>
                </div>
                <!-- End Vote Text -->



            </div>
            <!-- End Hight Light -->
        </div>
        <a name="block_<?= mb_strtolower($arResult["PROPERTIES"]["HD_DESC" . POSTFIX_PROPERTY]["CODE"]) ?>"></a>
        <!-- Description -->
        <div class="col-lg-9 hl-customer-like">
            <h2><? echo LANGUAGE_ID == "ru" ? $arResult["NAME"] : $arResult["PROPERTIES"]["NAME" . POSTFIX_PROPERTY]["VALUE"] ?> </h2>

            <? if (!empty($arResult["PROPERTIES"]["HD_DESC" . POSTFIX_PROPERTY]["VALUE"])): ?>
                <?= $arResult["DISPLAY_PROPERTIES"]["HD_DESC" . POSTFIX_PROPERTY]["DISPLAY_VALUE"] ?><br><br>
            <? endif ?>

            <? if (!empty($arResult["DISPLAY_PROPERTIES"]["DEPARTURE_EXC_TEXT" . POSTFIX_PROPERTY]["DISPLAY_VALUE"])): ?>
                <b><?= $arResult["DISPLAY_PROPERTIES"]["DEPARTURE_EXC_TEXT" . POSTFIX_PROPERTY]["NAME"] ?>: </b>
                <?= strip_tags($arResult["DISPLAY_PROPERTIES"]["DEPARTURE_EXC_TEXT" . POSTFIX_PROPERTY]["DISPLAY_VALUE"]) ?><br>
            <? endif; ?>
            <? if (!empty($arResult["DISPLAY_PROPERTIES"]["ROUTE" . POSTFIX_PROPERTY]["DISPLAY_VALUE"])): ?>
                <b><?= $arResult["DISPLAY_PROPERTIES"]["ROUTE" . POSTFIX_PROPERTY]["NAME"] ?>: </b>
                <?= strip_tags($arResult["DISPLAY_PROPERTIES"]["ROUTE" . POSTFIX_PROPERTY]["DISPLAY_VALUE"]) ?><br>
            <? endif; ?>

        </div>
        <!-- End Description -->
    </div>

</section>
<!-- End Hotel Content One --> 
<?$this->EndViewTarget();?>
<!-- Check Rates-->
<? if (!empty($GLOBALS["PRICE_CALCULATION_RESULT_HTML"])): ?>
    <?$scroll[] = array("iblock_detail_prices", GetMessage('PRICES'));?>
    <section class="check-rates detail-cn" id="iblock_detail_prices" style="border-top: none">
        <div class="check-rates-cn" style="padding-top: 0">
            <!-- Hotel Availability -->
            <?= $GLOBALS["PRICE_CALCULATION_RESULT_HTML"] ?>  
            <!-- Hotel Availability -->
            <?if(!empty($arResult["DISPLAY_PROPERTIES"]["PRICE_INCLUDE" . POSTFIX_PROPERTY]["DISPLAY_VALUE"])):?>
                <div class="policies-item">
                    <h3><?= $arResult["PROPERTIES"]["PRICE_INCLUDE" . POSTFIX_PROPERTY]["NAME"] ?></h3>
                    <p><?= $arResult["DISPLAY_PROPERTIES"]["PRICE_INCLUDE" . POSTFIX_PROPERTY]["DISPLAY_VALUE"] ?></p>
                </div>
            <?endif;?>
            <?if(!empty($arResult["DISPLAY_PROPERTIES"]["PRICE_NO_INCLUDE" . POSTFIX_PROPERTY]["DISPLAY_VALUE"])):?>
                <div class="policies-item">
                    <h3><?= $arResult["PROPERTIES"]["PRICE_NO_INCLUDE" . POSTFIX_PROPERTY]["NAME"] ?></h3>
                    <p><?= $arResult["DISPLAY_PROPERTIES"]["PRICE_NO_INCLUDE" . POSTFIX_PROPERTY]["DISPLAY_VALUE"] ?></p>
                </div>
            <?endif;?>
        </div>
    </section>
<? endif ?>
<!-- End Check Rates -->
<!-- Tour Program  -->
<?if(!empty($arResult["PROPERTIES"]["NDAYS" . POSTFIX_PROPERTY]["VALUE"])):?>
    <?$scroll[] = array("iblock_detail_ndays", GetMessage('NDAYS'));?>
    <section class="hl-features detail-cn" id="iblock_detail_ndays">
        <div class="hl-features-cn">
            <?if (count($arResult["PROPERTIES"]["NDAYS" . POSTFIX_PROPERTY]["VALUE"]) == 1):?>
                <a class="color" name="iblock_detail_ndays"></a>
                <div class="featured-service byday">
                    <div class="policies-item">
                        <h3><?= $arResult["PROPERTIES"]["NDAYS" . POSTFIX_PROPERTY]["NAME"] ?></h3>
                        <h5><?= $arResult["DISPLAY_PROPERTIES"]["NDAYS" . POSTFIX_PROPERTY]["DESCRIPTION"][0] ?> </h5>
                        <?=$arResult["DISPLAY_PROPERTIES"]["NDAYS" . POSTFIX_PROPERTY]["DISPLAY_VALUE"]?>
                    </div>
                </div>
            <? elseif (count($arResult["PROPERTIES"]["NDAYS" . POSTFIX_PROPERTY]["VALUE"]) > 1): ?>
                <a class="color" name="iblock_detail_ndays"></a>
                <div class="featured-service byday">
                    <div class="policies-item">
                        <h3><?= $arResult["PROPERTIES"]["NDAYS" . POSTFIX_PROPERTY]["NAME"] ?></h3>
                        <div class="panel-group no-margin" id="accordion">
                            <? $d = 1; ?>
                            <? foreach ($arResult["DISPLAY_PROPERTIES"]["NDAYS" . POSTFIX_PROPERTY]["DISPLAY_VALUE"] as $k => $value): ?>
                                <div class="panel">
                                    <div class="panel-heading" id="heading<?= $d ?>">
                                        <h4 class="panel-title"><a class="accordion-toggle<? if ($d != 1): ?> collapsed<? endif ?>" data-toggle="collapse" data-parent="#accordion" href="#collapse<?= $d ?>">
                                                <small><?= GetMessage('DAY') ?> <?= $d ?>:</small><?= $arResult["DISPLAY_PROPERTIES"]["NDAYS" . POSTFIX_PROPERTY]["DESCRIPTION"][$k] ?> <span class="icon fa fa-angle-<? if ($d == 1): ?>up<?else:?>down<? endif ?>"></span>
                                            </a></h4>
                                    </div>
                                    <div id="collapse<?= $d ?>" class="panel-collapse collapse<? if ($d == 1): ?> in<? endif ?>" aria-labelledby="heading<?= $d ?>">
                                        <div class="panel-body">
                                            <?= $value ?>
                                        </div>
                                    </div>
                                </div>
                                <? $d++ ?>
                            <? endforeach; ?>
                        </div>
                    </div>
                </div>
            <? endif ?>
            <? if (!empty($arResult["PROPERTIES"]["LANG"]["VALUE"])): ?>
                <div class="featured-service">
                    <h3><?= GetMessage('LANG') ?></h3>
                    <ul class="service-spoken">
                        <li><img src="<?= SITE_TEMPLATE_PATH ?>/images/icon-check.png" alt="">
                            <?
                            $lang = null;
                            if ($p["LANG"]["VALUE"]) {
                                $p["LANG"]["VALUE"] = (array) $p["LANG"]["VALUE"];
                                $db_res_lang = CIBlockElement::GetList(false, array("ACTIVE" => "Y", "IBLOCK_ID" => $p["LANG"]["LINK_IBLOCK_ID"], "ID" => $p["LANG"]["VALUE"]), false, false, array("ID", "NAME", "PROPERTY_NAME" . POSTFIX_PROPERTY));
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
            <? endif ?>
            <? if (!empty($arResult["PROPERTIES"]["ADDITIONAL" . POSTFIX_PROPERTY]["VALUE"])): ?>
                <div class="policies-item">
                    <h3><?= $arResult["PROPERTIES"]["ADDITIONAL" . POSTFIX_PROPERTY]["NAME"] ?></h3>
                    <?= $arResult["DISPLAY_PROPERTIES"]["ADDITIONAL" . POSTFIX_PROPERTY]["DISPLAY_VALUE"] ?>
                </div>
            <? endif ?>
        </div>
    </section>
<?endif;?>
<!-- End Tour Program -->
<? if (!empty($arResult["PROPERTIES"]["DOCUMENT" . POSTFIX_PROPERTY]["VALUE"])): ?>
    <?$scroll[] = array("iblock_detail_document", GetMessage('DOCUMENT'));?>
    <!-- Details-Policies -->
    <section class="details-policies detail-cn">
        <div class="details-policies-cn">

            <a class="color" name="iblock_detail_document" id="iblock_detail_map"></a>
            <div class="policies-item">
                <h3><?= $arResult["PROPERTIES"]["DOCUMENT" . POSTFIX_PROPERTY]["NAME"] ?></h3>
                <?= $arResult["DISPLAY_PROPERTIES"]["DOCUMENT" . POSTFIX_PROPERTY]["DISPLAY_VALUE"] ?>
            </div>

        </div>
    </section>
    <!-- End Details Policies Item -->
<? endif ?>
<? if (!empty($arResult["PROPERTIES"]["TOWN"]["VALUE"]) && !empty($arResult['ROUTE_INFO']) && count($arResult['ROUTE_INFO']) > 1): ?>
    <?$scroll[] = array("iblock_detail_map", GetMessage('MAP'));?>
    <section class="hl-features detail-cn" id="iblock_detail_map">
        <div class="hl-features-cn">
            <a class="color" name="iblock_detail_map"></a>
            <div class="policies-item">
                <h3><?= GetMessage('MAP') ?></h3>
                <?
                $this->addExternalJs("https://maps.googleapis.com/maps/api/js?key=AIzaSyAV5vry8G8fZEURwW2XQUx-X9TzVA-ih0I");
                $this->addExternalJs($templateFolder . "/jquery-custom-google-map-lib.js");
                ?>

                <div style="width: 100%; height: 400px" id="<?= $htmlMapID ?>"></div>
                <script>
                    (function (gm) {
                        // init map and draw route
                        gm.createGoogleMap("<?= $htmlMapID ?>", {center: gm.LatLng(0, 0), zoom: 5})
                            .drawRoute(<?= \Bitrix\Main\Web\Json::encode($arResult['ROUTE_INFO']) ?>);
                    })(window.GoogleMapFunctionsContainer)
                </script>

            </div>
        </div>
    </section>
<? endif ?>
<? if (!empty($arResult["PROPERTIES"]["YOUTUBE" . POSTFIX_PROPERTY]["VALUE"]) || !empty($arResult["PROPERTIES"]["VIMEO" . POSTFIX_PROPERTY]["VALUE"])): ?>
    <?$scroll[] = array("iblock_detail_youtube", GetMessage('YOUTUBE'));?>
    <section class="details-policies detail-cn" id="iblock_detail_youtube">
        <div class="details-policies-cn">
            <!-- Details Policies Item -->
            <? if (!empty($arResult["PROPERTIES"]["YOUTUBE" . POSTFIX_PROPERTY]["VALUE"]) || !empty($arResult["PROPERTIES"]["VIMEO" . POSTFIX_PROPERTY]["VALUE"])): ?>
                <a name="iblock_detail_youtube"></a>
                <div class="policies-item">
                    <h3><?= GetMessage('VIDEO') ?></h3>
                    <? if (!empty($arResult["PROPERTIES"]["YOUTUBE" . POSTFIX_PROPERTY]["VALUE"])): ?>
                        <div class="video-block">
                            <iframe width="812" height="350" style="border: none;" src="https://www.youtube.com/embed/<?= $arResult["PROPERTIES"]["YOUTUBE" . POSTFIX_PROPERTY]["VALUE"] ?>" allowfullscreen=""></iframe>
                        </div>
                    <? endif ?>
                    <? if (!empty($arResult["PROPERTIES"]["VIMEO" . POSTFIX_PROPERTY]["VALUE"])): ?>
                        <div class="video-block">
                            <iframe width="812" height="350" style="border: none;" src="https://player.vimeo.com/video/<?= $arResult["PROPERTIES"]["YOUTUBE" . POSTFIX_PROPERTY]["VALUE"] ?>" allowfullscreen="" frameborder="0" webkitallowfullscreen mozallowfullscreen></iframe>
                        </div>
                    <? endif ?>
                </div>
            <? endif ?>
            <!-- End Details Policies Item -->
        </div>
    </section>
<? endif ?>

<?$this->SetViewTarget("menu-item-detail-tours");?>
<?if(!empty($scroll)):?>

    <?foreach($scroll as $s):?>
        <?if(!empty($s)):?>
            <li><a href="#<?= $s[0]?>" class="anchor"><?= $s[1]?></a></li>
        <?endif?>
    <?endforeach?>

<?endif?>
<?$this->EndViewTarget();?>
