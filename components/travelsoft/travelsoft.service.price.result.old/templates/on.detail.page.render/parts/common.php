<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();
    $use_limit = false; if (!in_array($arParams["TYPE"], array('placements', 'sanatorium'))) $use_limit = true;
?>
<!-- Временное сообщение о недоступности бронированя  -->
<template id="booking-stop-notify">
  <div class="box-modal" id="boxUserFirstInfo" style="margin-bottom: 22%;">
    <div class="box-modal_close arcticmodal-close">ОК</div>
    <?=getMessage("BOOKING_STOP_NOTIFY"); ?>
  </div>
</template>
<div class="hl-availability"><?
if ($arResult["NEED_LAZY_LOAD"]) {
    ?><div id="search-preloader">

            <div id="search-page-loading">
                <div></div>
            </div>

            <div class="target-text">
                <h3 class="sub-target-title"><?= GetMessage("PRELOADING_MESSAGE") ?></h3>
            </div>
        </div><?
    } else {

        if ($arParams["IS_AJAX"] == "Y") {
            ob_end_clean();
        }
        ob_start();
        foreach ($arResult["HTML_DATA"] as $arHtmlData):
            
            foreach ($arHtmlData as $arData) :
                ?>
                <div class='row ts-mx-0'>
                    <? if ($arData["MAIN_BLOCK"]): ?>

                        <div class="service-info-container">
                            <?
                            $col_ = 9;
                            if ($arData["MAIN_BLOCK"]["IMAGE_ID"]): $col_ = 6;
                                ?>
                                <div class="col-lg-3 col-md-3">
										<?if($arParams["TYPE"] != "transfers"): ?>
											<a <? if ($arResult["SERVICE_POPUP_JS"]) : ?>data-id="<?= $arData["MAIN_BLOCK"]["ID"] ?>"<? endif ?> href="<? if ($arResult["SERVICE_POPUP_JS"]) : ?>#srv-popup-<?= $arData["MAIN_BLOCK"]["ID"] ?><? else: ?>javascript:void(0)<? endif ?>" class="pointer <? if ($arResult["SERVICE_POPUP_JS"]) : ?> open-service-popup <? endif ?>">
										<?endif;?>
											<figure>
												<img  loading="lazy"  src="<?= getSrcImage($arData["MAIN_BLOCK"]["IMAGE_ID"], array('width' => 300, 'height' => 195)) ?>">
											</figure>
										<?if($arParams["TYPE"] != "transfers"): ?></a><?endif;?>
                                </div>
                            <? endif ?> 
                            <div class="col-lg-<?= $col_ ?> col-md-<?= $col_ ?>">
                                <div class="service-title">
									<? if($arParams["TYPE"] != "transfers"): ?>
									<a <? if ($arResult["SERVICE_POPUP_JS"]) : ?>data-id="<?= $arData["MAIN_BLOCK"]["ID"] ?>"<? endif ?> href="<? if ($arResult["SERVICE_POPUP_JS"]) : ?>#srv-popup-<?= $arData["MAIN_BLOCK"]["ID"] ?><? else: ?>javascript:void(0)<? endif ?>" class="pointer <? if ($arResult["SERVICE_POPUP_JS"]) : ?> open-service-popup <? endif ?>"><?= $arData["MAIN_BLOCK"]["TITLE"] ?></a>
									<? else:
										echo "<span style=\"color:#264B87\">".$arData["MAIN_BLOCK"]["TITLE"]."</span>";
									 endif; ?>
								</div>
                                <? if ($arResult['FORSPOTPAYMENT']) : $existsfsptitle = true ?>
                                    <div class="fsp-container"><span class="for-spot-payment"><?= GetMessage("AVAIL_ON_SPOT") ?></span></div>
                                <? endif ?>
                                <div class="service-short-description">
								
								    <?if($arParams["TYPE"] != "transfers"): ?>
										<a <? if ($arResult["SERVICE_POPUP_JS"]) : ?>data-id="<?= $arData["MAIN_BLOCK"]["ID"] ?>"<? endif ?> href="<? if ($arResult["SERVICE_POPUP_JS"]) : ?>#srv-popup-<?= $arData["MAIN_BLOCK"]["ID"] ?><? else: ?>javascript:void(0)<? endif ?>" class="pointer <? if ($arResult["SERVICE_POPUP_JS"]) : ?> open-service-popup <? endif ?>"><?= $arData["MAIN_BLOCK"]["DESCRIPTION"] ?></a>
									<?else:?>
											<?= $arData["MAIN_BLOCK"]["DESCRIPTION"] ?>
									<?endif;?>
								</div>

                            </div>
                            <div class="col-lg-3 col-md-3 text-center">
                                <div class="price-border">
                                    <?= GetMessage("FROM") ?> <span class="min-price-container"><?= $arData["MAIN_BLOCK"]["PRICE"] ?></span>
                                </div>
                                <? if (isset($arData["MAIN_BLOCK"]["FOR_SALE"]) && $arData["MAIN_BLOCK"]["FOR_SALE"] < 3): ?>
                                    <span class="few-number-alert"><?= GetMessage("FOR_SALE") ?> <?= $arData["MAIN_BLOCK"]["FOR_SALE"] ?></span>
                                <? endif ?>
                            </div>
                        </div>

                    <? endif ?>
                    <!-- результат -->
                    <? if ($arData["ROWS"]): ?>

                        <div class="rate-info-container">
                            <div class="col-lg-12 col-md-12">
                                <table class="table rate-table mt-20  ts-d-none ts-d-sm-table">

                                    <? if (!$arData["MAIN_BLOCK"]): ?>
                                        <tr>
                                            <td style="text-align:left"><span class="tour-date"><?= GetMessage("DATE_TEXT") ?></span><?= GetMessage("TARIFF_TEXT") ?></td>
                                            <td><?= GetMessage("PRICE_TEXT") ?></td>
                                            <td></td>
                                        </tr>
                                    <? endif ?>

                                    <? 
                                    $count=0;
                                    foreach ($arData["ROWS"] as $key => $arRow): $count++; ?>
                                        <tr class="<?if ($count>3 && $use_limit):?>hidden<?endif;?>">
                                            <td style="text-align:left">
                                                <!-- hello world 2 -->
                                                <? if ($arRow["DATE"]): ?><span class="tour-date"><?= $arRow["DATE"] ?></span><? endif ?>
                                                <div class="ts-d-flex ts-flex-direction__column">
                                                <h4 class="rate-name"><?= $arRow["TITLE"] ?></h4>
                                                <div class="fsp-container" style="float: left">
                                                    <? if (($arResult['FORSPOTPAYMENT'] || $arRow["FOR_SPOT_PAYMENT"]) && !$existsfsptitle) : ?>
                                                        <span class="for-spot-payment"><?= GetMessage("AVAIL_ON_SPOT") ?></span><br>
                                                    <? endif ?>
                                                    <? if ($arResult["RATE_POPUP_JS"]): ?><a class="open-rate-popup" data-id="<?= $arRow["ID"] ?>" href="#rate-popup-<?= $key ?>"><?= GetMessage("ABOUT_RATE_TITLE") ?></a> &nbsp&nbsp<? endif ?><? if ($arResult["CANCELLATION_POLICY_POPUP_JS"]): ?><a class="open-cancellation-policy-popup" data-id="<?= $arRow["ID"] ?>" href="#cancellation-policy-popup-<?= $key ?>"><?= GetMessage("CANCELLATION_POLICY_TITLE") ?></a><? endif ?>
                                                </div>
                                                </div>
                                            </td>
                                            <td class="price-container">
                                                <?if (isset($arRow["DISCOUNT_PRICE"]) && $arRow["DISCOUNT_PRICE"] > 0):?>
                                                <div class="detail-price__old"><?= $arRow["PRICE"]; ?></div>
                                                <?= $arRow["DISCOUNT_PRICE"]; ?>
                                                <?else:?>
												<?= $arRow["PRICE"]; ?>
                                                <?endif?>
                                            </td>
                                            <td>
                                                <a href="javascript:void(0)" data-add2cart="<?= $arRow["ADD2CART"] ?>"  class="add-to-cart awe-btn awe-btn-1 awe-btn-small"><?= GetMessage("BOOK") ?></a>
                                            </td>
                                        </tr>
                                        <div class="ts-wrap ts-d-block ts-d-sm-none ts-pb-1 ts-my-1 <?if ($count>3 && $use_limit):?>hidden<?endif;?>" style="border: 1px solid lightgrey">
                                            <div class="ts-row">
                                                <div class="ts-col-24 ts-justify-content__center">
                                                    <h4 class="rate-name"><?= $arRow["TITLE"] ?></h4>
                                                </div>
                                                <div class="ts-col-24 ts-justify-content__center">
                                                <? if ($arRow["DATE"]): ?><span class="tour-date ts-mx-0"><?= $arRow["DATE"] ?></span><? endif ?>
                                                </div>
                                                <div class="ts-col-24 ts-justify-content__center">
                                                    <? if (($arResult['FORSPOTPAYMENT'] || $arRow["FOR_SPOT_PAYMENT"]) && !$existsfsptitle) : ?>
                                                        <span class="for-spot-payment ts-d-block ts-width-100" style="text-align: center"><?= GetMessage("AVAIL_ON_SPOT") ?></span>
                                                    <? endif ?>
                                                    <? if ($arResult["RATE_POPUP_JS"]): ?><a class="open-rate-popup" data-id="<?= $arRow["ID"] ?>" href="#rate-popup-<?= $key ?>"><?= GetMessage("ABOUT_RATE_TITLE") ?></a> &nbsp&nbsp<? endif ?><? if ($arResult["CANCELLATION_POLICY_POPUP_JS"]): ?><a class="open-cancellation-policy-popup" data-id="<?= $arRow["ID"] ?>" href="#cancellation-policy-popup-<?= $key ?>"><?= GetMessage("CANCELLATION_POLICY_TITLE") ?></a><? endif ?>
                                                </div>
                                                <div class="ts-col-24 ts-py-2 ts-justify-content__center" style="text-align: center">
                                                    <?if (isset($arRow["DISCOUNT_PRICE"]) && $arRow["DISCOUNT_PRICE"] > 0):?>
                                                        <div class="detail-price__old" ><?= $arRow["PRICE"]; ?></div>
														<?= $arRow["DISCOUNT_PRICE"]; ?>
                                                    <?else:?>
                                                        <?= $arRow["PRICE"]; ?>
                                                    <?endif?>
                                                </div>
                                                <div class="ts-col-24 ts-justify-content__center">
                                                    <a href="javascript:void(0)" data-add2cart="<?= $arRow["ADD2CART"] ?>"  class="add-to-cart awe-btn awe-btn-1 awe-btn-small"><?= GetMessage("BOOK") ?></a>
                                                </div>
                                            </div>
                                        </div>
                                    <? endforeach ?>
                                </table>
                                <?if ($count>3 && $use_limit):?>
                                    <div class=" text-right mb-10 ">
                                        <div onclick="$('.rate-table tr').removeClass('hidden'); $('.ts-wrap.ts-d-block').removeClass('hidden');  $(this).addClass('hidden')"  class="btn btn-primary  ts-width-100  ts-d-flex " id="show-more-btn"><?= GetMessage("SHOW_ALL") ?>
                                        </div>
                                    </div>
                                <?endif;?>
                            </div>
                        </div>

                    <? endif ?>
                </div>

                <?
            endforeach;
        endforeach;

        $buffer = ob_get_clean();

        if ($arParams["IS_AJAX"] == "Y") {
            echo $buffer;
            return;
        }

        echo $buffer;
    }
    include_once "js.php";
    ?>
</div>





