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
<div class="info-list-cn clearfix">
   <?if($arParams["DISPLAY_TOP_PAGER"]):?>
   <?=$arResult["NAV_STRING"]?><br />
   <?endif;?>
   <?foreach($arResult["ITEMS"] as $arItem):?>
   <?
      $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
      $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
      ?>
   <div class="info-list-item" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
      <figure class="info-img float-left"> 
         <a href="<?echo $arItem["DETAIL_PAGE_URL"]?>" title=""> 
         <?if (!empty($arItem["PREVIEW_PICTURE"])):
            $an_file = CFile::ResizeImageGet($arItem["PREVIEW_PICTURE"], array('width'=>410, 'height'=>250), BX_RESIZE_IMAGE_EXACT, true);
            $pre_photo=$an_file["src"];
            elseif (!empty($arItem["PROPERTIES"]["PICTURES"]["VALUE"])):
            $an_file = CFile::ResizeImageGet($arItem["PROPERTIES"]["PICTURES"]["VALUE"][0], array('width'=>410, 'height'=>250), BX_RESIZE_IMAGE_EXACT, true);
            $pre_photo=$an_file["src"];
            else:
            $pre_photo=SITE_TEMPLATE_PATH."/images/nophoto.jpg";
            endif;
            ?>
         <img src="<?if ($i=='1'):?><?=$pre_photo_915?><?else:?><?=$pre_photo?><?endif;?>" alt="<?echo $arItem["NAME"]?>">
         </a> 
      </figure>
      <div class="info-text">
         <div class="info-name">
            <a href="<?echo $arItem["DETAIL_PAGE_URL"]?>" title=""><?echo $arItem["NAME"]?></a>
         </div>
         <?if (!empty($arItem["PROPERTIES"]["TOWN"]["VALUE"])):?>
         <div class="info-star-address">
            <address class="info-address">
               <?if ($arItem['PROPERTIES']['DATE_FROM']['VALUE']):?>
		           <i class="fa fa-calendar"></i> <?= implode2(array_map(function ($it) {return date("d.m.Y", MakeTimeStamp($it)); }, (array)$arItem['PROPERTIES']['DATE_FROM']['VALUE']), " - ")?><br>
               <?endif?>
               <?if($arParams["DISPLAY_DATE"]!="N"):?><i class="fa fa-calendar-o"></i> <? echo FormatDateFromDB($arItem["DATE_CREATE"], 'SHORT');?><br><?endif;?>
               <i class="fa fa-map-marker"></i>
               <?= implode2(array(
                  $arItem["DISPLAY_PROPERTIES"]["REGIONS"]["DISPLAY_VALUE"],
                  $arItem["DISPLAY_PROPERTIES"]["TOWN"]["DISPLAY_VALUE"],
                  $arItem["PROPERTIES"]["ADDRESS"]["VALUE"]
                  ))?>
            </address>
         </div>
         <?endif;?>
         <p>
            <?if (!empty($arItem["PROPERTIES"]["PREVIEW_TEXT"]["VALUE"])):?>
            <?=substr2($arItem["DISPLAY_PROPERTIES"]["PREVIEW_TEXT"]["DISPLAY_VALUE"], 200);?>
            <?elseif (!empty($arItem["PROPERTIES"]["DETAIL_TEXT"]["VALUE"])):?>
            <?=substr2($arItem["DISPLAY_PROPERTIES"]["DETAIL_TEXT"]["DISPLAY_VALUE"], 200);?>
            <?else:?>
            <?=substr2($arItem["DISPLAY_PROPERTIES"]["HD_DESC"]["DISPLAY_VALUE"], 200);?>
            <?endif?>
         </p>
         <hr class="hr">
         <div class="price-box float-right">
            <a href="<?echo $arItem["DETAIL_PAGE_URL"]?>" title=""><span class="price old-price"><?=GetMessage("MORE")?></span></a>
         </div>
      </div>
   </div>
   <?endforeach;?>
   <?if($arParams["DISPLAY_BOTTOM_PAGER"]):?>
   <br /><?=$arResult["NAV_STRING"]?>
   <?endif;?>
</div>
<?
   //count elements tags
   $this->SetViewTarget("cnt__elements");
   ?>
<div class="search-result">
   <p>Найдено <ins id="searching__cnt__elements"><?= $arResult['NAV_RESULT']->NavRecordCount?></ins></p>
</div>
<?$this->EndViewTarget()?>
<span id="cnt__elements"><?= $arResult['NAV_RESULT']->NavRecordCount?></span>

