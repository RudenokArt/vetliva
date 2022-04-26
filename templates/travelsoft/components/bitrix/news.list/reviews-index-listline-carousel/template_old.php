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
			<?
			$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
			$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));?>
			<div class="item">
				<a href="<?=$arItem["DETAIL_PAGE_URL"]?>" class="sales-item-a">
					<div class="sales-item">
						<figure class="home-sales-img">
										 <?if (!empty($arItem["PREVIEW_PICTURE"])):
													$an_file = CFile::ResizeImageGet($arItem["PREVIEW_PICTURE"], array('width'=>292, 'height'=>180), BX_RESIZE_IMAGE_EXACT, true, array(), false, 70);
													$pre_photo=$an_file["src"];
													elseif (!empty($arItem["PROPERTIES"]["PHOTO"]["VALUE"])):
													$an_file = CFile::ResizeImageGet($arItem["PROPERTIES"]["PHOTO"]["VALUE"][0], array('width'=>292, 'height'=>180), BX_RESIZE_IMAGE_EXACT, true, array(), false, 70);
													$pre_photo=$an_file["src"];
													else:
													$pre_photo=SITE_TEMPLATE_PATH."/images/nophoto-292x180.jpg";
													endif;
													?>
							<img src="<?=$pre_photo?>" alt="<?echo LANGUAGE_ID == "ru" ? $arItem["PROPERTIES"]["PHOTO"]["DESCRIPTION"][$i - 1] : $arItem["PROPERTIES"]["IMG_DESCRIPTION".POSTFIX_PROPERTY]["VALUE"][$i - 1]?>">
						</figure>
						<div class="home-sales-text">
							<div class="home-sales-name-places">
								<div class="home-sales-name">
									<?echo substr2($arItem["NAME"], 70);// LANGUAGE_ID == "ru" ? substr2($arItem["NAME"], 70) : substr2($arItem["PROPERTIES"]["NAME".POSTFIX_PROPERTY]["VALUE"], 70);?>
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
        margin:10,
        navigation:true,
        navigationText: ['<span class="prev-next-room prev-room"></span>','<span class="prev-next-room next-room"></span>'],
        dots: false
    })
</script>