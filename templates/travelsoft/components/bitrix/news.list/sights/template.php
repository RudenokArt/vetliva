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

    <?$this->SetViewTarget("menu-item-sights-tours");?>
        <li><a href="#iblock_detail_sights" class="anchor"><?= GetMessage('SIGHTS')?></a></li>
    <?$this->EndViewTarget();?>

	<section class="info-list sights_list js-show-hide-wrp">
		<?if($arParams["TITLE_LIST"]):?>
            <div class="list-title-block">
                <h3 class="list-title-block__title"><?=$arParams["TITLE_LIST"]?></h3>
            </div>
        <?endif;?>
		<div class="info-list-cn row">
		   <?if($arParams["DISPLAY_TOP_PAGER"]):?>
		   <?=$arResult["NAV_STRING"]?><br />
		   <?endif;?>
		   <?foreach($arResult["ITEMS"] as $key => $arItem):?>
		   <?
			  $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
			  $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
			  ?>
		   <div class="col-lg-2 bot-slide-item" id="<?=$this->GetEditAreaId($arItem['ID'])?>">
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
                  <?if (!empty($arItem["PROPERTIES"]["TOWN"]["VALUE"])):?>
                      <div class="info-star-address">
                          <address class="info-address">
                              <?if ($arItem['PROPERTIES']['DATE_FROM']['VALUE']):?>
                                  <i class="fa fa-calendar"></i> <?= implode2(array_map(function ($it) {return date("d.m.Y", MakeTimeStamp($it)); }, (array)$arItem['PROPERTIES']['DATE_FROM']['VALUE']), " - ")?><br>
                              <?endif?>
                              <?if($arParams["DISPLAY_DATE"]!="N"):?><i class="fa fa-calendar-o"></i> <? echo FormatDateFromDB($arItem["DATE_CREATE"], 'SHORT');?><br><?endif;?>
                              <? if (!empty($arItem["PROPERTIES"]["TOWN"]["VALUE"])): ?><i class="fa fa-map-marker"></i><? endif; ?>
                              <?if (!empty($arItem["PROPERTIES"]["REGIONS"]["VALUE"])) {
                                  $region = strip_tags($arItem["DISPLAY_PROPERTIES"]["REGIONS"]["DISPLAY_VALUE"]);
                                  if (LANGUAGE_ID != "ru") {
                                      $prop = getIBElementProperties($arItem["PROPERTIES"]["REGIONS"]["VALUE"]);
                                      $region = $prop["NAME".POSTFIX_PROPERTY]["VALUE"];
                                  }
                              }
                              if (!empty($arItem["PROPERTIES"]["TOWN"]["VALUE"])) {
                                  $town = strip_tags($arItem["DISPLAY_PROPERTIES"]["TOWN"]["DISPLAY_VALUE"]);
                                  if (LANGUAGE_ID != "ru") {
                                      $prop = getIBElementProperties($arItem["PROPERTIES"]["TOWN"]["VALUE"]);
                                      $town = $prop["NAME".POSTFIX_PROPERTY]["VALUE"];
                                  }
                              }
                              if (!empty($arItem["PROPERTIES"]["ACCOMODATION"]["VALUE"])) {
                                  $accomodation = strip_tags($arItem["DISPLAY_PROPERTIES"]["ACCOMODATION"]["DISPLAY_VALUE"]);
                                  if (LANGUAGE_ID != "ru") {
                                      $prop = getIBElementProperties($arItem["PROPERTIES"]["ACCOMODATION"]["VALUE"]);
                                      $accomodation = $prop["NAME".POSTFIX_PROPERTY]["VALUE"];
                                  }
                              }
                              if (!empty($arItem["PROPERTIES"]["SANATORIUM"]["VALUE"])) {
                                  $sanatorium = strip_tags($arItem["DISPLAY_PROPERTIES"]["SANATORIUM"]["DISPLAY_VALUE"]);
                                  if (LANGUAGE_ID != "ru") {
                                      $prop = getIBElementProperties($arItem["PROPERTIES"]["SANATORIUM"]["VALUE"]);
                                      $sanatorium = $prop["NAME".POSTFIX_PROPERTY]["VALUE"];
                                  }
                              }
                              if (!empty($arItem["PROPERTIES"]["ATTRACTION"]["VALUE"])) {
                                  $attraction = strip_tags($arItem["DISPLAY_PROPERTIES"]["ATTRACTION"]["DISPLAY_VALUE"]);
                                  if (LANGUAGE_ID != "ru") {
                                      $prop = getIBElementProperties($arItem["PROPERTIES"]["ATTRACTION"]["VALUE"]);
                                      $attraction = $prop["NAME".POSTFIX_PROPERTY]["VALUE"];
                                  }
                              }
                              ?>
                              <? if ($region): ?><?= $region?><? endif; ?>
                              <? if ($town): ?> <?= $town ?><? endif; ?>
                              <? if ($accomodation): ?> <?= $accomodation ?><? endif; ?>
                              <? if ($sanatorium): ?> <?= $sanatorium ?><? endif; ?>
                              <? if ($attraction): ?> <?= $attraction ?><? endif; ?>
                          </address>
                      </div>
                  <?endif;?>
				 <div class="info-name">
					<a href="<? echo $arItem["DETAIL_PAGE_URL"] ?>" title="<? echo LANGUAGE_ID == "ru" ? $arItem["NAME"] : $arItem["PROPERTIES"]["NAME" . POSTFIX_PROPERTY]["VALUE"] ?>"><? echo LANGUAGE_ID == "ru" ? $arItem["NAME"] : $arItem["PROPERTIES"]["NAME" . POSTFIX_PROPERTY]["VALUE"] ?></a>
				 </div>


                  <a href="<?echo $arItem["DETAIL_PAGE_URL"]?>" title="">
				 <div class="price-box float-right">
                     <span class="price old-price"><?=GetMessage("MORE")?></span>
				 </div>
                  </a>
			  </div>
		   </div>
		   <?endforeach;?>
		<div style="clear: both;"></div>
		   <?if($arParams["DISPLAY_BOTTOM_PAGER"]):?>
		   <br /><?=$arResult["NAV_STRING"]?>
		   <?endif;?>
		</div>
	</section>
<?endif;?>
