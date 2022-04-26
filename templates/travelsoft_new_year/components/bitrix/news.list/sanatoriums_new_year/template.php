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

<section class="destinations">
    <div class="destinations-cn">

            <div class="row">
                <div class="tab-content destinations-grid">
                    <?$i = 1;?>
      <? foreach ($arResult["ITEMS"] as $arItem): ?>
      <?
         $_request_string = $arItem["DETAIL_PAGE_URL"] . $request_string;
         $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
         $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
         ?>
          <!-- Destinations Item -->
          <div class="col-xs-6 col-sm-4 col-md-6 col-lg-4" id="<?= $this->GetEditAreaId($arItem['ID']); ?>">
              <div class="destinations-item ">

                  <?if($i % 2 != 0):?>

                      <figure class="destinations-img">
                          <a href="<? echo $_request_string?>" title="<?echo LANGUAGE_ID == "ru" ? $arItem["NAME"] : $arItem["PROPERTIES"]["NAME".POSTFIX_PROPERTY]["VALUE"]?>">
                              <?
                              if (!empty($arItem["PREVIEW_PICTURE"])):
                                  $an_file = CFile::ResizeImageGet($arItem["PREVIEW_PICTURE"], array('width' => 390, 'height' => 390), BX_RESIZE_IMAGE_EXACT, true);
                                  $pre_photo = $an_file["src"];
                              elseif (!empty($arItem["PROPERTIES"]["PICTURES"]["VALUE"])):
                                  $an_file = CFile::ResizeImageGet($arItem["PROPERTIES"]["PICTURES"]["VALUE"][0], array('width' => 390, 'height' => 390), BX_RESIZE_IMAGE_EXACT, true);
                                  $pre_photo = $an_file["src"];
                              else:
                                  $pre_photo = SITE_TEMPLATE_PATH . "/images/nophoto.jpg";
                              endif;
                              ?>
                              <img src="<?= $pre_photo ?>" alt="<?echo LANGUAGE_ID == "ru" ? $arItem["NAME"] : $arItem["PROPERTIES"]["NAME".POSTFIX_PROPERTY]["VALUE"]?>">
                          </a>
                      </figure>

                      <div class="destinations-text">
                          <div class="destinations-name">
                              <a href="<? echo $_request_string?>" title="<?echo LANGUAGE_ID == "ru" ? $arItem["NAME"] : $arItem["PROPERTIES"]["NAME".POSTFIX_PROPERTY]["VALUE"]?>"><?echo LANGUAGE_ID == "ru" ? $arItem["NAME"] : $arItem["PROPERTIES"]["NAME".POSTFIX_PROPERTY]["VALUE"]?></a>
                          </div>
                          <hr class="min-w">
                          <?
                          if (!empty($arItem["PROPERTIES"]["HD_DESC".POSTFIX_PROPERTY]["VALUE"]["TEXT"])) {
                              $desc = strip_tags($arItem["DISPLAY_PROPERTIES"]["HD_DESC".POSTFIX_PROPERTY]["DISPLAY_VALUE"]);
                              ?>
                              <div class="home-destinations-places">
                                  <?=substr($desc, 0, 100)."..."?>
                              </div>
                              <?
                          }?>
                          <div class="price-box">
                              <a href="<? echo $_request_string?>" title="<? echo LANGUAGE_ID == "ru" ? $arItem["NAME"] : $arItem["PROPERTIES"]["NAME" . POSTFIX_PROPERTY]["VALUE"] ?>"><span class="detail"><?=GetMessage('MORE')?></span></a>
                          </div>
                      </div>

                  <?else:?>

                      <div class="destinations-text">
                          <div class="destinations-name">
                              <a href="<? echo $_request_string ?>" title="<?echo LANGUAGE_ID == "ru" ? $arItem["NAME"] : $arItem["PROPERTIES"]["NAME".POSTFIX_PROPERTY]["VALUE"]?>"><?echo LANGUAGE_ID == "ru" ? $arItem["NAME"] : $arItem["PROPERTIES"]["NAME".POSTFIX_PROPERTY]["VALUE"]?></a>
                          </div>
                          <hr class="min-w">
                          <?
                          if (!empty($arItem["PROPERTIES"]["HD_DESC".POSTFIX_PROPERTY]["VALUE"]["TEXT"])) {
                              $desc = strip_tags($arItem["DISPLAY_PROPERTIES"]["HD_DESC".POSTFIX_PROPERTY]["DISPLAY_VALUE"]);
                              ?>
                              <div class="home-destinations-places">
                                  <?=substr($desc, 0, 100)."..."?>
                              </div>
                              <?
                          }?>
                          <div class="price-box">
                              <a href="<? echo $_request_string ?>" title="<? echo LANGUAGE_ID == "ru" ? $arItem["NAME"] : $arItem["PROPERTIES"]["NAME" . POSTFIX_PROPERTY]["VALUE"] ?>"><span class="detail"><?=GetMessage('MORE')?></span></a>
                          </div>
                      </div>
                      <figure class="destinations-img">
                          <a href="<? echo $_request_string ?>" title="<?echo LANGUAGE_ID == "ru" ? $arItem["NAME"] : $arItem["PROPERTIES"]["NAME".POSTFIX_PROPERTY]["VALUE"]?>">
                              <?
                              if (!empty($arItem["PREVIEW_PICTURE"])):
                                  $an_file = CFile::ResizeImageGet($arItem["PREVIEW_PICTURE"], array('width' => 370, 'height' => 370), BX_RESIZE_IMAGE_EXACT, true);
                                  $pre_photo = $an_file["src"];
                              elseif (!empty($arItem["PROPERTIES"]["PICTURES"]["VALUE"])):
                                  $an_file = CFile::ResizeImageGet($arItem["PROPERTIES"]["PICTURES"]["VALUE"][0], array('width' => 370, 'height' => 370), BX_RESIZE_IMAGE_EXACT, true);
                                  $pre_photo = $an_file["src"];
                              else:
                                  $pre_photo = SITE_TEMPLATE_PATH . "/images/nophoto.jpg";
                              endif;
                              ?>
                              <img src="<?= $pre_photo ?>" alt="<?echo LANGUAGE_ID == "ru" ? $arItem["NAME"] : $arItem["PROPERTIES"]["NAME".POSTFIX_PROPERTY]["VALUE"]?>">
                          </a>
                      </figure>

                  <?endif?>
              </div>
          </div>
          <!-- End Destinations Item -->
          <?$i++?>
      <? endforeach; ?>
                </div>
            </div>

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