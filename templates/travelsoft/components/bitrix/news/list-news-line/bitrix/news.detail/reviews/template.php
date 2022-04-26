<?

$this->addExternalCss(SITE_TEMPLATE_PATH . "/css/slider-prop.css");

$this->addExternalJs(SITE_TEMPLATE_PATH . "/js/jquery.sliderPro.min.js");
$this->addExternalJs(SITE_TEMPLATE_PATH . "/js/slide.js");

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
<!--<h1><?/*=$arResult["NAME"]*/?></h1>-->
<p>
    <?if (count($arResult["PROPERTIES"]["TRAVEL_DATE"]["VALUE"]) == 1):?>
			<i class="fa fa-calendar"></i> <span <?if($arResult["IBLOCK_ID"] == NEWS_IBLOCK_ID):?>itemprop="datePublished" content="<?=date("Y-m-d", MakeTimeStamp($arResult['PROPERTIES']['TRAVEL_DATE']['VALUE']))?>"<?endif?>><?= substr($arResult["PROPERTIES"]["DATE_FROM"]["VALUE"], 0, 10) ?></span>
			<? if (!empty($arResult["PROPERTIES"]["TRAVEL_DATE"]["VALUE"])): ?>
				- <?= substr($arResult["PROPERTIES"]["TRAVEL_DATE"]["VALUE"], 0, 10) ?>
			<? endif; ?>
    <? elseif (count($arResult["PROPERTIES"]["TRAVEL_DATE"]["VALUE"]) > 1): ?>
		<? if (!empty($arResult["PROPERTIES"]["TRAVEL_DATE"]["VALUE"][0])): ?>
			<i class="fa fa-calendar"></i> <span <?if($arResult["IBLOCK_ID"] == NEWS_IBLOCK_ID):?>itemprop="datePublished" content="<?=date("Y-m-d", MakeTimeStamp($arResult['PROPERTIES']['TRAVEL_DATE']['VALUE'][0]))?>"<?endif?>><?= substr($arResult["PROPERTIES"]["DATE_FROM"]["VALUE"][0], 0, 10) ?></span>
			<? if (!empty($arResult["PROPERTIES"]["TRAVEL_DATE"]["VALUE"][1])): ?>
				- <?= substr($arResult["PROPERTIES"]["TRAVEL_DATE"]["VALUE"][1], 0, 10) ?>
			<? endif; ?>
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

<?/*if ($arParams["HIDE_DETAIL_PICTURE"] !== "Y"): ?>
    <? $waterMark = ''; ?>
    <? if ($arParams["NO_SHOW_WATERMARK"] !== "Y"): ?>
        <? $waterMark = $arWaterMark ?>
    <? endif ?>
    <?
    if (count($arResult["PROPERTIES"]["PHOTO"]["VALUE"]) == 1):
        $an_file = CFile::ResizeImageGet($arResult["PROPERTIES"]["PHOTO"]["VALUE"][0], array('width' => 823, 'height' => 428), BX_RESIZE_IMAGE_EXACT, true, $waterMark);
        $pre_photo_915 = $an_file["src"];
        ?>
        <img src="<?= $pre_photo_915 ?>" alt="<?echo LANGUAGE_ID == "ru" ? $arResult["PROPERTIES"]["PHOTO"]["DESCRIPTION"]["0"] : $arResult["PROPERTIES"]["IMG_DESCRIPTION".POSTFIX_PROPERTY]["VALUE"]["0"]?>" class="img-responsive">
    <? elseif (count($arResult["PROPERTIES"]["PHOTO"]["VALUE"]) > 1): ?>
        <section class="detail-slider">
            <!-- Lager Image -->
            <div class="slide-room-lg">
                <div id="slide-room-lg">
                    <?
                    foreach ($arResult["PROPERTIES"]["PHOTO"]["VALUE"] as $key => $item):
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
                            foreach ($arResult["PROPERTIES"]["PHOTO"]["VALUE"] as $item):
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
<? endif; */?>

<?if ($arParams["HIDE_DETAIL_PICTURE"] !== "Y"): ?>
    <? $waterMark = ''; ?>
    <? if ($arParams["NO_SHOW_WATERMARK"] !== "Y"): ?>
        <? $waterMark = $arWaterMark ?>
    <? endif ?>
    <?
    if (count($arResult["PROPERTIES"]["PHOTO"]["VALUE"]) == 1):
        $an_file = CFile::ResizeImageGet($arResult["PROPERTIES"]["PHOTO"]["VALUE"][0], array('width' => 823, 'height' => 428), BX_RESIZE_IMAGE_EXACT, true, $waterMark);
        $pre_photo_915 = $an_file["src"];
        ?>
        <img src="<?= $pre_photo_915 ?>" alt="<?echo LANGUAGE_ID == "ru" ? $arResult["PROPERTIES"]["PHOTO"]["DESCRIPTION"]["0"] : $arResult["PROPERTIES"]["IMG_DESCRIPTION".POSTFIX_PROPERTY]["VALUE"]["0"]?>" class="img-responsive">
    <? elseif (count($arResult["PROPERTIES"]["PHOTO"]["VALUE"]) > 1): ?>
        <div style="margin-top: 20px;">
            <div class="slider-container">
                <div id="search-preloader-slider" class="reloader-postion">

                    <div id="search-page-loading">
                        <div></div>
                    </div>
                </div>
            <div id="slider-room" class="slider-pro">
                <div class="sp-slides">
                    <?
                    $i = 0;
                    foreach ($arResult["PROPERTIES"]["PHOTO"]["VALUE"] as $item):
                        $file_big = CFile::ResizeImageGet($item, Array('width' => 1170, 'height' => 640), BX_RESIZE_IMAGE_EXACT, true, $arWaterMark);
                        $img_count++;
                        ?>
                        <div class="sp-slide">
                            <img src="" data-src="<?= $file_big["src"]; ?>"  alt="<?= $arResult["PROPERTIES"]["PHOTO"]["DESCRIPTION"][$i] ?>">
                        </div>
                        <? $i++;
                    endforeach;
                    ?>
                </div>
                <div class="sp-thumbnails">
                    <?
                    $i = 0;
                    foreach ($arResult["PROPERTIES"]["PHOTO"]["VALUE"] as $item):
                        $file_small = CFile::ResizeImageGet($item, Array('width' => 220, 'height' => 100), BX_RESIZE_IMAGE_EXACT, true);
                        $img_count++;
                        ?>
                        <div class="sp-thumbnail">

                            <img src="" data-src="<?= $file_small["src"]; ?>"  alt="<?= $arResult["PROPERTIES"]["PHOTO"]["DESCRIPTION"][$i] ?>">
                        </div>
                        <? $i++;
                    endforeach;
                    ?>
                </div>
            </div>

            </div>
        </div>
       
    <? endif; ?>
<? endif; ?>

<? if (!empty($arResult["PROPERTIES"]["ITEM" . POSTFIX_PROPERTY]["VALUE"])): ?>
	<? if (is_array($arResult["DISPLAY_PROPERTIES"]["ITEM" . POSTFIX_PROPERTY]["DISPLAY_VALUE"])): ?>
	<div class="policies-item">
	<? foreach($arResult["DISPLAY_PROPERTIES"]["ITEM" . POSTFIX_PROPERTY]["DISPLAY_VALUE"] as $tmpitem):?>
		<p>
			<?=$tmpitem;?>
		</p>
	<? endforeach; ?>
	</div>
	<? else: ?>
	<div class="policies-item">
		<p>
			<?=$arResult["DISPLAY_PROPERTIES"]["ITEM" . POSTFIX_PROPERTY]["DISPLAY_VALUE"];?>
		</p>
	</div>
	<? endif; ?>
<? elseif (!empty($arResult["PROPERTIES"]["ITEM"]["VALUE"])): ?>
	<? if (is_array($arResult["DISPLAY_PROPERTIES"]["ITEM"]["DISPLAY_VALUE"])): ?>
	<div class="policies-item">
	<? foreach($arResult["DISPLAY_PROPERTIES"]["ITEM"]["DISPLAY_VALUE"] as $tmpitem):?>
		<p>
			<?=$tmpitem; ?>
		</p>
	<? endforeach; ?>
	</div>
	<? else: ?>
	<div class="policies-item">
		<p>
			<?=$arResult["DISPLAY_PROPERTIES"]["ITEM"]["DISPLAY_VALUE"];?>
		</p>
	</div>
	<? endif; ?>
<?endif;?>
<?if(!empty($arResult["PREVIEW_TEXT"])):?>
	<div class="policies-item">
		<p>
			<?=$arResult["PREVIEW_TEXT"]?>
		</p>
	</div>
<?endif?>
					

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
       <div> 
<script src="//yastatic.net/es5-shims/0.0.2/es5-shims.min.js"></script>
<script src="//yastatic.net/share2/share.js"></script>
<div class="ya-share2" data-services="vkontakte,facebook,odnoklassniki,gplus,twitter" data-counter="" 
						data-lang="<?if (LANGUAGE_ID=="by"){ echo "be";} else {echo LANGUAGE_ID; }?>" 
<?if (!empty($pre_photo_915)):?>
						data-image="https://vetliva.<?=LANGUAGE_ID?><?=$pre_photo_915?>"
<?elseif(!empty($file_big["src"])):?>
						data-image="https://vetliva.<?=LANGUAGE_ID?><?=$file_big["src"]?>"
<?endif;?>

</div>
</div>

<div style="clear: both;"></div>
