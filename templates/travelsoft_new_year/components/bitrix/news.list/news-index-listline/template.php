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
		<?$i=0;?>
		<?foreach($arResult["ITEMS"] as $arItem):?>
		<?$i++;?>
			<?
			$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
			$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));?>
			<div class="col-xs-6 col-md-3">
				<a href="<?=$arItem["DETAIL_PAGE_URL"]?>" class="sales-item-a">
					<div class="sales-item">
						<figure class="home-sales-img">
										 <?if (!empty($arItem["PREVIEW_PICTURE"])):
													$an_file = CFile::ResizeImageGet($arItem["PREVIEW_PICTURE"], array('width'=>292, 'height'=>180), BX_RESIZE_IMAGE_EXACT, true, array(), false, 70);
													$pre_photo=$an_file["src"];
													elseif (!empty($arItem["PROPERTIES"]["PICTURES"]["VALUE"])):
													$an_file = CFile::ResizeImageGet($arItem["PROPERTIES"]["PICTURES"]["VALUE"][0], array('width'=>292, 'height'=>180), BX_RESIZE_IMAGE_EXACT, true, array(), false, 70);
													$pre_photo=$an_file["src"];
													else:
													$pre_photo=SITE_TEMPLATE_PATH."/images/nophoto-292x180.jpg";
													endif;
													?>
							<img src="<?=$pre_photo?>" alt="">
						</figure>
						<div class="home-sales-text">
							<div class="home-sales-name-places">
								<div class="home-sales-name">
									<?echo LANGUAGE_ID == "ru" ? substr2($arItem["NAME"], 70) : substr2($arItem["PROPERTIES"]["NAME".POSTFIX_PROPERTY]["VALUE"], 70);?>
								</div>
							</div>
							<?if($arParams["DISPLAY_DATE"]!="N"):?><i class="fa fa-calendar"></i> <? echo FormatDateFromDB($arItem["DATE_CREATE"], 'SHORT');?><br><?endif;?>
							<?if ($arItem['PROPERTIES']['DATE_FROM']['VALUE']):?>
								<i class="fa fa-calendar"></i> <?= implode2(array_map(function ($it) {return date("d.m.Y", MakeTimeStamp($it)); }, (array)$arItem['PROPERTIES']['DATE_FROM']['VALUE']), " - ")?><?endif?>
						</div>
					</div>
				</a>
			</div>
		<?endforeach;?>
	</div>
</div>
