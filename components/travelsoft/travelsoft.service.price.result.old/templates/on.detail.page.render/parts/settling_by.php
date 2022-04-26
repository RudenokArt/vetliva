<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();
?><div class="hl-availability"><?
if ($arResult["NEED_LAZY_LOAD"]) {
    ?><div id="preloader">

            <div id="page-loading">
                <div></div>
            </div>

            <div class="target-text">
                <h3 class="sub-target-title"><?= GetMessage("PRELOADING_MESSAGE")?></h3>
            </div>
        </div><?
    } else {

        $current_currency = travelsoft\booking\Utils::getCurrentCurrency()["id"];

        if ($arParams["IS_AJAX"] == "Y") {
            ob_end_clean();
        }

        ob_start();

        foreach ($arResult["SETTLING_BY"] as $arr_offers):
            ?>
            <? foreach ($arr_offers as $arr_offer): if(empty($arr_offer["DETAILS"])) {continue;}?>
                <div class="row settling-by">
                    <? foreach ($arr_offer["DETAILS"] as $arr_service): ?>
                        <div class="col-md-12 service-info-container">
                            <?$col = 12;if ($arr_service["MAIN_BLOCK"]["IMAGE_ID"]):?>
                            <div class="col-md-3">

                                    <figure>
                                        <img src="<?= getSrcImage($arr_service["MAIN_BLOCK"]["IMAGE_ID"], array('width' => 400, 'height' => 260)) ?>">
                                    </figure>

                                </div>
                            <?$col = 6;endif?>
                            <div class="col-md-<?= $col?>"><div class="service-title"><a rel="nofollow" <? if ($arResult["SERVICE_POPUP_JS"]) : ?>data-id="<?= $arr_service["MAIN_BLOCK"]["ID"] ?>"<? endif ?> href="<? if ($arResult["SERVICE_POPUP_JS"]) : ?>#srv-popup-<?= $arr_service["MAIN_BLOCK"]["ID"] ?><? else: ?>javascript:void(0)<? endif ?>" class="pointer <? if ($arResult["SERVICE_POPUP_JS"]) : ?> open-service-popup <? endif ?>"><?= $arr_service["MAIN_BLOCK"]["TITLE"] ?></a></div></div>
                            <? if ($arResult['FORSPOTPAYMENT']) : ?>
                                <div class="fsp-container"><span class="for-spot-payment"><?= GetMessage("AVAIL_ON_SPOT") ?></span></div>
                            <? endif ?>
                            <div class="col-md-12">
                                <table class="table rate-table mt-20">


                                    <? foreach ($arr_service["ROWS"] as $key => $arRow): ?>
                                        <tr>
                                            <td style="text-align:left">

                                                <h4 class="rate-name"><?= $arRow["TITLE"] ?></h4>
                                                <div class="fsp-container" style="float: left">
                                                    <? if ($arResult["RATE_POPUP_JS"]): ?><a class="open-rate-popup" data-id="<?= $arRow["ID"] ?>" href="#rate-popup-<?= $key ?>"><?= GetMessage("ABOUT_RATE_TITLE") ?></a> &nbsp&nbsp<? endif ?><? if ($arResult["CANCELLATION_POLICY_POPUP_JS"]): ?><a rel="nofollow" class="open-cancellation-policy-popup" data-id="<?= $arRow["ID"] ?>" href="#cancellation-policy-popup-<?= $key ?>"><?= GetMessage("CANCELLATION_POLICY_TITLE") ?></a><? endif ?>
                                                </div>
                                            </td>
                                            <td><? if ($arRow["X"] > 1): ?><b><?= GetMessage("ROOMS_COUNT", array("#ROOMS_COUNT#" => $arRow["X"])) ?></b><? endif ?></td>
                                            <td class="price-container">
                                                <?if ($arRow["DISCOUNT_PRICE"]):?>
                                                    <div class="detail-price__old"><?= travelsoft\booking\Utils::convertCurrency($arRow["PRICE"], $current_currency) ?>
                                                    <?= travelsoft\booking\Utils::convertCurrency($arRow["DISCOUNT_PRICE"], $current_currency) ?>
                                                <?else:?>
                                                    <?= travelsoft\booking\Utils::convertCurrency($arRow["PRICE"], $current_currency) ?>
                                                <?endif?>
                                            </td>
                                        </tr>
                                    <? endforeach ?>
                                </table>
                            </div>

                        </div>

                    <? endforeach ?>
                    <div class="col-md-12 text-right booking-btn-block">
                        <div class="price-container">Итого <?= travelsoft\booking\Utils::convertCurrency($arr_offer["TOTAL_PRICE"], $current_currency) ?></div>
                        <a href="javascript:void(0)" data-add2cart="<?= $arr_offer["ADD2CART"] ?>"  class="add-to-cart awe-btn awe-btn-1 awe-btn-small"><?= GetMessage("BOOK") ?></a>
                    </div>
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


