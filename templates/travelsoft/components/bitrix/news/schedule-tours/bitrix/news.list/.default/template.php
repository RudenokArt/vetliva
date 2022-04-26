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
   ?>
<div class="col-md-3 col-md-push-0">
   <div class="sidebar-cn">
      <?$APPLICATION->IncludeComponent(
         "bitrix:main.include",
         "",
         Array(
         	"AREA_FILE_RECURSIVE" => "Y",
         	"AREA_FILE_SHOW" => "sect",
         	"AREA_FILE_SUFFIX" => "inc",
         	"EDIT_TEMPLATE" => ""
         )
         );?>
   </div>
</div>
<div class="col-md-9 col-md-pull-0 content-page-detail">
   <section class="tour-list">
      <!-- Sort by and View by -->
      <div class="sort-view clearfix">
         <!-- Sort by 
            <div class="sort-by float-left">
                <label>Сортировать: </label>
                <div class="sort-select select float-left">
                    <span data-placeholder="Select">По алфавиту</span>
                    <select name="start">
                        <option value="1">А - Я</option>
                        <option selected value="1">Я - А</option>
                    </select>
                </div>
                <div class="sort-select select float-left">
                    <span data-placeholder="Select">По рейтингу</span>
                    <select name="guest">
                        <option value="1">Сначала высокий</option>
                        <option selected value="1">Сначала низкий</option>
                    </select>
                </div>
                <div class="sort-select select float-left">
                    <span data-placeholder="Select">По цене</span>
                    <select name="pricing">
                        <option value="1">Сначала дешевле</option>
                        <option selected value="1">Сначала дороже</option>
                    </select>
                </div>
            </div>
             End Sort by -->
         <!-- View by 
            <div class="view-by float-right">
                <ul>
            <li><a href="#list" title="" class="current"><img src="<?= SITE_TEMPLATE_PATH?>/images/icon-list.png" alt=""></a></li>
            <li><a href="#map" title=""><img src="<?= SITE_TEMPLATE_PATH?>/images/icon-map.png" alt=""></a></li>
                </ul>
            </div>
             View by -->
      </div>
      <!-- End Sort by and View by -->
      <div class="tour-schedule-list-cn tour-list-cn clearfix">
         <?if($arParams["DISPLAY_TOP_PAGER"]):?>
         <?=$arResult["NAV_STRING"]?><br />
         <?endif;?>
         <?foreach($arResult["ITEMS"] as $arItem):?>
         <? $p = $arItem["DISPLAY_PROPERTIES"];
            $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
            $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
            ?>
         <div class="tour-schedule-item" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
            <div class="tour-schedule-text">
               <div class="tour-schedule-name"><a href="<?echo $arItem["DETAIL_PAGE_URL"]?>"><?echo LANGUAGE_ID == "ru" ? $arItem["NAME"] : $arItem["PROPERTIES"]["NAME".POSTFIX_PROPERTY]["VALUE"]?></a></div>
               <ul class="ship-port">
                  <?if (!empty($arItem["PROPERTIES"]["TOWN"]["VALUE"])):?>
                  <li>
                     <span class="label"><?=GetMessage('TOWN')?> </span>
                     <?
                        $p["TOWN"]["VALUE"] = (array)$p["TOWN"]["VALUE"];
                        $db_res_towns = CIBlockElement::GetList(false, array("ACTIVE" => "Y", "IBLOCK_ID" => $p["TOWN"]["LINK_IBLOCK_ID"], "ID" => $p["TOWN"]["VALUE"]), false, false, array("ID", "NAME", "PROPERTY_NAME" . POSTFIX_PROPERTY));
                        $towns = null;
                        while ($res = $db_res_towns->Fetch()) {
                            $towns[] = $res["PROPERTY_NAME" . POSTFIX_PROPERTY . "_VALUE"] ? $res["PROPERTY_NAME" . POSTFIX_PROPERTY . "_VALUE"] : $res["NAME"];
                        }
                        if($towns) { echo implode(" - ", $towns); }
                        ?>
                  </li>
                  <?endif;?>
                  <?
                     $food = null;
                     if ($p["FOOD"]["VALUE"]) {
                         $p["FOOD"]["VALUE"] = (array)$p["FOOD"]["VALUE"];
                         $db_res_food = CIBlockElement::GetList(false, array("ACTIVE" => "Y", "IBLOCK_ID" => $p["FOOD"]["LINK_IBLOCK_ID"], "ID" => $p["FOOD"]["VALUE"]), false, false, array("ID", "NAME", "PROPERTY_NAME" . POSTFIX_PROPERTY));
                         $food = null;
                         while ($res = $db_res_food->Fetch()) {
                             $food[] = $res["PROPERTY_NAME" . POSTFIX_PROPERTY . "_VALUE"] ? $res["PROPERTY_NAME" . POSTFIX_PROPERTY . "_VALUE"] : $res["NAME"];
                         }
                     }
                     if($food):?>
                  <li>
                     <span class="label"><?=GetMessage('FOOD')?> </span> <?= implode(", ", $food)?>
                  </li>
                  <?endif?>         
               </ul>
               <div class="price-box"><span class="price">01.01<br>20.02</span> </div>
               <div class="price-box"><span class="price">01.01<br>20.02</span> </div>
               <div class="price-box"><span class="price">01.01<br>20.02</span> </div>
               <div class="price-box"><span class="price">01.01<br>20.02</span> </div>
               <div class="price-box"><span class="price">01.01<br>20.02</span> </div>
               <div class="price-box"><span class="price">01.01<br>20.02</span> </div>
				<div style="clear:both"></div>
            </div>
         </div>
         <?endforeach;?>
         <?if($arParams["DISPLAY_BOTTOM_PAGER"]):?>
         <br /><?=$arResult["NAV_STRING"]?>
         <?endif;?>
      </div>
   </section>
</div>
<script>
   (function(){
       function initPopover(){
           $('.tour-service a').webuiPopover({
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
   <p><?=GetMessage('FOUND')?> <ins id="searching__cnt__elements"><?= $arResult['NAV_RESULT']->NavRecordCount?></ins></p>
</div>
<?$this->EndViewTarget()?>
<span id="cnt__elements"><?= $arResult['NAV_RESULT']->NavRecordCount?></span>