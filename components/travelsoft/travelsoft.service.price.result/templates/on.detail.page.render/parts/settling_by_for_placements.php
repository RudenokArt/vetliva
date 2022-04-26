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
                <h3 class="sub-target-title"><?= GetMessage("PRELOADING_MESSAGE") ?></h3>
            </div>
        </div><?
    } else {

        $current_currency = travelsoft\booking\Utils::getCurrentCurrency()["id"];
        $current_currency_iso = travelsoft\booking\Utils::getCurrentCurrency()['iso'];
        if ($arParams["IS_AJAX"] == "Y") {
            ob_end_clean();
        }

        ob_start();

        foreach ($arResult["SETTLING_BY"] as $arr_data_groped_by_objects):
            foreach ($arr_data_groped_by_objects as $arr_offers):
            $total_start_price = 0.00;
                ?>
                <div class="resultRoom">
                    <div class="headerResultRoom">
                        <h2><?= GetMessage("ROOMS_COUNT", ["#ROOMS_COUNT#" => count($arr_offers)])?></h2>
                        <hr>
                    </div>
                    <div class="rooms">
                        <?
                        $rooms_number = 0;
                        
                        foreach ($arr_offers as $arr_offer): 
                            
                            $rooms_number++;
                            ?>


                            <div class="room">
                                <div class="mainContentRoom">
                                    <figure><img src="<?= getSrcImage($arr_offer["MAIN_BLOCK"]["IMAGE_ID"], array('width' => 200, 'height' => 130)) ?>" alt=""></figure>
                                    <article>
                                        <h3>
                                            <a rel="nofollow" <? if ($arResult["SERVICE_POPUP_JS"]) : ?>data-id="<?= $arr_offer["MAIN_BLOCK"]["ID"] ?>"<? endif ?> href="<? if ($arResult["SERVICE_POPUP_JS"]) : ?>#srv-popup-<?= $arr_offer["MAIN_BLOCK"]["ID"] ?><? else: ?>javascript:void(0)<? endif ?>" class="pointer <? if ($arResult["SERVICE_POPUP_JS"]) : ?> open-service-popup <? endif ?>"><?= $arr_offer["MAIN_BLOCK"]["TITLE"] ?></a>
                                        </h3>
                                        <?if($arResult['FORSPOTPAYMENT']):?>
                                        <p class="forPayment"><?= GetMessage("AVAIL_ON_SPOT")?></p>
                                        <?endif?>
                                        <p><?if (strlen($arr_offer["MAIN_BLOCK"]['DESCRIPTION']) > 156) { echo substr($arr_offer["MAIN_BLOCK"]['DESCRIPTION'], 153) . "..."; } else {echo $arr_offer["MAIN_BLOCK"]['DESCRIPTION'];}?></p>
                                    </article>
                                    <div class="dopInfo">
                                        <div class="numberRoom">#<?= $rooms_number ?></div>
                                        <div class="countPeople">
                                            <div class="d-inline">
                                                <?= GetMessage("INLINE_PEOPLE_INFO", ["#ADULTS#" => intVal($arr_offer["MAIN_BLOCK"]['REQUEST']['adults']), "#CHILDREN#" => intVal($arr_offer["MAIN_BLOCK"]['REQUEST']['children']) ])?>
                                                
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="optionsRoom">
                                <?$first = true; foreach ($arr_offer['ROWS'] as $arRow):?>

                                <div data-price-value="<?= travelsoft\booking\Utils::convertCurrency($arRow["~PRICE"], $current_currency, $current_currency, true) ?>" data-add2cart-request='<?= $arRow['ADD2CART']?>' class="optionRoom <?if($first) {echo "active";}?>">
                                        <div class="content">
                                            <div class="checkbox">
                                                <label for="checkbox1"></label>
                                                <input type="checkbox" checked>
                                            </div>
                                            <h4> <?= $arRow["TITLE"] ?>
                                                <div class="info"><? if ($arResult["RATE_POPUP_JS"]): ?><a class="open-rate-popup" data-id="<?= $arRow["ID"] ?>" href="#rate-popup-<?= $key ?>"><?= GetMessage("ABOUT_RATE_TITLE") ?></a> &nbsp&nbsp<? endif ?><? if ($arResult["CANCELLATION_POLICY_POPUP_JS"]): ?><a rel="nofollow" class="open-cancellation-policy-popup" data-id="<?= $arRow["ID"] ?>" href="#cancellation-policy-popup-<?= $key ?>"><?= GetMessage("CANCELLATION_POLICY_TITLE") ?></a><? endif ?>
                                                </div>
                                            </h4>
                                        </div>
                                        <div class="price price-container">
                                            <?if ($arRow["DISCOUNT_PRICE"]):?>
                                                <div class="detail-price__old"><?= travelsoft\booking\Utils::convertCurrency($arRow["PRICE"], $current_currency) ?></div>
                                                <?= travelsoft\booking\Utils::convertCurrency($arRow["DISCOUNT_PRICE"], $current_currency) ?>
                                            <?else:?>
                                                <?= travelsoft\booking\Utils::convertCurrency($arRow["PRICE"], $current_currency) ?>
                                            <?endif?>
                                        </div>

                                    </div>
                                <?
                                if ($first) {
                                    $first = false;
                                    if ($arRow["DISCOUNT_PRICE"])
                                        $total_start_price += travelsoft\booking\Utils::convertCurrency($arRow["~DISCOUNT_PRICE"], $current_currency, $current_currency, true);
                                    else
                                        $total_start_price += travelsoft\booking\Utils::convertCurrency($arRow["~PRICE"], $current_currency, $current_currency, true); 
                                }
                                endforeach ?>
                            </div>
                            <?
                        endforeach;
                        ?>
                    </div>
                    <div class="result">
                        <div class="content">
                            <h4 class="summ"><?= GetMessage("SUM")?>: <span class="price-value"><?= number_format($total_start_price, 1, ".", " ")?></span> <?= $current_currency_iso?></h4>
                        </div><a href="/booking/" class="add2basket add-to-cart awe-btn awe-btn-1 awe-btn-small"><?= GetMessage("BOOK")?></a>
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


