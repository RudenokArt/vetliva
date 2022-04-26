<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();
    use Bitrix\Main\Application;
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
   $obRequest = Application::getInstance()->getContext()->getRequest();
   $requestsort = $obRequest->get("sort_by"); 
   $is_mobile = check_smartphone();  
   if(empty($arResult["ITEMS"])):
   ?>
<div class="col-md-9 col-md-pull-0 content-page-detail">
   <div class="alert-box alert-attention"><?=GetMessage("TEXT_NOT_FOUND", array("#LINK#" => $APPLICATION->GetCurDir()))?></div>
</div>
<?
   if ($is_mobile):
    $this->SetViewTarget("cnt__elements");
    ?>
    <div class="search-result">
        <div class="mobile-filtr-sort-block">
            <div class="filter-block">
                <a class="magnificbutton show-filter-link" href="#filter-area"><?= GetMessage('FILTRES') ?></a>
            </div>
            <div class="searcbyname-block">
                <a href="javascript:void(0)" onclick="$('.searchbyname-block-content').toggle();" class="show-search-link"><i class="fa fa-search" aria-hidden="true"></i></a>
            </div>
        </div>
    </div>
    <? $this->EndViewTarget();
    endif; 
   return;
   endif;

$this->addExternalCss(SITE_TEMPLATE_PATH . "/css/webui-popover/jquery.webui-popover.min.css");
$this->addExternalJs(SITE_TEMPLATE_PATH . "/js/webui-popover/jquery.webui-popover.min.js");
?>
<? //for price
if (empty($arParams["CALCULATION_PRICE_RESULT"])) {
$idelements=[]; foreach ($arResult["ITEMS"] as $keyitem=>$arItem) $idelements[]=$arItem['ID'];
$parameters = $arParams["__BOOKING_REQUEST"];
$parameters["id"] = $idelements;

$arParams["CALCULATION_PRICE_RESULT"] = $APPLICATION->IncludeComponent(
        "travelsoft:travelsoft.service.price.result", "on.detail.page.render", Array(
    "RETURN_RESULT" => "Y",
    "CACHE_TIME" => 3600,
    "CACHE_TYPE" => "A",
    "FILTER_BY_PRICES_FOR_CITIZEN" => $arParams["FILTER_BY_PRICES_FOR_CITIZEN"] == "Y" ? "Y" : "N",
    "TYPE" => $arParams["TYPE"],
    "MAKE_ORDER_PAGE" => "/booking/",
    "POSTFIX_PROPERTY" => POSTFIX_PROPERTY,
    "__BOOKING_REQUEST" => $parameters,
    "MP" => "Y",
        )
);
}
?>
<div class="col-md-9 col-md-pull-0 content-page-detail">
    <h1><?= $APPLICATION->GetTitle() ?></h1>
    <section class="tour-list">
		<?if ($arParams["SORT_PARAMETERS"]) :
            $sortnames = array();
            foreach ($arParams["SORT_PARAMETERS"] as $arp) $sortnames[] = $arp['name'];
        ?>
      <!-- Sort by and View by -->
         <div <?if ($is_mobile):?> class="header-auth-form sort-view-mobile mfp-hide clearfix" id="sort-area"<?else:?>class="sort-view clearfix"<?endif;?>>
         
             <div class="sort-by float-left">
             <?if ($is_mobile):?>
                <? foreach ($arParams["SORT_PARAMETERS"] as $arp): ?>
                        <?if (in_array($arp["name"], array('price', 'name'))):?>
                        <div class="sort-select select float-left">
                            <a id="link_sort<?=$arp["name"]?>asc" class="sorting" href="<?= $APPLICATION->GetCurPageParam("sort_by=" . $arp["name"] . "&" . "order=asc", array("sort_by", "order"), false) ?>"></a>    
                            <input data-name="<?= GetMessage($arp["name"]) ?>" name="sortfield" id="sort<?=$arp["name"]?>asc" type="radio" <?if (($_REQUEST['sort_by']==$arp["name"] && $_REQUEST["order"]=='asc') || ($_REQUEST['sort_by']=='' && $arp["name"]=='sort')):?>checked=""<?endif;?>>
                            <label for="sort<?=$arp["name"]?>asc"><?= GetMessage($arp["name"]) ?> <?= GetMessage($arp["name"].'_asc') ?></label>
                        </div>
                        <div class="sort-select select float-left">  
                            <a id="link_sort<?=$arp["name"]?>desc" class="sorting" href="<?= $APPLICATION->GetCurPageParam("sort_by=" . $arp["name"] . "&" . "order=desc", array("sort_by", "order"), false) ?>"></a>
                            <input data-name="<?= GetMessage($arp["name"]) ?>" name="sortfield" id="sort<?=$arp["name"]?>desc" type="radio" <?if (($_REQUEST['sort_by']==$arp["name"] && $_REQUEST["order"]=='desc') || ($_REQUEST['sort_by']=='' && $arp["name"]=='sort')):?>checked=""<?endif;?>>
                            <label for="sort<?=$arp["name"]?>desc"><?= GetMessage($arp["name"]) ?> <?= GetMessage($arp["name"].'_desc') ?></label>
                        </div>
                        <?else:?>
                        <div class="sort-select select float-left">
                            <a id="link_sort<?=$arp["name"]?>" class="sorting" href="<?= $APPLICATION->GetCurPageParam("sort_by=" . $arp["name"] . "&" . "order=" . $arp["order"], array("sort_by", "order"), false) ?>"></a>
                            <input data-name="<?= GetMessage($arp["name"]) ?>" name="sortfield" id="sort<?=$arp["name"]?>" type="radio" <?if ($_REQUEST['sort_by']==$arp["name"] || ($_REQUEST['sort_by']=='' && $arp["name"]=='sort')):?>checked=""<?endif;?>>
                            <label for="sort<?=$arp["name"]?>"><?= GetMessage($arp["name"]) ?></label>
                        </div>
                        <?endif;?>
                <? endforeach ?>
              <?else:?>
                 <label><?= GetMessage("SORT_TITLE")?>: </label>
                 <?foreach ($arParams["SORT_PARAMETERS"] as $arp):?>
                 <div class="sort-select select float-left <?if ($arp["selected"]):?>current<?endif;?>">
                     <?
                     $arrow = "<i class=\"fa fa-long-arrow-up\" aria-hidden=\"true\"></i> <i class=\"fa fa-long-arrow-down\" aria-hidden=\"true\"></i>";
                     if ($arp["selected"]) {
                         $arrow = $arp["order"] == "asc" ? "<i class=\"fa fa-long-arrow-up\" aria-hidden=\"true\"></i>" : "<i class=\"fa fa-long-arrow-down\" aria-hidden=\"true\"></i>";
                     }
                     ?>
                     <a class="sorting" rel="nofollow" href="<?= $APPLICATION->GetCurPageParam("sort_by=" . $arp["name"] . "&" . "order=" . $arp["order"], array("sort_by", "order"), false)?>"><?= GetMessage($arp["name"])?></a> <?= $arrow?>
                 </div>
                 <?endforeach?>
              <?endif;?>
             </div>
             <?/*<!-- View by -->
             <div class="view-by float-right">
                 <ul>
                     <li><a href="#list" title="" class="current"><img src="<?= SITE_TEMPLATE_PATH ?>/images/icon-list.png" alt=""></a></li>
                     <li><a href="#map" title=""><img src="<?= SITE_TEMPLATE_PATH ?>/images/icon-map.png" alt=""></a></li>
                 </ul>
             </div> */?>
         </div>
      <?endif?>

        <div class="tour-list-cn clearfix">
            <? if ($arParams["DISPLAY_TOP_PAGER"]): ?>
                <?= $arResult["NAV_STRING"] ?><br />
            <? endif; ?>
			<? foreach ($arResult["ITEMS"] as $keyitem=>$arItem): ?>
			<? /* if (!empty($arItem["PROPERTIES"]["SHOWONPAGE" . POSTFIX_PROPERTY]["VALUE"])): */?>
                <?
                $p = $arItem["DISPLAY_PROPERTIES"];
                $arParams["__BOOKING_REQUEST"]["id"] = array($arItem["ID"]);
                $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
                $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
                ?>
                <div class="tour-list-item excursion" id="<?= $this->GetEditAreaId($arItem['ID']); ?>">
                    <figure class="tour-img float-left">
                        <div class="stickers">
                            <?if (is_array($arItem["PROPERTIES"]["HIT"]["VALUE_XML_ID"])):?>
                            <?
                                // отображение по сортировке
                                asort($arItem["PROPERTIES"]["HIT"]["VALUE_SORT"]);
                                $sorts_key = array_keys($arItem["PROPERTIES"]["HIT"]["VALUE_SORT"]);
                                $sorts_key = array_slice($sorts_key,0,3);
                            ?>
								<?foreach($sorts_key as $key){
								    if (LANGUAGE_ID != "ru") $arItem["PROPERTIES"]["HIT"]["VALUE"][$key] = GetMessage(strtolower($arItem["PROPERTIES"]["HIT"]["VALUE_XML_ID"][$key]));
                                    ?>
									<div class="sticker_<?=strtolower($arItem["PROPERTIES"]["HIT"]["VALUE_XML_ID"][$key]);?>" title="<?=$arItem["PROPERTIES"]["HIT"]["VALUE"][$key]?>"></div>
								<?}?>
							<?endif;?>
						</div>
                    <?
                    $pre_photo=array();
                    $detail_link = getCalculateDetailLink($arItem["DETAIL_PAGE_URL"], $arParams["__BOOKING_REQUEST"]);?>
                    <?
                    if (!empty($arItem["PREVIEW_PICTURE"])):
                        $an_file = CFile::ResizeImageGet($arItem["PREVIEW_PICTURE"], array('width' => 410, 'height' => 250), BX_RESIZE_IMAGE_EXACT, true);
                        $pre_photo[] = $an_file["src"];
                    endif;
					if (!empty($arItem["PROPERTIES"]["PICTURES". POSTFIX_PROPERTY]["VALUE"])):
                        $countfile = 0;
                        foreach ($arItem["PROPERTIES"]["PICTURES". POSTFIX_PROPERTY]["VALUE"] as $idfile) {
                            if ($countfile>4) continue;
                            $an_file = CFile::ResizeImageGet($idfile, array('width' => 410, 'height' => 250), BX_RESIZE_IMAGE_EXACT, true);
                            $pre_photo[] = $an_file["src"];
                            $countfile++;
                        }
                    elseif (!empty($arItem["PROPERTIES"]["PICTURES"]["VALUE"])):
                        $countfile = 0;
                        foreach ($arItem["PROPERTIES"]["PICTURES"]["VALUE"] as $idfile) {
                            if ($countfile>4) continue;
                            $an_file = CFile::ResizeImageGet($idfile, array('width' => 410, 'height' => 250), BX_RESIZE_IMAGE_EXACT, true);
                            $pre_photo[] = $an_file["src"];
                            $countfile++;
                        }
                    endif;
                    if (count($pre_photo)==0) $pre_photo = SITE_TEMPLATE_PATH . "/images/nophoto.jpg";
                    ?>
                    <?if (count($pre_photo)>1): $limit = (count($pre_photo)>5)? 5 : count($pre_photo);?>
                        <div class="banners-slider-list owl-carousel">
                            <?for ($i=0; $i<$limit; $i++):?>
                                <a itemprop ="url" href="<?echo $detail_link?>" title="" target="_blank">
                                <?$webpfile = makeWebp($pre_photo[$i]);?>
                                <picture> 
                                    <?if ($webpfile!=''):?>
                                    <source type="image/webp" srcset="<?=$webpfile?>"> 
                                    <?endif;?>
                                    <img loading="lazy"  itemprop="image" src="<?=$pre_photo[$i]?>" alt="<?echo LANGUAGE_ID == "ru" ? $arItem["NAME"] : $arItem["PROPERTIES"]["NAME".POSTFIX_PROPERTY]["VALUE"]?>"/>
                                </picture>    
                                
                                </a>
                            <?endfor;?>
                        </div>
                     <?else:?>
                        <a itemprop ="url" href="<?echo $detail_link?>" title="" target="_blank">
                        <?$webpfile = makeWebp($pre_photo[0]);?>
                        <picture> 
                            <?if ($webpfile!=''):?>
                            <source type="image/webp" srcset="<?=$webpfile?>"> 
                            <?endif;?>
                            <img loading="lazy"  itemprop="image" src="<?=$pre_photo[0]?>" alt="<?echo LANGUAGE_ID == "ru" ? $arItem["NAME"] : $arItem["PROPERTIES"]["NAME".POSTFIX_PROPERTY]["VALUE"]?>"/>
                        </picture>       
                        </a> 
                     <?endif;?>
                    </figure>
                    <div class="tour-text">
                        <div class="tour-name">
                            <a href="<? echo $detail_link?>" title="<? echo LANGUAGE_ID == "ru" ? $arItem["NAME"] : $arItem["PROPERTIES"]["NAME" . POSTFIX_PROPERTY]["VALUE"] ?>" target="_blank"><? echo LANGUAGE_ID == "ru" ? $arItem["NAME"] : $arItem["PROPERTIES"]["NAME" . POSTFIX_PROPERTY]["VALUE"] ?></a>
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
                               // }
                                ?>
                            </div>
                        </div>
                        <div class="ship-port">                            
                                <?php 
                                    $tmpfiltr = null; 
                                    $tmpID = null; 
                                ?>
                                <?if (!empty($arItem["PROPERTIES"]["TOWN"]["VALUE"]) && count($arItem["PROPERTIES"]["TOWN"]["VALUE"])>1):
                                    $tmpfiltr = ['ID'=>$arItem["PROPERTIES"]["TOWN"]["VALUE"], 'TYPE'=>'route'];
                                    $tmpID = $arItem["PROPERTIES"]["TOWN"]["VALUE"][0];
                                ?>    
                                <?php elseif (!empty($arItem["PROPERTIES"]["SIGHTS"]["VALUE"]) && count($arItem["PROPERTIES"]["SIGHTS"]["VALUE"])):
                                    $tmpfiltr = ['ID'=>$arItem["PROPERTIES"]["SIGHTS"]["VALUE"], 'TYPE'=>'route'];
                                    $tmpID = $arItem["PROPERTIES"]["SIGHTS"]["VALUE"][0];    
                                ?>
                                <?endif?>                                
								<? if (!empty($arItem["PROPERTIES"]["ROUTE"]["VALUE"])): ?>
								<div class="ship-port-item">
                                    <span class="label"><a data-content="<?= GetMessage('TOWN') ?>" class="border_icon"><img loading="lazy"  src="<?= SITE_TEMPLATE_PATH ?>/images/icon/new-route.png"></a> </span>
                                    <?if ($tmpfiltr) :?>
                                        <a
                                            href="javascript:;"
                                            title="<?= GetMessage('T_ITEMS_LIST_MAP_LINK') ?>"
                                            class="show-map"
                                            data-id="<?=$tmpID?>"
                                            data-filter='<?= json_encode($tmpfiltr) ?>'
                                        >
                                            <?= strip_tags($arItem["DISPLAY_PROPERTIES"]["ROUTE" . POSTFIX_PROPERTY]["DISPLAY_VALUE"]) ?>
                                        </a>
                                    <? else: ?>
                                        <?= strip_tags($arItem["DISPLAY_PROPERTIES"]["ROUTE" . POSTFIX_PROPERTY]["DISPLAY_VALUE"]) ?>    	
                                    <? endif; ?>    								
								</div>
                                <? elseif (!empty($arItem["PROPERTIES"]["TOWN"]["VALUE"])): ?>
                                <div class="ship-port-item">
                                    <span class="label"><a data-content="<?= GetMessage('TOWN') ?>" class="border_icon"><img loading="lazy" src="<?= SITE_TEMPLATE_PATH ?>/images/icon/new-route.png"></a> </span>
                                    <?
                                    $p["TOWN"]["VALUE"] = (array) $p["TOWN"]["VALUE"];
                                    $db_res_towns = CIBlockElement::GetList(false, array("ACTIVE" => "Y", "IBLOCK_ID" => $p["TOWN"]["LINK_IBLOCK_ID"], "ID" => $p["TOWN"]["VALUE"]), false, false, array("ID", "NAME", "PROPERTY_NAME" . POSTFIX_PROPERTY));
                                    $towns = null;
                                    while ($res = $db_res_towns->Fetch()) {
                                        $towns[] = $res["PROPERTY_NAME" . POSTFIX_PROPERTY . "_VALUE"] ? $res["PROPERTY_NAME" . POSTFIX_PROPERTY . "_VALUE"] : $res["NAME"];
                                    }?>
                                    <? if($towns) :?>
                                        <? if($tmpfiltr) :?>
                                            <a
                                                href="javascript:;"
                                                title="<?= GetMessage('T_ITEMS_LIST_MAP_LINK') ?>"
                                                class="show-map"
                                                data-id="<?=$tmpID?>"
                                                data-filter='<?= json_encode($tmpfiltr) ?>'
                                            >
                                            <? echo implode(" - ", $towns); ?> 	
                                            </a>
                                        <? else: ?>
                                            <? echo implode(" - ", $towns); ?> 	
                                        <? endif; ?>                                        
                                    <? endif; ?>
                                </div>
                            <? endif; ?>
                            <div class="description">
                                <div class="dex-item">
                                    <? if (!empty($arItem["PROPERTIES"]["DEPARTURE_TIME". POSTFIX_PROPERTY]["VALUE"])): ?>
                                    <div class="ship-port-item">
                                        <span class="label"><a data-content="<?= GetMessage('DEPARTURE_TIME') ?>" class="border_icon"><img loading="lazy" src="<?= SITE_TEMPLATE_PATH ?>/images/icon/clock.png"></a></span>  
                                            <?= GetMessage('DEPARTURE_TIME') ?>: <? echo $arItem["DISPLAY_PROPERTIES"]["DEPARTURE_TIME" . POSTFIX_PROPERTY]["DISPLAY_VALUE"]; ?>
                                        <? if ($arItem["PROPERTIES"]["DAYS"]["VALUE"]>"1"): ?>                          
                                            <span class="vl"> | </span>
                                            <?= GetMessage('DAYS') ?> <?= $arItem["PROPERTIES"]["DAYS"]["VALUE"] ?>                         
                                        <?elseif (!empty($arItem["PROPERTIES"]["DURATION_TIME"]["VALUE"])):?>                        
                                            <span class="vl"> | </span>
                                            <?= GetMessage('DURATION_TIME') ?> <?= $arItem["PROPERTIES"]["DURATION_TIME"]["VALUE"] ?>                           
                                        <? endif; ?>
                                    </div>
                                <? endif; ?>
                                <?if (!empty($arResult['AVAIL_DATES'][$arItem['ID']])):?>
                                <div class="ship-port-item">
                                    <span class="label"><a data-content="<?= GetMessage('TOURS_DATE') ?>" class="border_icon"><img loading="lazy" src="<?= SITE_TEMPLATE_PATH ?>/images/icon/calendar.png"></a></span>  
                                        <?= GetMessage('TOURS_DATE') ?>: 
                                        <?$tmpcount=0; $dateslimit = []; foreach ($arResult['AVAIL_DATES'][$arItem['ID']] as $dateunix): $tmpcount++;
                                            $dateslimit[]= date('d.m.Y', $dateunix);
                                            if ($tmpcount>3) break;
                                            endforeach;
                                        ?>
                                        <span <?if ($arItem['PROPERTIES']['IS_EXCURSION_TOUR']['VALUE']!=''):?>data-countday=<?=$arItem['PROPERTIES']['DAYS']['VALUE']?><?endif;?> data-dates="<?=json_encode($arResult['AVAIL_DATES'][$arItem['ID']])?>" data-link="<?=$detail_link?>" data-id="<?=$arItem['ID']?>" class="selectdates" onclick="initSelectDates($(this))"><?=implode($dateslimit, ', ')?></span>
                                        
                                        <input type="text" name="eventdate" style="cursor:pointer;outline: none; color:white; border:none; height: 0px;" readonly />
                                        <br>
                                </div>
                                <? /*elseif (!empty($arItem["PROPERTIES"]["TOURS_DATE". POSTFIX_PROPERTY]["VALUE"])): ?>
                                    <li>
                                        <span class="label"><a data-content="<?= GetMessage('TOURS_DATE') ?>" class="border_icon"><img loading="lazy" src="<?= SITE_TEMPLATE_PATH ?>/images/icon/calendar.png"></a></span>  <?= GetMessage('TOURS_DATE') ?>: <? echo $arItem["DISPLAY_PROPERTIES"]["TOURS_DATE" . POSTFIX_PROPERTY]["DISPLAY_VALUE"]; ?> <br>

                                    </li>
                                <? */ endif; ?>
                                <?
                                if (count($p["HOTEL"]["VALUE"])>0):?>
                                    <div class="ship-port-item">
                                        <span class="label"><a data-content="<?= GetMessage('HOTEL') ?>" class="border_icon"><img loading="lazy" src="<?= SITE_TEMPLATE_PATH ?>/images/icon/house-visiting.png"></a> </span>
                                    <?foreach ($p["HOTEL"]["VALUE"] as $hotelid):?>
                                        <?=$arResult['HOTELS'][$hotelid]['name'];?><?if ($arResult['CAT_HOTELS'][$arResult['HOTELS'][$hotelid]['cat']]):?>, <?=$arResult['CAT_HOTELS'][$arResult['HOTELS'][$hotelid]['cat']]?><?endif;?><?if ($arResult['TOWNS_HOTELS'][$arResult['HOTELS'][$hotelid]['town']]):?>, <?=$arResult['TOWNS_HOTELS'][$arResult['HOTELS'][$hotelid]['town']]?><?endif;?><br />   
                                    <?endforeach;?>
                                    </div>    
                                <? endif ?>
                                <?
                                $food = null;
                                if ($p["FOOD"]["VALUE"]) {
                                    $p["FOOD"]["VALUE"] = (array) $p["FOOD"]["VALUE"];
                                    $db_res_food = CIBlockElement::GetList(false, array("ACTIVE" => "Y", "IBLOCK_ID" => $p["FOOD"]["LINK_IBLOCK_ID"], "ID" => $p["FOOD"]["VALUE"]), false, false, array("ID", "NAME", "PROPERTY_NAME" . POSTFIX_PROPERTY));
                                    $food = null;
                                    while ($res = $db_res_food->Fetch()) {
                                        $food[] = $res["PROPERTY_NAME" . POSTFIX_PROPERTY . "_VALUE"] ? $res["PROPERTY_NAME" . POSTFIX_PROPERTY . "_VALUE"] : $res["NAME"];
                                    }
                                }
                                if ($food):
                                    ?>
                                    <div class="ship-port-item">
                                        <span class="label"><a data-content="<?= GetMessage('FOOD') ?>" class="border_icon"><img loading="lazy" src="<?= SITE_TEMPLATE_PATH ?>/images/icon/dinner.png"></a></span> <?= implode(", ", $food) ?>
                                    </div>
                                <? endif ?>                       
                                <?
                                    $tourtype = null;
                                    if ($p["TOURTYPE"]["VALUE"]) {
                                        $p["TOURTYPE"]["VALUE"] = (array) $p["TOURTYPE"]["VALUE"];
                                        $db_res_tourtype = CIBlockElement::GetList(false, array("ACTIVE" => "Y", "IBLOCK_ID" => $p["TOURTYPE"]["LINK_IBLOCK_ID"], "ID" => $p["TOURTYPE"]["VALUE"]), false, false, array("ID", "NAME", "PROPERTY_NAME" . POSTFIX_PROPERTY));
                                        $tourtype = null;
                                        while ($res = $db_res_tourtype->Fetch()) {
                                            $tourtype[] = $res["PROPERTY_NAME" . POSTFIX_PROPERTY . "_VALUE"] ? $res["PROPERTY_NAME" . POSTFIX_PROPERTY . "_VALUE"] : $res["NAME"];
                                        }
                                    }
                                    if ($tourtype):
                                        ?>
                                    <div class="ship-port-item">
                                        <span class="label"><a data-content="<?= GetMessage('TYPE') ?>" class="border_icon"><img src="<?= SITE_TEMPLATE_PATH ?>/images/icon/earth-pictures.png"></a> </span>
                                        <?= implode(", ", $tourtype) ?>
                                    </div>
                                <? endif; ?>
                                </div>
                               <div class="dex-item dex-right">
                                   <?if ($arItem["PROPERTIES"]["FOR_SPOT_PAYMENT"]["VALUE"]):?>                                    
                                <p><?= GetMessage('FOR_SPOT_PAYMENT') ?></p>                                   
                            <?endif?>
                                <div class="ship-port-item">
                                    <?
                                    if ($arParams["CALCULATION_PRICE_RESULT"][$arItem["ID"]]):
                                        $price = \travelsoft\Currency::getInstance()->convertCurrency(
                                                $arParams["CALCULATION_PRICE_RESULT"][$arItem["ID"]]["PRICE"], $arParams["CALCULATION_PRICE_RESULT"][$arItem["ID"]]['CURRENCY_ID']
                                        );
                                    $discount_price = null;
                                    
                                    if (isset($arParams["CALCULATION_PRICE_RESULT"][$arItem["ID"]]["DISCOUNT_PRICE"]) && $arParams["CALCULATION_PRICE_RESULT"][$arItem["ID"]]["DISCOUNT_PRICE"] > 0) {
                                        $discount_price = \travelsoft\Currency::getInstance()->convertCurrency(
                                                $arParams["CALCULATION_PRICE_RESULT"][$arItem["ID"]]["DISCOUNT_PRICE"], $arParams["CALCULATION_PRICE_RESULT"][$arItem["ID"]]['CURRENCY_ID']
                                        );
                                    }
                                        ?><?if (!$discount_price):?>
                                    <a href="<?= $detail_link?>" target="_blank">
                                        <div class="price-box float-right" Style="cursor: pointer;"><?= GetMessage("price_night_title", array("#price#" => $price)); ?></div>
                                    </a>
                                    <?else:?>
                                    <a href="<?= $detail_link?>" target="_blank">
                                        <div class="price__old"><?= $price?></div>
                                            <div class="price-box float-right" Style="cursor: pointer;"><?= GetMessage("price_night_title", array("#price#" => $discount_price)); ?></div>
                                        </a>
                                    <?endif?>
                                        <? else: ?>
                                        <a href="<? echo $detail_link?>" title="" target="_blank">
                                            <div class="price-box float-right"><span class="detail"><?= GetMessage("MORE") ?></span></div>
                                        </a>
                                        <? endif ?>
                                </div>  
                               </div>
                            </div>    
                            <p>
                                <? if (!empty($arItem["PROPERTIES"]["PREVIEW_TEXT" . POSTFIX_PROPERTY]["VALUE"])): ?>
                                    <?= substr2($arItem["DISPLAY_PROPERTIES"]["PREVIEW_TEXT" . POSTFIX_PROPERTY]["DISPLAY_VALUE"], 150); ?>
                                <? elseif (!empty($arItem["PROPERTIES"]["DETAIL_TEXT" . POSTFIX_PROPERTY]["VALUE"])): ?>
                                    <?= substr2($arItem["DISPLAY_PROPERTIES"]["DETAIL_TEXT" . POSTFIX_PROPERTY]["DISPLAY_VALUE"], 150); ?>
                                <? else: ?>
                                    <?= substr2($arItem["DISPLAY_PROPERTIES"]["HD_DESC" . POSTFIX_PROPERTY]["DISPLAY_VALUE"], 150); ?>
                                <? endif ?>                                            
                            </p>
                                           
                        </div>    
						<?/* div class="tour-star-address">
                            <span class="rating">
                                Рейтинг <br>
                                <ins>7.5</ins>
                            </span>
						</div */?>                        
						<?/*
						<div class="tour-service float-left">
                            <? if (!empty($arItem["DISPLAY_PROPERTIES"]["SERVICES"]["VALUE"])): ?>
        <? $count = 0; ?>
        <? foreach ($arItem["DISPLAY_PROPERTIES"]["SERVICES"]["VALUE"] as $k => $value): ?>
                        <? if (!empty($arResult["SERVICES_ICON"][$value]["ICON"]) && $count <= 6): ?>
                                        <a data-content="<?= $arResult["SERVICES_ICON"][$value]["TITLE"] ?>" class="border_icon <?= $arResult["SERVICES_ICON"][$value]["ICON"] ?>"></a>
                        <? endif ?>
                        <? $count++ ?>
                    <? endforeach; ?>
    <? endif; ?>
</div> */?>
                    </div>
                </div>
		<?/* endif */?>
<? endforeach; ?>
<? if ($arParams["DISPLAY_BOTTOM_PAGER"]): ?>
			<br /><?= $arResult["NAV_STRING"] ?>
<? endif; ?>
        </div>
    </section>
</div>
<script>
    (function () {
        function initPopover() {
            $('.tour-service a, .ship-port .ship-port-item .label a').webuiPopover({
                placement: "left",
                trigger: "hover"
            });
			/*$('.tour-service a, .ship-port li .').webuiPopover({
                placement: "left",
                trigger: "hover"
			});*/
        }
        initPopover();
    })();
</script>
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
    <?if (!empty($arResult["ITEMS"])):?>
    <div class="sort-block">
        <a href="#sort-area" class="magnificbutton show-sort-link"><?if ($requestsort!='' && in_array($requestsort, $sortnames)): $sort_name =$requestsort; ?><?= GetMessage($sort_name) ?><?else:?><?= GetMessage('sort') ?><?endif;?></a>
    </div>
    <?endif;?>
    <div class="searcbyname-block">
        <a href="javascript:void(0)" onclick="$('.searchbyname-block-content').toggle();" class="show-search-link"><i class="fa fa-search" aria-hidden="true"></i></a>
    </div>
    </div>
    <?endif;?>
    <?if (!$is_mobile):?>
    <p><?= GetMessage('FOUND') ?> <ins id="searching__cnt__elements"><?= $arResult['NAV_RESULT']->NavRecordCount ?></ins></p>
    <?endif;?>
</div>
<? $this->EndViewTarget() ?>
<?if ($is_mobile) {
    $this->SetViewTarget("cnt__elements_header");?>
    (<ins id="searching__cnt__elements"><?=$arResult['NAV_RESULT']->NavRecordCount?></ins>)
    <?$this->EndViewTarget();
}?>
<span id="cnt__elements"><?= $arResult['NAV_RESULT']->NavRecordCount ?></span>
<script>
<?/*$(window).load(function(){
    $( '.list-favorite-button' ).each(function( index, val ) {
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
    });
});*/?>
$( document ).ready(function() {
    
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