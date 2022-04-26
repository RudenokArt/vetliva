<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();
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

if (empty($arResult["ITEMS"])):
    ?>
    <div class="col-md-9 col-md-pull-0 content-page-detail">
        <div class="alert-box alert-attention"><?= GetMessage("TEXT_NOT_FOUND", array("#LINK#" => $APPLICATION->GetCurDir())) ?></div>
    </div>
    <?
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
<div class="col-md-9 col-md-pull-0 content-page-detail">
    <h1><?= $APPLICATION->GetTitle() ?></h1>
    <section class="hotel-list">
        <? if ($arParams["SORT_PARAMETERS"]) : ?>
            <!-- Sort by and View by -->
            <div class="sort-view clearfix">

                <div class="sort-by float-left">
                    <label><?= GetMessage("SORT_TITLE") ?>: </label>
                    <? foreach ($arParams["SORT_PARAMETERS"] as $arp): ?>
                        <div class="sort-select select float-left">
                            <?
                            $arrow = "<i class=\"fa fa-long-arrow-up\" aria-hidden=\"true\"></i> <i class=\"fa fa-long-arrow-down\" aria-hidden=\"true\"></i>";
                            if ($arp["selected"]) {
                                $arrow = $arp["order"] == "asc" ? "<i class=\"fa fa-long-arrow-up\" aria-hidden=\"true\"></i>" : "<i class=\"fa fa-long-arrow-down\" aria-hidden=\"true\"></i>";
                            }
                            ?>
                            <a class="sorting" rel="nofollow" href="<?= $APPLICATION->GetCurPageParam("sort_by=" . $arp["name"] . "&" . "order=" . $arp["order"], array("sort_by", "order"), false) ?>"><?= GetMessage($arp["name"]) ?></a> <?= $arrow ?>
                        </div>
                    <? endforeach ?>
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
            <? foreach ($arResult["ITEMS"] as $arItem): ?>
                <?
                $_request_string = $arItem["DETAIL_PAGE_URL"] . "?booking[id][]=" . $arItem["ID"];
                $arParams["__BOOKING_REQUEST"]["id"] = array($arItem["ID"]);
                $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
                $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
                ?>
                <div class="hotel-list-item" id="<?= $this->GetEditAreaId($arItem['ID']); ?>" <? if ($arResult["ID"] == SANATORIUM_IBLOCK_ID): ?>itemscope itemtype="http://schema.org/Place"<? endif ?>>
                    <figure class="hotel-img float-left">
                        <a href="<? echo getCalculateDetailLink($arItem["DETAIL_PAGE_URL"], $arParams["__BOOKING_REQUEST"]) ?>" title="" <? if ($arResult["ID"] == SANATORIUM_IBLOCK_ID): ?>itemprop="url"<? endif ?>>
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
                            <img src="<?= $pre_photo ?>" alt="" <? if ($arResult["ID"] == SANATORIUM_IBLOCK_ID): ?>itemprop="image"<? endif ?>>
                        </a>
                    </figure>
                    <div class="hotel-text">
                        <div class="hotel-name" <? if ($arResult["ID"] == SANATORIUM_IBLOCK_ID): ?>itemprop="name"<? endif ?>>
                            <a <? if ($arResult["ID"] == SANATORIUM_IBLOCK_ID): ?>itemprop="url"<? endif ?> href="<? echo getCalculateDetailLink($arItem["DETAIL_PAGE_URL"], $arParams["__BOOKING_REQUEST"]) ?>" title="<? echo LANGUAGE_ID == "ru" ? $arItem["NAME"] : $arItem["PROPERTIES"]["NAME" . POSTFIX_PROPERTY]["VALUE"] ?>"><? echo LANGUAGE_ID == "ru" ? $arItem["NAME"] : $arItem["PROPERTIES"]["NAME" . POSTFIX_PROPERTY]["VALUE"] ?></a>
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
                                    <? elseif ($arItem["PROPERTIES"]["CAT_ID"]["VALUE"] == '4169'): ?>
                                        <?= GetMessage('BEZ_ZVEZD'); ?>
                                    <? endif; ?>
                                </span>
                                <? /* rating
                                  <span class="rating">
                                  Рейтинг <br>
                                  <ins>7.5</ins>
                                  </span>
                                  end rating */ ?>
                            </div>
                        </div>
                        <address class="hotel-address" <? if ($arResult["ID"] == SANATORIUM_IBLOCK_ID): ?>itemprop="address" itemscope itemtype="http://schema.org/PostalAddress"<? endif ?>>
                            <? if (!empty($arItem["PROPERTIES"]["ADDRESS" . POSTFIX_PROPERTY]["VALUE"])): $adress = ''; ?>
                                <i class="fa fa-map-marker"></i> <? $adress = substr2($arItem["DISPLAY_PROPERTIES"]["ADDRESS" . POSTFIX_PROPERTY]["DISPLAY_VALUE"], 200); ?><? if ($arResult["ID"] == SANATORIUM_IBLOCK_ID): ?><? $adress = "<span itemprop=\"streetAddress\">" . $adress . "</span>"; ?><? endif ?>
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
                        </address>
                        <ul class="ship-port">
                            <?
                            if ($arItem["PROPERTIES"]["FOR_SPOT_PAYMENT"]["VALUE"] && (

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
                                <li>
                                    <i class="fa fa-info-circle blue"></i> <span class="for-spot-payment"><?= GetMessage('FOR_SPOT_PAYMENT') ?><span>
                                            </li>
                                        <? endif ?>
                                        <? if (!empty($arItem["PROPERTIES"]["DISTANCE_MINSK"]["VALUE"])): ?>
                                            <li<? if ($arResult["ID"] == SANATORIUM_IBLOCK_ID): ?> itemprop="description"<? endif ?>>
                                                <i class="fa fa-info-circle blue"></i> <?= GetMessage('DISTANCE_MINSK') ?>: <?= substr2($arItem["PROPERTIES"]["DISTANCE_MINSK"]["VALUE"], 100); ?> km
                                            </li>
                                        <? endif ?>
                                        <? if (!empty($arItem["PROPERTIES"]["DISTANCE_CENTER"]["VALUE"])): ?>
                                            <li<? if ($arResult["ID"] == SANATORIUM_IBLOCK_ID): ?> itemprop="description"<? endif ?>>
                                                <i class="fa fa-info-circle blue"></i> <?= GetMessage('DISTANCE_CENTER') ?>: <?= substr2($arItem["PROPERTIES"]["DISTANCE_CENTER"]["VALUE"], 100); ?> km
                                            </li>
                                        <? endif ?>
                                        <? if (!empty($arItem["PROPERTIES"]["DISTANCE_AIRPORT"]["VALUE"])): ?>
                                            <li<? if ($arResult["ID"] == SANATORIUM_IBLOCK_ID): ?> itemprop="description"<? endif ?>>
                                                <i class="fa fa-info-circle blue"></i> <?= GetMessage('DISTANCE_AIRPORT') ?>: <?= substr2($arItem["PROPERTIES"]["DISTANCE_AIRPORT"]["VALUE"], 100); ?> km
                                            </li>
                                        <? endif ?>
      									<? if (!empty($arItem["PROPERTIES"]["DISTANCE_DYNAMO"]["VALUE"])): ?>
                                            <li<? if ($arResult["ID"] == SANATORIUM_IBLOCK_ID): ?> itemprop="description"<? endif ?>>
                                                <i class="fa fa-info-circle blue"></i> <?= GetMessage('DISTANCE_DYNAMO') ?>: <?= substr2($arItem["PROPERTIES"]["DISTANCE_DYNAMO"]["VALUE"], 100); ?> km
                                            </li>
                                        <? endif ?>  
                                        <? if (!empty($arItem["PROPERTIES"]["FEATURES" . POSTFIX_PROPERTY]["VALUE"])): ?>
                                            <li<? if ($arResult["ID"] == SANATORIUM_IBLOCK_ID): ?> itemprop="description"<? endif ?>>
                                                <i class="fa fa-info-circle blue"></i> <?= substr2($arItem["PROPERTIES"]["FEATURES" . POSTFIX_PROPERTY]["VALUE"], 200); ?>
                                            </li>
                                        <? endif ?>
                                        </ul>
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
                                            <a <? if ($arResult["ID"] == SANATORIUM_IBLOCK_ID): ?>itemprop="url"<? endif ?> href="<? echo getCalculateDetailLink($arItem["DETAIL_PAGE_URL"], $arParams["__BOOKING_REQUEST"], array("scroll-to-sp" => "Y")) ?>" title="">
                                                <div class="price-box float-right" Style="cursor: pointer;"><?= str_replace("#price#", $price, $price_title) . $title_price_for ?></div>
                                            </a>
                                            <?else:?>
                                            <a <? if ($arResult["ID"] == SANATORIUM_IBLOCK_ID): ?>itemprop="url"<? endif ?> href="<? echo getCalculateDetailLink($arItem["DETAIL_PAGE_URL"], $arParams["__BOOKING_REQUEST"], array("scroll-to-sp" => "Y")) ?>" title="">
                                                <div class="price__old"><?= $price?></div>
                                                <div class="price-box float-right" Style="cursor: pointer;"><?= str_replace("#price#", $discount_price, $price_title) . $title_price_for ?></div>
                                            </a>
                                            <?endif?>
                                        <? else: ?>
                                            <a <? if ($arResult["ID"] == SANATORIUM_IBLOCK_ID): ?>itemprop="url"<? endif ?> href="<? echo getCalculateDetailLink($arItem["DETAIL_PAGE_URL"], $arParams["__BOOKING_REQUEST"], array("scroll-to-sp" => "Y")) ?>" title="">
                                                <div class="price-box float-right"><span class="detail"><?= GetMessage("MORE") ?></span></div>
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
                                        (function () {
                                            function initPopover() {
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
                                        <p><?= GetMessage('FOUND') ?> <ins id="searching__cnt__elements"><?= $arResult['NAV_RESULT']->NavRecordCount ?></ins></p>
                                    </div>
                                    <? $this->EndViewTarget() ?>
                                    <span id="cnt__elements"><?= $arResult['NAV_RESULT']->NavRecordCount ?></span>