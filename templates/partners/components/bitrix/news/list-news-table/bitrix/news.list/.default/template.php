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
<?if($arParams["DISPLAY_TOP_PAGER"]):?>
	<?=$arResult["NAV_STRING"]?><br />
<?endif;?>
<ul class="media-list content-group">
<?foreach($arResult["ITEMS"] as $arItem):?>
	<?
	$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
	$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
	?>
													<li class="col-lg-6 stack-media-on-mobile">
				                					<div class="media-left">
														<div class="thumb">
																 <?
			$imgs = (array)$arItem["PROPERTIES"]["PICTURES"]["VALUE"];
			$pre_photo = getSrcImage($imgs[0], array('width'=>120, 'height'=>90), NO_PHOTO_PATH);
		?>
															<a href="<?echo $arItem["DETAIL_PAGE_URL"]?>">
																<img src="<?=$pre_photo?>" class="img-responsive img-rounded media-preview" alt="<?echo $arItem["NAME"]?>">
																<span class="zoom-image"><i class="icon-play3"></i></span>
															</a>
														</div>
													</div>

				                					<div class="media-body">
														<h6 class="media-heading"><a href="<?echo $arItem["DETAIL_PAGE_URL"]?>"><?echo $arItem["NAME"]?></a></h6>
							                    		<ul class="list-inline list-inline-separate text-muted mb-5">
							                    			<?if (!empty($arItem["PROPERTIES"]["VIDEO"]["VALUE"])):?><li><i class="icon-book-play position-left"></i> Видео</li><?endif;?>
							                    			<?if($arParams["DISPLAY_DATE"]!="N"):?><li><i class="fa fa-calendar"></i> <? echo FormatDateFromDB($arItem["DATE_CREATE"], 'SHORT');?> </li><?endif;?>
							                    		</ul>
														            <?if (!empty($arItem["PROPERTIES"]["PREVIEW_TEXT".POSTFIX_PROPERTY]["VALUE"])):?>
            <?=substr2($arItem["DISPLAY_PROPERTIES"]["PREVIEW_TEXT".POSTFIX_PROPERTY]["DISPLAY_VALUE"], 200);?>
            <?elseif (!empty($arItem["PROPERTIES"]["DETAIL_TEXT".POSTFIX_PROPERTY]["VALUE"])):?>
            <?=substr2($arItem["DISPLAY_PROPERTIES"]["DETAIL_TEXT".POSTFIX_PROPERTY]["DISPLAY_VALUE"], 200);?>
            <?else:?>
            <?=substr2($arItem["DISPLAY_PROPERTIES"]["HD_DESC".POSTFIX_PROPERTY]["DISPLAY_VALUE"], 200);?>
            <?endif?>
													</div>
												</li>
		<?endforeach;?>
		</ul>
<?if($arParams["DISPLAY_BOTTOM_PAGER"]):?>
	<br /><?=$arResult["NAV_STRING"]?>
<?endif;?>