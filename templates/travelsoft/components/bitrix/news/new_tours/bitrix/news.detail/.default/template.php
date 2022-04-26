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

//$this->addExternalCss(SITE_TEMPLATE_PATH . "/css/slider-prop.css");
//
$this->addExternalJs(SITE_TEMPLATE_PATH . "/js/jquery.sliderPro.min.js");
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
$p = $arResult["PROPERTIES"];
$scroll[] = array();
?>
<? $this->SetViewTarget("head-detail-tours"); ?>

<? if($arResult["PROPERTIES"]["SCH_SHOW"]["VALUE"]): ?>
<!-- Разметка schema для раздела экскурсии -->
<div itemscope itemtype="http://schema.org/Product" style="display:none;">
 <?
	$site_suff = (LANGUAGE_ID != "en")?LANGUAGE_ID:"com";
	$cur_partition = "/tourism/tours-in-belarus/";
	if(preg_match("/tourism\\/cognitive-tourism\\/.+/", $_SERVER["REQUEST_URI"]) != 0){
		$cur_partition = "/tourism/cognitive-tourism/";
	}
	$schema_url = "https://vetliva.".$site_suff.$cur_partition.$arResult["CODE"]."/"."?booking[id][]=".$arResult["ID"];
?>
 <span itemprop="brand">VETLIVA</span>
 <span itemprop="name"><?=$arResult["NAME"];?></span>
 <img itemprop="image" src="<?=$arResult["PROPERTIES"]["SCH_IMG_PATH"]["VALUE"];?>" />
 <span itemprop="description"><?=$arResult["PROPERTIES"]["SCH_SHORT_DSCR"]["VALUE"]["TEXT"];?></span>
 <span itemprop="offers" itemscope itemtype="http://schema.org/Offer">
 		<link itemprop="url" href="<?=$schema_url;?>" />
       <meta itemprop="priceCurrency" content="BYN" />
       <span itemprop="price"><?=$arResult["PROPERTIES"]["SCH_PRICE"]["VALUE"];?></span>
       <link itemprop="availability" href="http://schema.org/InStock"/>
 </span>
<div itemprop="review" itemscope itemtype="http://schema.org/Review">
   <span itemprop="reviewRating" itemscope itemtype="http://schema.org/Rating">
   	   <link itemprop="url" href="<?=$schema_url;?>" />

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
            <div class="col-sm-10">
                <h1>
                    <? echo LANGUAGE_ID == "ru" ? $arResult["NAME"] : $arResult["PROPERTIES"]["NAME" . POSTFIX_PROPERTY]["VALUE"] ?>

                </h1>
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
            <div class="col-sm-2 text-right"><?= $GLOBALS["favorites_html"]?></div>
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
    <? $imgarr = !empty($arResult["PROPERTIES"]["PICTURES" . POSTFIX_PROPERTY]["VALUE"]) ? $arResult["PROPERTIES"]["PICTURES" . POSTFIX_PROPERTY] : $arResult["PROPERTIES"]["PICTURES"]; ?>

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

                        <? if (!empty($arResult["PROPERTIES"]["DAYS"]["VALUE"])): ?>
                            <b><?= GetMessage("DAYS") ?> </b>
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
                            <b><?= GetMessage("DURATION_TIME") ?> </b>
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
                        <?
                        $city = null;
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
                        <?
                        $sposob_pr = null;
                        if ($p["SPOSOB_PROVEDENIA"]["VALUE"]) {
                            $p["SPOSOB_PROVEDENIA"]["VALUE"] = (array) $p["SPOSOB_PROVEDENIA"]["VALUE"];
                            $db_res_sp_pr = CIBlockElement::GetList(false, array("ACTIVE" => "Y", "IBLOCK_ID" => $p["SPOSOB_PROVEDENIA"]["LINK_IBLOCK_ID"], "ID" => $p["SPOSOB_PROVEDENIA"]["VALUE"]), false, false, array("ID", "NAME", "PROPERTY_NAME" . POSTFIX_PROPERTY));
                            $sposob_pr = null;
                            while ($res = $db_res_sp_pr->Fetch()) {
                                $sposob_pr[] = $res["PROPERTY_NAME" . POSTFIX_PROPERTY . "_VALUE"] ? $res["PROPERTY_NAME" . POSTFIX_PROPERTY . "_VALUE"] : $res["NAME"];
                            }
                        }
                        if ($sposob_pr):
                            ?>
                            <b><?= GetMessage("SPOSOB_PROVEDENIA") ?>: </b>
                            <?= implode(", ", $sposob_pr) ?><br>
                        <? endif; ?>
                        <!-- End Quote -->

                        <!--  Additional Text -->
                        <? if (!empty($arResult["DISPLAY_PROPERTIES"]["DEPARTURE_EXC_TEXT" . POSTFIX_PROPERTY]["DISPLAY_VALUE"])): ?>
                            <b><?= $arResult["DISPLAY_PROPERTIES"]["DEPARTURE_EXC_TEXT" . POSTFIX_PROPERTY]["NAME"] ?>: </b>
                            <?= strip_tags($arResult["DISPLAY_PROPERTIES"]["DEPARTURE_EXC_TEXT" . POSTFIX_PROPERTY]["DISPLAY_VALUE"]) ?><br>
                        <? endif; ?>

                        <? if (!empty($arResult["DISPLAY_PROPERTIES"]["ROUTE" . POSTFIX_PROPERTY]["DISPLAY_VALUE"])):
                        $link_route  = '';
                        if (!empty($arResult["DISPLAY_PROPERTIES"]["TOWN"]["VALUE"]) && count($arResult["DISPLAY_PROPERTIES"]["TOWN"]["VALUE"])>1) {
                        $tmpfiltr = ['ID'=>$arResult["DISPLAY_PROPERTIES"]["TOWN"]["VALUE"], 'TYPE'=>'route'];
                        ob_start();?>
                        <a href="javascript:;" class="show-map" data-id="<?=$arResult["DISPLAY_PROPERTIES"]["TOWN"]["VALUE"][0]?>"  data-filter='<?= json_encode($tmpfiltr) ?>'>
                            <?$link_route = ob_get_clean();
                            }
                            elseif (!empty($arResult["DISPLAY_PROPERTIES"]["SIGHTS"]["VALUE"]) && count($arResult["DISPLAY_PROPERTIES"]["SIGHTS"]["VALUE"])) {
                            $tmpfiltr = ['ID'=>$arResult["DISPLAY_PROPERTIES"]["SIGHTS"]["VALUE"], 'TYPE'=>'route'];
                            ob_start();?>
                            <a href="javascript:;" class="show-map" data-id="<?=$arResult["DISPLAY_PROPERTIES"]["SIGHTS"]["VALUE"][0]?>"  data-filter='<?= json_encode($tmpfiltr) ?>'>
                                <?$link_route = ob_get_clean();
                                }
                                ?>
                                <b><?= $arResult["DISPLAY_PROPERTIES"]["ROUTE" . POSTFIX_PROPERTY]["NAME"] ?>: </b>
                                <?if ($link_route!=''):?><?=$link_route?><?endif;?>
                                <?= strip_tags($arResult["DISPLAY_PROPERTIES"]["ROUTE" . POSTFIX_PROPERTY]["DISPLAY_VALUE"]) ?>
                                <?if ($link_route!=''):?></a><?endif;?>
                            <br>
                            <? endif; ?>

                            <!-- lang -->
                            <? if (!empty($arResult["PROPERTIES"]["LANG"]["VALUE"])): ?>
                                <?
                                $lang = null;
                                if ($p["LANG"]["VALUE"]) {
                                    $p["LANG"]["VALUE"] = (array)$p["LANG"]["VALUE"];
                                    $db_res_lang = CIBlockElement::GetList(false, array("ACTIVE" => "Y", "IBLOCK_ID" => $p["LANG"]["LINK_IBLOCK_ID"], "ID" => $p["LANG"]["VALUE"]), false, false, array("ID", "NAME", "PROPERTY_NAME" . POSTFIX_PROPERTY));
                                    $lang = null;
                                    while ($res = $db_res_lang->Fetch()) {
                                        $lang[] = $res["PROPERTY_NAME" . POSTFIX_PROPERTY . "_VALUE"] ? $res["PROPERTY_NAME" . POSTFIX_PROPERTY . "_VALUE"] : $res["NAME"];
                                    }
                                }
                                if ($lang):
                                    ?>
                                    <b><?= GetMessage('LANG') ?>:</b> <?= implode(", ", $lang) ?><br>
                                <? endif; ?>
                            <? endif ?>
                            <!-- End lang -->

                            <br>
                            <? if (!empty($arResult["PROPERTIES"]["HD_DESC" . POSTFIX_PROPERTY]["VALUE"])): ?>
                                <?= $arResult["DISPLAY_PROPERTIES"]["HD_DESC" . POSTFIX_PROPERTY]["DISPLAY_VALUE"] ?><br><br>
                            <? endif ?>
                            <!-- End Additional Text -->

            <!-- End Hight Light -->
        </div>
        <a name="block_<?= mb_strtolower($arResult["PROPERTIES"]["HD_DESC" . POSTFIX_PROPERTY]["CODE"]) ?>"></a>
        <!-- Description -->

        <!-- End Description -->
    </div>

</section>
<!-- End Hotel Content One -->
<? $this->EndViewTarget(); ?>
<!-- Check Rates-->
<? if (!empty($GLOBALS["PRICE_CALCULATION_RESULT_HTML"])): ?>
    <? $scroll[] = array("iblock_detail_prices", GetMessage('PRICES')); ?>
    <section class="check-rates detail-cn tab-pane fade in active" id="iblock_detail_prices" style="border-top: none">
        <div class="check-rates-cn " style="padding-top: 0">
            <!-- Hotel Availability -->
            <?= $GLOBALS["PRICE_CALCULATION_RESULT_HTML"] ?>
            <!-- Hotel Availability -->
			<!-- <div class="mobile-more-btn"><?=GetMessage("BUTTON_DETAIL")?></div> -->
        </div>

        <? if (!empty($arResult["DISPLAY_PROPERTIES"]["PRICE_INCLUDE" . POSTFIX_PROPERTY]["DISPLAY_VALUE"])): ?>
<!--            --><?// $scroll[] = array("iblock_detail_price_include", $arResult["PROPERTIES"]["PRICE_INCLUDE" . POSTFIX_PROPERTY]["NAME"]); ?>
            <section id="iblock_detail_price_include">
                <div class="policies-item" id="price_include">
                    <h3><?= $arResult["PROPERTIES"]["PRICE_INCLUDE" . POSTFIX_PROPERTY]["NAME"] ?></h3>
                    <p><?= $arResult["DISPLAY_PROPERTIES"]["PRICE_INCLUDE" . POSTFIX_PROPERTY]["DISPLAY_VALUE"] ?></p>
                </div>
            </section>

        <? endif; ?>

        <? if (!empty($arResult["DISPLAY_PROPERTIES"]["PRICE_NO_INCLUDE" . POSTFIX_PROPERTY]["DISPLAY_VALUE"])): ?>
<!--            --><?// $scroll[] = array("iblock_detail_price_no_include", $arResult["PROPERTIES"]["PRICE_NO_INCLUDE" . POSTFIX_PROPERTY]["NAME"]); ?>
            <section id="iblock_detail_price_no_include">
                <div class="policies-item ">
                    <h3><?= $arResult["PROPERTIES"]["PRICE_NO_INCLUDE" . POSTFIX_PROPERTY]["NAME"] ?></h3>
                    <p><?= $arResult["DISPLAY_PROPERTIES"]["PRICE_NO_INCLUDE" . POSTFIX_PROPERTY]["DISPLAY_VALUE"] ?></p>
                </div>
            </section>
        <? endif; ?>

    </section>

<? endif ?>
<!-- End Check Rates -->
<!-- Tour Program  -->
<? if (!empty($arResult["PROPERTIES"]["NDAYS" . POSTFIX_PROPERTY]["VALUE"])): ?>
    <? $scroll[] = array("iblock_detail_ndays", $arResult["PROPERTIES"]["NDAYS" . POSTFIX_PROPERTY]["NAME"]); ?>

    <section class="hl-features detail-cn tab-pane fade" id="iblock_detail_ndays">
        <div class="hl-features-cn">
            <? if (count($arResult["PROPERTIES"]["NDAYS" . POSTFIX_PROPERTY]["VALUE"]) == 1): ?>
                <a class="color" name="iblock_detail_ndays"></a>
                <div class="featured-service byday catalog_element" id="byday">
                    <div class="policies-item ">
                        <h3><?= $arResult["PROPERTIES"]["NDAYS" . POSTFIX_PROPERTY]["NAME"] ?></h3>
                        <h5><?= $arResult["DISPLAY_PROPERTIES"]["NDAYS" . POSTFIX_PROPERTY]["DESCRIPTION"][0] ?> </h5>
                        <?= $arResult["DISPLAY_PROPERTIES"]["NDAYS" . POSTFIX_PROPERTY]["DISPLAY_VALUE"] ?>
                    </div>
					<div class="options-gradient"></div>
                </div>
				<div class="mobile-more-btn"><?=GetMessage("BUTTON_DETAIL")?></div>
            <? elseif (count($arResult["PROPERTIES"]["NDAYS" . POSTFIX_PROPERTY]["VALUE"]) > 1): ?>
                <a class="color" name="iblock_detail_ndays"></a>
                <div class="featured-service byday catalog_element_tour">
                    <div class="policies-item">
                        <h3><?= $arResult["PROPERTIES"]["NDAYS" . POSTFIX_PROPERTY]["NAME"] ?></h3>
                        <div class="panel-group no-margin" id="accordion">
                            <? $d = 1; ?>
                            <? foreach ($arResult["DISPLAY_PROPERTIES"]["NDAYS" . POSTFIX_PROPERTY]["DISPLAY_VALUE"] as $k => $value): ?>
                                <div class="panel">
                                    <div class="panel-heading" id="heading<?= $d ?>">
                                        <h4 class="panel-title"><a class="accordion-toggle<? if ($d != 1): ?> collapsed<? endif ?>" data-toggle="collapse" href="#collapse<?= $d ?>">
                                                <small><?= GetMessage('DAY') ?> <?= $d ?>:</small><?= $arResult["DISPLAY_PROPERTIES"]["NDAYS" . POSTFIX_PROPERTY]["DESCRIPTION"][$k] ?> <span class="icon fa fa-angle-<? if ($d == 1): ?>up<? else: ?>down<? endif ?>"></span>
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
        </div>

    </section>

<? endif; ?>
<? if (!empty($arResult["PROPERTIES"]["ADDITIONAL" . POSTFIX_PROPERTY]["VALUE"])): ?>
    <? $scroll[] = array("iblock_detail_additionally", GetMessage('ADDITIONAL')); ?>
    <!-- Details-Additional -->
    <section id="iblock_detail_additionally" class="details-policies detail-cn tab-pane fade">
        <div class="details-policies-cn">
            <div class="policies-item">
                <h3><?= $arResult["PROPERTIES"]["ADDITIONAL" . POSTFIX_PROPERTY]["NAME"] ?></h3>
                <?= $arResult["DISPLAY_PROPERTIES"]["ADDITIONAL" . POSTFIX_PROPERTY]["DISPLAY_VALUE"] ?>
            </div>

        </div>
    </section>
    <!-- End Details Additional -->
<? endif ?>
<!-- End Tour Program -->
<? if (!empty($arResult["PROPERTIES"]["DOCUMENT" . POSTFIX_PROPERTY]["VALUE"])): ?>
    <? $scroll[] = array("iblock_detail_document", GetMessage('DOCUMENT')); ?>
    <!-- Details-Policies -->
    <section id="iblock_detail_document" class="details-policies detail-cn tab-pane fade">
        <div class="details-policies-cn">
            <div class="policies-item">
                <h3><?= $arResult["PROPERTIES"]["DOCUMENT" . POSTFIX_PROPERTY]["NAME"] ?></h3>
                <?= $arResult["DISPLAY_PROPERTIES"]["DOCUMENT" . POSTFIX_PROPERTY]["DISPLAY_VALUE"] ?>
            </div>

        </div>
    </section>
    <!-- End Details Policies Item -->
<? endif ?>
<? if (!empty($arResult["PROPERTIES"]["FILE"]["VALUE"])): ?>
    <? $scroll[] = array("iblock_detail_file_download", GetMessage('FILE')); ?>
    <!-- Details-Policies -->
    <section id="iblock_detail_file_download" class="details-policies detail-cn tab-pane fade">
        <div class="details-policies-cn">
            <div class="policies-item">
                <h3><?= GetMessage('FILE') ?></h3>
                <?if (is_array($arResult["DISPLAY_PROPERTIES"]["FILE"]["DISPLAY_VALUE"])){
                    foreach ($arResult["DISPLAY_PROPERTIES"]["FILE"]["FILE_VALUE"] as $file):?>
                <a target="_blank" href="<?= $file["SRC"]?>"><?= $file["ORIGINAL_NAME"]?></a>
                    <?endforeach;
                } else {
                    $file = $arResult["DISPLAY_PROPERTIES"]["FILE"]["FILE_VALUE"];
                    ?><a target="_blank" href="<?= $file["SRC"]?>"><?= $file["ORIGINAL_NAME"]?></a><?
                }?>
            </div>

        </div>
    </section>
    <!-- End Details Policies Item -->
<? endif ?>
<?if ((!empty($arResult["DISPLAY_PROPERTIES"]["TOWN"]["VALUE"]) && count($arResult["DISPLAY_PROPERTIES"]["TOWN"]["VALUE"])>1) || (!empty($arResult["DISPLAY_PROPERTIES"]["SIGHTS"]["VALUE"]) && count($arResult["DISPLAY_PROPERTIES"]["SIGHTS"]["VALUE"]))):?>
    <? $scroll[] = array("iblock_detail_map", GetMessage('MAP'));
        if (!empty($arResult["DISPLAY_PROPERTIES"]["TOWN"]["VALUE"]) && count($arResult["DISPLAY_PROPERTIES"]["TOWN"]["VALUE"])>1)
             $datatofitr = ['id'=>$arResult["DISPLAY_PROPERTIES"]["TOWN"]["VALUE"][0], 'filter'=>['ID'=>$arResult["DISPLAY_PROPERTIES"]["TOWN"]["VALUE"], 'TYPE'=>'route', 'minmap'=>'Y']];
        else $datatofitr = ['id'=>$arResult["DISPLAY_PROPERTIES"]["SIGHTS"]["VALUE"][0], 'filter'=>['ID'=>$arResult["DISPLAY_PROPERTIES"]["SIGHTS"]["VALUE"], 'TYPE'=>'route', 'minmap'=>'Y']];
    ?>
    <section class="hl-features detail-cn tab-pane fade" id="iblock_detail_map">
        <div class="hl-features-cn">
            <div class="policies-item">
                <h3><?= GetMessage('MAP') ?></h3>
                <div class="mapnew" style="width: 100%; height: 400px"></div>
                <script>
                    var data = <?=\Bitrix\Main\Web\Json::encode($datatofitr)?>;
                    BX.ajax.runAction('kosmos:main.api.map.get', {
                          data: data
                      }).then(function(response){

                          if(response.status == 'success'){

                              if(response.data.success){
                                  $('.mapnew').html(response.data.html);
                              }
                              else{
                                  console.error(response.data.message);
                              }

                          }
                          else{
                              console.error(response.errors);
                          }

                      });
                </script>
            </div>
        </div>
    </section>
<?endif;?>
<? if (!empty($arResult["PROPERTIES"]["TOWN"]["VALUE"]) && !empty($arResult['ROUTE_INFO']) && count($arResult['ROUTE_INFO']) > 1): ?>
    <? $scroll[] = array("iblock_detail_map", GetMessage('MAP')); ?>
    <section class="hl-features detail-cn tab-pane fade" id="iblock_detail_map">
        <div class="hl-features-cn">
            <div class="policies-item">
                <h3><?= GetMessage('MAP') ?></h3>
                <?
                $this->addExternalJs(SITE_TEMPLATE_PATH . "/js/MapAdapter/MapAdapter.js");
                ?>

                <div style="width: 100%; height: 400px" id="<?= $htmlMapID ?>"></div>
                <script>
                    $(document).ready(function () {
                        var mapAdapter = new MapAdapter({
                            map_id: "<?= $htmlMapID ?>",
                            center: {
                                lat: 53.53,
                                lng: 27.34
                            },
                            object: "ymaps"
                        });

                        <? if (count($arResult['ROUTE_INFO']) > 1): ?>
                        mapAdapter.drawRoute(<?= \Bitrix\Main\Web\Json::encode($arResult['ROUTE_INFO']) ?>);
                        <? else: ?>
                        mapAdapter.addMarker(<?= \Bitrix\Main\Web\Json::encode($arResult['ROUTE_INFO'][0]) ?>);
                        <? endif ?>
                    });

                </script>

            </div>
        </div>
    </section>
<? endif ?>
<? if (!empty($arResult["PROPERTIES"]["YOUTUBE" . POSTFIX_PROPERTY]["VALUE"]) || !empty($arResult["PROPERTIES"]["VIMEO" . POSTFIX_PROPERTY]["VALUE"])): ?>
    <? $scroll[] = array("iblock_detail_youtube", GetMessage('YOUTUBE')); ?>
    <section class="details-policies detail-cn tab-pane fade" id="iblock_detail_youtube">
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

<? $this->SetViewTarget("menu-item-detail-tours"); ?>

<? if (!empty($scroll)): ?>

    <? foreach ($scroll as $key => $s): ?>
        <? if (!empty($s)): ?>
            <li class="<?=($key == 1) ? 'active' : ''?>"><a data-toggle="tab" href="#<?= $s[0] ?>"><?= $s[1] ?></a></li>
        <? endif ?>
    <? endforeach ?>

<? endif ?>
<? $this->EndViewTarget(); ?>