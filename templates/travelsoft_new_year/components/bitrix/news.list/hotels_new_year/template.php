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
   
   $this->addExternalCss(SITE_TEMPLATE_PATH . "/css/webui-popover/jquery.webui-popover.min.css");
   $this->addExternalJs(SITE_TEMPLATE_PATH . "/js/webui-popover/jquery.webui-popover.min.js");
   
   $request_string = $arParams["BOOKING_REQUEST_DETAIL_STRING"] != "" ? "&" . $arParams["BOOKING_REQUEST_DETAIL_STRING"] : "";
   $price_title = $request_string != "" ? "#price#" : GetMessage("price_night_title");
   ?>
<?if(!empty($arResult["ITEMS"])):?>

<?
// ПРОИЗВОДИМ РАСЧЁТ ЦЕН
if ($arParams["MAKE_PRICING"] == "Y") {
    
    foreach ($arResult["ITEMS"] as $arItem) {
        $parameters["id"][] = $arItem["ID"];
    }
    
    $result = $APPLICATION->IncludeComponent(
                "travelsoft:travelsoft.service.price.result", "on.detail.page.render", Array(
            "RETURN_RESULT" => "Y",
            "FILTER_BY_PRICES_FOR_CITIZEN" => $arParams["FILTER_BY_PRICES_FOR_CITIZEN"] == "Y" ? "Y" : "N",
            "TYPE" => $arParams["OBJECT_TYPE"],
            "POSTFIX_PROPERTY" => POSTFIX_PROPERTY,
            "__BOOKING_REQUEST" => $parameters,
                    "MP" => "Y"
                )
        );
}

?>


    <?$this->SetViewTarget("menu-item-hotel-tours");?>
        <li><a href="#iblock_detail_hotel" class="anchor"><?= GetMessage('HOTEL')?></a></li>
    <?$this->EndViewTarget();?>

<section class="hotel-list hotel-list-new-year">
   <?if($arParams["TITLE_LIST"]):?>
   <h3><?=$arParams["TITLE_LIST"]?></h3>
   <?endif;?>
   <div class="hotel-list-cn clearfix">
      <? foreach ($arResult["ITEMS"] as $arItem): ?>
      <?
         $_request_string = $arItem["DETAIL_PAGE_URL"] . $request_string;
         $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
         $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
         ?>
      <div class="row hotel-list-item" id="<?= $this->GetEditAreaId($arItem['ID']); ?>">
          <?
          if (!empty($arItem["PREVIEW_PICTURE"])):
              $an_file = CFile::ResizeImageGet($arItem["PREVIEW_PICTURE"], array('width' => 740, 'height' => 350), BX_RESIZE_IMAGE_EXACT, true);
              $pre_photo = $an_file["src"];
          elseif (!empty($arItem["PROPERTIES"]["PICTURES"]["VALUE"])):
              $an_file = CFile::ResizeImageGet($arItem["PROPERTIES"]["PICTURES"]["VALUE"][0], array('width' => 740, 'height' => 350), BX_RESIZE_IMAGE_EXACT, true);
              $pre_photo = $an_file["src"];
          else:
              $pre_photo = SITE_TEMPLATE_PATH . "/images/nophoto.jpg";
          endif;
          ?>
         <div class="col-xs-12 col-sm-8 col-md-8 col-lg-8">
          <figure class="hotel-img float-left" style="background-image: url(<?= $pre_photo ?>)">
            <!--<a href="<?/* echo $_request_string */?>" title="">

            <img src="<?/*= $pre_photo */?>" alt="">
            </a>-->
         </figure>
         </div>
          <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
         <div class="hotel-text">
            <div class="hotel-name">
               <a href="<? echo $_request_string?>" title="<?echo LANGUAGE_ID == "ru" ? $arItem["NAME"] : $arItem["PROPERTIES"]["NAME".POSTFIX_PROPERTY]["VALUE"]?>"><?echo LANGUAGE_ID == "ru" ? $arItem["NAME"] : $arItem["PROPERTIES"]["NAME".POSTFIX_PROPERTY]["VALUE"]?></a>
            </div>
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
               <!-- rating
                  <span class="rating">
                  	Рейтинг <br>
                  	<ins>7.5</ins>
                  </span> 
                  end rating -->
            </div>
             <hr class="min-w">
            <address class="hotel-address">
               <? if (!empty($arItem["PROPERTIES"]["ADDRESS".POSTFIX_PROPERTY]["VALUE"])): ?>
               <?= substr2($arItem["DISPLAY_PROPERTIES"]["ADDRESS".POSTFIX_PROPERTY]["DISPLAY_VALUE"], 200); ?>
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
               <? if ($town): ?>, <?= $town ?><? endif; ?>
               <?if ($town&&$region):?>, <? endif; ?>
               <? if ($region): ?><?= $region?><? endif; ?>
               <? if ($country): ?>, <?= $country ?><? endif; ?>
               <?/* if ($accomodation): ?> <?= $accomodation ?><? endif; ?>
               <? if ($sanatorium): ?> <?= $sanatorium ?><? endif; ?>
               <? if ($attraction): ?> <?= $attraction ?><? endif; */?>
               <? endif ?>
            </address>
            <?
            if ($result[$arItem["ID"]]):
                $price = \travelsoft\Currency::getInstance()->convertCurrency(
                        $result[$arItem["ID"]]["PRICE"], $result[$arItem["ID"]]['CURRENCY_ID']
                );
                ?>
                <div class="price-box float-right" Style="cursor: pointer;">
                    <a href="<?= $_request_string?>"><?= GetMessage("price_night_title", array("#price#" => $price)); ?></a>
                </div>
            <? else: ?>
                <div class="price-box float-right">
                    <a href="<?= $_request_string?>" title=""><span class="detail"><?= GetMessage("MORE") ?></span></a>
                </div>
            <? endif ?>
            </div>
          </div>
      </div>
      <? endforeach; ?>
   </div>
</section>
<?endif;?>
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