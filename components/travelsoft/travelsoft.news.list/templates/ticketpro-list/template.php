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
                 <div class="sort-select select float-left <?if ($arp["selected"]):?>current<?endif;?> ">
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
             
         </div>
      <?endif?>

        <div class="tour-list-cn clearfix">
            <? if ($arParams["DISPLAY_TOP_PAGER"]): ?>
                <?= $arResult["NAV_STRING"] ?><br />
            <? endif; ?>
			<? foreach ($arResult["ITEMS"] as $arItem): ?>
			<? /* if (!empty($arItem["PROPERTIES"]["SHOWONPAGE" . POSTFIX_PROPERTY]["VALUE"])): */?>
                <?
                $p = $arItem["DISPLAY_PROPERTIES"];
                $arParams["__BOOKING_REQUEST"]["id"] = array($arItem["ID"]);
                $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
                $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
                ?>
                <div class="tour-list-item" id="<?= $this->GetEditAreaId($arItem['ID']); ?>">
                    <figure class="tour-img float-left">
                    <?
                    $pre_photo=array();
                    $detail_link = getCalculateDetailLink($arItem["DETAIL_PAGE_URL"], $arParams["__BOOKING_REQUEST"]);?>
                    <?
                    if (!empty($arItem["PREVIEW_PICTURE"])):
                        $an_file = CFile::ResizeImageGet($arItem["PREVIEW_PICTURE"], array('width' => 300, 'height' => 436), BX_RESIZE_IMAGE_EXACT, true);
                        $pre_photo[] = $an_file["src"];
                    elseif (!empty($arItem["DETAIL_PICTURE"])):
                        $an_file = CFile::ResizeImageGet($arItem["DETAIL_PICTURE"], array('width' => 300, 'height' => 436), BX_RESIZE_IMAGE_EXACT, true);
                        $pre_photo[] = $an_file["src"];
                    endif;
					
                    if (count($pre_photo)==0) $pre_photo[] = SITE_TEMPLATE_PATH . "/images/nophoto.jpg";
                    ?>
                    <?if (count($pre_photo)>1): $limit = (count($pre_photo)>5)? 5 : count($pre_photo);?>
                        <div class="banners-slider-list">
                            <?for ($i=0; $i<$limit; $i++):?>
                                <a itemprop ="url" href="<?echo $detail_link?>" title="" target="_blank">
                                <img itemprop="image" src="<?=$pre_photo[$i]?>" alt="<?echo LANGUAGE_ID != "en" ? $arItem["NAME"] : $arItem["PROPERTIES"]["NAME".POSTFIX_PROPERTY]["VALUE"]?>"/>
                                </a>
                            <?endfor;?>
                        </div>
                     <?else:?>
                        <a itemprop ="url" href="<?echo $detail_link?>" title="" target="_blank">
                            <img itemprop="image" src="<?=$pre_photo[0]?>" alt="<?echo LANGUAGE_ID != "en" ? $arItem["NAME"] : $arItem["PROPERTIES"]["NAME".POSTFIX_PROPERTY]["VALUE"]?>"/>
                        </a> 
                     <?endif;?>
                    </figure>
                    <div class="tour-text">
                        <div class="tour-name">
                            <a href="<? echo getCalculateDetailLink($arItem["DETAIL_PAGE_URL"], $arParams["__BOOKING_REQUEST"])?>" title="<? echo LANGUAGE_ID != "en" ? $arItem["NAME"] : $arItem["PROPERTIES"]["NAME" . POSTFIX_PROPERTY]["VALUE"] ?>" target="_blank"><? echo LANGUAGE_ID != "en" ? $arItem["NAME"] : $arItem["PROPERTIES"]["NAME" . POSTFIX_PROPERTY]["VALUE"] ?></a>
                            <div class="list-favorite-button">
                                <?$APPLICATION->IncludeComponent(
                                    	"travelsoft:favorites.add",
                                    	"",
                                    	Array(
                                            "SHORT_DISPLAY"=>"Y",
                                    		"OBJECT_ID" => $arItem["ID"],
                                    		"OBJECT_TYPE" => "IBLOCK_ELEMENT",
                                            "STORE_ID" => $arParams["IBLOCK_ID"]
                                    	)
                                    );
                                ?>
                            </div>
                        </div>
                        <ul class="ship-port">
                            <?if ($arItem["PROPERTIES"]["place"]["VALUE"]):?>
                            <?if (POSTFIX_PROPERTY=='_EN') {
                                if ($arItem["PROPERTIES"]["city_EN"]["VALUE"]!='') $arItem["PROPERTIES"]["city"]["VALUE"] = $arItem["PROPERTIES"]["city_EN"]["VALUE"];
                                if ($arItem["PROPERTIES"]["place_EN"]["VALUE"]!='') $arItem["PROPERTIES"]["place"]["VALUE"] = $arItem["PROPERTIES"]["place_EN"]["VALUE"];
                            }?>
                             <li class="info-star-address">
                                <address class="info-address">
                                    <i class="fa fa-map-marker"></i>
                                    <span itemprop="location" itemscope="" itemtype="http://schema.org/Place">
                                        <span itemprop="name"><?=$arItem["PROPERTIES"]["city"]["VALUE"]?>, <?=$arItem["PROPERTIES"]["place"]["VALUE"]?></span>
                                        <span itemprop="address" itemscope="" itemtype="http://schema.org/PostalAddress">
                                        </span>
                                     </span>
                                </address>
                             </li>
                            <?endif?>
							<? if (!empty($arItem["PROPERTIES"]["TIMESTART"]["VALUE"])): ?>
                                <li>
									<span class="label"><a data-content="<?= GetMessage('DEPARTURE_TIME') ?>" class="border_icon"><img src="<?= SITE_TEMPLATE_PATH ?>/images/icon/clock.png"></a></span>  <?= GetMessage('DEPARTURE_TIME') ?>: <? echo date('H:i', strtotime($arItem["DISPLAY_PROPERTIES"]["TIMESTART"]['VALUE'])); ?> <br>
                                </li>
								<li>
									<span class="label"><a data-content="<?= GetMessage('TOURS_DATE') ?>" class="border_icon"><img src="<?= SITE_TEMPLATE_PATH ?>/images/icon/calendar.png"></a></span>  
                                    <? echo date('d.m.Y', strtotime($arItem["DISPLAY_PROPERTIES"]["TIMESTART"]['VALUE'])); ?><br>
                                </li>
							<? endif; ?>
                           
                        </ul>
						
                        <p>
                        <? if (!empty($arItem["PROPERTIES"]["PREVIEW_TEXT" . POSTFIX_PROPERTY]["VALUE"])): ?>
                            <?= substr2($arItem["DISPLAY_PROPERTIES"]["PREVIEW_TEXT" . POSTFIX_PROPERTY]["DISPLAY_VALUE"], 150); ?>
                        <? elseif (!empty($arItem["PROPERTIES"]["DETAIL_TEXT" . POSTFIX_PROPERTY]["VALUE"])): ?>
                            <?= substr2($arItem["DISPLAY_PROPERTIES"]["DETAIL_TEXT" . POSTFIX_PROPERTY]["DISPLAY_VALUE"], 150); ?>
                        <? else: ?>
                            <?= substr2($arItem["DISPLAY_PROPERTIES"]["HD_DESC" . POSTFIX_PROPERTY]["DISPLAY_VALUE"], 150); ?>
                        <? endif ?>                                            
                        </p>
                        <?if ($arItem['PROPERTIES']['min_price']['VALUE']):?>
                            <a href="<?= getCalculateDetailLink($arItem["DETAIL_PAGE_URL"], $arParams["__BOOKING_REQUEST"])?>" target="_blank">
    							<div class="price-box float-left" Style="cursor: pointer;"><?= GetMessage("price_night_title", array("#price#" => $arItem['PROPERTIES']['min_price']['VALUE'].' BYN')); ?></div>
    						</a>
                        <?else:?>
                            <a href="<? echo getCalculateDetailLink($arItem["DETAIL_PAGE_URL"], $arParams["__BOOKING_REQUEST"])?>" title="" target="_blank">
                                <div class="price-box float-right"><span class="detail"><?= GetMessage("MORE") ?></span></div>
                            </a>
                        <?endif;?>
                         
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
            $('.tour-service a, .ship-port li .label a').webuiPopover({
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