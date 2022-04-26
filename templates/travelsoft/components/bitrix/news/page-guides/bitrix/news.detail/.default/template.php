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

$this->addExternalCss(SITE_TEMPLATE_PATH . "/css/slider-prop.css");

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
        </div>
    </div>
</section>
<?
if ($arResult["PROPERTIES"]["PICTURES" . POSTFIX_PROPERTY]["VALUE"] != false && count($arResult["PROPERTIES"]["PICTURES" . POSTFIX_PROPERTY]["VALUE"]) == 1):
    $an_file = CFile::ResizeImageGet($arResult["PROPERTIES"]["PICTURES" . POSTFIX_PROPERTY]["VALUE"][0], array('width' => 1170, 'height' => 641), BX_RESIZE_IMAGE_EXACT, true);
    $pre_photo = $an_file["src"];
    ?>
    <img src="<?= $pre_photo ?>" alt="<?= $arResult["PROPERTIES"]["PICTURES" . POSTFIX_PROPERTY]["DESCRIPTION"][0] ?>" class="img-responsive img-center">
<?
elseif (count($arResult["PROPERTIES"]["PICTURES"]["VALUE"]) == 1):
    $an_file = CFile::ResizeImageGet($arResult["PROPERTIES"]["PICTURES"]["VALUE"][0], array('width' => 1170, 'height' => 641), BX_RESIZE_IMAGE_EXACT, true);
    $pre_photo = $an_file["src"];
    ?>
    <img src="<?= $pre_photo ?>" alt="<?= $arResult["PROPERTIES"]["PICTURES"]["DESCRIPTION"][0] ?>" class="img-responsive img-center">
<? elseif (count($arResult["PROPERTIES"]["PICTURES"]["VALUE"]) > 1 || count($arResult["PROPERTIES"]["PICTURES" . POSTFIX_PROPERTY]["VALUE"]) > 1): ?>
    <? $imgarr = !empty($arResult["PROPERTIES"]["PICTURES" . POSTFIX_PROPERTY]["VALUE"]) ? $arResult["PROPERTIES"]["PICTURES" . POSTFIX_PROPERTY] : $arResult["PROPERTIES"]["PICTURES"]; ?>
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
                            <img data-src="<?= $file_big["src"]; ?>" data-src="<?= $file_big["src"]; ?>"  alt="<?= $arResult["PROPERTIES"]["PICTURES"]["DESCRIPTION"][$i] ?>">
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

                            <img data-src="<?= $file_small["src"]; ?>" alt="<?= $arResult["PROPERTIES"]["PICTURES"]["DESCRIPTION"][$i] ?>">
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
            <!-- Hight Light -->
            <div class="hight-light">

                <!-- Vote Text -->
                <div class="row">
                    <!-- Recommend -->

                    <div class="scroll-heading col-xs-12 col-sm-12 col-md-6 col-lg-12">

                    </div>
                </div>
                <!-- End Vote Text -->



            </div>
            <!-- End Hight Light -->
        </div>
        <!-- Description -->
        <div class="col-lg-9 hl-customer-like">

            <?php if(!empty($arResult['PROPERTIES']['PHONE']['VALUE'])):?>
                <a href="javascript:;" title="" data-src="#guide-contacts-<?=$arResult['ID']?>" class="m-popup btn-blue">
                    <?=Loc::getMessage('T_GUIDE_DETAIL_SHOW_CONTACTS')?>
                </a>
                <div id="guide-contacts-<?=$arResult['ID']?>" class="add-review-form mfp-hide">
                    <div class="row bg-none">
                        <div class="col-md-12">
                            <div class="popup-inner">
								<div class="popup-ttl"><?=Loc::getMessage('T_GUIDE_DETAIL_CONTACTS_TITLE')?></div>
								<div class="popup-phones">
									<?php foreach($arResult['PROPERTIES']['PHONE']['VALUE'] as $value):?>
										<a href="tel:+<?=\Kosmos\Main\Helpers\Common::getPhone($value)?>" title="<?=$value?>" target="_blank" rel="nofollow"><?=$value?></a><br>
									<?php endforeach?>
								</div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif?>
            <a href="javascript:;" data-src="#feedback-guide-form-wrapper" class="m-popup btn-blue" title="" id="call-feedback-guide-form"><?=Loc::getMessage('T_GUIDE_DETAIL_FEEDBACK')?></a>

        </div>
        <!-- End Description -->
    </div>

</section>
<!-- End Hotel Content One -->
<? $this->EndViewTarget(); ?>

<?php if($arResult['DISPLAY_PROPERTIES']['ABOUT_SELF' . POSTFIX_PROPERTY]['DISPLAY_VALUE']):?>
    <?php
    $sectionId = 'guide-about-self';
    $scroll[] = [$sectionId, Loc::getMessage('T_GUIDE_DETAIL_ABOUT_SELF')]?>
    <section class="details-policies detail-cn" id="<?=$sectionId?>">
        <div class="details-policies-cn">
            <h3><?=Loc::getMessage('T_GUIDE_DETAIL_ABOUT_SELF')?></h3>
            <p><?=$arResult['DISPLAY_PROPERTIES']['ABOUT_SELF' . POSTFIX_PROPERTY]['DISPLAY_VALUE']?></p>
        </div>
    </section>
<?php endif?>

<?php if(!empty($arResult['DISPLAY_PROPERTIES']['TOUR_LANGUAGE']['DISPLAY_VALUE'])):?>
    <?php
    $sectionId = 'guide-tour-language';
    $scroll[] = [$sectionId, Loc::getMessage('T_GUIDE_DETAIL_TOUR_LANGUAGE')]?>
    <section class="details-policies detail-cn" id="<?=$sectionId?>">
        <div class="details-policies-cn">
            <h3><?=Loc::getMessage('T_GUIDE_DETAIL_TOUR_LANGUAGE')?></h3>
            <div class="featured-service">
                <ul class="service-accmd popover-ul">
                    <?php foreach($arResult['DISPLAY_PROPERTIES']['TOUR_LANGUAGE']['DISPLAY_VALUE'] as $value):?>
                        <li><div><img src="/local/templates/travelsoft/images/icon-check.png" alt=""></div><?=$value?></li>
                    <?php endforeach ?>
                </ul>
            </div>
        </div>
		<div class="options-gradient"></div>
    </section>
	<div class="mobile-more-btn"><?=GetMessage("BUTTON_DETAIL")?></div>
<?php endif?>

<?php if(!empty($arResult['DISPLAY_PROPERTIES']['TOUR_TYPE']['DISPLAY_VALUE'])):?>
    <?php
    $sectionId = 'guide-tour-type';
    $scroll[] = [$sectionId, Loc::getMessage('T_GUIDE_DETAIL_TOUR_TYPE')]?>
    <section class="details-policies detail-cn" id="<?=$sectionId?>">
        <div class="details-policies-cn">
            <h3><?=Loc::getMessage('T_GUIDE_DETAIL_TOUR_TYPE')?></h3>
            <div class="featured-service">
                <ul class="service-accmd popover-ul">
                    <?php foreach($arResult['DISPLAY_PROPERTIES']['TOUR_TYPE']['DISPLAY_VALUE'] as $value):?>
                        <li><div><img src="/local/templates/travelsoft/images/icon-check.png" alt=""></div><?=$value?></li>
                    <?php endforeach ?>
                </ul>
            </div>
        </div>
		<div class="options-gradient"></div>
    </section>
	<div class="mobile-more-btn"><?=GetMessage("BUTTON_DETAIL")?></div>
<?php endif?>

<? if (!empty($arResult["PROPERTIES"]["YOUTUBE" . POSTFIX_PROPERTY]["VALUE"]) || !empty($arResult["PROPERTIES"]["VIMEO" . POSTFIX_PROPERTY]["VALUE"])): ?>
    <? $scroll[] = array("iblock_detail_youtube", GetMessage('YOUTUBE')); ?>
    <section class="details-policies detail-cn" id="iblock_detail_youtube">
        <div class="details-policies-cn">
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
    </section>
<? endif ?>

<? $this->SetViewTarget("menu-item-detail-tours"); ?>

<? if (!empty($scroll)): ?>

    <? foreach ($scroll as $s): ?>
        <? if (!empty($s)): ?>
            <li><a href="#<?= $s[0] ?>" class="anchor"><?= $s[1] ?></a></li>
        <? endif ?>
    <? endforeach ?>

<? endif ?>
<? $this->EndViewTarget(); ?>