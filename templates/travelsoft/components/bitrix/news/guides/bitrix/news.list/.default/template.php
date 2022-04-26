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
   <? $p = $arItem["DISPLAY_PROPERTIES"];
      $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
      $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
      ?>
   <div class="info-list-item" id="<?=$this->GetEditAreaId($arItem['ID']);?>" itemscope <?if($arResult["ID"] == ATTRACTION_IBLOCK_ID || $arResult["ID"] == ACTIONS_IBLOCK_ID):?>itemtype="http://schema.org/Place"<?elseif($arResult["ID"] == NEWS_IBLOCK_ID):?>itemtype="http://schema.org/CreativeWork"<?elseif($arResult["ID"] == POSTER_IBLOCK_ID):?>itemtype="http://schema.org/Event"<?elseif($arResult["ID"] == GETTING_THERE_IBLOCK_ID):?>itemtype="http://schema.org/Thing"<?endif;?>>
       <?if($arResult["ID"] == NEWS_IBLOCK_ID):?><meta itemprop="genre" content="Полезная информация"><?endif?>
      <figure class="info-img float-left">
         <a itemprop ="url" href="<?echo $arItem["DETAIL_PAGE_URL"]?>" title="">
		 <?
			$imgs = (array)$arItem["PROPERTIES"]["PICTURES"]["VALUE"];
			$pre_photo = getSrcImage($imgs[0], array('width'=>410, 'height'=>250), NO_PHOTO_PATH);
		?>
         <img itemprop="image" src="<?=$pre_photo?>" alt="<?echo LANGUAGE_ID == "ru" ? $arItem["NAME"] : $arItem["PROPERTIES"]["NAME".POSTFIX_PROPERTY]["VALUE"]?>">
         </a> 
      </figure>
      <div class="info-text" <?if($arResult["ID"] == NEWS_IBLOCK_ID):?>itemprop="description"<?endif?>>
         <div class="info-name" <?if($arResult["ID"] == ATTRACTION_IBLOCK_ID || $arResult["ID"] == POSTER_IBLOCK_ID || $arResult["ID"] == GETTING_THERE_IBLOCK_ID || $arResult["ID"] == ACTIONS_IBLOCK_ID):?>itemprop="name"<?endif?>>
			 <a href="<?echo $arItem["DETAIL_PAGE_URL"]?>" title="" itemprop="url"><?echo LANGUAGE_ID == "ru" ? $arItem["NAME"] : $arItem["PROPERTIES"]["NAME".POSTFIX_PROPERTY]["VALUE"]?></a>
         </div>
         <?if (!empty($arItem["PROPERTIES"]["TOWN"]["VALUE"])):?>
         <div class="info-star-address">
            <address class="info-address">
               <?if ($arItem['PROPERTIES']['DATE_FROM']['VALUE']):?>
		           <i class="fa fa-calendar"></i> <span itemprop="startDate" content="<?=date("Y-m-d", MakeTimeStamp($arItem['PROPERTIES']['DATE_FROM']['VALUE'][0]))?>"><?= implode2(array_map(function ($it) {return date("d.m.Y", MakeTimeStamp($it)); }, (array)$arItem['PROPERTIES']['DATE_FROM']['VALUE']), "</span> - <span itemprop=\"endDate\" content=".date("Y-m-d", MakeTimeStamp($arItem['PROPERTIES']['DATE_FROM']['VALUE'][1])).">")?></span>
               <?endif?>
               <?if($arParams["DISPLAY_DATE"]!="N"):?><i class="fa fa-calendar"></i> <? echo FormatDateFromDB($arItem["DATE_CREATE"], 'SHORT');?> <?endif;?>
               <i class="fa fa-map-marker"></i>
                <span itemprop="location" itemscope itemtype="http://schema.org/Place">
                    <span itemprop="name">
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
                        ?>
                       <?= implode2(array(
                          $region,
                          $town,
                          $arItem["PROPERTIES"]["ADDRESS".POSTFIX_PROPERTY]["VALUE"]
                          ))?>
                        </span>
                    <span itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">
                    </span>
                 </span>
            </address>
         </div>
         <?endif;?>
         <ul class="ship-port">
                        <?
                            $kitchentype = null;
                            if ($p["KITCHEN2"]["VALUE"]) {
                                $p["KITCHEN2"]["VALUE"] = (array) $p["KITCHEN2"]["VALUE"];
                                $db_res_kitchentype = CIBlockElement::GetList(false, array("ACTIVE" => "Y", "IBLOCK_ID" => $p["KITCHEN2"]["LINK_IBLOCK_ID"], "ID" => $p["KITCHEN2"]["VALUE"]), false, false, array("ID", "NAME", "PROPERTY_NAME" . POSTFIX_PROPERTY));
                                $kitchentype = null;
                                while ($res = $db_res_kitchentype->Fetch()) {
                                    $kitchentype[] = $res["PROPERTY_NAME" . POSTFIX_PROPERTY . "_VALUE"] ? $res["PROPERTY_NAME" . POSTFIX_PROPERTY . "_VALUE"] : $res["NAME"];
                                }
                            }
                            if ($kitchentype):
                                ?>
                            <li>
								<i class="fa fa-info-circle blue"></i> <?= GetMessage('KITCHEN_TYPE')?>:	<?= implode(", ", $kitchentype) ?>
							</li>
                        <? endif; ?>
                        <?
                            $type = null;
                            if ($p["TYPE"]["VALUE"]) {
                                $p["TYPE"]["VALUE"] = (array) $p["TYPE"]["VALUE"];
                                $db_res_type = CIBlockElement::GetList(false, array("ACTIVE" => "Y", "IBLOCK_ID" => $p["TYPE"]["LINK_IBLOCK_ID"], "ID" => $p["TYPE"]["VALUE"]), false, false, array("ID", "NAME", "PROPERTY_NAME" . POSTFIX_PROPERTY));
                                $type = null;
                                while ($res = $db_res_type->Fetch()) {
                                    $type[] = $res["PROPERTY_NAME" . POSTFIX_PROPERTY . "_VALUE"] ? $res["PROPERTY_NAME" . POSTFIX_PROPERTY . "_VALUE"] : $res["NAME"];
                                }
                            }
                            if ($type):
                                ?>
                            <li>
								<i class="fa fa-info-circle blue"></i> <?= GetMessage('TYPE')?>:	<?= implode(", ", $type) ?>
							</li>
                        <? endif; ?>
			 <?/*if ($p["TYPE".POSTFIX_PROPERTY]["VALUE"]):?>
                            <li>
								<i class="fa fa-info-circle blue"></i> <?= $p["TYPE".POSTFIX_PROPERTY]["VALUE"] ?>
							</li>
<?endif;*/?>
		</ul>
         <p <?if($arResult["ID"] == ATTRACTION_IBLOCK_ID || $arResult["ID"] == POSTER_IBLOCK_ID || $arResult["ID"] == GETTING_THERE_IBLOCK_ID || $arResult["ID"] == ACTIONS_IBLOCK_ID):?>itemprop="description"<?endif?>>
            <?if (!empty($arItem["PROPERTIES"]["PREVIEW_TEXT".POSTFIX_PROPERTY]["VALUE"])):?>
            <?=substr2($arItem["DISPLAY_PROPERTIES"]["PREVIEW_TEXT".POSTFIX_PROPERTY]["DISPLAY_VALUE"], 200);?>
            <?elseif (!empty($arItem["PROPERTIES"]["DETAIL_TEXT".POSTFIX_PROPERTY]["VALUE"])):?>
            <?=substr2($arItem["DISPLAY_PROPERTIES"]["DETAIL_TEXT".POSTFIX_PROPERTY]["DISPLAY_VALUE"], 200);?>
            <?else:?>
            <?=substr2($arItem["DISPLAY_PROPERTIES"]["HD_DESC".POSTFIX_PROPERTY]["DISPLAY_VALUE"], 200);?>
            <?endif?>
         </p>
         <a href="<?echo $arItem["DETAIL_PAGE_URL"]?>" title="" itemprop="url">
            <div class="price-box float-right"><span class="detail"><?=GetMessage("MORE_BTN")?></span></div>
         </a>
      </div>
   </div>
   <?endforeach;?>
	<div style="clear: both;"></div>
   <?if($arParams["DISPLAY_BOTTOM_PAGER"]):?>
   <br /><?=$arResult["NAV_STRING"]?>
   <?endif;?>
</div>
<?
   //count elements tags
   $this->SetViewTarget("cnt__elements");
   ?>
<div class="search-result">
   <p><?=GetMessage("FOUND")?> <ins id="searching__cnt__elements"><?= $arResult['NAV_RESULT']->NavRecordCount?></ins></p>
</div>
<?$this->EndViewTarget()?>
<span id="cnt__elements"><?= $arResult['NAV_RESULT']->NavRecordCount?></span>

