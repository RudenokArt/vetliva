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
   
   $request_string = $arParams["BOOKING_REQUEST_DETAIL_STRING"] != "" ? "?" . $arParams["BOOKING_REQUEST_DETAIL_STRING"] : "";
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


    <?$this->SetViewTarget("menu-item-sport-tours");?>
        <li><a data-toggle="tab" href="#iblock_detail_sport"><?= GetMessage('SPORTS_OBJ')?></a></li>
    <?$this->EndViewTarget();?>

<section class="hotel-list">
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
      <div class="hotel-list-item" id="<?= $this->GetEditAreaId($arItem['ID']); ?>">
         <figure class="hotel-img float-left">
            <a href="<? echo $_request_string ?>" title="">
            <?
               if (!empty($arItem["PREVIEW_PICTURE"])):
               	$an_file = CFile::ResizeImageGet($arItem["PREVIEW_PICTURE"], array('width' => 410, 'height' => 250), BX_RESIZE_IMAGE_EXACT, true);
               	$pre_photo = $an_file["src"];
               elseif (!empty($arItem["PROPERTIES"]["PICTURES"]["VALUE"])):
               	$an_file = CFile::ResizeImageGet($arItem["PROPERTIES"]["PICTURES"]["VALUE"][0], array('width' => 410, 'height' => 250), BX_RESIZE_IMAGE_EXACT, true);
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
            <address class="hotel-address">
               <? if (!empty($arItem["PROPERTIES"]["ADDRESS".POSTFIX_PROPERTY]["VALUE"])): ?>
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
				<? endif; ?>
            </address>
            <ul class="ship-port">
				<? if (!empty($arItem["PROPERTIES"]["DISTANCE_MINSK"]["VALUE"])): ?>
                  <li>
                     <i class="fa fa-info-circle blue"></i> <?= GetMessage('DISTANCE_MINSK') ?>: <?= substr2($arItem["PROPERTIES"]["DISTANCE_MINSK"]["VALUE"], 100); ?> km
                  </li>
				<? endif ?>
				<? if (!empty($arItem["PROPERTIES"]["DISTANCE_CENTER'"]["VALUE"])): ?>
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
                  <i class="fa fa-info-circle blue"></i> <?= substr2($arItem["DISPLAY_PROPERTIES"]["FEATURES".POSTFIX_PROPERTY]["DISPLAY_VALUE"], 200); ?>
               </li>
               <? endif ?>
            </ul>
            <?
                            if ($result[$arItem["ID"]]):
                                $price = \travelsoft\Currency::getInstance()->convertCurrency(
                                        $result[$arItem["ID"]]["PRICE"], $result[$arItem["ID"]]['CURRENCY_ID']
                                );
                                ?>
                        <div class="price-box float-right" Style="cursor: pointer;">
                            <a href="<?= getCalculateDetailLink($arItem["DETAIL_PAGE_URL"], array('id' => array($arItem["ID"])))?>"><?= GetMessage("price_night_title", array("#price#" => $price)); ?></a>
                            </div>
                            <? else: ?>
                            <div class="price-box float-right">
                                <a href="<? echo getCalculateDetailLink($arItem["DETAIL_PAGE_URL"], array('id' => array($arItem["ID"])))?>" title=""><span class="detail"><?= GetMessage("MORE") ?></span></a>
                            </div>
                            <? endif ?>
            <div class="hotel-service float-left">
               <? if (!empty($arItem["DISPLAY_PROPERTIES"]["SERVICES"]["VALUE"])): ?>
               <? $count = 0; ?>
               <? foreach ($arItem["DISPLAY_PROPERTIES"]["SERVICES"]["VALUE"] as $k => $value): ?>
               <? if (!empty($arResult["SERVICES_ICON"][$value]["ICON"]) && $count <= 6): ?>
               <a data-content="<?= $arResult["SERVICES_ICON"][$value]["TITLE"] ?>" class="border_icon <?= $arResult["SERVICES_ICON"][$value]["ICON"] ?>"></a>
               <? endif ?>
               <? $count++ ?>
               <? endforeach; ?>
               <? endif; ?>
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