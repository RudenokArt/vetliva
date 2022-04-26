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
		<div id="reviews-slide" class="owl-carousel">
		<?$i=0;?>
		<?foreach($arResult["ITEMS"] as $arItem):?>
		<?$i++;?>
			<div class="item">
				<a href="<?=$arItem["DETAIL_PAGE_URL"]?>" class="sales-item-a">
					<div class="sales-item">
						<figure class="home-sales-img">
										 <?if (!empty($arItem["PREVIEW_PICTURE"])):
													$an_file = CFile::ResizeImageGet($arItem["PREVIEW_PICTURE"], array('width'=>315, 'height'=>207), BX_RESIZE_IMAGE_EXACT, true, array(), false, 70);
													$pre_photo=$an_file["src"];
													elseif (!empty($arItem["PROPERTIES"]["PHOTO"]["VALUE"])):
													$an_file = CFile::ResizeImageGet($arItem["PROPERTIES"]["PHOTO"]["VALUE"][0], array('width'=>315, 'height'=>207), BX_RESIZE_IMAGE_EXACT, true, array(), false, 70);
													$pre_photo=$an_file["src"];
													else:
													$pre_photo=SITE_TEMPLATE_PATH."/images/no_review_photo.png";
													endif;
													?>
							<img src="<?=$pre_photo?>" alt="<?echo LANGUAGE_ID == "ru" ? $arItem["PROPERTIES"]["PHOTO"]["DESCRIPTION"][$i - 1] : $arItem["PROPERTIES"]["IMG_DESCRIPTION".POSTFIX_PROPERTY]["VALUE"][$i - 1]?>">
						</figure>
						<div class="home-sales-text">
						
							<div class="name_for_index">
								<?=substr2($arItem["PREVIEW_TEXT"], 59); ?>
							</div>
							
							<?if($arParams["DISPLAY_DATE"]!="N"):?>
								<div class="review-index--date">
										<?
											if (!empty($arItem["PROPERTIES"]["DATE_NEED"]["VALUE"]))
												echo FormatDateFromDB($arItem["PROPERTIES"]["DATE_NEED"]["VALUE"], 'SHORT');
											else
												echo FormatDateFromDB($arItem["DATE_CREATE"], 'SHORT');
										?>
								</div>
							<?endif;?>
							
							<?if ($arItem['PROPERTIES']['DATE_FROM']['VALUE']):?>
								<div class="review-index--date">
									<?= implode2(array_map(function ($it) {return date("d.m.Y", MakeTimeStamp($it)); }, (array)$arItem['PROPERTIES']['DATE_FROM']['VALUE']), " - ")?>
								</div>
							<?endif?>
							
							<div class="home-sales-name-places">
								<div class="home-sales-name">
									<?echo substr2($arItem["NAME"], 70);// LANGUAGE_ID == "ru" ? substr2($arItem["NAME"], 70) : substr2($arItem["PROPERTIES"]["NAME".POSTFIX_PROPERTY]["VALUE"], 70);?>
								</div>
							</div>
							
							
							<?if(!empty($arItem["PREVIEW_TEXT"])):?>
								<p>
									<?=substr2($arItem["PREVIEW_TEXT"], 95); ?>
								</p>
							<?endif?>
						</div>
					</div>
				</a>
			</div>
		<?endforeach;?>
		</div>
	</div>
</div>
<script>
    $('#reviews-slide').owlCarousel({
        items: 4,
        loop:true,
        margin:17,
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
					margin: 10,
					stagePadding: 20,
					
				},

				768 : {
					items: 2,
					slideBy:2,
				}
				,
				991 : {
					items: 3,
				}
				,
				1040 : {
					items: 4,
				}
		}
    })
</script>