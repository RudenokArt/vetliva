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
   
   if(empty($arResult["ITEMS"])):
   ?>
<div class="col-md-9 col-md-pull-0 content-page-detail">
   <div class="alert-box alert-attention"><?=GetMessage("TEXT_NOT_FOUND", array("#LINK#" => $APPLICATION->GetCurDir()))?></div>
</div>
<?
   return;
   endif;
   
   $this->addExternalCss(SITE_TEMPLATE_PATH . "/css/webui-popover/jquery.webui-popover.min.css");
   $this->addExternalJs(SITE_TEMPLATE_PATH . "/js/webui-popover/jquery.webui-popover.min.js");
   
   
   $price_title = GetMessage("price_night_title");
   if ($arParams["__BOOKING_REQUEST"]["date_to"] - $arParams["__BOOKING_REQUEST"]["date_from"] > 86400) {
       $price_title = "#price#";
   }
   ?>
<div class="col-md-9 col-md-pull-0 content-page-detail">
   <section class="hotel-list">
      <div class="hotel-list-cn clearfix">
         <? if ($arParams["DISPLAY_TOP_PAGER"]): ?>
         <?= $arResult["NAV_STRING"] ?><br />
         <? endif; ?>
         <? foreach ($arResult["ITEMS"] as $arItem): ?>
         <?
            $_request_string = $arItem["DETAIL_PAGE_URL"] . $request_string;
            $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
            $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
            ?>
         <div class="hotel-list-item" id="<?= $this->GetEditAreaId($arItem['ID']); ?>">
            <figure class="hotel-img float-left">
               <a href="<? echo $_request_string ?>" title="">
               <?
                  if (!empty($arItem["PREVIEW_PICTURE"])):
                      $an_file = CFile::ResizeImageGet($arItem["PREVIEW_PICTURE"], array('width' => 410, 'height' => 250), BX_RESIZE_IMAGE_EXACT, true, array(), false, 80);
                      $pre_photo = $an_file["src"];
                  elseif (!empty($arItem["PROPERTIES"]["PICTURES"]["VALUE"])):
                      $an_file = CFile::ResizeImageGet($arItem["PROPERTIES"]["PICTURES"]["VALUE"][0], array('width' => 410, 'height' => 250), BX_RESIZE_IMAGE_EXACT, true, array(), false, 80);
                      $pre_photo = $an_file["src"];
                  else:
                      $pre_photo = SITE_TEMPLATE_PATH . "/images/nophoto.jpg";
                  endif;
                  ?>
               <img src="<?= $pre_photo ?>" alt="">
               </a>
            </figure>
            <div class="hotel-text">
               <div class="hotel-name">
                  <a href="<? echo $_request_string ?>" title="<?echo LANGUAGE_ID == "ru" ? $arItem["NAME"] : $arItem["PROPERTIES"]["NAME".POSTFIX_PROPERTY]["VALUE"]?>"><?echo LANGUAGE_ID == "ru" ? $arItem["NAME"] : $arItem["PROPERTIES"]["NAME".POSTFIX_PROPERTY]["VALUE"]?></a>
                  <div class="hotel-star-address">
                     <span class="hotel-star">
                     <? if ($arItem["PROPERTIES"]["CAT_ID"]["VALUE"] == '1491'): ?>
                     <i class="glyphicon glyphicon-star"></i>
                     <i class="glyphicon glyphicon-star"></i>
                     <i class="glyphicon glyphicon-star"></i>
                     <i class="glyphicon glyphicon-star"></i>
                     <i class="glyphicon glyphicon-star"></i>
                     <? elseif ($arItem["PROPERTIES"]["CAT_ID"]["VALUE"] == '1492'): ?>
                     <i class="glyphicon glyphicon-star"></i>
                     <i class="glyphicon glyphicon-star"></i>
                     <i class="glyphicon glyphicon-star"></i>
                     <i class="glyphicon glyphicon-star"></i>
                     <? elseif ($arItem["PROPERTIES"]["CAT_ID"]["VALUE"] == '1493'): ?>
                     <i class="glyphicon glyphicon-star"></i>
                     <i class="glyphicon glyphicon-star"></i>
                     <i class="glyphicon glyphicon-star"></i>
                     <? elseif ($arItem["PROPERTIES"]["CAT_ID"]["VALUE"] == '1494'): ?>
                     <i class="glyphicon glyphicon-star"></i>
                     <i class="glyphicon glyphicon-star"></i>
                     <? else: ?>
                     <?= $arItem["DISPLAY_PROPERTIES"]["CAT_ID"]["DISPLAY_VALUE"]; ?>
                     <? endif; ?>
                     </span>
                  </div>
               </div>
               <address class="hotel-address">
                  <? if (!empty($arItem["PROPERTIES"]["ADDRESS" . POSTFIX_PROPERTY]["VALUE"])): $adress = '';?>
                  <i class="fa fa-map-marker"></i> <?$adress = substr2($arItem["DISPLAY_PROPERTIES"]["ADDRESS".POSTFIX_PROPERTY]["DISPLAY_VALUE"], 200); ?>
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
                     if (!empty($arItem["PROPERTIES"]["COUNTRY"]["VALUE"])) {
                     $country = strip_tags($arItem["DISPLAY_PROPERTIES"]["COUNTRY"]["DISPLAY_VALUE"]);
                     if (LANGUAGE_ID != "ru") {
                     	$prop = getIBElementProperties($arItem["PROPERTIES"]["COUNTRY"]["VALUE"]);
                     	$country = $prop["NAME".POSTFIX_PROPERTY]["VALUE"];
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
					<?if ($town):?><?$adress .= ", ".$town;?><?if($region):?><?$adress .= ", ";?><?endif;?><?endif;?>
					<?if ($region):?><?$adress .= $region;?><?endif;?>
					<?if ($country):?><?$adress .= ", ".$country;?><?endif;?>
					<?echo $adress;?>

                  <? endif ?>
                   <?if(!empty($arItem["DISPLAY_PROPERTIES"]["CAPACITY"]["VALUE"])):?>
                       <i class="fa fa-map-marker"></i> <?=GetMessage('CAPACITY')?> <?=strip_tags($arItem["DISPLAY_PROPERTIES"]["CAPACITY"]["DISPLAY_VALUE"])?>
                   <?endif?>
               </address>
               <ul class="ship-port">
                   
				<? if (!empty($arItem["PROPERTIES"]["DISTANCE_MINSK"]["VALUE"])): ?>
                  <li>
                     <i class="fa fa-info-circle blue"></i> <?= GetMessage('DISTANCE_MINSK') ?>: <?= substr2($arItem["PROPERTIES"]["DISTANCE_MINSK"]["VALUE"], 100); ?> km
                  </li>
				<? endif ?>
				<? if (!empty($arItem["PROPERTIES"]["DISTANCE_CENTER"]["VALUE"])): ?>
                  <li>
                     <i class="fa fa-info-circle blue"></i> <?= GetMessage('DISTANCE_CENTER') ?>: <?= substr2($arItem["PROPERTIES"]["DISTANCE_CENTER"]["VALUE"], 100); ?> km
                  </li>
				<? endif ?>
                  <? if (!empty($arItem["PROPERTIES"]["DISTANCE_AIRPORT"]["VALUE"])): ?>
                  <li>
                     <i class="fa fa-info-circle blue"></i> <?= GetMessage('DISTANCE_AIRPORT') ?>: <?= substr2($arItem["PROPERTIES"]["DISTANCE_AIRPORT"]["VALUE"], 100); ?> km
                  </li>
                  <? endif ?>  
                  <? if (!empty($arItem["PROPERTIES"]["FEATURES".POSTFIX_PROPERTY]["VALUE"])): ?>
                  <li>
                     <i class="fa fa-info-circle blue"></i> <?= substr2($arItem["PROPERTIES"]["FEATURES".POSTFIX_PROPERTY]["VALUE"], 200); ?>
                  </li>
                  <? endif ?>
               </ul>
               <?
                  $arParams["__BOOKING_REQUEST"]["id"] = array($arItem["ID"]);
                  if ($arParams["CALCULATION_PRICE_RESULT"][$arItem["ID"]]):
                      $price = \travelsoft\Currency::getInstance()->convertCurrency(
                              $arParams["CALCULATION_PRICE_RESULT"][$arItem["ID"]]["PRICE"], $arParams["CALCULATION_PRICE_RESULT"][$arItem["ID"]]['CURRENCY_ID']
                      );
                      ?>
                <a href="<?echo getCalculateDetailLink($arItem["DETAIL_PAGE_URL"], $arParams["__BOOKING_REQUEST"])?>" title="">
               <div class="price-box float-right" Style="cursor: pointer;">
                 <?= str_replace("#price#", $price, $price_title)?>
               </div>
                </a>
               <?else:?>
                <a href="<?echo getCalculateDetailLink($arItem["DETAIL_PAGE_URL"], $arParams["__BOOKING_REQUEST"])?>" title="">
               <div class="price-box float-right">
                  <span class="detail"><?=GetMessage("MORE")?></span>
               </div>
                </a>
               <? endif ?>
               <div class="hotel-service float-left">
                  <? if (!empty($arItem["DISPLAY_PROPERTIES"]["SERVICES"]["VALUE"])): ?>
                  <? $count = 0; ?>
                  <? foreach ($arItem["DISPLAY_PROPERTIES"]["SERVICES"]["VALUE"] as $k => $value): ?>
                  <? if (!empty($arResult["SERVICES_ICON"][$value]["ICON"]) && $count <= 6): ?>
                  <a data-content="<?= $arResult["SERVICES_ICON"][$value]["TITLE"] ?>" class="border_icon <?= $arResult["SERVICES_ICON"][$value]["ICON"] ?>"></a>
                  <? $count++ ?>
                  <? endif ?>
                  <? endforeach; ?>
                  <? endif; ?>
               </div>
            </div>
         </div>
         <? endforeach; ?>
         <? if ($arParams["DISPLAY_BOTTOM_PAGER"]): ?>
         <br /><?= $arResult["NAV_STRING"] ?>
         <? endif; ?>
      </div>
 	</section>
</div>
<script>
   (function(){
       function initPopover(){
           $('.hotel-service a').webuiPopover({
               placement: "left",
               trigger: "hover"
           });
       }
       initPopover();
   })();
</script>
<?
   //count elements tags
   $this->SetViewTarget("cnt__elements");
   ?>
<div class="search-result">
   <p><?=GetMessage("FOUND")?> <ins id="searching__cnt__elements"><?= $arResult['NAV_RESULT']->NavRecordCount?></ins></p>
</div>
<?$this->EndViewTarget()?>
<span id="cnt__elements"><?= $arResult['NAV_RESULT']->NavRecordCount?></span>