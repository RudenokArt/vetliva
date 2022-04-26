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
 <div class="banner_index">  
<?foreach($arResult["ITEMS"] as $arItem):?>
	<?
	$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
	$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
    if (LANGUAGE_ID!='ru') {
        if (!empty($arItem["PROPERTIES"]["NAME".POSTFIX_PROPERTY]["VALUE"])) $arItem["NAME"] = $arItem["PROPERTIES"]["NAME".POSTFIX_PROPERTY]["VALUE"];
        if (!empty($arItem["PROPERTIES"]["LINK".POSTFIX_PROPERTY]["VALUE"])) $arItem["PROPERTIES"]["LINK"]["VALUE"] = $arItem["PROPERTIES"]["LINK".POSTFIX_PROPERTY]["VALUE"];
        if (!empty($arItem["PROPERTIES"]["PICTURES".POSTFIX_PROPERTY]["VALUE"])) $arItem['PREVIEW_PICTURE']['SRC'] = CFile::GetPath($arItem["PROPERTIES"]["PICTURES".POSTFIX_PROPERTY]["VALUE"]);
    }
	?>
		<a href="<?=$arItem["PROPERTIES"]["LINK"]["VALUE"]?>">
    	<div  class="item" style="background:url(<?=$arItem['PREVIEW_PICTURE']['SRC'];?>) no-repeat top center;" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
		   <div class="slider-text"  id="<?=$this->GetEditAreaId($arItem['ID']);?>">
			   <div class="container">
					<div class="row">
						<div class="col-md-12">
							<div class="display-table">
								   <div class="news-title">										
										<?echo $arItem['NAME'];?>
								   </div>
									
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		</a>
<?endforeach;?>
</div>