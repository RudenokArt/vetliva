<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
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
<div class="sales-cn">
	<div class="row">
		<div id="news-slide" class="news-section owl-carousel">
		<?$i=0;?>
		<?foreach($arResult["ITEMS"] as $arItem):?>
		<?$i++;?>
        <?if ($i==1):?>
			<div class="item first-item">
				<a href="<?=$arItem["DETAIL_PAGE_URL"]?>" class="sales-item-a">
					<div class="sales-item">
						<figure class="home-sales-img">
										 <?if (!empty($arItem["PREVIEW_PICTURE"])):
													$an_file = CFile::ResizeImageGet($arItem["PREVIEW_PICTURE"], array('width'=>650, 'height'=>427), BX_RESIZE_IMAGE_EXACT, true, array(), false, 70);
													$pre_photo=$an_file["src"];
													elseif (!empty($arItem["PROPERTIES"]["PICTURES".POSTFIX_PROPERTY]["VALUE"])):
													$an_file = CFile::ResizeImageGet($arItem["PROPERTIES"]["PICTURES".POSTFIX_PROPERTY]["VALUE"][0], array('width'=>650, 'height'=>427), BX_RESIZE_IMAGE_EXACT, true, array(), false, 70);
													$pre_photo=$an_file["src"];
													elseif (!empty($arItem["PROPERTIES"]["PICTURES"]["VALUE"])):
													$an_file = CFile::ResizeImageGet($arItem["PROPERTIES"]["PICTURES"]["VALUE"][0], array('width'=>650, 'height'=>427), BX_RESIZE_IMAGE_EXACT, true, array(), false, 70);
													$pre_photo=$an_file["src"];
													else:
													$pre_photo=SITE_TEMPLATE_PATH."/images/nophoto-292x180.jpg";
													endif;
													?>
							<img src="<?=$pre_photo?>" alt="<?echo LANGUAGE_ID == "ru" ? $arItem["PROPERTIES"]["PICTURES"]["DESCRIPTION"][$i - 1] : $arItem["PROPERTIES"]["IMG_DESCRIPTION".POSTFIX_PROPERTY]["VALUE"][$i - 1]?>">
						</figure>
						<? if (!empty($arItem["PROPERTIES"]["YOUTUBE" . POSTFIX_PROPERTY]["VALUE"])): ?><div class="video-play"><img src="<?=SITE_TEMPLATE_PATH?>/images/icon/play-button.png"></div><?endif;?>
						<div class="home-sales-text">
							<div class="news-date"><?=$arItem['DISPLAY_ACTIVE_FROM']?></div>
							<div class="home-sales-name-places">
								<div class="home-sales-name">
									<?echo LANGUAGE_ID == "ru" ? substr2($arItem["NAME"], 75) : substr2($arItem["PROPERTIES"]["NAME".POSTFIX_PROPERTY]["VALUE"], 70);?>
								</div>
							</div>
							<?if($arParams["DISPLAY_DATE"]!="N"):?><i class="fa fa-calendar"></i>
								<?
									if (!empty($arItem["PROPERTIES"]["DATE_NEED"]["VALUE"]))
										echo FormatDateFromDB($arItem["PROPERTIES"]["DATE_NEED"]["VALUE"], 'SHORT');
									else
										echo FormatDateFromDB($arItem["DATE_CREATE"], 'SHORT');
								?>
							<br>
							<?endif;?>
							<?if ($arItem['PROPERTIES']['DATE_FROM']['VALUE']):?>
								<i class="fa fa-calendar"></i> <?= implode2(array_map(function ($it) {return date("d.m.Y", MakeTimeStamp($it)); }, (array)$arItem['PROPERTIES']['DATE_FROM']['VALUE']), " - ")?><?endif?>
						</div>
					</div>
				</a>
			</div>
			
		   <?else:?>
			
		   <div class="item other-item">
				<a href="<?=$arItem["DETAIL_PAGE_URL"]?>" class="sales-item-a">
					<div class="sales-item other-item-inner">
						<figure class="home-sales-img">
										 <?if (!empty($arItem["PREVIEW_PICTURE"])):
													$an_file = CFile::ResizeImageGet($arItem["PREVIEW_PICTURE"], array('width'=>196, 'height'=>129), BX_RESIZE_IMAGE_EXACT, true, array(), false, 70);
													$pre_photo=$an_file["src"];
													elseif (!empty($arItem["PROPERTIES"]["PICTURES".POSTFIX_PROPERTY]["VALUE"])):
													$an_file = CFile::ResizeImageGet($arItem["PROPERTIES"]["PICTURES".POSTFIX_PROPERTY]["VALUE"][0], array('width'=>196, 'height'=>129), BX_RESIZE_IMAGE_EXACT, true, array(), false, 70);
													$pre_photo=$an_file["src"];
													elseif (!empty($arItem["PROPERTIES"]["PICTURES"]["VALUE"])):
													$an_file = CFile::ResizeImageGet($arItem["PROPERTIES"]["PICTURES"]["VALUE"][0], array('width'=>196, 'height'=>129), BX_RESIZE_IMAGE_EXACT, true, array(), false, 70);
													$pre_photo=$an_file["src"];
													else:
													$pre_photo=SITE_TEMPLATE_PATH."/images/nophoto-292x180.jpg";
													endif;
													?>
							<img src="<?=$pre_photo?>" alt="<?echo LANGUAGE_ID == "ru" ? $arItem["PROPERTIES"]["PICTURES"]["DESCRIPTION"][$i - 1] : $arItem["PROPERTIES"]["IMG_DESCRIPTION".POSTFIX_PROPERTY]["VALUE"][$i - 1]?>">
							<? if (!empty($arItem["PROPERTIES"]["YOUTUBE" . POSTFIX_PROPERTY]["VALUE"])): ?><div class="video-play"><img src="<?=SITE_TEMPLATE_PATH?>/images/icon/play-button.png"></div><?endif;?>
						</figure>
						
						<div class="home-sales-text">
							<div class="news-date"><?=$arItem['DISPLAY_ACTIVE_FROM']?></div>
							<div class="home-sales-name-places">
								<div class="home-sales-name">
									<?echo LANGUAGE_ID == "ru" ? substr2($arItem["NAME"], 75) : substr2($arItem["PROPERTIES"]["NAME".POSTFIX_PROPERTY]["VALUE"], 70);?>
								</div>
							</div>
							<?if($arParams["DISPLAY_DATE"]!="N"):?><i class="fa fa-calendar"></i>
								<?
									if (!empty($arItem["PROPERTIES"]["DATE_NEED"]["VALUE"]))
										echo FormatDateFromDB($arItem["PROPERTIES"]["DATE_NEED"]["VALUE"], 'SHORT');
									else
										echo FormatDateFromDB($arItem["DATE_CREATE"], 'SHORT');
								?>
							<br>
							<?endif;?>
							<?if ($arItem['PROPERTIES']['DATE_FROM']['VALUE']):?>
								<i class="fa fa-calendar"></i> <?= implode2(array_map(function ($it) {return date("d.m.Y", MakeTimeStamp($it)); }, (array)$arItem['PROPERTIES']['DATE_FROM']['VALUE']), " - ")?><?endif?>
						</div>
					</div>
				</a>
			</div>
			
			<?endif;?>
		<?endforeach;?>
		</div>
	</div>
</div>
<script>
    /*$('#news-slide').owlCarousel({
        items: 4,
        loop:true,
        margin:10,
        navigation:true,
        navigationText: ['<span class="prev-next-room prev-room"></span>','<span class="prev-next-room next-room"></span>'],
        dots: false
    })*/
	
	if ($(window).width() < 767) {
		$('#news-slide').owlCarousel({
			items: 1,
			loop:true,
			margin:20,
			nav:false,
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
						margin: 10,
						stagePadding: 20,
					},

					768 : {
						items: 2,
						slideBy:2,
					}
					,
					991 : {
						items: 4,
					}
			}
		});
	
}
	
</script>