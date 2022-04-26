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
<div class="magazine-thum" id="magazine-thum">
<?if($arParams["DISPLAY_TOP_PAGER"]):?>
	<?=$arResult["NAV_STRING"]?><br />
<?endif;?>
<?foreach($arResult["ITEMS"] as $arItem):?>
	<?
	$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
	$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));?>
					<a href="<?=$arItem["DETAIL_PAGE_URL"]?>">
						<div class="thumnail-item clearfix" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
						 <?if (!empty($arItem["PREVIEW_PICTURE"])):
									$an_file = CFile::ResizeImageGet($arItem["PREVIEW_PICTURE"], array('width'=>240, 'height'=>150), BX_RESIZE_IMAGE_EXACT, true);
									$pre_photo=$an_file["src"];
									elseif (!empty($arItem["PROPERTIES"]["PICTURES"]["VALUE"])):
									$an_file = CFile::ResizeImageGet($arItem["PROPERTIES"]["PICTURES"]["VALUE"][0], array('width'=>240, 'height'=>150), BX_RESIZE_IMAGE_EXACT, true);
									$pre_photo=$an_file["src"];
									else:
									$pre_photo=SITE_TEMPLATE_PATH."/images/nophoto-240x150.jpg";
									endif;
									?>
							<figure class="float-left"><img src="<?=$pre_photo?>" alt=""> </figure>
							<div class="thumnail-text">
								<h4><?echo $arItem["NAME"]?></h4>
										<?if($arParams["DISPLAY_DATE"]!="N" && $arItem["DISPLAY_ACTIVE_FROM"]):?>
											<?echo $arItem["DISPLAY_ACTIVE_FROM"]?>
										<?endif?>
                                                                                <?if ($arItem['PROPERTIES']['DATE_FROM']['VALUE']):?>
                                                                                    <i class="fa fa-calendar"></i> <?= implode2(array_map(function ($it) {return date("d.m.Y", MakeTimeStamp($it)); }, (array)$arItem['PROPERTIES']['DATE_FROM']['VALUE']), " - ")?>
                                                                                <?endif?>
							</div>
						</div>
					</a>
<?endforeach;?>
<?if($arParams["DISPLAY_BOTTOM_PAGER"]):?>
	<br /><?=$arResult["NAV_STRING"]?>
<?endif;?>
</div>
