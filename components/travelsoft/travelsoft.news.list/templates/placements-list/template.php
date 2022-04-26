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
use \Bitrix\Main\Localization\Loc;

$this->setFrameMode(true);
$obRequest = Application::getInstance()->getContext()->getRequest();
$requestsort = $obRequest->get("sort_by"); 
$is_mobile = check_smartphone();
if (empty($arResult["ITEMS"])):
    ?>
    <div class="col-md-9 col-md-pull-0 content-page-detail">
        <div class="alert-box alert-attention"><?= GetMessage("TEXT_NOT_FOUND", array("#LINK#" => $APPLICATION->GetCurDir())) ?></div>
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


$price_title = "";
$more_then_day = $arParams["__BOOKING_REQUEST"]["date_to"] - $arParams["__BOOKING_REQUEST"]["date_from"] > 86400;
if ($more_then_day) {
    $price_title = "#price#";
}
?>
<?
 //for price
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
    <div class="wrp-ttl">
        <h1><?= $APPLICATION->GetTitle() ?></h1>
        <a class="btn-lnk-map" href="<?=SITE_DIR?>belarus/map/?TYPE[]=<?=$arParams['IBLOCK_ID']?>" title="<?=Loc::getMessage('T_ITEMS_LIST_MAP_LINK')?>"><?=Loc::getMessage('T_ITEMS_LIST_MAP_LINK')?></a>
    </div>
    <section class="hotel-list">
        <? if ($arParams["SORT_PARAMETERS"]) : 
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
                        <input data-name="<?= GetMessage($arp["name"]) ?>" name="sortfield"  id="sort<?=$arp["name"]?>asc" type="radio" <?if (($_REQUEST['sort_by']==$arp["name"] && $_REQUEST["order"]=='asc') || ($_REQUEST['sort_by']=='' && $arp["name"]=='sort')):?>checked=""<?endif;?>>
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
                    <label><?= GetMessage("SORT_TITLE") ?>: </label>
                    <? foreach ($arParams["SORT_PARAMETERS"] as $arp): ?>
                        <div class="sort-select select float-left <?if ($arp["selected"]):?>current<?endif;?>">
                            <?
                            $arrow = "<i class=\"fa fa-long-arrow-up\" aria-hidden=\"true\"></i> <i class=\"fa fa-long-arrow-down\" aria-hidden=\"true\"></i>";
                            if ($arp["selected"]) {
                                $arrow = $arp["order"] == "asc" ? "<i class=\"fa fa-long-arrow-up\" aria-hidden=\"true\"></i>" : "<i class=\"fa fa-long-arrow-down\" aria-hidden=\"true\"></i>";
                            }
                            ?>
                            <a class="sorting " rel="nofollow" href="<?= $APPLICATION->GetCurPageParam("sort_by=" . $arp["name"] . "&" . "order=" . $arp["order"], array("sort_by", "order"), false) ?>"><?= GetMessage($arp["name"]) ?></a> <?= $arrow ?>
                        </div>
                    <? endforeach ?>
                <?endif;?>
                </div>
                <? /* <!-- View by -->
                  <div class="view-by float-right">
                  <ul>
                  <li><a href="#list" title="" class="current"><img src="<?= SITE_TEMPLATE_PATH ?>/images/icon-list.png" alt=""></a></li>
                  <li><a href="#map" title=""><img src="<?= SITE_TEMPLATE_PATH ?>/images/icon-map.png" alt=""></a></li>
                  </ul>
                  </div> */ ?>
            </div>
        <? endif ?>
        <!-- End Sort by and View by -->
        <div class="hotel-list-cn clearfix">
            <? if ($arParams["DISPLAY_TOP_PAGER"]): ?>
                <?= $arResult["NAV_STRING"] ?><br />
            <? endif; ?>
            <? foreach ($arResult["ITEMS"] as $keyitem=>$arItem): ?>
                <?
                $_request_string = $arItem["DETAIL_PAGE_URL"] . "?booking[id][]=" . $arItem["ID"];
                $arParams["__BOOKING_REQUEST"]["id"] = array($arItem["ID"]);
                $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
                $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
                ?>
                <div class="hotel-list-item" id="<?= $this->GetEditAreaId($arItem['ID']); ?>" <? if ($arResult["ID"] == SANATORIUM_IBLOCK_ID): ?>itemscope itemtype="http://schema.org/Place"<? endif ?>>
                    <figure class="hotel-img float-left">
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
                            <div class="banners-slider-list">
                                <?for ($i=0; $i<$limit; $i++):?>
                                    <a  <? if ($arResult["ID"] == SANATORIUM_IBLOCK_ID): ?>itemprop="url"<? endif ?> href="<?echo $detail_link?>" title="" target="_blank">
                                    <img src="<?=$pre_photo[$i]?>" alt="" <? if ($arResult["ID"] == SANATORIUM_IBLOCK_ID): ?>itemprop="image"<? endif ?>/>
                                    </a>
                                <?endfor;?>
                            </div>
                         <?else:?>
                            <a  <? if ($arResult["ID"] == SANATORIUM_IBLOCK_ID): ?>itemprop="url"<? endif ?> href="<?echo $detail_link?>" title="" target="_blank">
                                <img src="<?=$pre_photo[0]?>" alt="" <? if ($arResult["ID"] == SANATORIUM_IBLOCK_ID): ?>itemprop="image"<? endif ?>/>
                            </a> 
                         <?endif;?>
                    </figure>
                    <div class="hotel-text">
                        <div class="hotel-name" <? if ($arResult["ID"] == SANATORIUM_IBLOCK_ID): ?>itemprop="name"<? endif ?>>
                            <a <? if ($arResult["ID"] == SANATORIUM_IBLOCK_ID): ?>itemprop="url"<? endif ?> href="<? echo $detail_link ?>" title="<? echo LANGUAGE_ID == "ru" ? $arItem["NAME"] : $arItem["PROPERTIES"]["NAME" . POSTFIX_PROPERTY]["VALUE"] ?>" target="_blank"><? echo LANGUAGE_ID == "ru" ? $arItem["NAME"] : $arItem["PROPERTIES"]["NAME" . POSTFIX_PROPERTY]["VALUE"] ?></a>
							<? if($arItem["PROPERTIES"]["CAT_ID"]["VALUE"] != '4169' && !empty($arItem["PROPERTIES"]["CAT_ID"]["VALUE"])): ?>
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
                                    <? endif; ?>
                                </span>
                                <? /* rating
                                  <span class="rating">
                                  Рейтинг <br>
                                  <ins>7.5</ins>
                                  </span>
                                  end rating */ ?>
                            </div>
							<? endif; ?>
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
                        <address class="hotel-address" <? if ($arResult["ID"] == SANATORIUM_IBLOCK_ID): ?>itemprop="address" itemscope itemtype="http://schema.org/PostalAddress"<? endif ?>>
                            <img loading="lazy" src="/local/templates/travelsoft/images/icon/new-route.png">
                            <?if($arItem['DISPLAY_PROPERTIES']['MAP']['VALUE']):?>
                                <a
                                    href="javascript:;"
                                    title="<?= GetMessage('T_PLACEMENT_LIST_SHOW_MAP') ?>"
                                    class="show-map"
                                    data-id="<?=$arItem['ID']?>"
                                    data-filter='<?= \Bitrix\Main\Web\Json::encode($GLOBALS[$arParams['FILTER_NAME']]) ?>'
                                >
                            <?endif?>    
                            <? if (!empty($arItem["PROPERTIES"]["ADDRESS" . POSTFIX_PROPERTY]["VALUE"])): $adress = ''; ?>
                                <? $adress = substr2($arItem["DISPLAY_PROPERTIES"]["ADDRESS" . POSTFIX_PROPERTY]["DISPLAY_VALUE"], 200); ?><? if ($arResult["ID"] == SANATORIUM_IBLOCK_ID): ?><? $adress = "<span itemprop=\"streetAddress\">" . $adress . "</span>"; ?><? endif ?>
                                <?
                                if (!empty($arItem["PROPERTIES"]["REGIONS"]["VALUE"])) {
                                    $region = strip_tags($arItem["DISPLAY_PROPERTIES"]["REGIONS"]["DISPLAY_VALUE"]);
                                    if (LANGUAGE_ID != "ru") {
                                        $prop = getIBElementProperties($arItem["PROPERTIES"]["REGIONS"]["VALUE"]);
                                        $region = $prop["NAME" . POSTFIX_PROPERTY]["VALUE"];
                                    }
                                    if ($arResult["ID"] == SANATORIUM_IBLOCK_ID && !empty($region)) {
                                        $region = "<span itemprop=\"addressLocality\">" . $region . "</span>";
                                    }
                                }
                                if (!empty($arItem["PROPERTIES"]["TOWN"]["VALUE"])) {
                                    $town = strip_tags($arItem["DISPLAY_PROPERTIES"]["TOWN"]["DISPLAY_VALUE"]);
                                    if (LANGUAGE_ID != "ru") {
                                        $prop = getIBElementProperties($arItem["PROPERTIES"]["TOWN"]["VALUE"]);
                                        $town = $prop["NAME" . POSTFIX_PROPERTY]["VALUE"];
                                    }
                                    if ($arResult["ID"] == SANATORIUM_IBLOCK_ID && !empty($town)) {
                                        $town = "<span itemprop=\"addressLocality\">" . $town . "</span>";
                                    }
                                }
                                if (!empty($arItem["PROPERTIES"]["REGION"]["VALUE"])) {
                                    $obl = strip_tags($arItem["DISPLAY_PROPERTIES"]["REGION"]["DISPLAY_VALUE"]);
                                    if (LANGUAGE_ID != "ru") {
                                        $prop = getIBElementProperties($arItem["PROPERTIES"]["REGION"]["VALUE"]);
                                        $obl = $prop["NAME" . POSTFIX_PROPERTY]["VALUE"];
                                    }
                                    if ($arResult["ID"] == SANATORIUM_IBLOCK_ID && !empty($obl)) {
                                        $obl = "<span itemprop=\"addressLocality\">" . $obl . "</span>";
                                    }
                                }
                                if (!empty($arItem["PROPERTIES"]["COUNTRY"]["VALUE"])) {
                                    $country = strip_tags($arItem["DISPLAY_PROPERTIES"]["COUNTRY"]["DISPLAY_VALUE"]);
                                    if (LANGUAGE_ID != "ru") {
                                        $prop = getIBElementProperties($arItem["PROPERTIES"]["COUNTRY"]["VALUE"]);
                                        $country = $prop["NAME" . POSTFIX_PROPERTY]["VALUE"];
                                    }
                                    if ($arResult["ID"] == SANATORIUM_IBLOCK_ID && !empty($country)) {
                                        $country = "<span itemprop=\"addressLocality\">" . $country . "</span>";
                                    }
                                }
                                if (!empty($arItem["PROPERTIES"]["ACCOMODATION"]["VALUE"])) {
                                    $accomodation = strip_tags($arItem["DISPLAY_PROPERTIES"]["ACCOMODATION"]["DISPLAY_VALUE"]);
                                    if (LANGUAGE_ID != "ru") {
                                        $prop = getIBElementProperties($arItem["PROPERTIES"]["ACCOMODATION"]["VALUE"]);
                                        $accomodation = $prop["NAME" . POSTFIX_PROPERTY]["VALUE"];
                                    }
                                }
                                if (!empty($arItem["PROPERTIES"]["SANATORIUM"]["VALUE"])) {
                                    $sanatorium = strip_tags($arItem["DISPLAY_PROPERTIES"]["SANATORIUM"]["DISPLAY_VALUE"]);
                                    if (LANGUAGE_ID != "ru") {
                                        $prop = getIBElementProperties($arItem["PROPERTIES"]["SANATORIUM"]["VALUE"]);
                                        $sanatorium = $prop["NAME" . POSTFIX_PROPERTY]["VALUE"];
                                    }
                                }
                                if (!empty($arItem["PROPERTIES"]["ATTRACTION"]["VALUE"])) {
                                    $attraction = strip_tags($arItem["DISPLAY_PROPERTIES"]["ATTRACTION"]["DISPLAY_VALUE"]);
                                    if (LANGUAGE_ID != "ru") {
                                        $prop = getIBElementProperties($arItem["PROPERTIES"]["ATTRACTION"]["VALUE"]);
                                        $attraction = $prop["NAME" . POSTFIX_PROPERTY]["VALUE"];
                                    }
                                }
                                ?>
                                <? if ($town): ?><?
                                    $adress .= ", " . $town;
                                    unset($town);
                                    ?><? if ($region): ?><? $adress .= ", "; ?><? endif; ?><? endif; ?>
                                <? if ($region): ?><? $adress .= $region; ?><? endif; ?>
                                <? if ($obl): ?><? $adress .= ", " . $obl;?><? endif; ?>
                                <? if ($country): ?><? $adress .= ", " . $country; ?><? endif; ?>
                                <? echo $adress; unset($obl);?>
                                <? /* if ($accomodation): ?> <?= $accomodation ?><? endif; ?>
                                <? if ($sanatorium): ?> <?= $sanatorium ?><? endif; ?>
                                <? if ($attraction): ?> <?= $attraction ?><? endif; */ ?>
                            <? endif ?>
                            <?if(!empty($arItem["DISPLAY_PROPERTIES"]["CAPACITY"]["VALUE"])):?>
                                <br><i class="fa fa-users"></i> <?=GetMessage('CAPACITY')?> <?=strip_tags($arItem["DISPLAY_PROPERTIES"]["CAPACITY"]["DISPLAY_VALUE"])?>
                            <?endif?>   
                            <?if($arItem['DISPLAY_PROPERTIES']['MAP']['VALUE']):?>   
                                </a>
                            <?endif?>                                 
                        </address>
                        <div class="description">
                            <div class="dex-item">
                                <div class="ship-port <? if ($arResult["ID"] == SANATORIUM_IBLOCK_ID): ?> sanatorium-desc-list<? endif ?>">                            
                                    <? if (!empty($arItem["PROPERTIES"]["DISTANCE_MINSK"]["VALUE"])): ?>
                                        <div<? if ($arResult["ID"] == SANATORIUM_IBLOCK_ID): ?> itemprop="description"<? endif ?>>
                                            <i class="fa fa-info-circle blue"></i> <?= GetMessage('DISTANCE_MINSK') ?>: <?= substr2($arItem["PROPERTIES"]["DISTANCE_MINSK"]["VALUE"], 100); ?> km
                                        </div>
                                    <? endif ?>
                                    <? if (!empty($arItem["PROPERTIES"]["DISTANCE_CENTER"]["VALUE"])): ?>
                                        <div<? if ($arResult["ID"] == SANATORIUM_IBLOCK_ID): ?> itemprop="description"<? endif ?>>
                                            <i class="fa fa-info-circle blue"></i> <?= GetMessage('DISTANCE_CENTER') ?>: <?= substr2($arItem["PROPERTIES"]["DISTANCE_CENTER"]["VALUE"], 100); ?> km
                                        </div>
                                    <? endif ?>
                                    <? if (!empty($arItem["PROPERTIES"]["DISTANCE_AIRPORT"]["VALUE"])): ?>
                                        <div<? if ($arResult["ID"] == SANATORIUM_IBLOCK_ID): ?> itemprop="description"<? endif ?>>
                                            <i class="fa fa-info-circle blue"></i> <?= GetMessage('DISTANCE_AIRPORT') ?>: <?= substr2($arItem["PROPERTIES"]["DISTANCE_AIRPORT"]["VALUE"], 100); ?> km
                                        </div>
                                    <? endif ?>  
                                    <? if (!empty($arItem["PROPERTIES"]["FEATURES" . POSTFIX_PROPERTY]["VALUE"])): ?>
                                        <div <? if ($arResult["ID"] == SANATORIUM_IBLOCK_ID): ?> itemprop="description"<? endif ?> class= "<? if ($arResult["ID"] == SANATORIUM_IBLOCK_ID): ?> desc-sanatorium-li<? endif ?>">
                                            <i class="fa fa-info-circle blue"></i> <?= substr2($arItem["PROPERTIES"]["FEATURES" . POSTFIX_PROPERTY]["VALUE"], 200); ?>
                                        </div>
                                    <? endif ?>
                                    <? if (!empty($arItem["PROPERTIES"]["TYPE_TARIF"]["VALUE"])): 
                                        $tarifs = []; foreach ($arItem["PROPERTIES"]["TYPE_TARIF"]["VALUE"] as $idtmp) $tarifs[] = Get_Name_Element($idtmp);
                                    ?>
                                        <div <? if ($arResult["ID"] == SANATORIUM_IBLOCK_ID): ?> itemprop="description"<? endif ?> class="type_tour">
                                            <i class="fa fa-info-circle blue type-tour"></i> <?= implode2($tarifs); ?>
                                        </div>
                                    <? endif ?>
                                </div>
                                            
                                <div class="hotel-service float-left mobile-hotel-service">
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
                                <div class="hotel-service float-left desc-hotel-service">
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
                            <div class="dex-item dex-right">
                                <? if ($arItem["PROPERTIES"]["FOR_SPOT_PAYMENT"]["VALUE"] && (
                                    (!$arItem["PROPERTIES"]["TO_PAY_FROM1"]["VALUE"] &&
                                    !$arItem["PROPERTIES"]["TO_PAY_TO1"]["VALUE"] &&
                                    !$arItem["PROPERTIES"]["TO_PAY_FROM2"]["VALUE"] &&
                                    !$arItem["PROPERTIES"]["TO_PAY_TO2"]["VALUE"] &&
                                    !$arItem["PROPERTIES"]["TO_PAY_FROM3"]["VALUE"] &&
                                    !$arItem["PROPERTIES"]["TO_PAY_TO3"]["VALUE"]
                                    ) ||
                                    (
                                    strtotime($arItem["PROPERTIES"]["TO_PAY_FROM1"]["VALUE"]) <= $arParams["__BOOKING_REQUEST"]["date_from"] &&
                                    strtotime($arItem["PROPERTIES"]["TO_PAY_TO1"]["VALUE"]) >= $arParams["__BOOKING_REQUEST"]["date_to"]
                                    ) ||
                                    (
                                    strtotime($arItem["PROPERTIES"]["TO_PAY_FROM2"]["VALUE"]) <= $arParams["__BOOKING_REQUEST"]["date_from"] &&
                                    strtotime($arItem["PROPERTIES"]["TO_PAY_TO2"]["VALUE"]) >= $arParams["__BOOKING_REQUEST"]["date_to"]
                                    ) ||
                                    (
                                    strtotime($arItem["PROPERTIES"]["TO_PAY_FROM3"]["VALUE"]) <= $arParams["__BOOKING_REQUEST"]["date_from"] &&
                                    strtotime($arItem["PROPERTIES"]["TO_PAY_TO3"]["VALUE"]) >= $arParams["__BOOKING_REQUEST"]["date_to"]
                                    )
                                    )): 
                                ?>
                                    <p><?= GetMessage('FOR_SPOT_PAYMENT') ?></p>                            
                                <? endif ?>
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
                                
                                    $by_day = $arItem["PROPERTIES"]["CALC_BY_DAY"]["VALUE"] == "Y";

                                    $title_price_for = "";
                                    if ($more_then_day) {


                                        $duration = $by_day ? $arParams["CALCULATION_PRICE_RESULT"][$arItem["ID"]]["DURATION"] + 1 : $arParams["CALCULATION_PRICE_RESULT"][$arItem["ID"]]["DURATION"];
                                        
                                        if (!$discount_price) {
                                            $price_for = \travelsoft\Currency::getInstance()->convertCurrency(
                                                    $arParams["CALCULATION_PRICE_RESULT"][$arItem["ID"]]["PRICE"] / $duration, $arParams["CALCULATION_PRICE_RESULT"][$arItem["ID"]]['CURRENCY_ID']
                                            );
                                        } else {
                                            $price_for = \travelsoft\Currency::getInstance()->convertCurrency(
                                                    $arParams["CALCULATION_PRICE_RESULT"][$arItem["ID"]]["DISCOUNT_PRICE"] / $duration, $arParams["CALCULATION_PRICE_RESULT"][$arItem["ID"]]['CURRENCY_ID']
                                            );
                                        }
                                        

                                        if ($by_day) {
                                            $title_price_for = GetMessage("price_day_title", array("#price#" => $price_for));
                                        } else {
                                            $title_price_for = GetMessage("price_night_title", array("#price#" => $price_for));
                                        }

                                        $title_price_for = "<br>(" . $title_price_for . ")";
                                    } else {
                                        if (!$by_day) {
                                            $price_title = GetMessage("price_night_title");
                                        } else {
                                            $price_title = GetMessage("price_day_title");
                                        }
                                    }
                                ?>
                                <?if (!$discount_price):?>
                                <a <? if ($arResult["ID"] == SANATORIUM_IBLOCK_ID): ?>itemprop="url"<? endif ?> href="<? echo getCalculateDetailLink($arItem["DETAIL_PAGE_URL"], $arParams["__BOOKING_REQUEST"], array("scroll-to-sp" => "Y")) ?>" title="" target="_blank">
                                    <div class="price-box float-right" Style="cursor: pointer;"><?= str_replace("#price#", $price, $price_title) . $title_price_for ?></div>
                                </a>
                                <?else:?>
                                <a <? if ($arResult["ID"] == SANATORIUM_IBLOCK_ID): ?>itemprop="url"<? endif ?> href="<? echo getCalculateDetailLink($arItem["DETAIL_PAGE_URL"], $arParams["__BOOKING_REQUEST"], array("scroll-to-sp" => "Y")) ?>" title="" target="_blank">
                                    <div class="price__old"><?= $price?></div>
                                    <div class="price-box float-right" Style="cursor: pointer;"><?= str_replace("#price#", $discount_price, $price_title) . $title_price_for ?></div>
                                </a>
                                <?endif?>
                                <? else: ?>
                                    <a <? if ($arResult["ID"] == SANATORIUM_IBLOCK_ID): ?>itemprop="url"<? endif ?> href="<? echo getCalculateDetailLink($arItem["DETAIL_PAGE_URL"], $arParams["__BOOKING_REQUEST"], array("scroll-to-sp" => "Y")) ?>" title="" target="_blank">
                                        <div class="price-box float-right"><span class="detail"><?= GetMessage("MORE") ?></span></div>
                                    </a>
                                <? endif ?>
                            </div>
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
(function () {
    function initPopover() {
        if ($(window).width() < 767 ){
                $('.hotel-service a').webuiPopover({
                    placement: "right",
                    trigger: "hover"
                });
            } else {
                $('.hotel-service a').webuiPopover({
                    placement: "left",
                    trigger: "hover"
                });
            }
        
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
});
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