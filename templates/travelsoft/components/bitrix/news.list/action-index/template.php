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

\Bitrix\Main\Loader::includeModule("travelsoft.currency");
?>

<?
$is_mobile = check_smartphone();
?>


<div class="sales-cn sales-top-actions <?/*if ($is_mobile):?> owl-carousel  <?endif;*/?>">
    <div class="row">
	  <?if ($is_mobile):?> <div id="actions-slide" class="owl-carousel"> <?endif;?>
        <? $i = 0; ?>
        <? foreach ($arResult["ITEMS"] as $arItem): ?>
            <? $i++; ?>

            <div class="col-xs-6 col-md-4 <?/* if (($i != 3) & ($i != 4)): ?>col-md-3<? endif; */?>">
                <a href="<?= $arItem["DETAIL_PAGE_URL"] ?>" class="sales-item-a">
                    <div class="sales-item">
                        <figure class="home-sales-img">
                            <? if (($i != 3) & ($i != 4)): ?>
                                <?
                                if (!empty($arItem["PREVIEW_PICTURE"])):
                                    $an_file = CFile::ResizeImageGet($arItem["PREVIEW_PICTURE"], array('width' => 426, 'height' => 279), BX_RESIZE_IMAGE_EXACT, true, array(), false, 80);
                                    $pre_photo = $an_file["src"];
								elseif (!empty($arItem["PROPERTIES"]["PICTURES".POSTFIX_PROPERTY]["VALUE"])):
									$an_file = CFile::ResizeImageGet($arItem["PROPERTIES"]["PICTURES".POSTFIX_PROPERTY]["VALUE"][0], array('width'=>426, 'height'=>279), BX_RESIZE_IMAGE_EXACT, true, array(), false, 80);
									$pre_photo=$an_file["src"];
                                elseif (!empty($arItem["PROPERTIES"]["PICTURES"]["VALUE"])):
                                    $an_file = CFile::ResizeImageGet($arItem["PROPERTIES"]["PICTURES"]["VALUE"][0], array('width' => 426, 'height' => 279), BX_RESIZE_IMAGE_EXACT, true, array(), false, 80);
                                    $pre_photo = $an_file["src"];
                                else:
                                    $pre_photo = SITE_TEMPLATE_PATH . "/images/nophoto-240x150.jpg";
                                endif;
                                ?>
                            <? else: ?>
                                <?
                                if (!empty($arItem["PREVIEW_PICTURE"])):
                                    $an_file = CFile::ResizeImageGet($arItem["PREVIEW_PICTURE"], array('width' => 426, 'height' => 279), BX_RESIZE_IMAGE_EXACT, true, array(), false, 80);
                                    $pre_photo = $an_file["src"];
								elseif (!empty($arItem["PROPERTIES"]["PICTURES".POSTFIX_PROPERTY]["VALUE"])):
									$an_file = CFile::ResizeImageGet($arItem["PROPERTIES"]["PICTURES".POSTFIX_PROPERTY]["VALUE"][0], array('width'=>426, 'height'=>279), BX_RESIZE_IMAGE_EXACT, true, array(), false, 80);
									$pre_photo=$an_file["src"];
                                elseif (!empty($arItem["PROPERTIES"]["PICTURES"]["VALUE"])):
                                    $an_file = CFile::ResizeImageGet($arItem["PROPERTIES"]["PICTURES"]["VALUE"][0], array('width' => 426, 'height' => 279), BX_RESIZE_IMAGE_EXACT, true, array(), false, 80);
                                    $pre_photo = $an_file["src"];
                                else:
                                    $pre_photo = SITE_TEMPLATE_PATH . "/images/nophoto-240x150.jpg";
                                endif;
                                ?>
                                    <? endif; ?>
                            <img src="<?= $pre_photo ?>" alt="<?echo LANGUAGE_ID == "ru" ? $arItem["PROPERTIES"]["PICTURES"]["DESCRIPTION"][$i - 1] : $arItem["PROPERTIES"]["IMG_DESCRIPTION".POSTFIX_PROPERTY]["VALUE"][$i - 1]?>">
                        </figure>
                        <div class="home-sales-text">
                            <div class="home-sales-name-places">
                                <div class="home-sales-name">
                                    <? echo LANGUAGE_ID == "ru" ? $arItem["NAME"] : $arItem["PROPERTIES"]["NAME" . POSTFIX_PROPERTY]["VALUE"] ?>
                                </div>
								
								<?if($arParams["DISPLAY_DATE"]!="N" && $arItem['FIELDS']['DATE_ACTIVE_TO']):?>
									<div class="action-index--date">
											<img src="<?=SITE_TEMPLATE_PATH . "/images/clock-index.svg";?>" class="marker"><span><?=$arItem['DISPLAY_ACTIVE_FROM']?> - <?=$arItem['FIELDS']['DATE_ACTIVE_TO']?></span>
									</div>
								<?endif;?>
								
                                <div class="home-sales-places">
                                    <?
									$accomodation = $sanatorium = $attraction =$region = $town = "";
                                    if (!empty($arItem["PROPERTIES"]["REGIONS"]["VALUE"])) {
                                        $region = strip_tags($arItem["DISPLAY_PROPERTIES"]["REGIONS"]["DISPLAY_VALUE"]);
                                        if (LANGUAGE_ID != "ru") {
                                            $prop = getIBElementProperties($arItem["PROPERTIES"]["REGIONS"]["VALUE"]);
                                            $region = $prop["NAME" . POSTFIX_PROPERTY]["VALUE"];
                                        }
                                    }
                                    if (!empty($arItem["PROPERTIES"]["TOWN"]["VALUE"])) {
                                        $town = strip_tags($arItem["DISPLAY_PROPERTIES"]["TOWN"]["DISPLAY_VALUE"]);
                                        if (LANGUAGE_ID != "ru") {
                                            $prop = getIBElementProperties($arItem["PROPERTIES"]["TOWN"]["VALUE"]);
                                            $town = $prop["NAME" . POSTFIX_PROPERTY]["VALUE"];
                                        }
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
                                    ?>
									<?if ($accomodation): ?><img src="<?=SITE_TEMPLATE_PATH . "/images/location-marker.svg";?>" class="marker"><?= $accomodation ?> <?endif;?>
									<?if ($sanatorium): ?><img src="<?=SITE_TEMPLATE_PATH . "/images/location-marker.svg";?>" class="marker"><?= $sanatorium ?> <?endif;?>
									<?if ($attraction): ?><img src="<?=SITE_TEMPLATE_PATH . "/images/location-marker.svg";?>" class="marker"><?= $attraction ?> <?endif;?>
                                </div>
                            </div>
                            <?if ($arItem["PROPERTIES"]["PRICE"]["VALUE"] && $arItem["PROPERTIES"]["CURRENCY"]["VALUE"]):?>
                            <div class="price-box">

                                <?/*<span class="price old-price"><?= GetMessage("FROM") ?> </span>*/?> 
								
								<span class="price special-price"> <?=  \travelsoft\Currency::getInstance()->convertCurrency($arItem["PROPERTIES"]["PRICE"]["VALUE"], $arItem["PROPERTIES"]["CURRENCY"]["VALUE"])?></span>
								<?if ($arItem["PROPERTIES"]["OLD_PRICE"]["VALUE"] && $arItem["PROPERTIES"]["CURRENCY"]["VALUE"]):?>
									<div style="color: #939393; text-align: right; text-decoration: line-through"><?=  \travelsoft\Currency::getInstance()->convertCurrency($arItem["PROPERTIES"]["OLD_PRICE"]["VALUE"], $arItem["PROPERTIES"]["CURRENCY"]["VALUE"])?></div>
								<?endif?>
							
							</div>
                            <?endif?>
                        </div>
                    </div>
                </a>
            </div>
<? endforeach; ?>
		<?if ($is_mobile):?></div> <!-- End owl-carousel --> <?endif;?>
    </div>
</div>



<script>
  $(document).ready(function () {
			if ($(window).width() < 960) {
				$('#actions-slide').owlCarousel({
					items: 1,
					loop:true,
					margin:20,
					nav:true,
					navText: ['<span class="prev-next-room prev-room"></span>','<span class="prev-next-room next-room"></span>'],
					dots: true,
					pagination : true,
					responsive : {	
							0 : {
								items: 1,
								margin: 10,
								slideBy:1,
								stagePadding: 20,
							},
							
							480 : {
								items: 2,
								slideBy:1,
								stagePadding: 20,
								margin: 10,
							},

							768 : {
								items: 2,
								slideBy:2,
								nav:true,
							}
							,
							991 : {
								items: 4,
							}
					}
				});
				
			}
			
	}); 
	
	
	
</script>