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
<section class="hl-features ts-d-flex">
    <div class="hl-features-cn" style="padding-top: 0;margin-bottom: 30px">
        <h3><?= GetMessage("OTHER_OFFERS") ?></h3>
<div class="col-md-12 col-md-pull-0">
<div class="hotel-list-cn clearfix">
<? foreach ($arResult["ITEMS"] as $arItem): ?>
			<? /* if (!empty($arItem["PROPERTIES"]["SHOWONPAGE" . POSTFIX_PROPERTY]["VALUE"])): */?>
                <?
                $p = $arItem["DISPLAY_PROPERTIES"];
                $arParams["__BOOKING_REQUEST"]["id"] = array($arItem["ID"]);
                $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
                $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
                ?>
                <div class="hotel-list-item" id="<?= $this->GetEditAreaId($arItem['ID']); ?>">
                    <figure class="hotel-img float-left">
                        <a target="_blank" href="<? echo getCalculateDetailLink($arItem["DETAIL_PAGE_URL"], $arParams["__BOOKING_REQUEST"]) ?>" title="" <? if ($arResult["ID"] == SANATORIUM_IBLOCK_ID): ?>itemprop="url"<? endif ?>>
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
                            <?$webpfile = makeWebp($pre_photo);?>
                            <picture> 
                                <?if ($webpfile!=''):?>
                                <source type="image/webp" srcset="<?=$webpfile?>"> 
                                <?endif;?>
                                <img loading="lazy" src="<?= $pre_photo ?>" alt="" <? if ($arResult["ID"] == SANATORIUM_IBLOCK_ID): ?>itemprop="image"<? endif ?>>
                            </picture> 
                            
                        </a>
                    </figure>
                    <div class="hotel-text">
                        <div class="hotel-name">
                            <a target="_blank" href="<? echo getCalculateDetailLink($arItem["DETAIL_PAGE_URL"], $arParams["__BOOKING_REQUEST"])?>" title="<? echo LANGUAGE_ID == "ru" ? $arItem["NAME"] : $arItem["PROPERTIES"]["NAME" . POSTFIX_PROPERTY]["VALUE"] ?>"><? echo LANGUAGE_ID == "ru" ? $arItem["NAME"] : $arItem["PROPERTIES"]["NAME" . POSTFIX_PROPERTY]["VALUE"] ?></a>
                        </div>
                        <ul class="ship-port">
                            <?if ($arItem["PROPERTIES"]["FOR_SPOT_PAYMENT"]["VALUE"]):?>
                         <li>
                            <span style="display:none" class="label"><i class="fa fa-info-circle blue"></i></span> <span class="for-spot-payment"><?= GetMessage('FOR_SPOT_PAYMENT') ?></span>
                         </li>
                   <?endif?>
								<? if (!empty($arItem["PROPERTIES"]["ROUTE"]["VALUE"])): ?>
								<li>
									<span style="display:none" class="label"><a data-content="<?= GetMessage('TOWN') ?>" class="border_icon"><img loading="lazy" src="<?= SITE_TEMPLATE_PATH ?>/images/icon/route.png"></a> </span>
									<?= strip_tags($arItem["DISPLAY_PROPERTIES"]["ROUTE" . POSTFIX_PROPERTY]["DISPLAY_VALUE"]) ?>
								</li>
                                <? elseif (!empty($arItem["PROPERTIES"]["TOWN"]["VALUE"])): ?>
                                <li>
                                    <span style="display:none" class="label"><a data-content="<?= GetMessage('TOWN') ?>" class="border_icon"><img loading="lazy" src="<?= SITE_TEMPLATE_PATH ?>/images/icon/route.png"></a> </span>
                                    <?
                                    $p["TOWN"]["VALUE"] = (array) $p["TOWN"]["VALUE"];
                                    $db_res_towns = CIBlockElement::GetList(false, array("ACTIVE" => "Y", "IBLOCK_ID" => $p["TOWN"]["LINK_IBLOCK_ID"], "ID" => $p["TOWN"]["VALUE"]), false, false, array("ID", "NAME", "PROPERTY_NAME" . POSTFIX_PROPERTY));
                                    $towns = null;
                                    while ($res = $db_res_towns->Fetch()) {
                                        $towns[] = $res["PROPERTY_NAME" . POSTFIX_PROPERTY . "_VALUE"] ? $res["PROPERTY_NAME" . POSTFIX_PROPERTY . "_VALUE"] : $res["NAME"];
                                    }
                                    if ($towns) {
                                        echo implode(" - ", $towns);
                                    }
                                    ?>
                                </li>
                            <? endif; ?>
							<? if (!empty($arItem["PROPERTIES"]["DEPARTURE_TIME". POSTFIX_PROPERTY]["VALUE"])): ?>
								<li>
									<span class="label"><a data-content="<?= GetMessage('DEPARTURE_TIME') ?>" class="border_icon"><img loading="lazy" src="<?= SITE_TEMPLATE_PATH ?>/images/icon/clock.png"></a></span>  <?= GetMessage('DEPARTURE_TIME') ?>: <? echo $arItem["DISPLAY_PROPERTIES"]["DEPARTURE_TIME" . POSTFIX_PROPERTY]["DISPLAY_VALUE"]; ?> <br>

								</li>
							<? endif; ?>
							<?if (!empty($arResult['AVAIL_DATES'][$arItem['ID']])):?>
                            <li>
								<span class="label"><a data-content="<?= GetMessage('TOURS_DATE') ?>" class="border_icon"><img loading="lazy" src="<?= SITE_TEMPLATE_PATH ?>/images/icon/calendar.png"></a></span>  
                                    <?= GetMessage('TOURS_DATE') ?>: 
                                    <?$tmpcount=0; $dateslimit = []; foreach ($arResult['AVAIL_DATES'][$arItem['ID']] as $dateunix): $tmpcount++;
                                        $dateslimit[]= date('d.m.Y', $dateunix);
                                        if ($tmpcount>3) break;
                                        endforeach;
                                    ?>
                                    <span <?if ($arItem['PROPERTIES']['IS_EXCURSION_TOUR']['VALUE']!=''):?>data-countday=<?=$arItem['PROPERTIES']['DAYS']['VALUE']?><?endif;?> data-dates="<?=json_encode($arResult['AVAIL_DATES'][$arItem['ID']])?>" data-link="<?=$arItem["DETAIL_PAGE_URL"]?>" data-id="<?=$arItem['ID']?>" class="selectdates"><?=implode($dateslimit, ', ')?></span>
                                    
                                    <input type="text" name="eventdate" style="outline: none; color:white; border:none; height: 0px;" />
                                    <br>
                            </li>
                            <?/* elseif (!empty($arItem["PROPERTIES"]["TOURS_DATE". POSTFIX_PROPERTY]["VALUE"])): ?>
								<li>
									<span class="label"><a data-content="<?= GetMessage('TOURS_DATE') ?>" class="border_icon"><img loading="lazy" src="<?= SITE_TEMPLATE_PATH ?>/images/icon/calendar.png"></a></span>  <?= GetMessage('TOURS_DATE') ?>: <? echo $arItem["DISPLAY_PROPERTIES"]["TOURS_DATE" . POSTFIX_PROPERTY]["DISPLAY_VALUE"]; ?> <br>

								</li>
                            <? */endif; ?>
                            <?
                            if (count($p["HOTEL"]["VALUE"])>0):?>
                                <li>
                                    <span class="label"><a data-content="<?= GetMessage('HOTEL') ?>" class="border_icon"><img loading="lazy" src="<?= SITE_TEMPLATE_PATH ?>/images/icon/house-visiting.png"></a> </span>
                                <?foreach ($p["HOTEL"]["VALUE"] as $hotelid):?>
                                     <?=$arResult['HOTELS'][$hotelid]['name'];?><?if ($arResult['CAT_HOTELS'][$arResult['HOTELS'][$hotelid]['cat']]):?>, <?=$arResult['CAT_HOTELS'][$arResult['HOTELS'][$hotelid]['cat']]?><?endif;?><?if ($arResult['TOWNS_HOTELS'][$arResult['HOTELS'][$hotelid]['town']]):?>, <?=$arResult['TOWNS_HOTELS'][$arResult['HOTELS'][$hotelid]['town']]?><?endif;?><br />   
                                <?endforeach;?>
                                </li>    
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
                                <li>
									<span class="label"><a data-content="<?= GetMessage('FOOD') ?>" class="border_icon"><img loading="lazy" src="<?= SITE_TEMPLATE_PATH ?>/images/icon/dinner.png"></a></span> <?= implode(", ", $food) ?>
                                </li>
                            <? endif ?>
                        <? if ($arItem["PROPERTIES"]["DAYS"]["VALUE"]>"1"): ?>
                            <li>
								<span class="label"><a data-content="<?= GetMessage('DAYS') ?>" class="border_icon"><img loading="lazy" src="<?= SITE_TEMPLATE_PATH ?>/images/icon/numbered-list.png"></a> </span>
								<?= GetMessage('DAYS') ?> <?= $arItem["PROPERTIES"]["DAYS"]["VALUE"] ?>
							</li>
						<?elseif (!empty($arItem["PROPERTIES"]["DURATION_TIME"]["VALUE"])):?>
                            <li>
								<span class="label"><a data-content="<?= GetMessage('DURATION_TIME') ?>" class="border_icon"><img loading="lazy" src="<?= SITE_TEMPLATE_PATH ?>/images/icon/numbered-list.png"></a> </span>
								<?= GetMessage('DURATION_TIME') ?> <?= $arItem["PROPERTIES"]["DURATION_TIME"]["VALUE"] ?>
							</li>
                        <? endif; ?>
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
                            <li>
								<span class="label"><a data-content="<?= GetMessage('TYPE') ?>" class="border_icon"><img loading="lazy" src="<?= SITE_TEMPLATE_PATH ?>/images/icon/earth-pictures.png"></a> </span>
								<?= implode(", ", $tourtype) ?>
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
							<a target="_blank" href="<?= getCalculateDetailLink($arItem["DETAIL_PAGE_URL"], $arParams["__BOOKING_REQUEST"])?>">
								<div class="price-box float-right" Style="cursor: pointer;"><?= GetMessage("price_night_title", array("#price#" => $price)); ?></div>
							</a>
                        <?else:?>
                        <a target="_blank" href="<?= getCalculateDetailLink($arItem["DETAIL_PAGE_URL"], $arParams["__BOOKING_REQUEST"])?>">
                            <div class="price__old"><?= $price?></div>
								<div class="price-box float-right" Style="cursor: pointer;"><?= GetMessage("price_night_title", array("#price#" => $discount_price)); ?></div>
							</a>
                        <?endif?>
                            <? else: ?>
                            <a target="_blank" href="<? echo getCalculateDetailLink($arItem["DETAIL_PAGE_URL"], $arParams["__BOOKING_REQUEST"])?>" title="">
                                <div class="price-box float-right"><span class="detail"><?= GetMessage("MORE") ?></span></div>
                            </a>
                            <? endif ?>
					</div>
                </div>
<? endforeach; ?>
</div>
</div>
    </div>
</section>
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
$( document ).ready(function() {
    $(".selectdates").on("click", function (e) {
        var  $this = $(this), alloweddatestrue  =[], countday = $this.data('countday'), link = $this.data('link'), id = $this.data('id'),  alloweddates = $this.data('dates'), input = $this.closest('li').find('input');
        alloweddates.forEach(element => alloweddatestrue.push( moment(element, 'X').format('DD.MM.YYYY')));
        input.datepicker({
            beforeShowDay: function (date) {
                if (countday >1) {
                    showdate = false;
                    datetmp = moment(date).format('X');
					var mindates = [], maxdates = [];
                    alloweddates.forEach(function(item, i, arr) {
                        minDate = item;
                        maxDate = moment(minDate, 'X').add(countday,'days').format('X');
						mindates.push(minDate); maxdates.push((maxDate-86400));
                        if (datetmp >= minDate && datetmp < maxDate)  showdate = true;
                    });
                      if (showdate) {
                        datetmp = parseInt(datetmp);
                        if (mindates.includes(datetmp)) return [true, ' ui-datepicker-current-day green-day', ''];
                        else if (maxdates.includes(datetmp)) return [true, ' light-date-end green-day', ''];
                        else return [true, 'light-date', ''];
                    } 
                    else return [false, '', ''];
                }
                else {
                    datetmp = moment(date).format('DD.MM.YYYY');
                    if(jQuery.inArray(datetmp, alloweddatestrue) != -1) {
                        return [true, 'ui-datepicker-today light', ''];
                    }
                    else return [false, '', ''];
                }
            },
            onSelect: function (string, object) {
                if (countday >1) {
                    datetmp = moment(string, 'DD.MM.YYYY').format('X');
                    alloweddates.forEach(function(item, i, arr) {
                        minDate = item;
                        maxDate = moment(minDate, 'X').add(countday,'days').format('X');
                        if (datetmp >= minDate && datetmp < maxDate) {
                            $('#form-excursionstours [name="booking[date_to]"]').val(maxDate);
                            $('#form-excursionstours [name="booking[date_from]"]').val(minDate);
                            $('#form-excursionstours [name="booking[id][0]"]').val(id);
                            $('#form-excursionstours form').attr('action', link);
                            $('#form-excursionstours form').submit(); 
                        }
                    });
                }
                else {
                    $('#form-tours [name="booking[date_to]"]').val(moment(string, 'DD.MM.YYYY').format('X'));
                    $('#form-tours [name="booking[date_from]"]').val(moment(string, 'DD.MM.YYYY').format('X'));
                    $('#form-tours [name="booking[id][0]"]').val(id);
                    $('#form-tours form').attr('action', link);
                    $('#form-tours form').submit();  
                }
            },
            minDate:alloweddatestrue[0],
            maxDate:moment(alloweddatestrue[0], 'DD.MM.YYYY').add(1,'year').format('DD.MM.YYYY'),
            dateFormat: 'dd.mm.yy',
            numberOfMonths: window.innerWidth < 640 ? 1 : 2,
            allowedDates :alloweddatestrue
           });
     
           input.datepicker('show');
     });   
     $('.catalog_element').each(function(){
		  var height = $(this).height();
		  var clientHeight = $(window).height();
	      var clientHeightNew = clientHeight * 0.50;
		   if(height < clientHeightNew){
			   $(this).addClass('w_max');
			   $(this).closest('section').addClass('hide-btn');
				  } else {
			   $(this).addClass('with_max');
			   $(this).closest('section').removeClass('hide-btn');
			   $(this).closest('section').addClass('show-btn');
		   }
     });
		
});
</script>