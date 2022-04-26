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
<?if(!empty($arResult["ITEMS"])):?>
<div class="row">
<?foreach($arResult["ITEMS"] as $arItem):?>

<div class="col-lg-3 col-md-4 col-sm-6 col-xs-6 map-item">
	<div class="inside-item">
		
		<?
		$link = $arItem["PROPERTIES"]["LINK"]["VALUE"];
		if (LANGUAGE_ID == "ru")
		{
			$link = $arItem["PROPERTIES"]["LINK"]["VALUE"];
		}
		else
		{
			if (!empty($arItem["PROPERTIES"]["LINK".POSTFIX_PROPERTY]["VALUE"])) $link = $arItem["PROPERTIES"]["LINK".POSTFIX_PROPERTY]["VALUE"];
		}	
		
		?>
		
        <img src="<?=$arItem["PREVIEW_PICTURE"]["SRC"]?>" alt="<?echo LANGUAGE_ID == "ru" ? $arItem["NAME"] : $arItem["PROPERTIES"]["NAME".POSTFIX_PROPERTY]["VALUE"]?>">	
		<div class="map-text">
			<a href="<?=$link?>"><?echo LANGUAGE_ID == "ru" ? $arItem["NAME"] : $arItem["PROPERTIES"]["NAME".POSTFIX_PROPERTY]["VALUE"]?></a>
		</div>
	</div>
</div>	
<?endforeach;?>


</div>
<?endif;?>