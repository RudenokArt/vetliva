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
   $is_mobile = check_smartphone(); 
   ?>
<div class="sights-list info-list-cn clearfix">
   <?foreach($arResult["ITEMS"] as $keyitem=>$arItem):?>
   <? $p = $arItem["DISPLAY_PROPERTIES"];
      $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
      $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
      ?>
   <div class="info-list-item" id="<?=$this->GetEditAreaId($arItem['ID']);?>" itemscope <?if($arResult["ID"] == ATTRACTION_IBLOCK_ID || $arResult["ID"] == ACTIONS_IBLOCK_ID):?>itemtype="http://schema.org/Place"<?elseif($arResult["ID"] == NEWS_IBLOCK_ID):?>itemtype="http://schema.org/CreativeWork"<?elseif($arResult["ID"] == POSTER_IBLOCK_ID):?>itemtype="http://schema.org/Event"<?elseif($arResult["ID"] == GETTING_THERE_IBLOCK_ID):?>itemtype="http://schema.org/Thing"<?endif;?>>
       <?if($arResult["ID"] == NEWS_IBLOCK_ID):?><meta itemprop="genre" content="Полезная информация"><?endif?>
      <figure class="info-img float-left">
         <?
            if(!empty($arItem["PROPERTIES"]["PICTURES".POSTFIX_PROPERTY]["VALUE"]))
	  		{
				$imgs = (array)$arItem["PROPERTIES"]["PICTURES".POSTFIX_PROPERTY]["VALUE"];
				$pre_photo = getSrcImage($imgs[0], array('width'=>410, 'height'=>250), NO_PHOTO_PATH);
			}else{
				$imgs = (array)$arItem["PROPERTIES"]["PICTURES"]["VALUE"];
                $pre_photo = getSrcImage($imgs[0], array('width'=>410, 'height'=>250), NO_PHOTO_PATH);
			}
         ?>
         <?if (count($imgs)>1): $limit = (count($imgs)>5)? 5 : count($imgs);?>
            <div class="banners-slider-list">
                <?for ($i=0; $i<$limit; $i++):?>
                    <?$pre_photo = getSrcImage($imgs[$i], array('width'=>410, 'height'=>250), NO_PHOTO_PATH);?>
                    <a itemprop ="url" href="<?echo $arItem["DETAIL_PAGE_URL"]?>" title="">
                    <img itemprop="image" loading="lazy" src="<?=$pre_photo?>" alt="<?echo LANGUAGE_ID == "ru" ? $arItem["NAME"] : $arItem["PROPERTIES"]["NAME".POSTFIX_PROPERTY]["VALUE"]?>"/>
                    </a>
                <?endfor;?>
            </div>
         <?else:?>
            <a itemprop ="url" href="<?echo $arItem["DETAIL_PAGE_URL"]?>" title="">
                <img itemprop="image" loading="lazy" src="<?=$pre_photo?>" alt="<?echo LANGUAGE_ID == "ru" ? $arItem["NAME"] : $arItem["PROPERTIES"]["NAME".POSTFIX_PROPERTY]["VALUE"]?>"/>
            </a> 
         <?endif;?>
      </figure>
      <div class="info-text" <?if($arResult["ID"] == NEWS_IBLOCK_ID):?>itemprop="description"<?endif?>>
         <div class="info-name" <?if($arResult["ID"] == ATTRACTION_IBLOCK_ID || $arResult["ID"] == POSTER_IBLOCK_ID || $arResult["ID"] == GETTING_THERE_IBLOCK_ID || $arResult["ID"] == ACTIONS_IBLOCK_ID):?>itemprop="name"<?endif?>>
			 <a href="<?echo $arItem["DETAIL_PAGE_URL"]?>" title="" itemprop="url"><?echo LANGUAGE_ID == "ru" ? $arItem["NAME"] : $arItem["PROPERTIES"]["NAME".POSTFIX_PROPERTY]["VALUE"]?></a>
             <div class="list-favorite-button" <?if ($keyitem>0):?>data-short_display="Y" data-object_id="<?=$arItem["ID"]?>" data-object_type="IBLOCK_ELEMENT" data-store_id="<?=$arParams["IBLOCK_ID"]?>"<?endif;?>>
                <?
                //if ($keyitem==0){
                    $APPLICATION->IncludeComponent(
                    	"travelsoft:favorites.add",
                    	"",
                    	Array(
                            "SHORT_DISPLAY"=>"Y",
                    		"OBJECT_ID" => $arItem["ID"],
                    		"OBJECT_TYPE" => "IBLOCK_ELEMENT",
                            "STORE_ID" => $arParams["IBLOCK_ID"]
                    	)
                    );
                //}
                ?>
             </div>
         </div>
         <?if (!empty($arItem["PROPERTIES"]["TOWN"]["VALUE"])):?>
         <div class="info-star-address">
            <address class="info-address">

				<?if($arParams["DISPLAY_DATE"]!="N"):?>
				    <i class="fa fa-calendar"></i>
                    <?
                        if (!empty($arItem["PROPERTIES"]["DATE_NEED"]["VALUE"]))
                            echo FormatDateFromDB($arItem["PROPERTIES"]["DATE_NEED"]["VALUE"], 'SHORT');
                        else
                            echo FormatDateFromDB($arItem["DATE_CREATE"], 'SHORT');
                    ?>
				<?endif;?>
                <?if($arItem["IBLOCK_ID"] == EVENTS_IBLOCK_ID && !empty($arItem["PROPERTIES"]["DATE_FROM"]["VALUE"])):?>
                    <?$first_date = $arItem["PROPERTIES"]["DATE_FROM"]["VALUE"][0];$end_date = count($arItem["PROPERTIES"]["DATE_FROM"]["VALUE"]) > 1 ? $arItem["PROPERTIES"]["DATE_FROM"]["VALUE"][0] : '';?>
                    <?foreach($arItem["PROPERTIES"]["DATE_FROM"]["VALUE"] as $keydate=>$date_val):?>
                        <?$date = MakeTimeStamp($date_val, "DD.MM.YYYY");?>
                        <?if($date < MakeTimeStamp($first_date, "DD.MM.YYYY")){
                            $first_date = $date_val;
                        }?>
                        <?if(!empty($end_date) && $date > MakeTimeStamp($end_date, "DD.MM.YYYY")){
                            $end_date = $date_val;
                        }?>
                    <?endforeach?>
                    <i class="fa fa-calendar"></i> <?=date("d.m.Y", MakeTimeStamp($first_date, "DD.MM.YYYY"))?><?if(!empty($end_date)):?> - <?=date("d.m.Y", MakeTimeStamp($end_date, "DD.MM.YYYY"))?><?endif?>
                <?endif;?>
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
                 <?if($arItem['DISPLAY_PROPERTIES']['MAP']['VALUE'] && $arItem['IBLOCK_ID']==6):?>
                    <div class="show-map__wrapper">
                        <a
                                href="javascript:;"
                                title="<?= GetMessage('T_PLACEMENT_LIST_SHOW_MAP') ?>"
                                class="show-map"
                                data-id="<?=$arItem['ID']?>"
                                data-filter='<?= \Bitrix\Main\Web\Json::encode($GLOBALS[$arParams['FILTER_NAME']]) ?>'
                        ><?= GetMessage('T_PLACEMENT_LIST_SHOW_MAP') ?></a>
                    </div>
                 <?endif?>
            </address>
         </div>
		  <?else:?>
		 <div class="info-star-address">
            <address class="info-address">
				<?if($arParams["DISPLAY_DATE"]!="N"):?>
					<i class="fa fa-calendar"></i> 
					<?
						if (!empty($arItem["PROPERTIES"]["DATE_NEED"]["VALUE"]))
							echo FormatDateFromDB($arItem["PROPERTIES"]["DATE_NEED"]["VALUE"], 'SHORT');
						else
							echo FormatDateFromDB($arItem["DATE_CREATE"], 'SHORT');
					?>
				<?endif;?>
                <?if($arItem["IBLOCK_ID"] == EVENTS_IBLOCK_ID && !empty($arItem["PROPERTIES"]["DATE_FROM"]["VALUE"])):?>
                    <?$first_date = $arItem["PROPERTIES"]["DATE_FROM"]["VALUE"][0];$end_date = count($arItem["PROPERTIES"]["DATE_FROM"]["VALUE"]) > 1 ? $arItem["PROPERTIES"]["DATE_FROM"]["VALUE"][0] : '';?>
                    <?foreach($arItem["PROPERTIES"]["DATE_FROM"]["VALUE"] as $keydate=>$date_val):?>
                        <?$date = MakeTimeStamp($date_val, "DD.MM.YYYY");?>
                        <?if($date < MakeTimeStamp($first_date, "DD.MM.YYYY")){
                            $first_date = $date_val;
                        }?>
                        <?if(!empty($end_date) && $date > MakeTimeStamp($end_date, "DD.MM.YYYY")){
                            $end_date = $date_val;
                        }?>
                    <?endforeach?>
                    <i class="fa fa-calendar"></i> <?=date("d.m.Y", MakeTimeStamp($first_date, "DD.MM.YYYY"))?><?if(!empty($end_date)):?> - <?=date("d.m.Y", MakeTimeStamp($end_date, "DD.MM.YYYY"))?><?endif?>
                <?endif;?>
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
            <div class="price-box float-right"><span class="detail"><?=GetMessage("MORE")?></span></div>
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
    <?if ($is_mobile):?>
    <div class="mobile-filtr-sort-block">
    <div class="filter-block">
        <a class="magnificbutton show-filter-link" href="#filter-area"><?= GetMessage('FILTRES') ?></a>
    </div>
    <div class="searcbyname-block">
        <a href="javascript:void(0)" onclick="$('.searchbyname-block-content').toggle();" class="show-search-link"><i class="fa fa-search" aria-hidden="true"></i></a>
    </div>
    </div>
    <?endif;?>
    <?if (!$is_mobile):?>
   <p><?=GetMessage("FOUND")?> <ins id="searching__cnt__elements"><?= $arResult['NAV_RESULT']->NavRecordCount?></ins></p>
   <?endif;?>
</div>
<?$this->EndViewTarget()?>
<?if ($is_mobile) {
    $this->SetViewTarget("cnt__elements_header");?>
    (<ins id="searching__cnt__elements"><?=$arResult['NAV_RESULT']->NavRecordCount?></ins>)
    <?$this->EndViewTarget();
}?>
<span id="cnt__elements"><?= $arResult['NAV_RESULT']->NavRecordCount?></span>
<script>
$(window).load(function(){
    <?/*$( '.list-favorite-button' ).each(function( index, val ) {
        let $this = $(this);
        if ($( this ).data('object_id')) {
            $.ajax({
        		url: '/local/ajax/showblock.php?favorites=Y&short_display='+$( this ).data('short_display')+'&object_id='+$( this ).data('object_id')+'&object_type='+$( this ).data('object_type')+'&store_id='+$( this ).data('store_id'),
        		type: 'GET',
                dataType: 'html',
                success: function(html){
                    html = JSON.parse(html);
                    $this.html(html);
                }
        	})
        }
    });*/?>
    $('.banners-slider-list').owlCarousel({
        items: 1,
        navigation: true,
        autoplay:false,
        loop:true,
    	dots: false,
        pagination: false,    
    	margin:6,
        navigationText: ["<i class='fas fa-chevron-left icon-white'></i>","<i class='fas fa-chevron-right icon-white'></i>"],
        singleItem: true,
    });
    $('.owl-carousel').trigger( 'refresh.owl.carousel' ); 
});
</script>